<?php $__env->startSection('titulo', 'Activar 2FA'); ?>

<?php $__env->startSection('contenido'); ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Escanea el código QR</h3></div>
            <div class="card-body text-center">
                <p class="text-muted small">Usa Google Authenticator, Authy o similar para escanear este código.</p>
                <?php echo QrCode::size(200)->generate($qrCodeUrl); ?>

                <p class="mt-2 small text-muted">Clave manual: <code><?php echo e($secreto); ?></code></p>

                <form method="POST" action="<?php echo e(route('two-factor.confirm')); ?>" class="mt-3">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="code" maxlength="6" class="form-control text-center mb-2 <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Código de 6 dígitos" required>
                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <button class="btn btn-primary w-100">Confirmar y activar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/profile/two-factor-enable.blade.php ENDPATH**/ ?>