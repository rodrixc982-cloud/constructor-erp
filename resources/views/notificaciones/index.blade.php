@extends('layouts.app')
@section('titulo', 'Notificaciones')
@section('breadcrumbs')<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li><li class="breadcrumb-item active">Notificaciones</li>@endsection

@section('contenido')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Todas las notificaciones</h3>
        <button class="btn btn-outline-secondary btn-sm" id="btnMarcarTodas">Marcar todas como leídas</button>
    </div>
    <div class="card-body" id="listaNotificaciones">Cargando...</div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function cargar() {
    fetch('{{ route('notificaciones.datos') }}').then(r => r.json()).then(({ data }) => {
        const contenedor = document.getElementById('listaNotificaciones');
        if (!data.length) { contenedor.innerHTML = '<p class="text-muted">No tienes notificaciones.</p>'; return; }
        contenedor.innerHTML = data.map(n => `
            <div class="d-flex justify-content-between align-items-start border-bottom py-2 ${n.read_at ? 'opacity-50' : ''}">
                <div><strong>${n.data.titulo}</strong><br><small>${n.data.mensaje}</small></div>
                <small class="text-muted">${new Date(n.created_at).toLocaleString('es-PE')}</small>
            </div>`).join('');
    });
}

document.getElementById('btnMarcarTodas').addEventListener('click', () => {
    fetch('{{ route('notificaciones.marcar-todas') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } })
        .then(() => cargar());
});

cargar();
</script>
@endpush
