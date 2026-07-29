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
            @if($empresa?->logo)<img src="{{ public_path('storage/'.$empresa->logo) }}" style="max-height:50px"><br>@endif
            <h1>{{ $empresa->nombre ?? config('app.name') }}</h1>
            <small>RUC: {{ $empresa->ruc ?? '—' }} | {{ $empresa->direccion }}</small>
        </div>
        <div class="text-end">
            <strong>PRESUPUESTO {{ $presupuesto->codigo }}</strong> (v{{ $presupuesto->version }})<br>
            <small>Fecha: {{ $presupuesto->fecha->format('d/m/Y') }}</small><br>
            <small>Válido por {{ $presupuesto->validez_dias }} días</small>
        </div>
    </header>

    <p>
        <strong>Cliente:</strong> {{ $presupuesto->cliente?->nombre ?? '—' }} &nbsp;|&nbsp;
        <strong>Obra:</strong> {{ $presupuesto->obra?->nombre ?? '—' }} &nbsp;|&nbsp;
        <strong>Responsable:</strong> {{ $presupuesto->responsable?->name ?? '—' }}
    </p>

    <table>
        <thead><tr><th>#</th><th>Descripción</th><th>Und</th><th class="text-end">Metrado</th><th class="text-end">P. Unit.</th><th class="text-end">Subtotal</th></tr></thead>
        <tbody>
            @foreach($presupuesto->partidas as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td><td>{{ $p->descripcion }}</td><td>{{ $p->unidad }}</td>
                <td class="text-end">{{ number_format($p->metrado, 2) }}</td>
                <td class="text-end">{{ number_format($p->precio_unitario, 2) }}</td>
                <td class="text-end">{{ number_format($p->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($presupuesto->gastos->count())
    <table>
        <thead><tr><th>Otros gastos</th><th class="text-end">Cantidad</th><th class="text-end">P. Unit.</th><th class="text-end">Subtotal</th></tr></thead>
        <tbody>
            @foreach($presupuesto->gastos as $g)
            <tr><td>{{ $g->concepto }} ({{ $g->tipo }})</td><td class="text-end">{{ number_format($g->cantidad, 2) }}</td><td class="text-end">{{ number_format($g->precio_unitario, 2) }}</td><td class="text-end">{{ number_format($g->subtotal, 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <table class="totales" style="width:300px; margin-left:auto;">
        <tr><td>Subtotal partidas</td><td class="text-end">{{ $presupuesto->moneda }} {{ number_format($totales['subtotal_partidas'], 2) }}</td></tr>
        <tr><td>Subtotal otros gastos</td><td class="text-end">{{ $presupuesto->moneda }} {{ number_format($totales['subtotal_gastos'], 2) }}</td></tr>
        <tr><td>Costo directo</td><td class="text-end">{{ $presupuesto->moneda }} {{ number_format($totales['costo_directo'], 2) }}</td></tr>
        <tr><td>Descuento ({{ $presupuesto->descuento_pct }}%)</td><td class="text-end">- {{ $presupuesto->moneda }} {{ number_format($totales['descuento'], 2) }}</td></tr>
        <tr><td>Base imponible</td><td class="text-end">{{ $presupuesto->moneda }} {{ number_format($totales['base_imponible'], 2) }}</td></tr>
        <tr><td>IGV ({{ $presupuesto->igv }}%)</td><td class="text-end">{{ $presupuesto->moneda }} {{ number_format($totales['monto_igv'], 2) }}</td></tr>
        <tr class="total"><td>TOTAL GENERAL</td><td class="text-end">{{ $presupuesto->moneda }} {{ number_format($totales['total_general'], 2) }}</td></tr>
    </table>

    @if($presupuesto->observaciones)
    <p style="margin-top:16px"><strong>Observaciones:</strong> {{ $presupuesto->observaciones }}</p>
    @endif

    @if($empresa?->firma)
    <div class="firma"><img src="{{ public_path('storage/'.$empresa->firma) }}"><br><small>{{ $empresa->nombre }}</small></div>
    @endif

    <footer>{{ $empresa->pie_pagina_pdf ?? '' }}</footer>
</body>
</html>
