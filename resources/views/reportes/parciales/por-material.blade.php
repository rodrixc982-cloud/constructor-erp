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
        @forelse($datos as $d)
            <tr>
                <td><code>{{ $d['codigo'] }}</code></td>
                <td>{{ $d['nombre'] }}</td>
                <td>{{ $d['categoria'] }}</td>
                <td>{{ $d['stock'] }}</td>
                <td>{{ number_format($d['precio_venta'], 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No hay datos disponibles</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td colspan="4">TOTAL</td>
            <td>{{ number_format($datos->sum('precio_venta'), 2) }}</td>
        </tr>
    </tfoot>
</table>