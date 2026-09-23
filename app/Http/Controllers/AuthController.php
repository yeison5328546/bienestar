<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Ruta de inicio según el rol del usuario autenticado.
     */
    public function rutaPorRol(): string
    {
        return Auth::user()->esAdmin() ? 'solicitudes.index' : 'portal.index';
    }

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route($this->rutaPorRol());
        }

        return view('auth.login');
    }

    /**
     * Autentica al usuario y lo dirige según su rol.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();

            return redirect()->route($this->rutaPorRol());
        }

        return back()->withErrors([
            'email' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Muestra el formulario de registro de aprendices.
     */
    public function showRegistro()
    {
        if (Auth::check()) {
            return redirect()->route($this->rutaPorRol());
        }

        return view('auth.registro');
    }

    /**
     * Registra una cuenta de Aprendiz y la autentica automáticamente.
     */
    public function registro(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'documento' => ['required', 'string', 'max:50', 'unique:users,documento'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'programa' => ['nullable', 'string', 'max:255'],
            'ficha' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario = User::create([
            'name' => $datos['name'],
            'documento' => $datos['documento'],
            'email' => $datos['email'],
            'programa' => $datos['programa'],
            'ficha' => $datos['ficha'],
            'password' => $datos['password'],
            'role' => User::ROL_APRENDIZ,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()
            ->route('portal.index')
            ->with('exito', '¡Cuenta creada correctamente! Bienvenido(a) al portal de Bienestar al Aprendiz.');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}