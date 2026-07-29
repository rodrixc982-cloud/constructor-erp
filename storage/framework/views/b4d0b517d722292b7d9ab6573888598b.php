<?php $__env->startSection('titulo', 'Maquinaria'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Maquinaria</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Maquinaria pesada</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('maquinaria.crear')): ?>
        <button class="btn btn-primary btn-sm" id="btnNuevoMaquinaria">
            <i class="fas fa-plus me-1"></i>Nueva
        </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaMaquinaria">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Placa</th>
                    <th>Costo/hora</th>
                    <th>Costo/día</th>
                    <th>Costo/mes</th>
                    <th>Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalMaquinaria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalMaquinaria">Nueva maquinaria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMaquinaria">
                <div class="modal-body">
                    <input type="hidden" name="id" id="maquinaria_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre * (Retroexcavadora, Grúa, Volquete, Minicargador...)</label>
                        <input type="text" name="nombre" id="maquinaria_nombre" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" id="maquinaria_marca" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Placa</label>
                            <input type="text" name="placa" id="maquinaria_placa" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Costo/hora *</label>
                            <input type="number" step="0.01" name="costo_hora" id="maquinaria_costo_hora" class="form-control" required min="0">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Costo/día *</label>
                            <input type="number" step="0.01" name="costo_dia" id="maquinaria_costo_dia" class="form-control" required min="0">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Costo/mes *</label>
                            <input type="number" step="0.01" name="costo_mensual" id="maquinaria_costo_mensual" class="form-control" required min="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="maquinaria_observaciones" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" name="disponible" id="maquinaria_disponible" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="maquinaria_disponible">Disponible</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" name="estado" id="maquinaria_estado" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="maquinaria_estado">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarMaquinaria">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let cacheData = [];

    // Inicializar DataTable
    var tabla = $('#tablaMaquinaria').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("maquinaria.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: 'nombre' },
            { data: 'placa', defaultContent: '—' },
            { 
                data: 'costo_hora',
                render: function(c) {
                    return 'S/ ' + parseFloat(c).toFixed(2);
                }
            },
            { 
                data: 'costo_dia',
                render: function(c) {
                    return 'S/ ' + parseFloat(c).toFixed(2);
                }
            },
            { 
                data: 'costo_mensual',
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
                data: null,
                orderable: false,
                render: function(r) {
                    return `
                        <button class="btn btn-sm btn-outline-primary btnEditarMaquinaria" data-id="${r.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarMaquinaria" data-id="${r.id}">
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
    $('#btnNuevoMaquinaria').on('click', function() {
        $('#formMaquinaria')[0].reset();
        $('#maquinaria_id').val('');
        $('#maquinaria_disponible').prop('checked', true);
        $('#maquinaria_estado').prop('checked', true);
        $('#maquinaria_costo_hora').val('');
        $('#maquinaria_costo_dia').val('');
        $('#maquinaria_costo_mensual').val('');
        $('#tituloModalMaquinaria').text('Nueva maquinaria');
        $('#modalMaquinaria').modal('show');
    });

    // Editar
    $(document).on('click', '.btnEditarMaquinaria', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/maquinaria/' + id,
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
                        text: 'No se encontraron datos de la maquinaria'
                    });
                    return;
                }

                $('#maquinaria_id').val(data.id);
                $('#maquinaria_nombre').val(data.nombre || '');
                $('#maquinaria_marca').val(data.marca || '');
                $('#maquinaria_placa').val(data.placa || '');
                $('#maquinaria_costo_hora').val(data.costo_hora || 0);
                $('#maquinaria_costo_dia').val(data.costo_dia || 0);
                $('#maquinaria_costo_mensual').val(data.costo_mensual || 0);
                $('#maquinaria_observaciones').val(data.observaciones || '');
                $('#maquinaria_disponible').prop('checked', data.disponible == 1 || data.disponible == true);
                $('#maquinaria_estado').prop('checked', data.estado == 1 || data.estado == true);
                $('#tituloModalMaquinaria').text('Editar maquinaria');
                $('#modalMaquinaria').modal('show');
            },
            error: function(xhr) {
                var errorMsg = 'No se pudieron cargar los datos de la maquinaria';
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
    $(document).on('click', '.btnEliminarMaquinaria', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar maquinaria?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/maquinaria/' + id,
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
    $('#formMaquinaria').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#maquinaria_id').val();
        var datos = {
            nombre: $('#maquinaria_nombre').val(),
            marca: $('#maquinaria_marca').val(),
            placa: $('#maquinaria_placa').val(),
            costo_hora: $('#maquinaria_costo_hora').val(),
            costo_dia: $('#maquinaria_costo_dia').val(),
            costo_mensual: $('#maquinaria_costo_mensual').val(),
            observaciones: $('#maquinaria_observaciones').val(),
            disponible: $('#maquinaria_disponible').is(':checked') ? 1 : 0,
            estado: $('#maquinaria_estado').is(':checked') ? 1 : 0
        };

        var url = id ? '/maquinaria/' + id : '/maquinaria';
        var method = id ? 'PUT' : 'POST';

        $('#btnGuardarMaquinaria').prop('disabled', true);
        $('#btnGuardarMaquinaria').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

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
                $('#modalMaquinaria').modal('hide');
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
                $('#btnGuardarMaquinaria').prop('disabled', false);
                $('#btnGuardarMaquinaria').text('Guardar');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalMaquinaria').on('hidden.bs.modal', function() {
        $('#formMaquinaria')[0].reset();
        $('#maquinaria_id').val('');
        $('#maquinaria_disponible').prop('checked', true);
        $('#maquinaria_estado').prop('checked', true);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/maquinaria/index.blade.php ENDPATH**/ ?>