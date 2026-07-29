<?php $__env->startSection('titulo', 'Categorías'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Categorías</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Categorías de materiales</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('categorias.crear')): ?>
        <button class="btn btn-primary btn-sm" id="btnNuevaCategoria">
            <i class="fas fa-plus me-1"></i>Nueva categoría
        </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaCategorias">
            <thead>
                <tr>
                    <th>Icono</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalCategoria">Nueva categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCategoria">
                <div class="modal-body">
                    <input type="hidden" name="id" id="categoria_id">
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" id="categoria_nombre" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Color</label>
                            <input type="color" name="color" id="categoria_color" class="form-control form-control-color w-100" value="#2a5298">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Ícono (Font Awesome)</label>
                            <input type="text" name="icono" id="categoria_icono" class="form-control" value="fa-cubes" placeholder="fa-cubes">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="categoria_descripcion" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-check ms-1">
                        <input type="checkbox" name="estado" id="categoria_estado" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="categoria_estado">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCategoria">Guardar</button>
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
    
    // Inicializar DataTable
    var tabla = $('#tablaCategorias').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("categorias.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { 
                data: 'icono',
                render: function(data) {
                    return data ? '<i class="fas ' + data + ' fa-lg"></i>' : '<i class="fas fa-cubes fa-lg"></i>';
                }
            },
            { data: 'nombre' },
            { data: 'descripcion', defaultContent: '—' },
            {
                data: 'estado',
                render: function(data) {
                    return data ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-outline-primary btnEditarCategoria" data-id="${data.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarCategoria" data-id="${data.id}">
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

    // Botón Nueva Categoría
    $('#btnNuevaCategoria').on('click', function() {
        $('#formCategoria')[0].reset();
        $('#categoria_id').val('');
        $('#categoria_color').val('#2a5298');
        $('#categoria_icono').val('fa-cubes');
        $('#categoria_estado').prop('checked', true);
        $('#tituloModalCategoria').text('Nueva categoría');
        $('#modalCategoria').modal('show');
    });

    // Editar categoría
    $(document).on('click', '.btnEditarCategoria', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/categorias/' + id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                var data = response.data || response;
                $('#categoria_id').val(data.id);
                $('#categoria_nombre').val(data.nombre || '');
                $('#categoria_color').val(data.color || '#2a5298');
                $('#categoria_icono').val(data.icono || 'fa-cubes');
                $('#categoria_descripcion').val(data.descripcion || '');
                $('#categoria_estado').prop('checked', data.estado == 1 || data.estado == true);
                $('#tituloModalCategoria').text('Editar categoría');
                $('#modalCategoria').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los datos de la categoría'
                });
            }
        });
    });

    // Eliminar categoría
    $(document).on('click', '.btnEliminarCategoria', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar categoría?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/categorias/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Categoría eliminada',
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
    $('#formCategoria').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#categoria_id').val();
        var datos = {
            nombre: $('#categoria_nombre').val(),
            color: $('#categoria_color').val(),
            icono: $('#categoria_icono').val(),
            descripcion: $('#categoria_descripcion').val(),
            estado: $('#categoria_estado').is(':checked') ? 1 : 0
        };

        var url = id ? '/categorias/' + id : '/categorias';
        var method = id ? 'PUT' : 'POST';

        $('#btnGuardarCategoria').prop('disabled', true);
        $('#btnGuardarCategoria').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

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
                $('#modalCategoria').modal('hide');
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
                $('#btnGuardarCategoria').prop('disabled', false);
                $('#btnGuardarCategoria').text('Guardar');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalCategoria').on('hidden.bs.modal', function() {
        $('#formCategoria')[0].reset();
        $('#categoria_id').val('');
        $('#categoria_estado').prop('checked', true);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/categorias/index.blade.php ENDPATH**/ ?>