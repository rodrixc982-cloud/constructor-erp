@extends('layouts.app')
@section('titulo', 'Presupuesto '.$presupuesto->codigo)
@push('estilos')<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">@endpush
@section('breadcrumbs')<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li><li class="breadcrumb-item"><a href="{{ route('presupuestos.index') }}">Presupuestos</a></li><li class="breadcrumb-item active">{{ $presupuesto->codigo }}</li>@endsection

@section('contenido')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h4 class="mb-0">{{ $presupuesto->codigo }} <span class="badge bg-secondary" id="badgeVersion">v{{ $presupuesto->version }}</span> <span class="badge" id="badgeEstado">{{ $presupuesto->estado }}</span></h4>
        <small class="text-muted">{{ $presupuesto->cliente?->nombre ?? 'Sin cliente' }} — {{ $presupuesto->obra?->nombre ?? 'Sin obra' }}</small>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-secondary btn-sm" id="btnDuplicar"><i class="fas fa-copy me-1"></i>Duplicar</button>
        <button class="btn btn-outline-secondary btn-sm" id="btnNuevaVersion"><i class="fas fa-code-branch me-1"></i>Nueva versión</button>
        <button class="btn btn-outline-success btn-sm" id="btnAprobar"><i class="fas fa-check me-1"></i>Aprobar</button>
        <button class="btn btn-outline-danger btn-sm" id="btnRechazar"><i class="fas fa-times me-1"></i>Rechazar</button>
        <button class="btn btn-outline-dark btn-sm" id="btnArchivar"><i class="fas fa-archive me-1"></i>Archivar</button>
        <a href="/presupuestos/{{ $presupuesto->id }}/exportar/pdf" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>Exportar PDF</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Partidas (desde APU o manuales)</h3>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" id="btnAgregarPartidaApu"><i class="fas fa-plus me-1"></i>Desde APU</button>
                    <button class="btn btn-outline-primary btn-sm" id="btnAgregarPartidaManual"><i class="fas fa-plus me-1"></i>Manual</button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead><tr><th>#</th><th>Descripción</th><th>Und</th><th style="width:110px">Metrado</th><th style="width:120px">P. Unit.</th><th style="width:120px">Subtotal</th><th></th></tr></thead>
                    <tbody id="tbodyPartidas"></tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Otros gastos (transporte, hospedaje, viáticos, seguros, herramientas, otros)</h3>
                <button class="btn btn-outline-primary btn-sm" id="btnAgregarGasto"><i class="fas fa-plus me-1"></i>Agregar gasto</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead><tr><th>Tipo</th><th>Concepto</th><th style="width:110px">Cantidad</th><th style="width:120px">P. Unit.</th><th style="width:120px">Subtotal</th><th></th></tr></thead>
                    <tbody id="tbodyGastos"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Resumen</h3></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td>Subtotal partidas</td><td class="text-end" id="t_partidas">0.00</td></tr>
                    <tr><td>Subtotal otros gastos</td><td class="text-end" id="t_gastos">0.00</td></tr>
                    <tr class="fw-bold"><td>Costo directo</td><td class="text-end" id="t_directo">0.00</td></tr>
                    <tr><td>Descuento</td><td class="text-end text-danger" id="t_descuento">0.00</td></tr>
                    <tr><td>Base imponible</td><td class="text-end" id="t_base">0.00</td></tr>
                    <tr><td>IGV</td><td class="text-end" id="t_igv">0.00</td></tr>
                    <tr class="table-primary fw-bold fs-5"><td>TOTAL GENERAL</td><td class="text-end" id="t_total">0.00</td></tr>
                </table>
                <hr>
                <form id="formCabecera">
                    <div class="mb-2"><label class="form-label small">IGV %</label><input type="number" step="0.01" name="igv" class="form-control form-control-sm" value="{{ $presupuesto->igv }}"></div>
                    <div class="mb-2"><label class="form-label small">Descuento %</label><input type="number" step="0.01" name="descuento_pct" class="form-control form-control-sm" value="{{ $presupuesto->descuento_pct }}"></div>
                    <input type="hidden" name="fecha" value="{{ $presupuesto->fecha->format('Y-m-d') }}">
                    <input type="hidden" name="validez_dias" value="{{ $presupuesto->validez_dias }}">
                    <input type="hidden" name="moneda" value="{{ $presupuesto->moneda }}">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Actualizar cabecera</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modales --}}
<div class="modal fade" id="modalPartidaApu" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form id="formPartidaApu">
        <div class="modal-header"><h5 class="modal-title">Agregar partida desde APU</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">APU *</label>
                <select name="apu_id" class="form-select select2" required>
                    @foreach($apus as $a)<option value="{{ $a->id }}">{{ $a->codigo }} — {{ $a->descripcion }} ({{ $a->unidad }})</option>@endforeach
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Metrado *</label><input type="number" step="0.0001" name="metrado" class="form-control" required></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Agregar</button></div>
    </form>
