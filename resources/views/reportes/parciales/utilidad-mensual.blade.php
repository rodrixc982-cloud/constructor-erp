<table class="table table-sm table-striped table-hover" id="tablaReporte">
    <thead>
        <tr>
            <th>Mes</th>
            <th>Cantidad</th>
            <th>Total (S/)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $d)
            <tr>
                <td>{{ $d['nombre_mes'] ?? $d['mes'] }}</td>
                <td>{{ $d['cantidad'] }}</td>
                <td>{{ number_format($d['total'] ?? 0, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td>TOTAL</td>
            <td>{{ $datos->sum('cantidad') }}</td>
            <td>{{ number_format($datos->sum('total'), 2) }}</td>
        </tr>
    </tfoot>
</table>