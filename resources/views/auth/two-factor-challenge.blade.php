<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación en dos pasos — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:420px">
    <div class="card shadow-sm">
        <div class="card-body p-4 text-center">
            <i class="fas fa-shield-alt fa-2x text-primary mb-3"></i>
            <h5 class="mb-3">Verificación en dos pasos</h5>
            <p class="text-muted small">Ingresa el código de 6 dígitos de tu app de autenticación.</p>

            <form method="POST" action="{{ route('two-factor.verify') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" name="code" maxlength="6" inputmode="numeric" class="form-control text-center @error('code') is-invalid @enderror" placeholder="000000" autofocus required>
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-primary w-100">Verificar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
