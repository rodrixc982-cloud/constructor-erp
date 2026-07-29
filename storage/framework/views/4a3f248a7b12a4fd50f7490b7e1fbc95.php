<table class="table table-sm table-striped table-hover" id="tablaReporte">
    <thead>
        <tr>
            <th>Mes</th>
            <th>Cantidad</th>
            <th>Total (S/)</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($d['nombre_mes'] ?? $d['mes']); ?></td>
                <td><?php echo e($d['cantidad']); ?></td>
                <td><?php echo e(number_format($d['total'] ?? 0, 2)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td>TOTAL</td>
            <td><?php echo e($datos->sum('cantidad')); ?></td>
            <td><?php echo e(number_format($datos->sum('total'), 2)); ?></td>
        </tr>
    </tfoot>
</table><?php /**PATH C:\laragon\www\constructor-erp\resources\views/reportes/parciales/utilidad-mensual.blade.php ENDPATH**/ ?>