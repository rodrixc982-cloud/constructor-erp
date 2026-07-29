@extends('layouts.app')

@section('titulo', 'Auditoría')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .audit-card {
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: none;
        overflow: hidden;
    }
    .audit-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        padding: 16px 24px;
    }
    .audit-card .card-body {
        padding: 20px 24px;
    }
    .filter-card {
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: none;
    }
    .filter-card .card-body {
        padding: 20px 24px;
    }
    .btn-filtrar {
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        transition: all 0.3s ease;
    }
    .btn-filtrar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: #fff;
    }
    .btn-limpiar {
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        border: 1px solid #e9ecef;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .btn-limpiar:hover {
        background: #f8f9fa;
        color: #343a40;
    }
    .badge-modulo {
        font-size: 0.7rem;
        padding: 4px 12px;
        border-radius: 20px;
        background: #e9ecef;
        color: #495057;
        font-weight: 500;
    }
    [data-bs-theme="dark"] .badge-modulo {
        background: #2d3038;
        color: #e8eaed;
    }
    .badge-ip {
        font-size: 0.7rem;
        padding: 4px 12px;
        border-radius: 20px;
        background: #e3f2fd;
        color: #0d6efd;
        font-weight: 500;
        font-family: monospace;
    }
    [data-bs-theme="dark"] .badge-ip {
        background: #1a2a3a;
        color: #64b5f6;
    }
    .badge-user-agent {
        font-size: 0.65rem;
        padding: 3px 10px;
        border-radius: 20px;
        background: #f3e5f5;
        color: #7b1fa2;
        font-weight: 400;
        max-width: 200px;
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: help;
    }
    [data-bs-theme="dark"] .badge-user-agent {
        background: #2a1a3a;
        color: #ce93d8;
    }
    .btn-ver-detalle {
        border-radius: 50%;
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid #667eea;
        color: #667eea;
        background: transparent;
    }
    .btn-ver-detalle:hover {
        transform: scale(1.1);
        background: #667eea;
        color: #fff;
    }
    .btn-ver-detalle i {
        font-size: 0.8rem;
    }
    .detalle-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 8px;
        border-left: 3px solid #667eea;
    }
    [data-bs-theme="dark"] .detalle-item {
        background: #1e2128;
        border-left-color: #667eea;
    }
    .detalle-item .detalle-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 600;
    }
    [data-bs-theme="dark"] .detalle-item .detalle-label {
        color: #9aa0a6;
    }
    .detalle-item .detalle-value {
        font-size: 0.9rem;
        font-weight: 500;
        word-break: break-all;
    }
    .detalle-item .detalle-value code {
        font-size: 0.8rem;
        background: #e9ecef;
        padding: 2px 8px;
        border-radius: 4px;
    }
    [data-bs-theme="dark"] .detalle-item .detalle-value code {
        background: #2d3038;
        color: #e8eaed;
    }
    .stat-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    [data-bs-theme="dark"] .stat-box {
        background: linear-gradient(135deg, #1e2128 0%, #2d3038 100%);
    }
    .stat-box .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        line-height: 1.2;
    }
    .stat-box .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }
    [data-bs-theme="dark"] .stat-box .stat-label {
        color: #9aa0a6;
    }
    .icon-device {
        font-size: 1.2rem;
        margin-right: 6px;
    }
    .table-audit td {
        vertical-align: middle;
    }
    .table-audit .accion-text {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
    }
    @media (max-width: 768px) {
        .stat-box .stat-number {
            font-size: 1.5rem;
        }
        .badge-user-agent {
            max-width: 100px;
        }
    }
</style>
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Auditoría</li>
@endsection

