<table class="table table-sm table-striped table-hover" id="tablaReporte">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Stock</th>
            <th>Precio Venta (S/)</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><code><?php echo e($d['codigo']); ?></code></td>
                <td><?php echo e($d['nombre']); ?></td>
                <td><?php echo e($d['categoria']); ?></td>
                <td><?php echo e($d['stock']); ?></td>
                <td><?php echo e(number_format($d['precio_venta'], 2)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="text-center text-muted">No hay datos disponibles</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td colspan="4">TOTAL</td>
            <td><?php echo e(number_format($datos->sum('precio_venta'), 2)); ?></td>
        </tr>
    </tfoot>
</table><?php /**PATH C:\laragon\www\constructor-erp\resources\views/reportes/parciales/por-material.blade.php ENDPATH**/ ?>