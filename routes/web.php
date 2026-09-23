<?php

use App\Http\Controllers\AprendicesController;
use App\Http\Controllers\AprendizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\SolicitudController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/registro', [AuthController::class, 'showRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registro'])->name('registro.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
 * Panel de gestión del Administrador (Natalia).
 */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/solicitudes/exportar', [SolicitudController::class, 'exportar'])->name('solicitudes.exportar');
    Route::get('/solicitudes/nueva', [SolicitudController::class, 'create'])->name('solicitudes.create');
    Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');
    Route::get('/solicitudes/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');
    Route::get('/solicitudes/{solicitud}/editar', [SolicitudController::class, 'edit'])->name('solicitudes.edit');
    Route::put('/solicitudes/{solicitud}', [SolicitudController::class, 'update'])->name('solicitudes.update');
    Route::patch('/solicitudes/{solicitud}/estado', [SolicitudController::class, 'cambiarEstado'])->name('solicitudes.estado');
    Route::post('/solicitudes/{solicitud}/ia', [SolicitudController::class, 'analizarIa'])->name('solicitudes.ia');
    Route::get('/solicitudes/{solicitud}/chat/mensajes', [ChatController::class, 'mensajes'])->name('solicitudes.chat.mensajes');
    Route::post('/solicitudes/{solicitud}/chat/mensajes', [ChatController::class, 'enviar'])->name('solicitudes.chat.enviar');
    Route::post('/solicitudes/{solicitud}/chat/iniciar', [ChatController::class, 'iniciar'])->name('solicitudes.chat.iniciar');
    Route::post('/solicitudes/{solicitud}/chat/cerrar', [ChatController::class, 'cerrar'])->name('solicitudes.chat.cerrar');
    Route::post('/solicitudes/{solicitud}/eliminacion/aprobar', [SolicitudController::class, 'aprobarEliminacion'])->name('solicitudes.eliminacion.aprobar');
    Route::post('/solicitudes/{solicitud}/eliminacion/rechazar', [SolicitudController::class, 'rechazarEliminacion'])->name('solicitudes.eliminacion.rechazar');

    Route::get('/aprendices', [AprendicesController::class, 'index'])->name('aprendices.index');
    Route::get('/aprendices/nuevo', [AprendicesController::class, 'create'])->name('aprendices.create');
    Route::post('/aprendices', [AprendicesController::class, 'store'])->name('aprendices.store');
    Route::get('/aprendices/{aprendiz}', [AprendicesController::class, 'show'])->name('aprendices.show');
});

/*
 * Portal del Aprendiz.
 */
Route::middleware(['auth', 'role:aprendiz'])->prefix('aprendiz')->name('portal.')->group(function () {
    Route::get('/', [AprendizController::class, 'index'])->name('index');
    Route::get('/solicitudes/crear', [AprendizController::class, 'create'])->name('solicitudes.create');
    Route::post('/solicitudes', [AprendizController::class, 'store'])->name('solicitudes.store');
    Route::get('/solicitudes/{solicitud}', [AprendizController::class, 'show'])->name('solicitudes.show');
    Route::get('/solicitudes/{solicitud}/editar', [AprendizController::class, 'edit'])->name('solicitudes.edit');
    Route::put('/solicitudes/{solicitud}', [AprendizController::class, 'update'])->name('solicitudes.update');
    Route::post('/solicitudes/{solicitud}/ia', [AprendizController::class, 'analizarIa'])->name('solicitudes.ia');
    Route::post('/solicitudes/{solicitud}/eliminacion', [AprendizController::class, 'solicitarEliminacion'])->name('solicitudes.eliminacion.solicitar');

    Route::post('/solicitudes/{solicitud}/chat', [ChatController::class, 'solicitar'])->name('solicitudes.chat.solicitar');
    Route::get('/solicitudes/{solicitud}/chat/mensajes', [ChatController::class, 'mensajes'])->name('solicitudes.chat.mensajes');
    Route::post('/solicitudes/{solicitud}/chat/mensajes', [ChatController::class, 'enviar'])->name('solicitudes.chat.enviar');

    Route::get('/notificaciones/no-leidas', [NotificacionController::class, 'noLeidas'])->name('notificaciones.no-leidas');
    Route::post('/notificaciones/leidas', [NotificacionController::class, 'marcarLeidas'])->name('notificaciones.marcar-leidas');
});