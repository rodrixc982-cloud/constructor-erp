<table class="table table-sm table-striped table-hover" id="tablaReporte">
    <thead>
        <tr>
            <th>Obra</th>
            <th>Cliente</th>
            <th>Estado</th>
            <th>N° presupuestos</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($d['obra']); ?></td>
                <td><?php echo e($d['cliente']); ?></td>
                <td>
                    <?php
                        $colors = [
                            'activa' => 'success',
                            'planificacion' => 'secondary',
                            'pausada' => 'warning',
                            'terminada' => 'info',
                            'cancelada' => 'danger'
                        ];
                    ?>
                    <span class="badge bg-<?php echo e($colors[$d['estado']] ?? 'secondary'); ?>">
                        <?php echo e(ucfirst($d['estado'])); ?>

                    </span>
                </td>
                <td><?php echo e($d['presupuestos']); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4" class="text-center text-muted">No hay datos disponibles</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td colspan="3">TOTAL</td>
            <td><?php echo e($datos->sum('presupuestos')); ?></td>
        </tr>
    </tfoot>
</table><?php /**PATH C:\laragon\www\constructor-erp\resources\views/reportes/parciales/por-proyecto.blade.php ENDPATH**/ ?>