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
        @forelse($datos as $d)
            <tr>
                <td>{{ $d['obra'] }}</td>
                <td>{{ $d['cliente'] }}</td>
                <td>
                    @php
                        $colors = [
                            'activa' => 'success',
                            'planificacion' => 'secondary',
                            'pausada' => 'warning',
                            'terminada' => 'info',
                            'cancelada' => 'danger'
                        ];
                    @endphp
                    <span class="badge bg-{{ $colors[$d['estado']] ?? 'secondary' }}">
                        {{ ucfirst($d['estado']) }}
                    </span>
                </td>
                <td>{{ $d['presupuestos'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No hay datos disponibles</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td colspan="3">TOTAL</td>
            <td>{{ $datos->sum('presupuestos') }}</td>
        </tr>
    </tfoot>
</table>