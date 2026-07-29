<?php $__env->startSection('titulo', 'Compras'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .badge-estado {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 20px;
    }
    .btn-accion {
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-accion:hover {
        transform: scale(1.05);
    }
    .select-estado {
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 4px 8px;
        min-width: 100px;
        cursor: pointer;
    }
    .select-estado:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .card-header-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-generar {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #fff;
        border: none;
    }
    .btn-generar:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
    }
    .btn-nuevo {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
    }
    .btn-nuevo:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Compras</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0">
            <i class="fas fa-shopping-cart me-2 text-primary"></i>Órdenes de compra
        </h3>
        <div class="card-header-buttons">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('compras.crear')): ?>
            <button class="btn btn-generar btn-sm" id="btnGenerarDesdePresupuesto">
                <i class="fas fa-magic me-1"></i>Generar desde presupuesto
            </button>
            <button class="btn btn-nuevo btn-sm" id="btnNuevoOrden">
                <i class="fas fa-plus me-1"></i>Nueva orden
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaCompras">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalGenerar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff;">
                <h5 class="modal-title">
                    <i class="fas fa-magic me-2"></i>Generar órdenes desde presupuesto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Seleccionar presupuesto</label>
                    <select id="genPresupuestoId" class="form-select">
                        <option value="">-- Seleccione un presupuesto --</option>
                        <?php $__currentLoopData = $presupuestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"><?php echo e($p->codigo); ?> - <?php echo e($p->cliente?->nombre ?? 'Sin cliente'); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Se creará una orden de compra por cada proveedor con materiales asignados en las partidas del presupuesto.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarGenerar">
                    <i class="fas fa-magic me-1"></i>Generar órdenes
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalOrden" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Nueva orden de compra
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
            </div>
            <form id="formOrden">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Proveedor *</label>
                            <select name="proveedor_id" id="orden_proveedor_id" class="form-select" required>
                                <option value="">-- Seleccione un proveedor --</option>
                                <?php $__currentLoopData = $proveedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->id); ?>"><?php echo e($p->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Fecha *</label>
                            <input type="date" name="fecha" id="orden_fecha" class="form-control" value="<?php echo e(now()->format('Y-m-d')); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Presupuesto relacionado</label>
                        <select name="presupuesto_id" id="orden_presupuesto_id" class="form-select">
                            <option value="">-- Ninguno --</option>
                            <?php $__currentLoopData = $presupuestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"><?php echo e($p->codigo); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Ítems de la orden</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAgregarItem">
                            <i class="fas fa-plus me-1"></i>Agregar ítem
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm" id="tablaItems">
                            <thead>
                                <tr>
                                    <th>Material ID</th>
                                    <th>Cantidad</th>
                                    <th>P. Unit.</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="number" class="form-control form-control-sm item-material" placeholder="ID material" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm item-cantidad" value="1" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm item-precio" value="0" required>
                                    </td>
                                    <td class="item-subtotal text-end">0.00</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-item">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">TOTAL</td>
                                    <td class="text-end" id="totalOrden">0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label fw-semibold">Observaciones</label>
                        <textarea name="observaciones" id="orden_observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarOrden">
                        <i class="fas fa-save me-1"></i>Guardar orden
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
    let cacheData = [];

    const badges = {
        'pendiente': 'secondary',
        'aprobada': 'info',
        'recibida': 'success',
        'cancelada': 'danger'
    };

    const estadoLabels = {
        'pendiente': 'Pendiente',
        'aprobada': 'Aprobada',
        'recibida': 'Recibida',
        'cancelada': 'Cancelada'
    };

    // Inicializar DataTable
    var tabla = $('#tablaCompras').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("compras.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: 'codigo' },
            { 
                data: 'proveedor',
                render: function(p) {
                    return p?.nombre || '—';
                }
            },
            { data: 'fecha' },
            { 
                data: 'total',
                render: function(t) {
                    return 'S/ ' + parseFloat(t).toFixed(2);
                }
            },
            { 
                data: 'estado',
                render: function(e) {
                    var color = badges[e] || 'secondary';
                    var label = estadoLabels[e] || e;
                    return '<span class="badge bg-' + color + ' badge-estado">' + label + '</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(r) {
                    return `
                        <div class="d-flex gap-1">
                            <select class="form-select form-select-sm select-estado cambio-estado" data-id="${r.id}" style="min-width:100px;">
                                ${Object.keys(estadoLabels).map(function(e) {
                                    return `<option value="${e}" ${e === r.estado ? 'selected' : ''}>${estadoLabels[e]}</option>`;
                                }).join('')}
                            </select>
                            <button class="btn btn-sm btn-outline-danger btn-eliminar-orden" data-id="${r.id}" title="Eliminar">
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

    // Botón Nueva Orden
    $('#btnNuevoOrden').on('click', function() {
        $('#formOrden')[0].reset();
        $('#orden_fecha').val('<?php echo e(now()->format("Y-m-d")); ?>');
        $('#tablaItems tbody').html(`
            <tr>
                <td><input type="number" class="form-control form-control-sm item-material" placeholder="ID material" required></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm item-cantidad" value="1" required></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm item-precio" value="0" required></td>
                <td class="item-subtotal text-end">0.00</td>
                <td><button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-item"><i class="fas fa-times"></i></button></td>
            </tr>
        `);
        actualizarTotal();
        $('#modalOrden').modal('show');
    });

    // Botón Generar desde Presupuesto
    $('#btnGenerarDesdePresupuesto').on('click', function() {
        $('#genPresupuestoId').val('');
        $('#modalGenerar').modal('show');
    });

    // Botón Agregar Item
    $('#btnAgregarItem').on('click', function() {
        const tbody = $('#tablaItems tbody');
        const fila = `
            <tr>
                <td><input type="number" class="form-control form-control-sm item-material" placeholder="ID material" required></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm item-cantidad" value="1" required></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm item-precio" value="0" required></td>
                <td class="item-subtotal text-end">0.00</td>
                <td><button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-item"><i class="fas fa-times"></i></button></td>
            </tr>
        `;
        tbody.append(fila);
        actualizarTotal();
    });

    // Eliminar Item
    $(document).on('click', '.btn-eliminar-item', function() {
        const tbody = $('#tablaItems tbody');
        if (tbody.find('tr').length > 1) {
            $(this).closest('tr').remove();
            actualizarTotal();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Debe haber al menos un ítem',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });

    // Calcular subtotal y total
    $(document).on('input', '.item-cantidad, .item-precio', function() {
        const fila = $(this).closest('tr');
        const cantidad = parseFloat(fila.find('.item-cantidad').val()) || 0;
        const precio = parseFloat(fila.find('.item-precio').val()) || 0;
        const subtotal = cantidad * precio;
        fila.find('.item-subtotal').text(subtotal.toFixed(2));
        actualizarTotal();
    });

    function actualizarTotal() {
        let total = 0;
        $('#tablaItems tbody .item-subtotal').each(function() {
            total += parseFloat($(this).text()) || 0;
        });
        $('#totalOrden').text(total.toFixed(2));
    }

    // Cambiar estado
    $(document).on('change', '.cambio-estado', function() {
        const id = $(this).data('id');
        const estado = $(this).val();
        const $this = $(this);

        Swal.fire({
            title: '¿Cambiar estado?',
            text: 'El estado pasará a "' + estadoLabels[estado] + '"',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'Cancelar',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/compras/' + id + '/estado',
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({ estado: estado }),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Estado actualizado',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tabla.ajax.reload();
                    },
                    error: function(xhr) {
                        $this.val($this.data('estado-anterior'));
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo actualizar el estado'
                        });
                    }
                });
            } else {
                // Revertir selección
                $this.val($this.data('estado-anterior'));
            }
        });
    });

    // Guardar estado anterior
    $(document).on('focus', '.cambio-estado', function() {
        $(this).data('estado-anterior', $(this).val());
    });

    // Eliminar orden
    $(document).on('click', '.btn-eliminar-orden', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Eliminar orden de compra?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/compras/' + id,
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

    // Confirmar generar desde presupuesto
    $('#btnConfirmarGenerar').on('click', function() {
        const presupuestoId = $('#genPresupuestoId').val();

        if (!presupuestoId) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione un presupuesto',
                text: 'Debe seleccionar un presupuesto para generar las órdenes de compra.',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        $(this).prop('disabled', true);
        $(this).html('<span class="spinner-border spinner-border-sm" role="status"></span> Generando...');

        $.ajax({
            url: '/compras/generar-desde-presupuesto/' + presupuestoId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje || 'Órdenes generadas correctamente',
                    timer: 3000,
                    showConfirmButton: false
                });
                $('#modalGenerar').modal('hide');
                tabla.ajax.reload();
            },
            error: function(xhr) {
                var errorMsg = 'Ocurrió un error al generar las órdenes';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            },
            complete: function() {
                $(this).prop('disabled', false);
                $(this).html('<i class="fas fa-magic me-1"></i>Generar órdenes');
            }
        });
    });

    // Enviar formulario de nueva orden
    $('#formOrden').on('submit', function(e) {
        e.preventDefault();

        const items = [];
        let itemsValidos = true;

        $('#tablaItems tbody tr').each(function() {
            const materialId = $(this).find('.item-material').val();
            const cantidad = $(this).find('.item-cantidad').val();
            const precio = $(this).find('.item-precio').val();

            if (!materialId || !cantidad || !precio) {
                itemsValidos = false;
                return false;
            }

            items.push({
                material_id: materialId,
                cantidad: cantidad,
                precio_unitario: precio
            });
        });

        if (!itemsValidos) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Todos los ítems deben tener material, cantidad y precio.'
            });
            return;
        }

        const datos = {
            proveedor_id: $('#orden_proveedor_id').val(),
            fecha: $('#orden_fecha').val(),
            presupuesto_id: $('#orden_presupuesto_id').val(),
            observaciones: $('#orden_observaciones').val(),
            items: items
        };

        if (!datos.proveedor_id) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione un proveedor',
                text: 'Debe seleccionar un proveedor para la orden de compra.'
            });
            return;
        }

        $('#btnGuardarOrden').prop('disabled', true);
        $('#btnGuardarOrden').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

        $.ajax({
            url: '<?php echo e(route("compras.store")); ?>',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            contentType: 'application/json',
            data: JSON.stringify(datos),
            success: function(response) {
                $('#modalOrden').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje || 'Orden creada correctamente',
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
                $('#btnGuardarOrden').prop('disabled', false);
                $('#btnGuardarOrden').html('<i class="fas fa-save me-1"></i>Guardar orden');
            }
        });
    });

    // Limpiar formularios al cerrar modales
    $('#modalOrden').on('hidden.bs.modal', function() {
        $('#formOrden')[0].reset();
        $('#orden_fecha').val('<?php echo e(now()->format("Y-m-d")); ?>');
    });

    $('#modalGenerar').on('hidden.bs.modal', function() {
        $('#genPresupuestoId').val('');
        $('#btnConfirmarGenerar').prop('disabled', false);
        $('#btnConfirmarGenerar').html('<i class="fas fa-magic me-1"></i>Generar órdenes');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/compras/index.blade.php ENDPATH**/ ?>