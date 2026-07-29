@extends('layouts.app')

@section('titulo', 'Equipos')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Equipos</li>
@endsection

@section('contenido')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Equipos y herramientas</h3>
        @can('equipos.crear')
        <button class="btn btn-primary btn-sm" id="btnNuevoEquipo">
            <i class="fas fa-plus me-1"></i>Nuevo
        </button>
        @endcan
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaEquipos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Alquiler/día</th>
                    <th>Mantenimiento</th>
                    <th>Disponible</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal Crear/Editar --}}
<div class="modal fade" id="modalEquipo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalEquipo">Nuevo equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEquipo">
                <div class="modal-body">
                    <input type="hidden" name="id" id="equipo_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" id="equipo_nombre" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Marca</label>
                        <input type="text" name="marca" id="equipo_marca" class="form-control">
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Costo alquiler/día *</label>
                            <input type="number" step="0.01" name="costo_alquiler_dia" id="equipo_costo_alquiler_dia" class="form-control" required min="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Costo mantenimiento *</label>
                            <input type="number" step="0.01" name="costo_mantenimiento" id="equipo_costo_mantenimiento" class="form-control" required min="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="equipo_observaciones" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" name="disponible" id="equipo_disponible" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="equipo_disponible">Disponible</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" name="estado" id="equipo_estado" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="equipo_estado">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarEquipo">Guardar</button>
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

    // Inicializar DataTable
    var tabla = $('#tablaEquipos').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("equipos.datos") }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: 'nombre' },
            { data: 'marca', defaultContent: '—' },
            { 
                data: 'costo_alquiler_dia',
                render: function(c) {
                    return 'S/ ' + parseFloat(c).toFixed(2);
                }
            },
            { 
                data: 'costo_mantenimiento',
                render: function(c) {
                    return 'S/ ' + parseFloat(c).toFixed(2);
                }
            },
            { 
                data: 'disponible',
                render: function(d) {
                    return d ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>';
                }
            },
            { 
                data: 'estado',
                render: function(e) {
                    return e ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(r) {
                    return `
                        <button class="btn btn-sm btn-outline-primary btnEditarEquipo" data-id="${r.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarEquipo" data-id="${r.id}">
                            <i class="fas fa-trash"></i>
                        </button>
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
    $('#btnNuevoEquipo').on('click', function() {
        $('#formEquipo')[0].reset();
        $('#equipo_id').val('');
        $('#equipo_disponible').prop('checked', true);
        $('#equipo_estado').prop('checked', true);
        $('#equipo_costo_alquiler_dia').val('');
        $('#equipo_costo_mantenimiento').val('');
        $('#tituloModalEquipo').text('Nuevo equipo');
        $('#modalEquipo').modal('show');
    });

    // Editar
    $(document).on('click', '.btnEditarEquipo', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/equipos/' + id,
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
                        text: 'No se encontraron datos del equipo'
                    });
                    return;
                }

                $('#equipo_id').val(data.id);
                $('#equipo_nombre').val(data.nombre || '');
                $('#equipo_marca').val(data.marca || '');
                $('#equipo_costo_alquiler_dia').val(data.costo_alquiler_dia || 0);
                $('#equipo_costo_mantenimiento').val(data.costo_mantenimiento || 0);
                $('#equipo_observaciones').val(data.observaciones || '');
                $('#equipo_disponible').prop('checked', data.disponible == 1 || data.disponible == true);
                $('#equipo_estado').prop('checked', data.estado == 1 || data.estado == true);
                $('#tituloModalEquipo').text('Editar equipo');
                $('#modalEquipo').modal('show');
            },
            error: function(xhr) {
                var errorMsg = 'No se pudieron cargar los datos del equipo';
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
    $(document).on('click', '.btnEliminarEquipo', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar equipo?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/equipos/' + id,
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
    $('#formEquipo').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#equipo_id').val();
        var datos = {
            nombre: $('#equipo_nombre').val(),
            marca: $('#equipo_marca').val(),
            costo_alquiler_dia: $('#equipo_costo_alquiler_dia').val(),
            costo_mantenimiento: $('#equipo_costo_mantenimiento').val(),
            observaciones: $('#equipo_observaciones').val(),
            disponible: $('#equipo_disponible').is(':checked') ? 1 : 0,
            estado: $('#equipo_estado').is(':checked') ? 1 : 0
        };

        var url = id ? '/equipos/' + id : '/equipos';
        var method = id ? 'PUT' : 'POST';

        $('#btnGuardarEquipo').prop('disabled', true);
        $('#btnGuardarEquipo').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

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
                $('#modalEquipo').modal('hide');
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
                $('#btnGuardarEquipo').prop('disabled', false);
                $('#btnGuardarEquipo').text('Guardar');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalEquipo').on('hidden.bs.modal', function() {
        $('#formEquipo')[0].reset();
        $('#equipo_id').val('');
        $('#equipo_disponible').prop('checked', true);
        $('#equipo_estado').prop('checked', true);
    });
});
</script>
@endpush