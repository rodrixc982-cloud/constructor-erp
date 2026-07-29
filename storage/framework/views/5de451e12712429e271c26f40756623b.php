<?php $__env->startSection('titulo', 'Configuración del sistema'); ?>
<?php $__env->startSection('breadcrumbs'); ?><li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li><li class="breadcrumb-item active">Configuración</li><?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<form method="POST" action="<?php echo e(route('configuracion.update')); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header"><h3 class="card-title">General</h3></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Nombre del sistema</label><input type="text" name="sistema_nombre" class="form-control" value="<?php echo e(old('sistema_nombre', $configuraciones['sistema_nombre'])); ?>"></div>
                    <div class="mb-3"><label class="form-label">Color primario (tema)</label><input type="color" name="color_primario" class="form-control form-control-color w-100" value="<?php echo e(old('color_primario', $configuraciones['color_primario'] ?? '#2a5298')); ?>"></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label">Utilidad por defecto (%)</label><input type="number" step="0.01" name="utilidad_defecto" class="form-control" value="<?php echo e(old('utilidad_defecto', $configuraciones['utilidad_defecto'] ?? 10)); ?>"></div>
                        <div class="col-6 mb-3"><label class="form-label">IGV por defecto (%)</label><input type="number" step="0.01" name="igv_defecto" class="form-control" value="<?php echo e(old('igv_defecto', $configuraciones['igv_defecto'] ?? 18)); ?>"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Orientación PDF por defecto</label>
                        <select name="formato_pdf_orientacion" class="form-select">
                            <option value="portrait" <?php echo e(($configuraciones['formato_pdf_orientacion'] ?? '') == 'portrait' ? 'selected' : ''); ?>>Vertical</option>
                            <option value="landscape" <?php echo e(($configuraciones['formato_pdf_orientacion'] ?? '') == 'landscape' ? 'selected' : ''); ?>>Horizontal</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card mb-3">
                <div class="card-header"><h3 class="card-title">Correo (SMTP)</h3></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Host SMTP</label><input type="text" name="smtp_host" class="form-control" value="<?php echo e(old('smtp_host', $configuraciones['smtp_host'])); ?>"></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label">Puerto</label><input type="number" name="smtp_puerto" class="form-control" value="<?php echo e(old('smtp_puerto', $configuraciones['smtp_puerto'])); ?>"></div>
                        <div class="col-6 mb-3"><label class="form-label">Usuario</label><input type="text" name="smtp_usuario" class="form-control" value="<?php echo e(old('smtp_usuario', $configuraciones['smtp_usuario'])); ?>"></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3 class="card-title">WhatsApp</h3></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">URL de la API</label><input type="url" name="whatsapp_api_url" class="form-control" value="<?php echo e(old('whatsapp_api_url', $configuraciones['whatsapp_api_url'])); ?>"></div>
                    <div class="mb-3"><label class="form-label">Token</label><input type="text" name="whatsapp_api_token" class="form-control" value="<?php echo e(old('whatsapp_api_token', $configuraciones['whatsapp_api_token'])); ?>"></div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar configuración</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/configuracion/edit.blade.php ENDPATH**/ ?>