<table class="table table-sm table-striped table-hover" id="tablaReporte">
    <thead>
        <tr>
            <th>Estado</th>
            <th>Cantidad</th>
            <th>Porcentaje</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $total = $datos->sum('cantidad');
            $colors = [
                'aprobado' => 'success',
                'borrador' => 'secondary',
                'rechazado' => 'danger',
                'archivado' => 'dark'
            ];
        ?>
        <?php $__empty_1 = true; $__currentLoopData = $datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td>
                    <span class="badge bg-<?php echo e($colors[$d['estado']] ?? 'secondary'); ?>">
                        <?php echo e(ucfirst($d['estado'])); ?>

                    </span>
                </td>
                <td><?php echo e($d['cantidad']); ?></td>
                <td>
                    <?php if($total > 0): ?>
                        <?php echo e(number_format(($d['cantidad'] / $total) * 100, 1)); ?>%
                        <div class="progress" style="height: 6px; width: 100px; display: inline-block; margin-left: 8px;">
                            <div class="progress-bar bg-<?php echo e($colors[$d['estado']] ?? 'secondary'); ?>" 
                                 style="width: <?php echo e(($d['cantidad'] / $total) * 100); ?>%">
                            </div>
                        </div>
                    <?php else: ?>
                        0%
                    <?php endif; ?>
                </td>
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
            <td><?php echo e($total); ?></td>
            <td>100%</td>
        </tr>
    </tfoot>
</table><?php /**PATH C:\laragon\www\constructor-erp\resources\views/reportes/parciales/por-estado.blade.php ENDPATH**/ ?>