@extends('layouts.app')

@section('titulo', 'Obras')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Obras</li>
@endsection

@section('contenido')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Obras / Proyectos</h3>
        @can('obras.crear')
        <button class="btn btn-primary btn-sm" id="btnNuevoObra">
            <i class="fas fa-plus me-1"></i>Nueva obra
        </button>
        @endcan
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaObras">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Cliente</th>
                    <th>Responsable</th>
                    <th>Inicio</th>
                    <th>Fin estimado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal Crear/Editar --}}
<div class="modal fade" id="modalObra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalObra">Nueva obra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formObra">
                <div class="modal-body">
                    <input type="hidden" name="id" id="obra_id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" id="obra_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="cliente_id" id="obra_cliente_id" class="form-select">
                                <option value="">— Ninguno —</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Ubicación</label>
                            <input type="text" name="ubicacion" id="obra_ubicacion" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Responsable</label>
                            <select name="responsable_id" id="obra_responsable_id" class="form-select">
                                <option value="">— Ninguno —</option>
                                @foreach($responsables as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" id="obra_fecha_inicio" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha fin estimada</label>
                            <input type="date" name="fecha_fin_estimada" id="obra_fecha_fin_estimada" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado *</label>
                            <select name="estado" id="obra_estado" class="form-select" required>
                                @foreach(['planificacion'=>'Planificación','activa'=>'Activa','pausada'=>'Pausada','terminada'=>'Terminada','cancelada'=>'Cancelada'] as $v=>$l)
                                    <option value="{{ $v }}">{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="obra_observaciones" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarObra">Guardar</button>
                </div>
            </form>
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
    let cacheData = [];

    const badges = {
        'planificacion': 'secondary',
        'activa': 'success',
        'pausada': 'warning',
        'terminada': 'info',
        'cancelada': 'danger'
    };

    // Inicializar DataTable
    var tabla = $('#tablaObras').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("obras.datos") }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { 
                data: 'nombre',
                render: function(data, type, row) {
                    return '<a href="/obras/' + row.id + '/ver" class="text-primary fw-bold">' + data + '</a>';
                }
            },
            { 
                data: 'cliente',
                render: function(c) {
                    return c?.nombre || '—';
                }
            },
            { 
                data: 'responsable',
                render: function(r) {
                    return r?.name || '—';
                }
            },
            { data: 'fecha_inicio', defaultContent: '—' },
            { data: 'fecha_fin_estimada', defaultContent: '—' },
            { 
                data: 'estado',
                render: function(e) {
                    var color = badges[e] || 'secondary';
                    var label = e.charAt(0).toUpperCase() + e.slice(1);
                    return '<span class="badge bg-' + color + '">' + label + '</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(r) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/obras/${r.id}/ver" class="btn btn-outline-info" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-outline-primary btnEditarObra" data-id="${r.id}" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger btnEliminarObra" data-id="${r.id}" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
        ordering: true,
    });

    tabla.on('xhr', function() {
        cacheData = tabla.ajax.json().data || [];
    });

    // Botón Nuevo
    $('#btnNuevoObra').on('click', function() {
        $('#formObra')[0].reset();
        $('#obra_id').val('');
        $('#obra_estado').val('planificacion');
        $('#tituloModalObra').text('Nueva obra');
        $('#modalObra').modal('show');
    });

    // Editar
    $(document).on('click', '.btnEditarObra', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/obras/' + id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                var data = response.data || response;
                
                if (!data || !data.id) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se encontraron datos de la obra'
                    });
                    return;
                }

                $('#obra_id').val(data.id);
                $('#obra_nombre').val(data.nombre || '');
                $('#obra_cliente_id').val(data.cliente_id || '');
                $('#obra_ubicacion').val(data.ubicacion || '');
                $('#obra_responsable_id').val(data.responsable_id || '');
                $('#obra_fecha_inicio').val(data.fecha_inicio || '');
                $('#obra_fecha_fin_estimada').val(data.fecha_fin_estimada || '');
                $('#obra_estado').val(data.estado || 'planificacion');
                $('#obra_observaciones').val(data.observaciones || '');
                $('#tituloModalObra').text('Editar obra');
                $('#modalObra').modal('show');
            },
            error: function(xhr) {
                var errorMsg = 'No se pudieron cargar los datos de la obra';
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMsg = response.message;
                    }
                } catch(e) {}
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
                console.error('Error al cargar:', xhr.responseText);
            }
        });
    });

    // Eliminar
    $(document).on('click', '.btnEliminarObra', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar obra?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/obras/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Eliminado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tabla.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al eliminar'
                        });
                    }
                });
            }
        });
    });

    // Enviar formulario
    $('#formObra').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#obra_id').val();
        var datos = {
            nombre: $('#obra_nombre').val(),
            cliente_id: $('#obra_cliente_id').val(),
            ubicacion: $('#obra_ubicacion').val(),
            responsable_id: $('#obra_responsable_id').val(),
            fecha_inicio: $('#obra_fecha_inicio').val(),
            fecha_fin_estimada: $('#obra_fecha_fin_estimada').val(),
            estado: $('#obra_estado').val(),
            observaciones: $('#obra_observaciones').val()
        };

        var url = id ? '/obras/' + id : '/obras';
        var method = id ? 'PUT' : 'POST';

        $('#btnGuardarObra').prop('disabled', true);
        $('#btnGuardarObra').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

        $.ajax({
            url: url,
            type: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            contentType: 'application/json',
            data: JSON.stringify(datos),
            success: function(response) {
                $('#modalObra').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje || 'Operación exitosa',
                    timer: 2000,
                    showConfirmButton: false
                });
                tabla.ajax.reload();
            },
            error: function(xhr) {
                var errorMsg = 'Ocurrió un error al guardar';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    var firstError = Object.values(errors)[0];
                    if (firstError && firstError[0]) {
                        errorMsg = firstError[0];
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    errorMsg = xhr.responseJSON.mensaje;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            },
            complete: function() {
                $('#btnGuardarObra').prop('disabled', false);
                $('#btnGuardarObra').text('Guardar');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalObra').on('hidden.bs.modal', function() {
        $('#formObra')[0].reset();
        $('#obra_id').val('');
        $('#obra_estado').val('planificacion');
    });
});
</script>
@endpush