<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Services\GeminiService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AprendizController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly GeminiService $gemini)
    {
    }

    /**
     * Portal del aprendiz: historial de sus solicitudes.
     */
    public function index()
    {
        $solicitudes = auth()->user()->solicitudes()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('aprendiz.index', compact('solicitudes'));
    }

    /**
     * Formulario para reportar una solicitud desde el portal.
     */
    public function create()
    {
        $this->authorize('create', Solicitud::class);

        return view('aprendiz.create');
    }

    /**
     * Registrar la solicitud con los datos de la cuenta del aprendiz.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Solicitud::class);

        $datos = $request->validate([
            'tipo_requerimiento' => ['required', 'string', Rule::in(Solicitud::TIPOS)],
            'documento' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'descripcion' => ['required', 'string', 'max:5000'],
        ]);

        $usuario = $request->user();

        $documento = $usuario->documento ?: ($datos['documento'] ?? null);

        if (!$documento) {
            return back()
                ->withErrors(['documento' => 'Completa tu número de documento para poder registrar la solicitud.'])
                ->withInput();
        }

        $solicitud = Solicitud::create([
            'user_id' => $usuario->id,
            'nombre_aprendiz' => $usuario->name,
            'documento' => $documento,
            'email' => $usuario->email,
            'telefono' => $datos['telefono'] ?? null,
            'programa' => $usuario->programa,
            'ficha' => $usuario->ficha,
            'tipo_requerimiento' => $datos['tipo_requerimiento'],
            'descripcion' => $datos['descripcion'],
            'estado' => 'Pendiente',
        ]);

        $this->gemini->aplicarA($solicitud);

        return redirect()
            ->route('portal.solicitudes.show', $solicitud)
            ->with('exito', 'Tu solicitud fue enviada correctamente. Te dejamos aquí las recomendaciones de Bienestar; podrás corregirla durante los primeros 15 minutos.');
    }

    /**
     * Detalle de una solicitud propia.
     */
    public function show(Request $request, Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $conversacion = $solicitud->conversaciones()->with('mensajes.autor')->latest()->first();

        $usuario = $request->user();
        $usuario->notifications()
            ->where('data->solicitud_id', $solicitud->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('aprendiz.show', compact('solicitud', 'conversacion'));
    }

    /**
     * Formulario para corregir la solicitud dentro de la ventana de edición.
     */
    public function edit(Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        return view('aprendiz.edit', compact('solicitud'));
    }

    /**
     * Actualiza la solicitud del aprendiz.
     */
    public function update(Request $request, Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $datos = $request->validate([
            'tipo_requerimiento' => ['required', 'string', Rule::in(Solicitud::TIPOS)],
            'telefono' => ['nullable', 'string', 'max:30'],
            'descripcion' => ['required', 'string', 'max:5000'],
        ]);

        $solicitud->update($datos);

        return redirect()
            ->route('portal.solicitudes.show', $solicitud)
            ->with('exito', 'Solicitud corregida correctamente. Recuerda que solo puedes editarla durante los primeros 15 minutos.');
    }

    /**
     * Genera o actualiza las recomendaciones de IA para la solicitud.
     */
    public function analizarIa(Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $analizado = $this->gemini->aplicarA($solicitud);

        return back()->with(
            $analizado ? 'exito' : 'error',
            $analizado
                ? 'Nos tomamos un momento y ya tienes tus recomendaciones. Cuida de ti. '
                : 'No fue posible generar las recomendaciones ahora. Inténtalo de nuevo en un momento.'
        );
    }

    /**
     * Envía a Bienestar la petición de eliminación de una solicitud propia,
     * adjuntando la explicación del aprendiz.
     */
    public function solicitarEliminacion(Request $request, Solicitud $solicitud)
    {
        $this->authorize('solicitarEliminacion', $solicitud);

        $datos = $request->validate([
            'motivo' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $solicitud->update([
            'eliminacion_estado' => Solicitud::ELIMINACION_SOLICITADA,
            'eliminacion_motivo' => $datos['motivo'],
            'eliminacion_solicitada_at' => now(),
            'eliminacion_decidida_at' => null,
        ]);

        return back()
            ->with('exito', 'Tu solicitud de eliminación fue enviada a Bienestar con tu explicación. Natalia revisará tu petición y te informará la decisión.');
    }
}