</div></div></div>

<div class="modal fade" id="modalPartidaManual" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form id="formPartidaManual">
        <div class="modal-header"><h5 class="modal-title">Agregar partida manual</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Descripción *</label><input type="text" name="descripcion" class="form-control" required></div>
            <div class="row">
                <div class="col-4 mb-3"><label class="form-label">Unidad *</label><input type="text" name="unidad" class="form-control" required></div>
                <div class="col-4 mb-3"><label class="form-label">Metrado *</label><input type="number" step="0.0001" name="metrado" class="form-control" required></div>
                <div class="col-4 mb-3"><label class="form-label">P. Unit. *</label><input type="number" step="0.0001" name="precio_unitario" class="form-control" required></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Agregar</button></div>
    </form>
</div></div></div>

<div class="modal fade" id="modalGasto" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form id="formGasto">
        <div class="modal-header"><h5 class="modal-title">Agregar gasto</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Tipo *</label>
                <select name="tipo" class="form-select" required>
                    @foreach(['transporte'=>'Transporte','hospedaje'=>'Hospedaje','viaticos'=>'Viáticos','seguro'=>'Seguro','herramienta'=>'Herramienta','otro'=>'Otro'] as $v=>$l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Concepto *</label><input type="text" name="concepto" class="form-control" required></div>
            <div class="row">
                <div class="col-6 mb-3"><label class="form-label">Cantidad *</label><input type="number" step="0.0001" name="cantidad" class="form-control" value="1" required></div>
                <div class="col-6 mb-3"><label class="form-label">P. Unit. *</label><input type="number" step="0.0001" name="precio_unitario" class="form-control" required></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Agregar</button></div>
    </form>
</div></div></div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const presupuestoId = {{ $presupuesto->id }};
$('.select2').select2({ dropdownParent: $('#modalPartidaApu'), width: '100%' });

function pintarTotales(t) {
    document.getElementById('t_partidas').textContent = parseFloat(t.subtotal_partidas).toFixed(2);
    document.getElementById('t_gastos').textContent = parseFloat(t.subtotal_gastos).toFixed(2);
    document.getElementById('t_directo').textContent = parseFloat(t.costo_directo).toFixed(2);
    document.getElementById('t_descuento').textContent = parseFloat(t.descuento).toFixed(2);
    document.getElementById('t_base').textContent = parseFloat(t.base_imponible).toFixed(2);
    document.getElementById('t_igv').textContent = parseFloat(t.monto_igv).toFixed(2);
    document.getElementById('t_total').textContent = parseFloat(t.total_general).toFixed(2);
}

function pintarPartidas(partidas) {
    const tbody = document.getElementById('tbodyPartidas');
    tbody.innerHTML = '';
    partidas.forEach((p, i) => {
        tbody.innerHTML += `<tr>
            <td>${i + 1}</td>
            <td>${p.descripcion}${p.apu_id ? ' <span class="badge bg-info">APU</span>' : ''}</td>
            <td>${p.unidad}</td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm inp-editar-partida" data-id="${p.id}" data-campo="metrado" value="${p.metrado}"></td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm inp-editar-partida" data-id="${p.id}" data-campo="precio_unitario" value="${p.precio_unitario}"></td>
            <td class="text-end">${parseFloat(p.subtotal).toFixed(2)}</td>
            <td><button class="btn btn-sm btn-outline-danger btnEliminarPartida" data-id="${p.id}"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    });
}

function pintarGastos(gastos) {
    const tbody = document.getElementById('tbodyGastos');
    tbody.innerHTML = '';
    gastos.forEach(g => {
        tbody.innerHTML += `<tr>
            <td>${g.tipo}</td>
            <td>${g.concepto}</td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm inp-editar-gasto" data-id="${g.id}" data-campo="cantidad" value="${g.cantidad}"></td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm inp-editar-gasto" data-id="${g.id}" data-campo="precio_unitario" value="${g.precio_unitario}"></td>
            <td class="text-end">${parseFloat(g.subtotal).toFixed(2)}</td>
            <td><button class="btn btn-sm btn-outline-danger btnEliminarGasto" data-id="${g.id}"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    });
}

function cargarPresupuesto() {
    fetch(`/presupuestos/${presupuestoId}`).then(r => r.json()).then(({ data }) => {
        pintarTotales(data.totales);
        pintarPartidas(data.partidas);
        pintarGastos(data.gastos);
        document.getElementById('badgeVersion').textContent = 'v' + data.version;
        document.getElementById('badgeEstado').textContent = data.estado;
    });
}
cargarPresupuesto();

document.getElementById('btnAgregarPartidaApu').onclick = () => new bootstrap.Modal('#modalPartidaApu').show();
document.getElementById('btnAgregarPartidaManual').onclick = () => new bootstrap.Modal('#modalPartidaManual').show();
document.getElementById('btnAgregarGasto').onclick = () => new bootstrap.Modal('#modalGasto').show();

document.getElementById('formPartidaApu').addEventListener('submit', function (e) {
    e.preventDefault();
    fetch(`/presupuestos/${presupuestoId}/partidas/apu`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(Object.fromEntries(new FormData(this).entries())),
    }).then(async r => {
        const data = await r.json();
        if (!r.ok) { Swal.fire({ icon: 'error', title: data.errors ? Object.values(data.errors)[0][0] : 'Error.' }); return; }
        bootstrap.Modal.getInstance(document.getElementById('modalPartidaApu')).hide();
        this.reset();
        cargarPresupuesto();
    });
});

