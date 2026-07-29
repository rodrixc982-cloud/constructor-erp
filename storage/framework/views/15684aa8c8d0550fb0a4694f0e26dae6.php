<?php $__env->startSection('titulo', 'Mano de obra'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Mano de obra</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Mano de obra</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('mano_obra.crear')): ?>
        <button class="btn btn-primary btn-sm" id="btnNuevoManoObra">
            <i class="fas fa-plus me-1"></i>Nuevo
        </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaManoObra">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Tipo costo</th>
                    <th>Costo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalManoObra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalManoObra">Nuevo registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formManoObra">
                <div class="modal-body">
                    <input type="hidden" name="id" id="mano_obra_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" id="mano_obra_nombre" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Especialidad *</label>
                        <select name="especialidad" id="mano_obra_especialidad" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = ['Albañil','Ayudante','Maestro','Pintor','Electricista','Gasfitero','Carpintero','Soldador','Ingeniero','Arquitecto']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($e); ?>"><?php echo e($e); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Documento</label>
                            <input type="text" name="documento" id="mano_obra_documento" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="mano_obra_telefono" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Tipo de costo *</label>
                            <select name="tipo_costo" id="mano_obra_tipo_costo" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <?php $__currentLoopData = ['hora'=>'Por hora','dia'=>'Por día','semana'=>'Por semana','mes'=>'Por mes','m2'=>'Por m²','ml'=>'Por ml','m3'=>'Por m³']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($v); ?>"><?php echo e($l); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Costo *</label>
                            <input type="number" step="0.01" name="costo" id="mano_obra_costo" class="form-control" required min="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="mano_obra_observaciones" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" name="estado" id="mano_obra_estado" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="mano_obra_estado">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarManoObra">Guardar</button>
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
    var tabla = $('#tablaManoObra').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("mano-obra.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: 'nombre' },
            { data: 'especialidad' },
            { 
                data: 'tipo_costo',
                render: function(t) {
                    var tipos = {
                        'hora': 'Por hora',
                        'dia': 'Por día',
                        'semana': 'Por semana',
                        'mes': 'Por mes',
                        'm2': 'Por m²',
                        'ml': 'Por ml',
                        'm3': 'Por m³'
                    };
                    return tipos[t] || t;
                }
            },
            { 
                data: 'costo',
                render: function(c) {
                    return 'S/ ' + parseFloat(c).toFixed(2);
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
                        <button class="btn btn-sm btn-outline-primary btnEditarManoObra" data-id="${r.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarManoObra" data-id="${r.id}">
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
    $('#btnNuevoManoObra').on('click', function() {
        $('#formManoObra')[0].reset();
        $('#mano_obra_id').val('');
        $('#mano_obra_estado').prop('checked', true);
        $('#tituloModalManoObra').text('Nuevo registro');
        $('#modalManoObra').modal('show');
    });

    // Editar
    $(document).on('click', '.btnEditarManoObra', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/mano-obra/' + id,
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
                        text: 'No se encontraron datos'
                    });
                    return;
                }

                $('#mano_obra_id').val(data.id);
                $('#mano_obra_nombre').val(data.nombre || '');
                $('#mano_obra_especialidad').val(data.especialidad || '');
                $('#mano_obra_documento').val(data.documento || '');
                $('#mano_obra_telefono').val(data.telefono || '');
                $('#mano_obra_tipo_costo').val(data.tipo_costo || '');
                $('#mano_obra_costo').val(data.costo || 0);
                $('#mano_obra_observaciones').val(data.observaciones || '');
                $('#mano_obra_estado').prop('checked', data.estado == 1 || data.estado == true);
                $('#tituloModalManoObra').text('Editar registro');
                $('#modalManoObra').modal('show');
            },
            error: function(xhr) {
                var errorMsg = 'No se pudieron cargar los datos';
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
    $(document).on('click', '.btnEliminarManoObra', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar registro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/mano-obra/' + id,
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
    $('#formManoObra').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#mano_obra_id').val();
        var datos = {
            nombre: $('#mano_obra_nombre').val(),
            especialidad: $('#mano_obra_especialidad').val(),
            documento: $('#mano_obra_documento').val(),
            telefono: $('#mano_obra_telefono').val(),
            tipo_costo: $('#mano_obra_tipo_costo').val(),
            costo: $('#mano_obra_costo').val(),
            observaciones: $('#mano_obra_observaciones').val(),
            estado: $('#mano_obra_estado').is(':checked') ? 1 : 0
        };

        var url = id ? '/mano-obra/' + id : '/mano-obra';
        var method = id ? 'PUT' : 'POST';

        $('#btnGuardarManoObra').prop('disabled', true);
        $('#btnGuardarManoObra').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

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
                $('#modalManoObra').modal('hide');
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
                $('#btnGuardarManoObra').prop('disabled', false);
                $('#btnGuardarManoObra').text('Guardar');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalManoObra').on('hidden.bs.modal', function() {
        $('#formManoObra')[0].reset();
        $('#mano_obra_id').val('');
        $('#mano_obra_estado').prop('checked', true);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/mano-obra/index.blade.php ENDPATH**/ ?>