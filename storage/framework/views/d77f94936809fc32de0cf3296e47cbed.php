<?php $__env->startSection('titulo', 'Clientes'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Clientes</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de clientes</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('clientes.crear')): ?>
        <button class="btn btn-primary btn-sm" id="btnNuevoCliente"><i class="fas fa-plus me-1"></i>Nuevo cliente</button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaClientes">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>DNI / RUC</th>
                    <th>Empresa</th>
                    <th>WhatsApp</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCliente">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalCliente">Nuevo cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="cliente_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" id="cliente_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Empresa</label>
                            <input type="text" name="empresa" id="cliente_empresa" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">DNI</label>
                            <input type="text" name="dni" id="cliente_dni" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">RUC</label>
                            <input type="text" name="ruc" id="cliente_ruc" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="whatsapp" id="cliente_whatsapp" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="cliente_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="cliente_direccion" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Departamento</label>
                            <input type="text" name="departamento" id="cliente_departamento" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Provincia</label>
                            <input type="text" name="provincia" id="cliente_provincia" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Distrito</label>
                            <input type="text" name="distrito" id="cliente_distrito" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Referencia</label>
                            <input type="text" name="referencia" id="cliente_referencia" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="cliente_observaciones" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="estado" id="cliente_estado" class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="cliente_estado">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCliente">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.bootstrap5.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Inicializar DataTable
    const tabla = new DataTable('#tablaClientes', {
        ajax: {
            url: '<?php echo e(route('clientes.datos')); ?>',
            dataSrc: 'data'
        },
        columns: [
            { data: 'nombre' },
            { 
                data: null, 
                render: function(r) { 
                    return r.dni || r.ruc || '—'; 
                } 
            },
            { data: 'empresa', defaultContent: '—' },
            { data: 'whatsapp', defaultContent: '—' },
            { data: 'email', defaultContent: '—' },
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
                        <button class="btn btn-sm btn-outline-primary btnEditar" data-id="${r.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminar" data-id="${r.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            },
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-ES.json'
        },
    });

    let clientesCache = [];
    tabla.on('xhr', function() {
        clientesCache = tabla.ajax.json().data || [];
    });

    // Botón Nuevo Cliente
    const btnNuevo = document.getElementById('btnNuevoCliente');
    if (btnNuevo) {
        btnNuevo.addEventListener('click', function() {
            document.getElementById('formCliente').reset();
            document.getElementById('cliente_id').value = '';
            document.getElementById('cliente_estado').checked = true;
            document.getElementById('tituloModalCliente').textContent = 'Nuevo cliente';
            const modal = new bootstrap.Modal(document.getElementById('modalCliente'));
            modal.show();
        });
    }

    // Eventos para botones dinámicos (editar/eliminar)
    document.querySelector('#tablaClientes tbody')?.addEventListener('click', function(e) {
        const btnEditar = e.target.closest('.btnEditar');
        const btnEliminar = e.target.closest('.btnEliminar');

        if (btnEditar) {
            const cliente = clientesCache.find(c => c.id == btnEditar.dataset.id);
            if (!cliente) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se encontraron datos del cliente' });
                return;
            }
            
            const campos = ['id', 'nombre', 'empresa', 'dni', 'ruc', 'whatsapp', 'email', 'direccion', 'departamento', 'provincia', 'distrito', 'referencia', 'observaciones'];
            campos.forEach(function(campo) {
                const el = document.getElementById('cliente_' + campo);
                if (el) {
                    el.value = cliente[campo] !== undefined && cliente[campo] !== null ? cliente[campo] : '';
                }
            });
            
            document.getElementById('cliente_estado').checked = !!cliente.estado;
            document.getElementById('tituloModalCliente').textContent = 'Editar cliente';
            
            const modal = new bootstrap.Modal(document.getElementById('modalCliente'));
            modal.show();
        }

        if (btnEliminar) {
            Swal.fire({
                title: '¿Eliminar cliente?',
                text: 'Podrás restaurarlo luego desde Auditoría.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
            }).then(function(result) {
                if (!result.isConfirmed) return;
                
                fetch(`/clientes/${btnEliminar.dataset.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({ 
                        icon: 'success', 
                        title: data.mensaje || 'Cliente eliminado', 
                        timer: 2000, 
                        showConfirmButton: false 
                    });
                    tabla.ajax.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al eliminar' });
                });
            });
        }
    });

    // Envío del formulario
    const formCliente = document.getElementById('formCliente');
    if (formCliente) {
        formCliente.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('cliente_id').value;
            const formData = new FormData(this);
            const datos = {};
            formData.forEach(function(value, key) {
                datos[key] = value;
            });
            datos.estado = document.getElementById('cliente_estado').checked ? 1 : 0;

            const url = id ? `/clientes/${id}` : '/clientes';
            const method = id ? 'PUT' : 'POST';

            // Deshabilitar botón para evitar doble envío
            const btnGuardar = document.getElementById('btnGuardarCliente');
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(datos),
            })
            .then(async function(response) {
                const data = await response.json();
                if (!response.ok) {
                    const mensaje = data.errors ? Object.values(data.errors)[0][0] : data.mensaje || 'Ocurrió un error.';
                    throw new Error(mensaje);
                }
                return data;
            })
            .then(function(data) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalCliente'));
                if (modal) modal.hide();
                
                Swal.fire({ 
                    icon: 'success', 
                    title: data.mensaje || 'Operación exitosa', 
                    timer: 2000, 
                    showConfirmButton: false 
                });
                tabla.ajax.reload();
            })
            .catch(function(error) {
                console.error('Error:', error);
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error', 
                    text: error.message || 'Ocurrió un error al guardar' 
                });
            })
            .finally(function() {
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = 'Guardar';
            });
        });
    }

    // Limpiar formulario al cerrar el modal
    document.getElementById('modalCliente')?.addEventListener('hidden.bs.modal', function() {
        document.getElementById('formCliente').reset();
        document.getElementById('cliente_id').value = '';
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/clientes/index.blade.php ENDPATH**/ ?>