

<?php $__env->startSection('titulo', 'Usuarios y Roles'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .card-usuarios {
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: none;
        overflow: hidden;
    }
    .card-usuarios .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 16px 24px;
    }
    .card-usuarios .card-header .card-title {
        color: #fff;
        font-weight: 600;
    }
    .btn-nuevo {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
    }
    .btn-nuevo:hover {
        background: rgba(255,255,255,0.3);
        color: #fff;
    }
    .badge-role {
        font-size: 0.7rem;
        padding: 4px 12px;
        border-radius: 20px;
        margin: 2px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Usuarios y Roles</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card card-usuarios">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-users-cog me-2"></i>Gestión de Usuarios y Roles
        </h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('usuarios.crear')): ?>
        <button class="btn btn-nuevo btn-sm" id="btnNuevoUsuario">
            <i class="fas fa-plus me-1"></i>Nuevo usuario
        </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaUsuarios">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Roles</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
                <h5 class="modal-title" id="tituloModalUsuario">
                    <i class="fas fa-user me-2"></i>Nuevo usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
            </div>
            <form id="formUsuario">
                <div class="modal-body">
                    <input type="hidden" name="id" id="usuario_id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="name" id="usuario_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" id="usuario_email" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" id="usuario_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" id="usuario_password" class="form-control" placeholder="Mínimo 8 caracteres">
                            <small class="text-muted">Dejar en blanco para no cambiar</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Roles</label>
                            <select name="roles[]" id="usuario_roles" class="form-select" multiple>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Presiona Ctrl para seleccionar múltiples</small>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-center">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="usuario_is_active" class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="usuario_is_active">Usuario activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarUsuario">
                        <i class="fas fa-save me-1"></i>Guardar
                    </button>
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
    var tabla = $('#tablaUsuarios').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("usuarios.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: 'name' },
            { data: 'email' },
            { data: 'phone', defaultContent: '—' },
            { 
                data: 'roles',
                render: function(data) {
                    if (!data) return '—';
                    var roles = data.split(', ');
                    return roles.map(function(r) {
                        return '<span class="badge bg-primary badge-role">' + r + '</span>';
                    }).join(' ');
                }
            },
            { 
                data: 'is_active',
                render: function(data) {
                    return data 
                        ? '<span class="badge bg-success">Activo</span>' 
                        : '<span class="badge bg-secondary">Inactivo</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(r) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-primary btnEditarUsuario" data-id="${r.id}" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger btnEliminarUsuario" data-id="${r.id}" title="Eliminar">
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

    // Botón Nuevo Usuario
    $('#btnNuevoUsuario').on('click', function() {
        $('#formUsuario')[0].reset();
        $('#usuario_id').val('');
        $('#usuario_password').prop('required', true);
        $('#usuario_is_active').prop('checked', true);
        $('#tituloModalUsuario').html('<i class="fas fa-user me-2"></i>Nuevo usuario');
        $('#modalUsuario').modal('show');
    });

    // Editar Usuario
    $(document).on('click', '.btnEditarUsuario', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/usuarios/' + id,
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
                        text: 'No se encontraron datos del usuario'
                    });
                    return;
                }

                $('#usuario_id').val(data.id);
                $('#usuario_name').val(data.name || '');
                $('#usuario_email').val(data.email || '');
                $('#usuario_phone').val(data.phone || '');
                $('#usuario_password').prop('required', false);
                $('#usuario_roles').val(data.roles || []);
                $('#usuario_is_active').prop('checked', data.is_active == 1 || data.is_active == true);
                $('#tituloModalUsuario').html('<i class="fas fa-user-edit me-2"></i>Editar usuario');
                $('#modalUsuario').modal('show');
            },
            error: function(xhr) {
                var errorMsg = 'No se pudieron cargar los datos del usuario';
                if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    errorMsg = xhr.responseJSON.mensaje;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            }
        });
    });

    // Eliminar Usuario
    $(document).on('click', '.btnEliminarUsuario', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar usuario?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/usuarios/' + id,
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
                        var errorMsg = 'Ocurrió un error al eliminar';
                        if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                            errorMsg = xhr.responseJSON.mensaje;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    }
                });
            }
        });
    });

    // Enviar formulario
    $('#formUsuario').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#usuario_id').val();
        var datos = {
            name: $('#usuario_name').val(),
            email: $('#usuario_email').val(),
            phone: $('#usuario_phone').val(),
            password: $('#usuario_password').val(),
            is_active: $('#usuario_is_active').is(':checked') ? 1 : 0,
            roles: $('#usuario_roles').val() || []
        };

        var url = id ? '/usuarios/' + id : '/usuarios';
        var method = id ? 'PUT' : 'POST';

        // Si es edición y no se cambia la contraseña, no enviarla
        if (id && !datos.password) {
            delete datos.password;
        }

        $('#btnGuardarUsuario').prop('disabled', true);
        $('#btnGuardarUsuario').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

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
                $('#modalUsuario').modal('hide');
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
                
                // Mostrar errores de validación específicos
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
                $('#btnGuardarUsuario').prop('disabled', false);
                $('#btnGuardarUsuario').html('<i class="fas fa-save me-1"></i>Guardar');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalUsuario').on('hidden.bs.modal', function() {
        $('#formUsuario')[0].reset();
        $('#usuario_id').val('');
        $('#usuario_password').prop('required', false);
        $('#usuario_is_active').prop('checked', true);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/usuarios/index.blade.php ENDPATH**/ ?>