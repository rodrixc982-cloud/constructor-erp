<?php $__env->startSection('titulo', 'Datos de la Empresa'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Empresa</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header"><h3 class="card-title">Configuración de la empresa</h3></div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('empresa.update')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <img src="<?php echo e($empresa->logo ? asset('storage/'.$empresa->logo) : asset('images/logo-default.png')); ?>" class="img-fluid mb-2" style="max-height:100px">
                    <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                    <small class="text-muted">Logo (aparece en PDFs)</small>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Nombre *</label><input type="text" name="nombre" class="form-control" value="<?php echo e(old('nombre', $empresa->nombre)); ?>" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">RUC *</label><input type="text" name="ruc" class="form-control" value="<?php echo e(old('ruc', $empresa->ruc)); ?>" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Dirección</label><input type="text" name="direccion" class="form-control" value="<?php echo e(old('direccion', $empresa->direccion)); ?>"></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" value="<?php echo e(old('telefono', $empresa->telefono)); ?>"></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo e(old('email', $empresa->email)); ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Página web</label><input type="url" name="pagina_web" class="form-control" value="<?php echo e(old('pagina_web', $empresa->pagina_web)); ?>"></div>
                        <div class="col-md-3 mb-3"><label class="form-label">IGV / IVA (%)</label><input type="number" step="0.01" name="igv" class="form-control" value="<?php echo e(old('igv', $empresa->igv ?? 18)); ?>" required></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Moneda</label><input type="text" name="moneda" class="form-control" value="<?php echo e(old('moneda', $empresa->moneda ?? 'PEN')); ?>" required></div>
                    </div>
                </div>
            </div>

            <hr>
            <h6>Redes sociales</h6>
            <div class="row">
                <div class="col-md-4 mb-3"><label class="form-label">Facebook</label><input type="url" name="redes_sociales[facebook]" class="form-control" value="<?php echo e(old('redes_sociales.facebook', $empresa->redes_sociales['facebook'] ?? '')); ?>"></div>
                <div class="col-md-4 mb-3"><label class="form-label">Instagram</label><input type="url" name="redes_sociales[instagram]" class="form-control" value="<?php echo e(old('redes_sociales.instagram', $empresa->redes_sociales['instagram'] ?? '')); ?>"></div>
                <div class="col-md-4 mb-3"><label class="form-label">TikTok</label><input type="url" name="redes_sociales[tiktok]" class="form-control" value="<?php echo e(old('redes_sociales.tiktok', $empresa->redes_sociales['tiktok'] ?? '')); ?>"></div>
            </div>

            <hr>
            <h6>Documentos PDF</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Firma digital (imagen)</label>
                    <?php if($empresa->firma): ?><div class="mb-1"><img src="<?php echo e(asset('storage/'.$empresa->firma)); ?>" style="max-height:60px"></div><?php endif; ?>
                    <input type="file" name="firma" class="form-control" accept="image/*">
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Pie de página en PDFs</label>
                    <textarea name="pie_pagina_pdf" class="form-control" rows="2"><?php echo e(old('pie_pagina_pdf', $empresa->pie_pagina_pdf)); ?></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar cambios</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/empresa/edit.blade.php ENDPATH**/ ?>