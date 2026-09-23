<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AprendicesController extends Controller
{
    /**
     * Lista los aprendices registrados con búsqueda.
     */
    public function index(Request $request)
    {
        $consulta = User::query()
            ->where('role', User::ROL_APRENDIZ)
            ->withCount('solicitudes');

        if ($request->filled('buscar')) {
            $buscar = trim($request->input('buscar'));

            $consulta->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                    ->orWhere('documento', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%")
                    ->orWhere('programa', 'like', "%{$buscar}%")
                    ->orWhere('ficha', 'like', "%{$buscar}%");
            });
        }

        $aprendices = $consulta->latest()->paginate(15)->withQueryString();

        $conteos = [
            'total' => User::where('role', User::ROL_APRENDIZ)->count(),
            'conSolicitudes' => User::where('role', User::ROL_APRENDIZ)->has('solicitudes')->count(),
            'sinSolicitudes' => User::where('role', User::ROL_APRENDIZ)->whereDoesntHave('solicitudes')->count(),
        ];

        return view('aprendices.index', compact('aprendices', 'conteos'));
    }

    /**
     * Formulario para que Natalia registre un aprendiz manualmente.
     */
    public function create()
    {
        return view('aprendices.create');
    }

    /**
     * Guarda un aprendiz creado por el administrador.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'documento' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'programa' => ['nullable', 'string', 'max:255'],
            'ficha' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create($datos + ['role' => User::ROL_APRENDIZ]);

        return redirect()
            ->route('aprendices.index')
            ->with('exito', 'Aprendiz registrado correctamente. Ya puede iniciar sesión en el portal con su correo y contraseña.');
    }

    /**
     * Muestra el detalle de un aprendiz y sus solicitudes.
     */
    public function show(User $aprendiz)
    {
        abort_unless($aprendiz->esAprendiz(), 404);

        $solicitudes = $aprendiz->solicitudes()->latest()->paginate(10)->withQueryString();

        $conteos = [
            'Pendiente' => $aprendiz->solicitudes()->where('estado', 'Pendiente')->count(),
            'En Proceso' => $aprendiz->solicitudes()->where('estado', 'En Proceso')->count(),
            'Resuelto' => $aprendiz->solicitudes()->where('estado', 'Resuelto')->count(),
        ];

        return view('aprendices.show', compact('aprendiz', 'solicitudes', 'conteos'));
    }
}