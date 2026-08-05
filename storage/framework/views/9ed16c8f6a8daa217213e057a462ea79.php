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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            background: #0a0a0a;
            position: relative;
            overflow: hidden;
        }

        /* Fondo de albañil construyendo casa */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                /* Cielo */
                linear-gradient(180deg, #1a2a4a 0%, #3a6b9a 40%, #87CEEB 70%),
                /* Nubes */
                radial-gradient(ellipse at 20% 15%, rgba(255,255,255,0.3) 0%, transparent 40%),
                radial-gradient(ellipse at 70% 20%, rgba(255,255,255,0.2) 0%, transparent 35%),
                /* Sol */
                radial-gradient(circle at 85% 12%, #FFD93D 0%, #FF6B35 30%, transparent 50%);
            z-index: 0;
        }

        /* Capa de construcción - albañil y casa */
        body::after {
            content: '';
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65%;
            background:
                /* Tierra / suelo */
                linear-gradient(180deg, transparent 0%, #8B7355 50%, #6B4226 70%, #4A2F1A 100%),
                /* Casa en construcción - estructura */
                /* Paredes */
                linear-gradient(90deg, transparent 0%, transparent 25%, #D4A574 25%, #D4A574 35%, transparent 35%, transparent 55%, #C4956A 55%, #C4956A 68%, transparent 68%, transparent 78%, #D4A574 78%, #D4A574 88%, transparent 88%),
                /* Techo en construcción */
                linear-gradient(130deg, transparent 0%, transparent 20%, #8B0000 20%, #8B0000 28%, transparent 28%, transparent 45%, #A52A2A 45%, #A52A2A 55%, transparent 55%, transparent 70%, #8B0000 70%, #8B0000 78%, transparent 78%),
                /* Ventanas */
                radial-gradient(circle at 30% 45%, #4A90D9 5%, transparent 5%),
                radial-gradient(circle at 62% 45%, #4A90D9 5%, transparent 5%),
                /* Puerta */
                linear-gradient(90deg, transparent 0%, transparent 43%, #5D3A1A 43%, #5D3A1A 52%, transparent 52%),
                /* Andamios */
                linear-gradient(90deg, transparent 0%, transparent 10%, #FF8C00 10%, #FF8C00 11%, transparent 11%),
                linear-gradient(90deg, transparent 0%, transparent 20%, #FF8C00 20%, #FF8C00 21%, transparent 21%),
                linear-gradient(90deg, transparent 0%, transparent 70%, #FF8C00 70%, #FF8C00 71%, transparent 71%),
                /* Ladrillos / bloques */
                repeating-linear-gradient(90deg,
                    transparent 0%,
                    transparent 8%,
                    #C4956A 8%,
                    #C4956A 9%,
                    transparent 9%,
                    transparent 17%
                ),
                /* Albañil - cuerpo */
                radial-gradient(ellipse at 22% 55%, #FFDAB9 6%, transparent 6%),
                radial-gradient(ellipse at 22% 45%, #FFDAB9 5%, transparent 5%),
                /* Casco amarillo */
                radial-gradient(ellipse at 22% 40%, #FFD700 5%, transparent 5%),
                /* Cuerpo - overol */
                radial-gradient(ellipse at 22% 60%, #2C3E50 8%, transparent 8%),
                /* Brazos */
                radial-gradient(ellipse at 18% 55%, #FFDAB9 3%, transparent 3%),
                radial-gradient(ellipse at 26% 55%, #FFDAB9 3%, transparent 3%),
                /* Paleta / cuchara */
                linear-gradient(140deg, transparent 0%, transparent 23%, #C0C0C0 23%, #C0C0C0 24%, transparent 24%),
                /* Ladrillos en el piso */
                radial-gradient(ellipse at 30% 85%, #C4956A 3%, transparent 3%),
                radial-gradient(ellipse at 35% 82%, #B8860B 2.5%, transparent 2.5%),
                radial-gradient(ellipse at 40% 86%, #C4956A 3%, transparent 3%),
                /* Carretilla */
                radial-gradient(ellipse at 50% 80%, #8B7355 4%, transparent 4%),
                /* Escalera */
                linear-gradient(160deg, transparent 0%, transparent 15%, #FF8C00 15%, #FF8C00 16%, transparent 16%),
                linear-gradient(170deg, transparent 0%, transparent 45%, #FF8C00 45%, #FF8C00 46%, transparent 46%),
                /* Grúa - pluma */
                linear-gradient(130deg, transparent 0%, transparent 75%, #FFD700 75%, #FFD700 76%, transparent 76%);
            background-size: 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%;
            background-repeat: no-repeat;
            background-position: bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom, bottom;
            opacity: 0.9;
            z-index: 0;
            pointer-events: none;
        }

        /* Overlay oscuro para legibilidad */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
            overflow: hidden;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 80px rgba(0, 0, 0, 0.8);
        }

        .login-brand {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: #fff;
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .login-brand .brand-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            margin: 0 auto 12px;
            border: 1px solid rgba(255, 215, 0, 0.2);
            font-size: 2rem;
            color: #FFD700;
        }

        .login-brand h4 {
            font-weight: 800;
            letter-spacing: 1px;
            color: #fff;
        }

        .login-brand small {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
            letter-spacing: 2px;
        }

        .login-body {
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.95);
        }

        .form-label {
            font-weight: 600;
            color: #1a1a2e;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e8ecf1;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: #FFD700;
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.15);
            background: #fff;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-check-input:checked {
            background-color: #FFD700;
            border-color: #FFD700;
        }

        .btn-login {
            background: linear-gradient(135deg, #FFD700 0%, #F4A460 100%);
            border: none;
            border-radius: 12px;
            padding: 0.85rem;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.5px;
            color: #1a1a2e;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
            color: #1a1a2e;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            transition: transform 0.3s ease;
        }

        .btn-login:hover i {
            transform: translateX(5px);
        }

        .forgot-link {
            color: #6c757d;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #FFD700;
        }

        .login-divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            border-top: 2px solid #e8ecf1;
        }

        .login-divider span {
            padding: 0 1rem;
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Animación de partículas de construcción */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 215, 0, 0.3);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) rotate(720deg);
                opacity: 0;
            }
        }

        .particle:nth-child(1) { left: 5%; animation-duration: 18s; animation-delay: 0s; width: 6px; height: 6px; }
        .particle:nth-child(2) { left: 15%; animation-duration: 14s; animation-delay: 2s; }
        .particle:nth-child(3) { left: 25%; animation-duration: 20s; animation-delay: 4s; width: 8px; height: 8px; background: rgba(255, 107, 53, 0.3); }
        .particle:nth-child(4) { left: 35%; animation-duration: 16s; animation-delay: 1s; }
        .particle:nth-child(5) { left: 45%; animation-duration: 22s; animation-delay: 3s; }
        .particle:nth-child(6) { left: 55%; animation-duration: 13s; animation-delay: 5s; width: 5px; height: 5px; }
        .particle:nth-child(7) { left: 65%; animation-duration: 19s; animation-delay: 2s; background: rgba(255, 215, 0, 0.4); }
        .particle:nth-child(8) { left: 75%; animation-duration: 17s; animation-delay: 4s; }
        .particle:nth-child(9) { left: 85%; animation-duration: 21s; animation-delay: 1s; width: 7px; height: 7px; }
        .particle:nth-child(10) { left: 95%; animation-duration: 15s; animation-delay: 3s; }

        @media (max-width: 768px) {
            .login-body {
                padding: 1.5rem;
            }

            .login-brand {
                padding: 1.5rem 1rem;
            }

            .login-brand .brand-icon {
                width: 55px;
                height: 55px;
                font-size: 1.5rem;
            }

            body::after {
                height: 50%;
                opacity: 0.6;
            }
        }

        @media (max-width: 576px) {
            .login-card {
                border-radius: 15px;
            }

            .login-body {
                padding: 1.25rem;
            }

            .btn-login {
                padding: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay oscuro -->
    <div class="overlay"></div>

    <!-- Partículas -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card login-card">
                    <!-- Brand -->
                    <div class="login-brand text-center">
                        <div class="brand-icon">
                            <i class="fas fa-hard-hat"></i>
                        </div>
                        <h4 class="mb-0">Constructor ERP</h4>
                        <small><i class="fas fa-tools me-1"></i> Presupuestos para construcción</small>
                    </div>

                    <!-- Form -->
                    <div class="login-body">
                        <form method="POST" action="<?php echo e(route('login')); ?>" id="formLogin">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope me-2 text-muted"></i>Correo electrónico
                                </label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                                       class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       placeholder="tucorreo@ejemplo.com" required autofocus>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock me-2 text-muted"></i>Contraseña
                                </label>
                                <input type="password" name="password"
                                       class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       placeholder="••••••••" required>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Recordarme</label>
                                </div>
                                <a href="<?php echo e(route('password.request')); ?>" class="forgot-link text-decoration-none small">
                                    <i class="fas fa-key me-1"></i>¿Olvidaste tu contraseña?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-login w-100 py-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                            </button>

                            <div class="login-divider">
                                <span>Acceso seguro</span>
                            </div>

                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i> Conexión encriptada SSL
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.0/sweetalert2.all.min.js"></script>
    <?php if($errors->any()): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'No se pudo iniciar sesión',
                text: <?php echo json_encode($errors->first(), 15, 512) ?>,
                confirmButtonColor: '#FFD700',
                confirmButtonText: 'Intentar de nuevo',
                background: '#fff',
                customClass: {
                    popup: 'rounded-4'
                }
            });
        </script>
    <?php endif; ?>
    <?php if(session('status')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Listo!',
                text: <?php echo json_encode(session('status'), 15, 512) ?>,
                confirmButtonColor: '#FFD700',
                confirmButtonText: 'Continuar',
                background: '#fff',
                customClass: {
                    popup: 'rounded-4'
                }
            });
        </script>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\laragon\www\constructor-erp\resources\views/auth/login.blade.php ENDPATH**/ ?>