<?php

namespace App\Http\Controllers;

use App\Models\Conversacion;
use App\Models\Solicitud;
use App\Notifications\NuevoMensajeDeBienestar;
use App\Services\GeminiService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SolicitudController extends Controller
{
    public function __construct(private readonly GeminiService $gemini)
    {
    }

    /**
     * Lista las solicitudes con filtros y búsqueda.
     */
    public function index(Request $request)
    {
        $solicitudes = $this->buildQuery($request)
            ->with('conversaciones')
            ->orderByDesc('alerta')
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $estados = Solicitud::ESTADOS;

        $conteos = [
            'Todos' => Solicitud::count(),
            'Pendiente' => Solicitud::where('estado', 'Pendiente')->count(),
            'En Proceso' => Solicitud::where('estado', 'En Proceso')->count(),
            'Resuelto' => Solicitud::where('estado', 'Resuelto')->count(),
            'Prioritarias' => Solicitud::where('alerta', true)->count(),
            'Eliminaciones' => Solicitud::where('eliminacion_estado', Solicitud::ELIMINACION_SOLICITADA)->count(),
        ];

        $chatsPendientes = Conversacion::where('estado', Conversacion::ESTADO_SOLICITADA)->count();

        return view('solicitudes.index', compact('solicitudes', 'estados', 'conteos', 'chatsPendientes'));
    }

    /**
     * Exporta a Excel (CSV con codificación UTF-8) la lista de solicitudes
     * respetando los filtros activos en la vista.
     */
    public function exportar(Request $request)
    {
        $solicitudes = $this->buildQuery($request)
            ->orderByDesc('alerta')
            ->latest('created_at')
            ->get();

        $nombre = 'reporte_solicitudes_' . now()->format('Y-m-d_H-i');

        return response()->streamDownload(function () use ($solicitudes) {
            $salida = fopen('php://output', 'w');

            // BOM UTF-8 para que Excel muestre correctamente los caracteres acentuados.
            fwrite($salida, "\xEF\xBB\xBF");

            fputcsv($salida, [
                'Nombre completo del Aprendiz',
                'Documento / Identificacion',
                'Programa de formacion',
                'Ficha',
                'Tipo de solicitud',
                'Descripcion',
                'Estado',
                'Fecha y hora de registro',
            ]);

            foreach ($solicitudes as $solicitud) {
                fputcsv($salida, [
                    $solicitud->nombre_aprendiz,
                    (string) $solicitud->documento,
                    (string) $solicitud->programa,
                    (string) $solicitud->ficha,
                    (string) $solicitud->tipo_requerimiento,
                    (string) $solicitud->descripcion,
                    (string) $solicitud->estado,
                    $solicitud->created_at?->format('d/m/Y H:i'),
                ]);
            }

            fclose($salida);
        }, $nombre . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Muestra el formulario para registrar una solicitud.
     */
    public function create()
    {
        return view('solicitudes.create');
    }

    /**
     * Almacena una solicitud nueva.
     */
    public function store(Request $request)
    {
        $datos = $this->validateDatos($request);

        $solicitud = new Solicitud($datos);
        $solicitud->created_at = now();
        $solicitud->updated_at = now();
        $solicitud->save();

        $this->gemini->aplicarA($solicitud);

        return redirect()
            ->route('solicitudes.index')
            ->with('exito', 'Solicitud registrada correctamente.');
    }

    /**
     * Recalcula el análisis de IA (prioridad, etiqueta y recomendaciones).
     */
    public function analizarIa(Solicitud $solicitud)
    {
        $analizado = $this->gemini->aplicarA($solicitud);

        return back()->with(
            $analizado ? 'exito' : 'error',
            $analizado
                ? 'Análisis con IA actualizado para esta solicitud.'
                : 'No fue posible analizar la solicitud ahora. Inténtalo de nuevo en un momento.'
        );
    }

    /**
     * Muestra el detalle de una solicitud.
     */
    public function show(Solicitud $solicitud)
    {
        $conversacion = $solicitud->conversaciones()->with('mensajes.autor')->latest()->first();

        return view('solicitudes.show', compact('solicitud', 'conversacion'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Solicitud $solicitud)
    {
        return view('solicitudes.edit', compact('solicitud'));
    }

    /**
     * Actualiza una solicitud.
     */
    public function update(Request $request, Solicitud $solicitud)
    {
        $datos = $this->validateDatos($request);

        $solicitud->update($datos);

        return redirect()
            ->route('solicitudes.show', $solicitud)
            ->with('exito', 'Solicitud actualizada correctamente.');
    }

    /**
     * Cambia el estado de una solicitud desde la lista o el detalle.
     */
    public function cambiarEstado(Request $request, Solicitud $solicitud)
    {
        $datos = $request->validate([
            'estado' => ['required', 'string', Rule::in(Solicitud::ESTADOS)],
        ]);

        $solicitud->update($datos);

        return redirect()
            ->back()
            ->with('exito', 'Estado actualizado a «' . $datos['estado'] . '».');
    }

    /**
     * Natalia aprueba la petición de eliminación del aprendiz: borra la solicitud.
     */
    public function aprobarEliminacion(Solicitud $solicitud)
    {
        if (!$solicitud->peticionEliminacionPendiente()) {
            return back()->with('error', 'Esta solicitud no tiene una petición de eliminación pendiente.');
        }

        $solicitud->delete();

        return redirect()
            ->route('solicitudes.index')
            ->with('exito', 'Petición aprobada. La solicitud del aprendiz fue eliminada.');
    }

    /**
     * Natalia rechaza la petición de eliminación del aprendiz y se lo notifica.
     */
    public function rechazarEliminacion(Request $request, Solicitud $solicitud)
    {
        if (!$solicitud->peticionEliminacionPendiente()) {
            return back()->with('error', 'Esta solicitud no tiene una petición de eliminación pendiente.');
        }

        $solicitud->update([
            'eliminacion_estado' => Solicitud::ELIMINACION_RECHAZADA,
            'eliminacion_decidida_at' => now(),
        ]);

        if ($solicitud->user) {
            $solicitud->user->notify(new NuevoMensajeDeBienestar(
                $solicitud,
                'Respuesta sobre tu solicitud de eliminación',
                'Bienestar revisó tu petición y decidió mantener tu solicitud activa: tu caso sigue en atención.',
                'mensaje'
            ));
        }

        return back()
            ->with('exito', 'Petición rechazada. El aprendiz fue notificado de que su solicitud sigue activa.');
    }

    /**
     * Validación compartida para crear y actualizar solicitudes.
     */
    private function validateDatos(Request $request): array
    {
        return $request->validate([
            'nombre_aprendiz' => ['required', 'string', 'max:255'],
            'documento' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'programa' => ['nullable', 'string', 'max:255'],
            'ficha' => ['nullable', 'string', 'max:50'],
            'tipo_requerimiento' => ['required', 'string', Rule::in(Solicitud::TIPOS)],
            'descripcion' => ['required', 'string', 'max:5000'],
            'estado' => ['required', 'string', Rule::in(Solicitud::ESTADOS)],
        ]);
    }

    /**
     * Construye la consulta base compartida entre la lista y la exportación,
     * aplicando los filtros activos: estado, prioridad, chat, fechas y búsqueda.
     */
    private function buildQuery(Request $request): Builder
    {
        $query = Solicitud::query();

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('alerta')) {
            $query->where('alerta', true);
        }

        if ($request->input('eliminacion') === 'pendiente') {
            $query->where('eliminacion_estado', Solicitud::ELIMINACION_SOLICITADA);
        }

        if ($request->filled('chat')) {
            $estadoChat = $request->input('chat') === 'activa'
                ? Conversacion::ESTADO_ACTIVA
                : Conversacion::ESTADO_SOLICITADA;

            $query->whereHas('conversaciones', function ($q) use ($estadoChat) {
                $q->where('estado', $estadoChat);
            });
        }

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->input('desde'));
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->input('hasta'));
        }

        if ($request->filled('buscar')) {
            $buscar = trim($request->input('buscar'));

            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_aprendiz', 'like', "%{$buscar}%")
                    ->orWhere('documento', 'like', "%{$buscar}%");
            });
        }

        return $query;
    }
}