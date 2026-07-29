<table class="table table-sm table-striped table-hover" id="tablaReporte">
    <thead>
        <tr>
            <th>Estado</th>
            <th>Cantidad</th>
            <th>Porcentaje</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = $datos->sum('cantidad');
            $colors = [
                'aprobado' => 'success',
                'borrador' => 'secondary',
                'rechazado' => 'danger',
                'archivado' => 'dark'
            ];
        @endphp
        @forelse($datos as $d)
            <tr>
                <td>
                    <span class="badge bg-{{ $colors[$d['estado']] ?? 'secondary' }}">
                        {{ ucfirst($d['estado']) }}
                    </span>
                </td>
                <td>{{ $d['cantidad'] }}</td>
                <td>
                    @if($total > 0)
                        {{ number_format(($d['cantidad'] / $total) * 100, 1) }}%
                        <div class="progress" style="height: 6px; width: 100px; display: inline-block; margin-left: 8px;">
                            <div class="progress-bar bg-{{ $colors[$d['estado']] ?? 'secondary' }}" 
                                 style="width: {{ ($d['cantidad'] / $total) * 100 }}%">
                            </div>
                        </div>
                    @else
                        0%
                    @endif
                </td>
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
            <td>{{ $total }}</td>
            <td>100%</td>
        </tr>
    </tfoot>
</table>