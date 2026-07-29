<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Iniciar sesión — <?php echo e(config('app.name')); ?></title>
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
                    <form method="POST" action="<?php echo e(route('login')); ?>" id="formLogin">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autofocus>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <button type="submit" class="btn btn-login w-100 text-white py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                        </button>
                        <div class="text-center mt-3">
                            <a href="<?php echo e(route('password.request')); ?>" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.0/sweetalert2.all.min.js"></script>
<?php if($errors->any()): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'No se pudo iniciar sesión',
        text: <?php echo json_encode($errors->first(), 15, 512) ?>,
        confirmButtonColor: '#2a5298',
    });
</script>
<?php endif; ?>
<?php if(session('status')): ?>
<script>
    Swal.fire({ icon: 'success', title: <?php echo json_encode(session('status'), 15, 512) ?>, confirmButtonColor: '#2a5298' });
</script>
<?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\constructor-erp\resources\views/auth/login.blade.php ENDPATH**/ ?>