document.getElementById('formPartidaManual').addEventListener('submit', function (e) {
    e.preventDefault();
    fetch(`/presupuestos/${presupuestoId}/partidas/manual`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(Object.fromEntries(new FormData(this).entries())),
    }).then(async r => {
        const data = await r.json();
        if (!r.ok) { Swal.fire({ icon: 'error', title: data.errors ? Object.values(data.errors)[0][0] : 'Error.' }); return; }
        bootstrap.Modal.getInstance(document.getElementById('modalPartidaManual')).hide();
        this.reset();
        cargarPresupuesto();
    });
});

document.getElementById('formGasto').addEventListener('submit', function (e) {
    e.preventDefault();
    fetch(`/presupuestos/${presupuestoId}/gastos`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(Object.fromEntries(new FormData(this).entries())),
    }).then(async r => {
        const data = await r.json();
        if (!r.ok) { Swal.fire({ icon: 'error', title: data.errors ? Object.values(data.errors)[0][0] : 'Error.' }); return; }
        bootstrap.Modal.getInstance(document.getElementById('modalGasto')).hide();
        this.reset();
        cargarPresupuesto();
    });
});

// Edición inline con recálculo en vivo (debounce simple con blur/change).
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('inp-editar-partida')) {
        const fila = e.target.closest('tr');
        const id = e.target.dataset.id;
        const metrado = fila.querySelector('[data-campo="metrado"]').value;
        const precio = fila.querySelector('[data-campo="precio_unitario"]').value;
        fetch(`/presupuestos/partidas/${id}`, {
            method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ descripcion: fila.children[1].textContent, unidad: fila.children[2].textContent, metrado, precio_unitario: precio }),
        }).then(r => r.json()).then(() => cargarPresupuesto());
    }

    if (e.target.classList.contains('inp-editar-gasto')) {
        const fila = e.target.closest('tr');
        const id = e.target.dataset.id;
        const cantidad = fila.querySelector('[data-campo="cantidad"]').value;
        const precio = fila.querySelector('[data-campo="precio_unitario"]').value;
        fetch(`/presupuestos/gastos/${id}`, {
            method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ tipo: fila.children[0].textContent, concepto: fila.children[1].textContent, cantidad, precio_unitario: precio }),
        }).then(r => r.json()).then(() => cargarPresupuesto());
    }
});

document.addEventListener('click', function (e) {
    const be = e.target.closest('.btnEliminarPartida');
    const bg = e.target.closest('.btnEliminarGasto');
    if (be) fetch(`/presupuestos/partidas/${be.dataset.id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } }).then(() => cargarPresupuesto());
    if (bg) fetch(`/presupuestos/gastos/${bg.dataset.id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } }).then(() => cargarPresupuesto());
});

document.getElementById('formCabecera').addEventListener('submit', function (e) {
    e.preventDefault();
    fetch(`/presupuestos/${presupuestoId}`, {
        method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(Object.fromEntries(new FormData(this).entries())),
    }).then(r => r.json()).then(() => { Swal.fire({ icon: 'success', title: 'Cabecera actualizada.', timer: 1500, showConfirmButton: false }); cargarPresupuesto(); });
});

function accionEstado(accion, mensaje) {
    fetch(`/presupuestos/${presupuestoId}/${accion}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } })
        .then(r => r.json()).then(data => { Swal.fire({ icon: 'success', title: data.mensaje, timer: 1500, showConfirmButton: false }); cargarPresupuesto(); });
}
document.getElementById('btnAprobar').onclick = () => accionEstado('aprobar');
document.getElementById('btnRechazar').onclick = () => accionEstado('rechazar');
document.getElementById('btnArchivar').onclick = () => accionEstado('archivar');
document.getElementById('btnDuplicar').onclick = () => fetch(`/presupuestos/${presupuestoId}/duplicar`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } })
    .then(r => r.json()).then(data => { Swal.fire({ icon: 'success', title: data.mensaje }).then(() => window.location.href = `/presupuestos/${data.data.id}/editar`); });
document.getElementById('btnNuevaVersion').onclick = () => fetch(`/presupuestos/${presupuestoId}/nueva-version`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } })
    .then(r => r.json()).then(data => { Swal.fire({ icon: 'success', title: data.mensaje }).then(() => window.location.href = `/presupuestos/${data.data.id}/editar`); });
</script>
@endpush
