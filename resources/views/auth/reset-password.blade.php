<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:480px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h5 class="mb-3">Establecer nueva contraseña</h5>
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo electrónico" value="{{ old('email', $request->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nueva contraseña" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña" required>
                </div>
                <button class="btn btn-primary w-100">Restablecer contraseña</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
