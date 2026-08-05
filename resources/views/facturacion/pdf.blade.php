<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ strtoupper(str_replace('_', ' ', $documento->tipo)) }} - {{ $documento->numero_completo }}</title>
    <style>
        /* ========== ESTILOS GENERALES ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #ffffff;
            padding: 30px;
            line-height: 1.5;
        }

        /* ========== CONTENEDOR PRINCIPAL ========== */
        .documento-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 35px 40px 30px 40px;
            border: 1px solid #e8ecf1;
        }

        /* ========== HEADER ========== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #2a5298;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .empresa-info {
            flex: 1;
        }

        .empresa-info .logo {
            font-size: 22px;
            font-weight: 700;
            color: #2a5298;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .empresa-info .logo span {
            color: #e74c3c;
        }

        .empresa-info .detalle {
            font-size: 10px;
            color: #5a6c7e;
            margin-top: 2px;
        }

        .empresa-info .detalle strong {
            color: #2a5298;
        }

        /* ========== TIPO DE DOCUMENTO ========== */
        .tipo-documento {
            background: linear-gradient(135deg, #2a5298 0%, #1a3a6a 100%);
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            text-align: center;
            min-width: 180px;
            box-shadow: 0 4px 12px rgba(42, 82, 152, 0.25);
        }

        .tipo-documento .titulo {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .tipo-documento .numero {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .tipo-documento .fecha {
            font-size: 9px;
            opacity: 0.85;
            margin-top: 4px;
        }

        /* ========== CLIENTE ========== */
        .cliente-section {
            background: #f8f9fc;
            border-radius: 8px;
            padding: 15px 18px;
            margin-bottom: 22px;
            border-left: 4px solid #2a5298;
        }

        .cliente-section .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #7a8a9e;
            font-weight: 600;
        }

        .cliente-section .nombre {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .cliente-section .doc-info {
            font-size: 10px;
            color: #5a6c7e;
            margin-top: 2px;
        }

        /* ========== TABLA DE DETALLES ========== */
        .detalles-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 18px 0 22px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .detalles-table thead th {
            background: #2a5298;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 10px 14px;
            text-align: left;
            font-weight: 600;
        }

        .detalles-table thead th.text-end {
            text-align: right;
        }

        .detalles-table tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid #edf1f7;
            font-size: 11px;
            background: #ffffff;
        }

        .detalles-table tbody tr:last-child td {
            border-bottom: none;
        }

        .detalles-table .text-end {
            text-align: right;
        }

        .detalles-table .text-center {
            text-align: center;
        }

        .detalles-table .total-row td {
            background: #f8f9fc;
            font-weight: 700;
            border-top: 2px solid #2a5298;
            padding: 12px 14px;
        }

        .detalles-table .total-row .label {
            font-weight: 600;
            color: #1a1a2e;
        }

        .detalles-table .total-row .valor {
            font-size: 16px;
            color: #2a5298;
        }

        /* ========== TOTALES ========== */
        .totales {
            margin-top: 10px;
        }

        .totales .fila {
            display: flex;
            justify-content: flex-end;
            padding: 4px 0;
            font-size: 11px;
        }

        .totales .fila .label {
            color: #5a6c7e;
            width: 140px;
            text-align: right;
            padding-right: 16px;
        }

        .totales .fila .valor {
            width: 120px;
            text-align: right;
            font-weight: 500;
        }

        .totales .fila.total-final {
            border-top: 2px solid #2a5298;
            padding-top: 10px;
            margin-top: 6px;
        }

        .totales .fila.total-final .label {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 13px;
        }

        .totales .fila.total-final .valor {
            font-size: 18px;
            font-weight: 800;
            color: #2a5298;
        }

        /* ========== OBSERVACIONES ========== */
        .observaciones {
            margin-top: 20px;
            padding: 14px 18px;
            background: #f8f9fc;
            border-radius: 8px;
            border-left: 3px solid #e74c3c;
        }

        .observaciones .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #7a8a9e;
            font-weight: 600;
        }

        .observaciones .texto {
            font-size: 11px;
            color: #2d3a4a;
            margin-top: 3px;
        }

        /* ========== FOOTER ========== */
        .footer {
            margin-top: 30px;
            padding-top: 18px;
            border-top: 1px solid #e8ecf1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #8a9aa8;
        }

        .footer .gracias {
            font-size: 11px;
            font-weight: 500;
            color: #2a5298;
        }

        .footer .info-legal {
            text-align: right;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 600px) {
            .documento-wrapper {
                padding: 20px;
            }
            .header {
                flex-direction: column;
                gap: 15px;
            }
            .tipo-documento {
                width: 100%;
            }
            .totales .fila {
                justify-content: space-between;
            }
            .totales .fila .label {
                width: auto;
                text-align: left;
                padding-right: 10px;
            }
            .totales .fila .valor {
                width: auto;
            }
        }
    </style>
</head>
<body>

<div class="documento-wrapper">

    <!-- ========== HEADER ========== -->
    <div class="header">
        <div class="empresa-info">
            <div class="logo">
                {{ $empresa->nombre ?? config('app.name') }}
                <span>·</span>
            </div>
            <div class="detalle">
                <strong>RUC:</strong> {{ $empresa->ruc ?? '—' }} &nbsp;|&nbsp; 
                <strong>Tel:</strong> {{ $empresa->telefono ?? '—' }} &nbsp;|&nbsp;
                <strong>Email:</strong> {{ $empresa->email ?? '—' }}
            </div>
            <div class="detalle">
                {{ $empresa->direccion ?? '' }}
                @if($empresa->distrito ?? false)
                    - {{ $empresa->distrito }}, {{ $empresa->departamento ?? '' }}
                @endif
            </div>
        </div>

        <div class="tipo-documento">
            <div class="titulo">
                {{ strtoupper(str_replace('_', ' ', $documento->tipo)) }}
            </div>
            <div class="numero">
                {{ $documento->numero_completo }}
            </div>
            <div class="fecha">
                {{ $documento->fecha ? $documento->fecha->format('d/m/Y') : '—' }}
            </div>
        </div>
    </div>

    <!-- ========== CLIENTE ========== -->
    <div class="cliente-section">
        <div class="label">Cliente</div>
        <div class="nombre">
            {{ $documento->cliente?->nombre ?? '—' }}
        </div>
        <div class="doc-info">
            @if($documento->cliente)
                <strong>RUC:</strong> {{ $documento->cliente->ruc ?? '—' }}
                @if($documento->cliente->dni)
                    &nbsp;|&nbsp; <strong>DNI:</strong> {{ $documento->cliente->dni }}
                @endif
                @if($documento->cliente->telefono)
                    &nbsp;|&nbsp; <strong>Tel:</strong> {{ $documento->cliente->telefono }}
                @endif
                @if($documento->cliente->email)
                    &nbsp;|&nbsp; <strong>Email:</strong> {{ $documento->cliente->email }}
                @endif
            @else
                <span style="color:#8a9aa8;">Cliente no registrado</span>
            @endif
        </div>
    </div>

    <!-- ========== TABLA DE DETALLES ========== -->
    <table class="detalles-table">
        <thead>
            <tr>
                <th style="width:60%;">Descripción</th>
                <th class="text-end" style="width:20%;">Precio</th>
                <th class="text-end" style="width:20%;">Importe</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí puedes iterar los items del documento -->
            @if($documento->items && $documento->items->count() > 0)
                @foreach($documento->items as $item)
                <tr>
                    <td>{{ $item->descripcion ?? 'Producto/Servicio' }}</td>
                    <td class="text-end">
                        S/ {{ number_format($item->precio_unitario ?? 0, 2) }}
                    </td>
                    <td class="text-end">
                        S/ {{ number_format($item->subtotal ?? 0, 2) }}
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" style="text-align:center; color:#8a9aa8; padding:20px;">
                        Sin items registrados
                    </td>
                </tr>
            @endif

            <!-- ========== FILA DE TOTALES ========== -->
            <tr class="total-row">
                <td colspan="2" class="text-end label">SUBTOTAL</td>
                <td class="text-end">S/ {{ number_format($documento->subtotal, 2) }}</td>
            </tr>
            <tr class="total-row" style="border-top: none;">
                <td colspan="2" class="text-end label">IGV ({{ $documento->igv_porcentaje ?? 18 }}%)</td>
                <td class="text-end">S/ {{ number_format($documento->igv, 2) }}</td>
            </tr>
            <tr class="total-row" style="border-top: none; background: #f0f4ff;">
                <td colspan="2" class="text-end label" style="font-size: 13px; color: #2a5298;">
                    <strong>TOTAL</strong>
                </td>
                <td class="text-end valor" style="font-size: 18px; color: #2a5298;">
                    S/ {{ number_format($documento->total, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- ========== OBSERVACIONES ========== -->
    @if($documento->observaciones)
    <div class="observaciones">
        <div class="label">Observaciones</div>
        <div class="texto">{{ $documento->observaciones }}</div>
    </div>
    @endif

    <!-- ========== FOOTER ========== -->
    <div class="footer">
        <div class="gracias">
            ¡Gracias por su preferencia!
        </div>
        <div class="info-legal">
            <div>Documento generado electrónicamente</div>
            <div style="font-size:8px; color:#b0bcc8; margin-top:2px;">
                {{ $empresa->nombre ?? config('app.name') }} &bull; {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

</div>

</body>
</html>