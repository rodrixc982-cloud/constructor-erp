<?php $__env->startSection('titulo', 'Mi perfil'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Perfil</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="row">
    <div class="col-md-4 text-center">
        <div class="card">
            <div class="card-body">
                <img src="<?php echo e($user->avatarUrl()); ?>" class="img-circle elevation-2 mb-3" style="width:120px;height:120px;object-fit:cover">
                <form method="POST" action="<?php echo e(route('profile.avatar')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
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
                <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $user->phone)); ?>">
                    </div>
                    <button class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Cambiar contraseña</h3></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('profile.password')); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
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
                <?php if($user->two_factor_enabled): ?>
                    <p class="text-success"><i class="fas fa-check-circle me-1"></i>El 2FA está activado en tu cuenta.</p>
                    <form method="POST" action="<?php echo e(route('two-factor.disable')); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-outline-danger btn-sm">Desactivar 2FA</button>
                    </form>
                <?php else: ?>
                    <p class="text-muted">Agrega una capa extra de seguridad a tu cuenta.</p>
                    <a href="<?php echo e(route('two-factor.enable')); ?>" class="btn btn-outline-primary btn-sm">Activar 2FA</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/profile/edit.blade.php ENDPATH**/ ?>