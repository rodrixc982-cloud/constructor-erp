<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #222; }
        header { display: flex; justify-content: space-between; border-bottom: 2px solid #2a5298; padding-bottom: 8px; margin-bottom: 12px; }
        h1 { font-size: 16px; color: #2a5298; margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2a5298; color: #fff; padding: 6px; text-align: left; font-size: 10px; }
        td { padding: 5px 6px; border-bottom: 1px solid #ddd; font-size: 10px; }
        .text-end { text-align: right; }
        footer { position: fixed; bottom: -20px; font-size: 9px; color: #888; }
    </style>
</head>
<body>
    <header>
        <div>
            <h1>{{ $empresa->nombre ?? config('app.name') }}</h1>
            <small>RUC: {{ $empresa->ruc ?? '—' }}</small>
        </div>
        <div class="text-end">
            <strong>Reporte de Materiales</strong><br>
            <small>Generado: {{ now()->format('d/m/Y H:i') }}</small>
        </div>
    </header>

    <table>
        <thead>
            <tr>
                <th>Código</th><th>Nombre</th><th>Marca</th><th>Categoría</th><th>Unidad</th>
                <th class="text-end">P. Compra</th><th class="text-end">P. Venta</th><th class="text-end">Stock</th><th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materiales as $m)
            <tr>
                <td>{{ $m->codigo }}</td>
                <td>{{ $m->nombre }}</td>
                <td>{{ $m->marca }}</td>
                <td>{{ $m->categoria?->nombre }}</td>
                <td>{{ $m->unidad }}</td>
                <td class="text-end">{{ number_format($m->precio_compra, 2) }}</td>
                <td class="text-end">{{ number_format($m->precio_venta, 2) }}</td>
                <td class="text-end">{{ number_format($m->stock, 2) }}</td>
                <td>{{ $m->estado ? 'Activo' : 'Inactivo' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>{{ $empresa->pie_pagina_pdf ?? '' }}</footer>
</body>
</html>
