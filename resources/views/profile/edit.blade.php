@extends('layouts.app')

@section('titulo', 'Mi perfil')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Perfil</li>
@endsection

@section('contenido')
<div class="row">
    <div class="col-md-4 text-center">
        <div class="card">
            <div class="card-body">
                <img src="{{ $user->avatarUrl() }}" class="img-circle elevation-2 mb-3" style="width:120px;height:120px;object-fit:cover">
                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar" class="form-control mb-2" accept="image/*" required>
                    <button class="btn btn-sm btn-primary w-100"><i class="fas fa-upload me-1"></i>Actualizar foto</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Datos personales</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <button class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Cambiar contraseña</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Contraseña actual</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button class="btn btn-primary">Actualizar contraseña</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Autenticación de dos factores (2FA)</h3></div>
            <div class="card-body">
                @if ($user->two_factor_enabled)
                    <p class="text-success"><i class="fas fa-check-circle me-1"></i>El 2FA está activado en tu cuenta.</p>
                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Desactivar 2FA</button>
                    </form>
                @else
                    <p class="text-muted">Agrega una capa extra de seguridad a tu cuenta.</p>
                    <a href="{{ route('two-factor.enable') }}" class="btn btn-outline-primary btn-sm">Activar 2FA</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
