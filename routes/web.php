<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// --- Invitados (no autenticados) ---
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    Route::get('two-factor-challenge', [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
    Route::post('two-factor-challenge', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
});

// --- Autenticados ---
Route::middleware(['auth', 'active'])->group(function () {
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tema
    Route::post('theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');

    // Perfil de usuario
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Autenticación de dos factores
    Route::get('profile/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('profile/two-factor/confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::delete('profile/two-factor', [TwoFactorController::class, 'disable'])->name('two-factor.disable');

    // =============================================
    // MÓDULO: USUARIOS
    // =============================================
    Route::middleware(['auth'])->prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::get('/datos', [UsuarioController::class, 'datos'])->name('datos');
        Route::get('/{user}', [UsuarioController::class, 'show'])->name('show');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::put('/{user}', [UsuarioController::class, 'update'])->name('update');
        Route::delete('/{user}', [UsuarioController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restaurar', [UsuarioController::class, 'restore'])->name('restore');
    });

    // =============================================
    // MÓDULO: CATEGORÍAS (NUEVO)
    // =============================================
    Route::middleware(['auth'])->prefix('categorias')->name('categorias.')->group(function () {
        // Vista principal (Listado)
        Route::get('/', [App\Http\Controllers\CategoriaController::class, 'index'])->name('index');
        
        // Endpoint para DataTable (AJAX)
        Route::get('/datos', [App\Http\Controllers\CategoriaController::class, 'datos'])->name('datos');
        
        // Obtener una categoría específica
        Route::get('/{id}', [App\Http\Controllers\CategoriaController::class, 'show'])->name('show');
        
        // Crear nueva categoría
        Route::post('/', [App\Http\Controllers\CategoriaController::class, 'store'])->name('store');
        
        // Actualizar categoría existente
        Route::put('/{id}', [App\Http\Controllers\CategoriaController::class, 'update'])->name('update');
        
        // Eliminar categoría
        Route::delete('/{id}', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('destroy');
        
        // Cambiar estado (Activar/Desactivar)
        Route::patch('/{id}/toggle-estado', [App\Http\Controllers\CategoriaController::class, 'toggleEstado'])->name('toggle-estado');
    });

    // =============================================
    // MÓDULOS EXISTENTES
    // =============================================
    require __DIR__.'/modules/catalogos.php';
    require __DIR__.'/modules/inventario.php';
    require __DIR__.'/modules/recursos_obra.php';
    require __DIR__.'/modules/presupuestos.php';
    require __DIR__.'/modules/administracion.php';
    require __DIR__.'/modules/obras.php';
    require __DIR__.'/modules/auditoria.php';
    require __DIR__.'/modules/reportes.php';
    require __DIR__.'/modules/usuarios.php';

    // Las siguientes fases registran aquí sus rutas:
    // ...
});