<?php

namespace App\Services;

use App\Models\Solicitud;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected const MAX_INTENTOS = 3;

    protected const ESPERA_BASE_INTENTOS = 800000;

    protected const ESPERA_INTENTOS = [600000, 1200000];

    protected string $clave;

    protected array $modelos;

    public function __construct()
    {
        $this->clave = (string) config('services.gemini.key', '');

        $principal = (string) config('services.gemini.model', 'gemini-2.5-flash');
        $fallbacks = array_values(array_filter(
            array_map('trim', explode(',', (string) config('services.gemini.fallback_models', ''))),
            fn (string $m) => $m !== ''
        ));

        $this->modelos = array_values(array_unique(array_merge([$principal], $fallbacks)));
    }

    /**
     * Indica si hay una clave de Gemini configurada.
     */
    public function disponible(): bool
    {
        return $this->clave !== '';
    }

    /**
     * Analiza una solicitud con Gemini y devuelve el resultado
     * estructurado, o null si no se pudo analizar.
     *
     * @return array{prioridad: string, delicado: bool, etiqueta: string, recomendaciones: ?string, motivacion: ?string, gestion_admin: ?string}|null
     */
    public function analizarSolicitud(Solicitud $solicitud): ?array
    {
        if (! $this->disponible()) {
            Log::warning('Gemini IA: no hay clave configurada (GEMINI_API_KEY).');

            return null;
        }

        $sistema = <<<'PROMPT'
Eres un asistente experto en bienestar y psicología educativa del SENA (Colombia), dentro del programa "Bienestar al Aprendiz". Analizas solicitudes de ayuda de aprendices.

Debes responder UN ÚNICO objeto JSON válido (sin etiquetas de markdown, sin texto adicional) con EXACTAMENTE estas claves:
{
  "prioridad": "Baja" | "Media" | "Alta" | "Crítica",
  "delicado": true | false,
  "etiqueta": "texto corto de máximo 4 palabras",
  "recomendaciones": "2 a 4 frases empáticas, cálidas y prácticas dirigidas al aprendiz, en español",
  "motivacion": "una frase corta de motivación y esperanza, o null si no aplica",
  "gestion_admin": "1 a 2 frases concretas para que el profesional de bienestar gestione el caso"
}

Reglas de priorización:
- Depresión, ideación o intento suicida, autolesión, crisis, acoso, violencia o desesperanza → "Crítica" o "Alta" con delicado=true.
- Ansiedad o estrés moderado, dificultades emocionales leves, problemas de convivencia → "Media".
- Trámites, dudas administrativas o temas sin afectación emocional → "Baja".
- Si la descripción es demasiado corta o ambigua, asigna "Media" por seguridad: es preferible atender con cautela.

Reglas:
- Si la descripción menciona depresión, ansiedad severa, crisis, autolesión, ideación o intento suicida, acoso, violencia o desesperanza, usa prioridad "Crítica" o "Alta" y delicado=true.
- En casos delicados, que "recomendaciones" sugiera hablar con una persona de confianza y contactar la Línea de Atención Psicosocial 106 (Colombia), además de acudir al área de Bienestar.
- No diagnostiques ni reemplaces a un profesional de salud mental.
- Mantén un tono humano, respetuoso y esperanzador, sin trivializar el sufrimiento del aprendiz.
- No inventes datos personales del aprendiz.
PROMPT;

        $usuario = "Categoría del caso: {$solicitud->tipo_requerimiento}\n"
            ."Estado actual: {$solicitud->estado}\n"
            ."Descripción del aprendiz:\n{$solicitud->descripcion}\n\n"
            .'Analiza el caso y responde únicamente el JSON solicitado.';

        foreach ($this->modelos as $modelo) {
            for ($intento = 1; $intento <= self::MAX_INTENTOS; $intento++) {
                [$texto, $http] = $this->solicitar($sistema, $usuario, $modelo);

                if (is_string($texto)) {
                    $analisis = $this->decodificar($texto);

                    if ($analisis !== null) {
                        Log::info("Gemini IA: análisis generado con el modelo {$modelo} (intento {$intento}).");

                        return $this->normalizar($analisis);
                    }
                }

                if ($http === 404) {
                    Log::warning("Gemini IA: el modelo {$modelo} no está disponible ({$http}), se prueba otro.");

                    break;
                }

                if ($intento < self::MAX_INTENTOS) {
                    usleep(self::ESPERA_INTENTOS[$intento - 1] ?? self::ESPERA_BASE_INTENTOS);
                }
            }
        }

        Log::warning('Gemini IA: no se obtuvo análisis tras agotar modelos e intentos.');

        return null;
    }

    /**
     * Realiza una llamada a Gemini y devuelve [texto|null, código HTTP].
     *
     * @return array{0: ?string, 1: int}
     */
    protected function solicitar(string $sistema, string $usuario, string $modelo): array
    {
        try {
            $respuesta = Http::timeout(30)
                ->withHeaders(['X-Goog-Api-Key' => $this->clave])
                ->asJson()
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$modelo}:generateContent",
                    [
                        'system_instruction' => ['parts' => [['text' => $sistema]]],
                        'contents' => [['role' => 'user', 'parts' => [['text' => $usuario]]]],
                        'generationConfig' => [
                            'responseMimeType' => 'application/json',
                            'temperature' => 0.1,
                        ],
                    ]
                );
        } catch (\Throwable $e) {
            Log::warning('Gemini IA: fallo la llamada HTTP al modelo '.$modelo.'. '.$e->getMessage());

            return [null, 0];
        }

        if ($respuesta->failed()) {
            Log::warning('Gemini IA: respuesta de error HTTP '.$respuesta->status().' con el modelo '.$modelo.': '.mb_substr($respuesta->body(), 0, 400));

            return [null, $respuesta->status()];
        }

        $texto = data_get($respuesta->json(), 'candidates.0.content.parts.0.text');

        return [is_string($texto) ? trim($texto) : null, $respuesta->status()];
    }

    /**
     * Convierte el texto devuelto por el modelo en un array de análisis.
     *
     * @return array<string, mixed>|null
     */
    protected function decodificar(string $texto): ?array
    {
        if ($texto === '') {
            Log::warning('Gemini IA: respuesta sin contenido de texto.');

            return null;
        }

        $analisis = json_decode($this->limpiarJson($texto), true);

        if (! is_array($analisis)) {
            // Intenta extraer el primer bloque JSON completo si el modelo lo rodeó de texto.
            if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $texto, $m)) {
                $analisis = json_decode($m[0], true);
            }
        }

        if (! is_array($analisis)) {
            Log::warning('Gemini IA: JSON inválido. '.mb_substr($texto, 0, 300));

            return null;
        }

        return $analisis;
    }

    /**
     * Aplica el análisis de Gemini a una solicitud y la guarda.
     * Devuelve true solo si el análisis fue exitoso.
     */
    public function aplicarA(Solicitud $solicitud): bool
    {
        $analisis = $this->analizarSolicitud($solicitud);

        if ($analisis === null) {
            return false;
        }

        $solicitud->prioridad = $analisis['prioridad'];
        $solicitud->etiqueta = $analisis['etiqueta'];
        $solicitud->recomendaciones = $analisis['recomendaciones'];
        $solicitud->motivacion = $analisis['motivacion'];
        $solicitud->gestion_admin = $analisis['gestion_admin'];
        $solicitud->alerta = $analisis['delicado'] || in_array($analisis['prioridad'], ['Alta', 'Crítica'], true);
        $solicitud->analizado = true;
        $solicitud->save();

        return true;
    }

    /**
     * Quita marcadores de bloque de código si el modelo los incluyera.
     */
    protected function limpiarJson(string $texto): string
    {
        $texto = trim($texto);

        return (string) preg_replace('/^```(?:json)?\s*/i', '', (string) preg_replace('/\s*```$/', '', $texto));
    }

    /**
     * Asegura que el JSON devuelto por el modelo tenga todas las claves
     * con tipos válidos. La búsqueda de claves ignora mayúsculas/minúsculas.
     */
    protected function normalizar(array $datos): array
    {
        $buscar = function (...$claves) use ($datos) {
            $lower = array_change_key_case($datos, CASE_LOWER);

            foreach ($claves as $clave) {
                $k = strtolower($clave);

                if (array_key_exists($k, $lower)) {
                    return $lower[$k];
                }
            }

            return null;
        };

        $prioridad = (string) ($buscar('prioridad', 'prioridad_del_caso') ?? 'Baja');

        if (! in_array($prioridad, Solicitud::PRIORIDADES, true)) {
            $prioridad = 'Baja';
        }

        $etiqueta = trim((string) ($buscar('etiqueta', 'etiqueta_ia') ?? ''));

        return [
            'prioridad' => $prioridad,
            'delicado' => ! empty($buscar('delicado', 'es_delicado')),
            'etiqueta' => $etiqueta !== '' ? mb_substr($etiqueta, 0, 80) : 'Sin etiquetar',
            'recomendaciones' => trim((string) ($buscar('recomendaciones', 'recomendacion') ?? '')) ?: null,
            'motivacion' => trim((string) ($buscar('motivacion', 'mensaje_motivacional') ?? '')) ?: null,
            'gestion_admin' => trim((string) ($buscar('gestion_admin', 'gestion', 'sugerencia_admin') ?? '')) ?: null,
        ];
    }
}