<?php $__env->startSection('titulo', 'Materiales'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Materiales</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title">Materiales</h3>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('materiales.importar.plantilla')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-file-download me-1"></i>Plantilla Excel
            </a>
            <button class="btn btn-outline-success btn-sm" id="btnImportar">
                <i class="fas fa-file-import me-1"></i>Importar
            </button>
            <a href="<?php echo e(route('materiales.exportar.excel')); ?>" class="btn btn-outline-success btn-sm">
                <i class="fas fa-file-excel me-1"></i>Excel
            </a>
            <a href="<?php echo e(route('materiales.exportar.pdf')); ?>" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i>PDF
            </a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('materiales.crear')): ?>
            <button class="btn btn-primary btn-sm" id="btnNuevoMaterial">
                <i class="fas fa-plus me-1"></i>Nuevo material
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <input type="file" id="inputImportar" accept=".xlsx,.xls,.csv" class="d-none">
        <table class="table table-striped table-bordered w-100" id="tablaMateriales">
            <thead>
                <tr>
                    <th>Img</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Unidad</th>
                    <th>P. Venta</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalMaterial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalMaterial">Nuevo material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMaterial" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="material_id">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Imagen</label>
                            <input type="file" name="imagen" id="material_imagen" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Código (autogenerado si vacío)</label>
                            <input type="text" name="codigo" id="material_codigo" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Código de barras</label>
                            <input type="text" name="codigo_barras" id="material_codigo_barras" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" id="material_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" id="material_marca" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo" id="material_modelo" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="categoria_id" id="material_categoria_id" class="form-select select2">
                                <option value="">— Ninguna —</option>
                                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c->id); ?>"><?php echo e($c->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Proveedor</label>
                            <select name="proveedor_id" id="material_proveedor_id" class="form-select select2">
                                <option value="">— Ninguno —</option>
                                <?php $__currentLoopData = $proveedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->id); ?>"><?php echo e($p->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unidad</label>
                            <select name="unidad" id="material_unidad" class="form-select">
                                <?php $__currentLoopData = ['UND','KG','M2','M3','ML','BLS','GAL','LTS']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u); ?>"><?php echo e($u); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Precio compra</label>
                            <input type="number" step="0.01" name="precio_compra" id="material_precio_compra" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Precio venta</label>
                            <input type="number" step="0.01" name="precio_venta" id="material_precio_venta" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" step="0.01" name="stock" id="material_stock" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Stock mínimo</label>
                            <input type="number" step="0.01" name="stock_minimo" id="material_stock_minimo" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">IVA (%)</label>
                            <input type="number" step="0.01" name="iva" id="material_iva" class="form-control" value="18" required>
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">Observaciones</label>
                            <input type="text" name="observaciones" id="material_observaciones" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="estado" id="material_estado" class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="material_estado">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarMaterial">Guardar</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Inicializar Select2
    $('.select2').select2({ 
        dropdownParent: $('#modalMaterial'), 
        width: '100%' 
    });

    // Inicializar DataTable
    var tabla = $('#tablaMateriales').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("materiales.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { 
                data: 'imagen',
                render: function(img) {
                    var src = img ? '/storage/' + img : '<?php echo e(asset('images/material-default.png')); ?>';
                    return '<img src="' + src + '" style="width:36px;height:36px;object-fit:cover;border-radius:4px">';
                }
            },
            { data: 'codigo' },
            { data: 'nombre' },
            { 
                data: 'categoria',
                render: function(c) {
                    return c ? c.nombre : '—';
                }
            },
            { data: 'unidad' },
            { 
                data: 'precio_venta',
                render: function(p) {
                    return 'S/ ' + parseFloat(p).toFixed(2);
                }
            },
            { 
                data: null,
                render: function(r) {
                    var html = r.stock + ' ' + r.unidad;
                    if (parseFloat(r.stock) <= parseFloat(r.stock_minimo)) {
                        html += ' <span class="badge bg-danger ms-1">Bajo</span>';
                    }
                    return html;
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
                        <a href="/materiales/${r.id}/qr" target="_blank" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-qrcode"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-primary btnEditarMaterial" data-id="${r.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarMaterial" data-id="${r.id}">
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

    let cacheData = [];
    tabla.on('xhr', function() {
        cacheData = tabla.ajax.json().data || [];
    });

    // Botón Nuevo Material
    $('#btnNuevoMaterial').on('click', function() {
        $('#formMaterial')[0].reset();
        $('#material_id').val('');
        $('.select2').val('').trigger('change');
        $('#material_estado').prop('checked', true);
        $('#material_precio_compra').val('0');
        $('#material_precio_venta').val('0');
        $('#material_stock').val('0');
        $('#material_stock_minimo').val('0');
        $('#material_iva').val('18');
        $('#material_unidad').val('UND');
        $('#tituloModalMaterial').text('Nuevo material');
        $('#modalMaterial').modal('show');
    });

    // Editar material
    $(document).on('click', '.btnEditarMaterial', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/materiales/' + id,
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
                        text: 'No se encontraron datos del material'
                    });
                    return;
                }

                $('#material_id').val(data.id);
                $('#material_codigo').val(data.codigo || '');
                $('#material_codigo_barras').val(data.codigo_barras || '');
                $('#material_nombre').val(data.nombre || '');
                $('#material_marca').val(data.marca || '');
                $('#material_modelo').val(data.modelo || '');
                $('#material_categoria_id').val(data.categoria_id || '').trigger('change');
                $('#material_proveedor_id').val(data.proveedor_id || '').trigger('change');
                $('#material_unidad').val(data.unidad || 'UND');
                $('#material_precio_compra').val(data.precio_compra || 0);
                $('#material_precio_venta').val(data.precio_venta || 0);
                $('#material_stock').val(data.stock || 0);
                $('#material_stock_minimo').val(data.stock_minimo || 0);
                $('#material_iva').val(data.iva || 18);
                $('#material_observaciones').val(data.observaciones || '');
                $('#material_estado').prop('checked', data.estado == 1 || data.estado == true);
                $('#tituloModalMaterial').text('Editar material');
                $('#modalMaterial').modal('show');
            },
            error: function(xhr) {
                var errorMsg = 'No se pudieron cargar los datos del material';
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
                console.error('Error al cargar material:', xhr.responseText);
            }
        });
    });

    // Eliminar material
    $(document).on('click', '.btnEliminarMaterial', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar material?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/materiales/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Material eliminado',
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
    $('#formMaterial').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#material_id').val();
        var formData = new FormData(this);
        var estado = $('#material_estado').is(':checked') ? 1 : 0;
        formData.set('estado', estado);

        var url = id ? '/materiales/' + id : '/materiales';
        var method = id ? 'POST' : 'POST';
        
        if (id) {
            formData.append('_method', 'PUT');
        }

        $('#btnGuardarMaterial').prop('disabled', true);
        $('#btnGuardarMaterial').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                $('#modalMaterial').modal('hide');
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
                $('#btnGuardarMaterial').prop('disabled', false);
                $('#btnGuardarMaterial').text('Guardar');
            }
        });
    });

    // Importar archivo
    $('#btnImportar').on('click', function() {
        $('#inputImportar').click();
    });

    $('#inputImportar').on('change', function() {
        var archivo = this.files[0];
        if (!archivo) return;
        
        var formData = new FormData();
        formData.append('archivo', archivo);

        Swal.fire({
            title: 'Importando...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?php echo e(route("materiales.importar")); ?>',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje || 'Materiales importados correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                tabla.ajax.reload();
            },
            error: function(xhr) {
                var errorMsg = 'No se pudo importar el archivo';
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
        
        this.value = '';
    });

    // Limpiar formulario al cerrar modal
    $('#modalMaterial').on('hidden.bs.modal', function() {
        $('#formMaterial')[0].reset();
        $('#material_id').val('');
        $('.select2').val('').trigger('change');
        $('#material_estado').prop('checked', true);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/materiales/index.blade.php ENDPATH**/ ?>