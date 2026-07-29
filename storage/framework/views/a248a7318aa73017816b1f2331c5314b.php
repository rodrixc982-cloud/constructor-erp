<?php $__env->startSection('titulo', 'Proveedores'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Proveedores</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de proveedores</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('proveedores.crear')): ?>
        <button class="btn btn-primary btn-sm" id="btnNuevoProveedor">
            <i class="fas fa-plus me-1"></i>Nuevo proveedor
        </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaProveedores">
            <thead>
                <tr>
                    <th>RUC</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalProveedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalProveedor">Nuevo proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formProveedor">
                <div class="modal-body">
                    <input type="hidden" name="id" id="proveedor_id">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">RUC *</label>
                            <input type="text" name="ruc" id="proveedor_ruc" class="form-control" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nombre / Razón social *</label>
                            <input type="text" name="nombre" id="proveedor_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Productos / Servicios</label>
                            <input type="text" name="productos" id="proveedor_productos" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contacto</label>
                            <input type="text" name="contacto" id="proveedor_contacto" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="proveedor_telefono" class="form-control">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="proveedor_email" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="proveedor_direccion" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Banco</label>
                            <input type="text" name="banco" id="proveedor_banco" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">N° Cuenta</label>
                            <input type="text" name="cuenta" id="proveedor_cuenta" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">CCI</label>
                            <input type="text" name="cci" id="proveedor_cci" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="proveedor_observaciones" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="estado" id="proveedor_estado" class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="proveedor_estado">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarProveedor">Guardar</button>
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
    var tabla = $('#tablaProveedores').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("proveedores.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: 'ruc' },
            { data: 'nombre' },
            { data: 'contacto', defaultContent: '—' },
            { data: 'telefono', defaultContent: '—' },
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
                        <button class="btn btn-sm btn-outline-primary btnEditar" data-id="${data.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminar" data-id="${data.id}">
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

    // Botón Nuevo Proveedor
    $('#btnNuevoProveedor').on('click', function() {
        $('#formProveedor')[0].reset();
        $('#proveedor_id').val('');
        $('#proveedor_estado').prop('checked', true);
        $('#tituloModalProveedor').text('Nuevo proveedor');
        $('#modalProveedor').modal('show');
    });

    // Editar proveedor
    $(document).on('click', '.btnEditar', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/proveedores/' + id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                var data = response.data || response;
                $('#proveedor_id').val(data.id);
                $('#proveedor_ruc').val(data.ruc || '');
                $('#proveedor_nombre').val(data.nombre || '');
                $('#proveedor_productos').val(data.productos || '');
                $('#proveedor_contacto').val(data.contacto || '');
                $('#proveedor_telefono').val(data.telefono || '');
                $('#proveedor_email').val(data.email || '');
                $('#proveedor_direccion').val(data.direccion || '');
                $('#proveedor_banco').val(data.banco || '');
                $('#proveedor_cuenta').val(data.cuenta || '');
                $('#proveedor_cci').val(data.cci || '');
                $('#proveedor_observaciones').val(data.observaciones || '');
                $('#proveedor_estado').prop('checked', data.estado == 1 || data.estado == true);
                $('#tituloModalProveedor').text('Editar proveedor');
                $('#modalProveedor').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los datos del proveedor'
                });
            }
        });
    });

    // Eliminar proveedor
    $(document).on('click', '.btnEliminar', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar proveedor?',
            text: 'Podrás restaurarlo luego desde Auditoría.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/proveedores/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Proveedor eliminado',
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
    $('#formProveedor').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#proveedor_id').val();
        var datos = {
            ruc: $('#proveedor_ruc').val(),
            nombre: $('#proveedor_nombre').val(),
            productos: $('#proveedor_productos').val(),
            contacto: $('#proveedor_contacto').val(),
            telefono: $('#proveedor_telefono').val(),
            email: $('#proveedor_email').val(),
            direccion: $('#proveedor_direccion').val(),
            banco: $('#proveedor_banco').val(),
            cuenta: $('#proveedor_cuenta').val(),
            cci: $('#proveedor_cci').val(),
            observaciones: $('#proveedor_observaciones').val(),
            estado: $('#proveedor_estado').is(':checked') ? 1 : 0
        };

        var url = id ? '/proveedores/' + id : '/proveedores';
        var method = id ? 'PUT' : 'POST';

        $('#btnGuardarProveedor').prop('disabled', true);
        $('#btnGuardarProveedor').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

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
                $('#modalProveedor').modal('hide');
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
                $('#btnGuardarProveedor').prop('disabled', false);
                $('#btnGuardarProveedor').text('Guardar');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalProveedor').on('hidden.bs.modal', function() {
        $('#formProveedor')[0].reset();
        $('#proveedor_id').val('');
        $('#proveedor_estado').prop('checked', true);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/proveedores/index.blade.php ENDPATH**/ ?>