@section('contenido')
{{-- Estadísticas rápidas --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="stat-box">
            <div class="stat-number" id="statTotal">0</div>
            <div class="stat-label">Total actividades</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box">
            <div class="stat-number" id="statHoy">0</div>
            <div class="stat-label">Actividades hoy</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box">
            <div class="stat-number" id="statModulos">0</div>
            <div class="stat-label">Módulos usados</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box">
            <div class="stat-number" id="statUsuarios">0</div>
            <div class="stat-label">Usuarios activos</div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="card filter-card mb-3">
    <div class="card-body">
        <div class="row align-items-end">
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="form-label fw-semibold">Módulo</label>
                <select id="filtroLogName" class="form-select">
                    <option value="">Todos los módulos</option>
                    @foreach($logNames as $ln)
                        <option value="{{ $ln }}">{{ ucfirst($ln) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <label class="form-label fw-semibold">Usuario</label>
                <select id="filtroUsuario" class="form-select">
                    <option value="">Todos</option>
                    @foreach($usuarios ?? [] as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <label class="form-label fw-semibold">Desde</label>
                <input type="date" id="filtroDesde" class="form-control">
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <label class="form-label fw-semibold">Hasta</label>
                <input type="date" id="filtroHasta" class="form-control">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-filtrar w-100" id="btnFiltrar">
                    <i class="fas fa-search me-1"></i>Filtrar
                </button>
                <button class="btn btn-limpiar" id="btnLimpiarFiltros" title="Limpiar filtros">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Tabla de auditoría --}}
<div class="card audit-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0">
            <i class="fas fa-history me-2 text-primary"></i>Registro de actividad
        </h3>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-secondary" id="totalRegistros">0 registros</span>
            <button class="btn btn-sm btn-outline-danger" id="btnLimpiarLog" title="Limpiar registros antiguos">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100 table-audit" id="tablaAuditoria">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                    <th>IP / Dispositivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal Detalle --}}
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalle de actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body" id="detalleContenido">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let tabla;

    // Función para obtener el nombre del navegador
    function getBrowserName(userAgent) {
        if (!userAgent) return '—';
        const ua = userAgent.toLowerCase();
        if (ua.includes('chrome')) return 'Chrome';
        if (ua.includes('firefox')) return 'Firefox';
        if (ua.includes('safari') && !ua.includes('chrome')) return 'Safari';
        if (ua.includes('edge')) return 'Edge';
        if (ua.includes('opera')) return 'Opera';
        if (ua.includes('brave')) return 'Brave';
        if (ua.includes('mobile')) return 'Móvil';
        return 'Otro';
    }

    // Función para obtener el ícono del dispositivo
    function getDeviceIcon(userAgent) {
        if (!userAgent) return 'fa-desktop';
        const ua = userAgent.toLowerCase();
        if (ua.includes('mobile') || ua.includes('android') || ua.includes('iphone')) return 'fa-mobile-alt';
        if (ua.includes('tablet') || ua.includes('ipad')) return 'fa-tablet-alt';
        return 'fa-desktop';
    }

    // Función para construir URL con filtros
    function construirUrl() {
        const params = new URLSearchParams();
        const logName = $('#filtroLogName').val();
        const usuario = $('#filtroUsuario').val();
        const desde = $('#filtroDesde').val();
        const hasta = $('#filtroHasta').val();

        if (logName) params.set('log_name', logName);
        if (usuario) params.set('usuario_id', usuario);
        if (desde) params.set('desde', desde);
        if (hasta) params.set('hasta', hasta);

        return '{{ route("auditoria.datos") }}?' + params.toString();
    }

    // Inicializar DataTable
    function inicializarTabla() {
        tabla = $('#tablaAuditoria').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: construirUrl(),
                type: 'GET',
                dataSrc: function(json) {
                    const data = json.data || [];
                    $('#totalRegistros').text(data.length + ' registros');
                    actualizarEstadisticas(data);
                    return data;
                }
            },
            columns: [
                { data: 'fecha' },
                { data: 'usuario' },
                { 
                    data: 'log_name',
                    render: function(data) {
                        return '<span class="badge-modulo">' + (data || '—') + '</span>';
                    }
                },
                { 
                    data: 'descripcion',
                    render: function(data) {
                        return '<span class="accion-text" title="' + (data || '') + '">' + (data || '—') + '</span>';
                    }
                },
                {
                    data: null,
                    render: function(r) {
                        const ip = r.ip || '—';
                        const ua = r.user_agent || '';
                        const browser = getBrowserName(ua);
                        const icon = getDeviceIcon(ua);
                        return `
                            <div class="d-flex flex-column align-items-start gap-1">
                                <span class="badge-ip"><i class="fas fa-network-wired me-1"></i>${ip}</span>
                                ${ua ? `<span class="badge-user-agent" title="${ua}"><i class="fas ${icon} icon-device"></i>${browser}</span>` : ''}
                            </div>
                        `;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(r) {
                        return `
                            <button class="btn btn-ver-detalle" data-id="${r.id || 0}" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            pageLength: 10,
            responsive: true,
        });

        tabla.on('xhr', function() {
            const data = tabla.ajax.json();
            if (data && data.data) {
                $('#totalRegistros').text(data.data.length + ' registros');
                actualizarEstadisticas(data.data);
            }
        });
    }

    // Actualizar estadísticas
    function actualizarEstadisticas(data) {
        if (!data || data.length === 0) {
            $('#statTotal').text(0);
            $('#statHoy').text(0);
            $('#statModulos').text(0);
            $('#statUsuarios').text(0);
            return;
        }

        const total = data.length;
        const hoy = new Date().toISOString().split('T')[0];
        const hoyCount = data.filter(d => d.fecha && d.fecha.startsWith(hoy)).length;
        const modulos = new Set(data.map(d => d.log_name).filter(Boolean));
        const usuarios = new Set(data.map(d => d.usuario).filter(Boolean));

        $('#statTotal').text(total);
        $('#statHoy').text(hoyCount);
        $('#statModulos').text(modulos.size);
        $('#statUsuarios').text(usuarios.size);
    }

    // Botón Filtrar
    $('#btnFiltrar').on('click', function() {
        if (tabla) {
            tabla.ajax.url(construirUrl()).load();
        }
    });

    // Botón Limpiar Filtros
    $('#btnLimpiarFiltros').on('click', function() {
        $('#filtroLogName').val('');
        $('#filtroUsuario').val('');
        $('#filtroDesde').val('');
        $('#filtroHasta').val('');
        if (tabla) {
            tabla.ajax.url(construirUrl()).load();
        }
    });

    // Limpiar Log antiguo
    $('#btnLimpiarLog').on('click', function() {
        Swal.fire({
            title: '¿Limpiar registros antiguos?',
            text: 'Se eliminarán todos los registros de auditoría con más de 90 días.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("auditoria.limpiar") }}',
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Registros eliminados',
                            timer: 3000,
                            showConfirmButton: false
                        });
                        tabla.ajax.reload();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron limpiar los registros'
                        });
                    }
                });
            }
        });
    });

    // Ver Detalle de actividad
    $(document).on('click', '.btn-ver-detalle', function() {
        const id = $(this).data('id');
        
        if (!id) {
            Swal.fire({
                icon: 'info',
                title: 'Sin detalles',
                text: 'Esta actividad no tiene detalles adicionales.',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        $('#detalleContenido').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `);

        $('#modalDetalle').modal('show');

        $.ajax({
            url: '/auditoria/' + id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                const data = response.data || response;
                const browser = getBrowserName(data.user_agent);
                const icon = getDeviceIcon(data.user_agent);

                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detalle-item">
                                <div class="detalle-label">Fecha y hora</div>
                                <div class="detalle-value">${data.fecha || '—'}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detalle-item">
                                <div class="detalle-label">Usuario</div>
                                <div class="detalle-value">${data.usuario || '—'}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detalle-item">
                                <div class="detalle-label">Módulo</div>
                                <div class="detalle-value"><span class="badge-modulo">${data.log_name || '—'}</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detalle-item">
                                <div class="detalle-label">IP</div>
                                <div class="detalle-value"><span class="badge-ip">${data.ip || '—'}</span></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="detalle-item">
                                <div class="detalle-label">Dispositivo / Navegador</div>
                                <div class="detalle-value">
                                    <i class="fas ${icon} me-2"></i>
                                    ${browser}
                                    ${data.user_agent ? `<small class="text-muted d-block mt-1" style="font-size:0.7rem;word-break:break-all;">${data.user_agent}</small>` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="detalle-item">
                                <div class="detalle-label">Acción</div>
                                <div class="detalle-value">${data.descripcion || '—'}</div>
                            </div>
                        </div>
                `;

                if (data.url) {
                    html += `
                        <div class="col-md-12">
                            <div class="detalle-item">
                                <div class="detalle-label">URL</div>
                                <div class="detalle-value"><code>${data.url}</code></div>
                            </div>
                        </div>
                    `;
                }

                if (data.method) {
                    html += `
                        <div class="col-md-12">
                            <div class="detalle-item">
                                <div class="detalle-label">Método HTTP</div>
                                <div class="detalle-value"><span class="badge bg-primary">${data.method}</span></div>
                            </div>
                        </div>
                    `;
                }

                if (data.valores_anteriores) {
                    html += `
                        <div class="col-md-6">
                            <div class="detalle-item" style="border-left-color: #dc3545;">
                                <div class="detalle-label">Valores anteriores</div>
                                <div class="detalle-value">
                                    <pre style="font-size:0.75rem;background:#f8f9fa;padding:8px;border-radius:4px;max-height:200px;overflow-y:auto;">${JSON.stringify(data.valores_anteriores, null, 2)}</pre>
                                </div>
                            </div>
                        </div>
                    `;
                }

                if (data.valores_nuevos) {
                    html += `
                        <div class="col-md-6">
                            <div class="detalle-item" style="border-left-color: #28a745;">
                                <div class="detalle-label">Valores nuevos</div>
                                <div class="detalle-value">
                                    <pre style="font-size:0.75rem;background:#f8f9fa;padding:8px;border-radius:4px;max-height:200px;overflow-y:auto;">${JSON.stringify(data.valores_nuevos, null, 2)}</pre>
                                </div>
                            </div>
                        </div>
                    `;
                }

                html += `</div>`;
                $('#detalleContenido').html(html);
            },
            error: function() {
                $('#detalleContenido').html(`
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                        <p class="text-muted">No se pudieron cargar los detalles de la actividad.</p>
                    </div>
                `);
            }
        });
    });

    // Inicializar la tabla
    inicializarTabla();

    // Recargar tabla al presionar Enter en los filtros
    $('#filtroLogName, #filtroUsuario, #filtroDesde, #filtroHasta').on('keypress', function(e) {
        if (e.which === 13) {
            $('#btnFiltrar').click();
        }
    });

    // Limpiar filtros con Escape
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#btnLimpiarFiltros').click();
        }
    });
});
</script>
@endpush