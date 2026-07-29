<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.0/sweetalert2.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0,0,0,.25);
            overflow: hidden;
        }
        .login-brand {
            background: #16213e;
            color: #fff;
        }
        .btn-login {
            background: #2a5298;
            border: none;
        }
        .btn-login:hover { background: #1e3c72; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card login-card">
                <div class="login-brand text-center py-4">
                    <i class="fas fa-hard-hat fa-2x mb-2"></i>
                    <h4 class="mb-0">Constructor ERP</h4>
                    <small class="opacity-75">Presupuestos para construcción</small>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('login') }}" id="formLogin">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <button type="submit" class="btn btn-login w-100 text-white py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                        </button>
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.0/sweetalert2.all.min.js"></script>
@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'No se pudo iniciar sesión',
        text: @json($errors->first()),
        confirmButtonColor: '#2a5298',
    });
</script>
@endif
@if (session('status'))
<script>
    Swal.fire({ icon: 'success', title: @json(session('status')), confirmButtonColor: '#2a5298' });
</script>
@endif
</body>
</html>
