<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #222; }
        header { display: flex; justify-content: space-between; border-bottom: 2px solid #2a5298; padding-bottom: 10px; margin-bottom: 16px; }
        h1 { font-size: 16px; color: #2a5298; margin: 0; }
        .caja { border: 1px solid #2a5298; padding: 8px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td, th { padding: 6px; border: 1px solid #ddd; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <header>
        <div>
            <h1>{{ $empresa->nombre ?? config('app.name') }}</h1>
            <small>RUC: {{ $empresa->ruc ?? '—' }}<br>{{ $empresa->direccion }}</small>
        </div>
        <div class="caja">
            <strong>{{ strtoupper(str_replace('_', ' ', $documento->tipo)) }}</strong><br>
            {{ $documento->numero_completo }}<br>
            <small>{{ $documento->fecha->format('d/m/Y') }}</small>
        </div>
    </header>

    <p><strong>Cliente:</strong> {{ $documento->cliente?->nombre ?? '—' }}<br>
       <strong>RUC/DNI:</strong> {{ $documento->cliente?->ruc ?? $documento->cliente?->dni ?? '—' }}</p>

    <table>
        <tr><td>Subtotal</td><td class="text-end">{{ number_format($documento->subtotal, 2) }}</td></tr>
        <tr><td>IGV</td><td class="text-end">{{ number_format($documento->igv, 2) }}</td></tr>
        <tr><td><strong>TOTAL</strong></td><td class="text-end"><strong>{{ number_format($documento->total, 2) }}</strong></td></tr>
    </table>

    @if($documento->observaciones)
    <p style="margin-top:16px"><strong>Observaciones:</strong> {{ $documento->observaciones }}</p>
    @endif
</body>
</html>
