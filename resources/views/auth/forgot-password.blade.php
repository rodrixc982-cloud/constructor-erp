<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:480px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h5 class="mb-3">Recuperar contraseña</h5>
            <p class="text-muted small">Te enviaremos un enlace a tu correo para restablecerla.</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-primary w-100">Enviar enlace</button>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small">Volver al login</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
