<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\EvaluacionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('pacientes.index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    // Cualquier usuario logueado puede ver
    Route::get('pacientes', [PacienteController::class, 'index'])->name('pacientes.index');

    // Solo personal administrativo y administrador pueden crear/editar/eliminar
    Route::middleware(['rol:personal_administrativo,administrador'])->group(function () {
        Route::get('pacientes/create', [PacienteController::class, 'create'])->name('pacientes.create');
        Route::post('pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
        Route::get('pacientes/{paciente}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
        Route::put('pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');
        Route::delete('pacientes/{paciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');
    });

        // Solo fisioterapeuta y administrador finalizan o reactivan un tratamiento
    Route::middleware(['rol:fisioterapeuta,administrador'])->group(function () {
        Route::patch('pacientes/{paciente}/alta', [PacienteController::class, 'darAlta'])->name('pacientes.alta');
        Route::patch('pacientes/{paciente}/reactivar', [PacienteController::class, 'reactivar'])->name('pacientes.reactivar');
    });

    // Esta va al final porque {paciente} coincide con cualquier texto (incluido "create")
    Route::get('pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
});


Route::middleware(['auth'])->group(function () {
    // Cualquier usuario logueado puede ver la agenda
    Route::get('citas', [CitaController::class, 'index'])->name('citas.index');

    // Solo personal administrativo y administrador gestionan citas
    Route::middleware(['rol:personal_administrativo,administrador'])->group(function () {
        Route::get('citas/create', [CitaController::class, 'create'])->name('citas.create');
        Route::post('citas', [CitaController::class, 'store'])->name('citas.store');
        Route::get('citas/{cita}/edit', [CitaController::class, 'edit'])->name('citas.edit');
        Route::put('citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
        Route::delete('citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
    });
});


Route::middleware(['auth'])->group(function () {
    Route::get('tratamientos', [TratamientoController::class, 'index'])->name('tratamientos.index');

    Route::middleware(['rol:fisioterapeuta,administrador'])->group(function () {
        Route::get('tratamientos/create', [TratamientoController::class, 'create'])->name('tratamientos.create');
        Route::post('tratamientos', [TratamientoController::class, 'store'])->name('tratamientos.store');
        Route::get('tratamientos/{tratamiento}/edit', [TratamientoController::class, 'edit'])->name('tratamientos.edit');
        Route::put('tratamientos/{tratamiento}', [TratamientoController::class, 'update'])->name('tratamientos.update');
        Route::delete('tratamientos/{tratamiento}', [TratamientoController::class, 'destroy'])->name('tratamientos.destroy');
    });

    Route::get('tratamientos/{tratamiento}', [TratamientoController::class, 'show'])->name('tratamientos.show');
});


// Historial de evoluciones de un paciente: cualquier usuario que pueda ver la ficha lo puede descargar
Route::middleware(['auth'])->get('reportes/evoluciones', [ReporteController::class, 'evolucionesPaciente'])
    ->name('reportes.evoluciones');

Route::middleware(['auth', 'rol:personal_administrativo,administrador'])->group(function () {
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/descargar', [ReporteController::class, 'descargar'])->name('reportes.descargar');
});


Route::middleware(['auth'])->group(function () {
    // Solo fisioterapeuta y administrador registran, editan o eliminan evaluaciones
    Route::middleware(['rol:fisioterapeuta,administrador'])->group(function () {
        Route::get('pacientes/{paciente}/evaluaciones/create', [EvaluacionController::class, 'create'])->name('evaluaciones.create');
        Route::post('pacientes/{paciente}/evaluaciones', [EvaluacionController::class, 'store'])->name('evaluaciones.store');
        Route::get('evaluaciones/{evaluacion}/edit', [EvaluacionController::class, 'edit'])->name('evaluaciones.edit');
        Route::put('evaluaciones/{evaluacion}', [EvaluacionController::class, 'update'])->name('evaluaciones.update');
        Route::delete('evaluaciones/{evaluacion}', [EvaluacionController::class, 'destroy'])->name('evaluaciones.destroy');
    });

    // Cualquier usuario logueado puede ver y descargar
    Route::get('evaluaciones/{evaluacion}/pdf', [EvaluacionController::class, 'pdf'])->name('evaluaciones.pdf');
    Route::get('evaluaciones/{evaluacion}', [EvaluacionController::class, 'show'])->name('evaluaciones.show');
});


Route::middleware(['auth', 'rol:administrador'])->group(function () {
    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::patch('usuarios/{usuario}/activar', [UserController::class, 'activar'])->name('usuarios.activar');
    Route::get('bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';