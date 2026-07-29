<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #222; }
        header { display: flex; justify-content: space-between; border-bottom: 2px solid #2a5298; padding-bottom: 10px; margin-bottom: 14px; }
        h1 { font-size: 16px; color: #2a5298; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        th { background: #2a5298; color: #fff; padding: 6px; text-align: left; font-size: 10px; }
        td { padding: 5px 6px; border-bottom: 1px solid #ddd; font-size: 10px; }
        .text-end { text-align: right; }
        .totales td { border: none; padding: 3px 6px; }
        .totales tr.total td { font-weight: bold; font-size: 13px; border-top: 2px solid #2a5298; }
        footer { position: fixed; bottom: -30px; font-size: 9px; color: #888; }
        .firma { margin-top: 40px; text-align: center; }
        .firma img { max-height: 50px; }
    </style>
</head>
<body>
    <header>
        <div>
            <?php if($empresa?->logo): ?><img src="<?php echo e(public_path('storage/'.$empresa->logo)); ?>" style="max-height:50px"><br><?php endif; ?>
            <h1><?php echo e($empresa->nombre ?? config('app.name')); ?></h1>
            <small>RUC: <?php echo e($empresa->ruc ?? '—'); ?> | <?php echo e($empresa->direccion); ?></small>
        </div>
        <div class="text-end">
            <strong>PRESUPUESTO <?php echo e($presupuesto->codigo); ?></strong> (v<?php echo e($presupuesto->version); ?>)<br>
            <small>Fecha: <?php echo e($presupuesto->fecha->format('d/m/Y')); ?></small><br>
            <small>Válido por <?php echo e($presupuesto->validez_dias); ?> días</small>
        </div>
    </header>

    <p>
        <strong>Cliente:</strong> <?php echo e($presupuesto->cliente?->nombre ?? '—'); ?> &nbsp;|&nbsp;
        <strong>Obra:</strong> <?php echo e($presupuesto->obra?->nombre ?? '—'); ?> &nbsp;|&nbsp;
        <strong>Responsable:</strong> <?php echo e($presupuesto->responsable?->name ?? '—'); ?>

    </p>

    <table>
        <thead><tr><th>#</th><th>Descripción</th><th>Und</th><th class="text-end">Metrado</th><th class="text-end">P. Unit.</th><th class="text-end">Subtotal</th></tr></thead>
        <tbody>
            <?php $__currentLoopData = $presupuesto->partidas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i + 1); ?></td><td><?php echo e($p->descripcion); ?></td><td><?php echo e($p->unidad); ?></td>
                <td class="text-end"><?php echo e(number_format($p->metrado, 2)); ?></td>
                <td class="text-end"><?php echo e(number_format($p->precio_unitario, 2)); ?></td>
                <td class="text-end"><?php echo e(number_format($p->subtotal, 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php if($presupuesto->gastos->count()): ?>
    <table>
        <thead><tr><th>Otros gastos</th><th class="text-end">Cantidad</th><th class="text-end">P. Unit.</th><th class="text-end">Subtotal</th></tr></thead>
        <tbody>
            <?php $__currentLoopData = $presupuesto->gastos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr><td><?php echo e($g->concepto); ?> (<?php echo e($g->tipo); ?>)</td><td class="text-end"><?php echo e(number_format($g->cantidad, 2)); ?></td><td class="text-end"><?php echo e(number_format($g->precio_unitario, 2)); ?></td><td class="text-end"><?php echo e(number_format($g->subtotal, 2)); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    <table class="totales" style="width:300px; margin-left:auto;">
        <tr><td>Subtotal partidas</td><td class="text-end"><?php echo e($presupuesto->moneda); ?> <?php echo e(number_format($totales['subtotal_partidas'], 2)); ?></td></tr>
        <tr><td>Subtotal otros gastos</td><td class="text-end"><?php echo e($presupuesto->moneda); ?> <?php echo e(number_format($totales['subtotal_gastos'], 2)); ?></td></tr>
        <tr><td>Costo directo</td><td class="text-end"><?php echo e($presupuesto->moneda); ?> <?php echo e(number_format($totales['costo_directo'], 2)); ?></td></tr>
        <tr><td>Descuento (<?php echo e($presupuesto->descuento_pct); ?>%)</td><td class="text-end">- <?php echo e($presupuesto->moneda); ?> <?php echo e(number_format($totales['descuento'], 2)); ?></td></tr>
        <tr><td>Base imponible</td><td class="text-end"><?php echo e($presupuesto->moneda); ?> <?php echo e(number_format($totales['base_imponible'], 2)); ?></td></tr>
        <tr><td>IGV (<?php echo e($presupuesto->igv); ?>%)</td><td class="text-end"><?php echo e($presupuesto->moneda); ?> <?php echo e(number_format($totales['monto_igv'], 2)); ?></td></tr>
        <tr class="total"><td>TOTAL GENERAL</td><td class="text-end"><?php echo e($presupuesto->moneda); ?> <?php echo e(number_format($totales['total_general'], 2)); ?></td></tr>
    </table>

    <?php if($presupuesto->observaciones): ?>
    <p style="margin-top:16px"><strong>Observaciones:</strong> <?php echo e($presupuesto->observaciones); ?></p>
    <?php endif; ?>

    <?php if($empresa?->firma): ?>
    <div class="firma"><img src="<?php echo e(public_path('storage/'.$empresa->firma)); ?>"><br><small><?php echo e($empresa->nombre); ?></small></div>
    <?php endif; ?>

    <footer><?php echo e($empresa->pie_pagina_pdf ?? ''); ?></footer>
</body>
</html>
<?php /**PATH C:\laragon\www\constructor-erp\resources\views/presupuestos/pdf.blade.php ENDPATH**/ ?>