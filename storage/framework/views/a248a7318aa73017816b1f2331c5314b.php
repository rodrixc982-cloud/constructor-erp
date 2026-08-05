<?php $__env->startSection('titulo', 'Proveedores'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    /* ===== ESTILOS MODERNOS ===== */
    
    /* Tarjetas y contenedores */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    
    .card-header {
        border-bottom: 1px solid #f0f0f0;
        border-radius: 16px 16px 0 0 !important;
        padding: 1.25rem 1.5rem;
        background: #fff;
    }
    
    .card-title {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 1.1rem;
    }
    
    /* Tabla mejorada */
    .table {
        font-size: 0.9rem;
    }
    
    .table thead th {
        font-weight: 600;
        color: #4a4a6a;
        border-bottom: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        background: #f8f9fc;
    }
    
    .table tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fc;
        transition: background-color 0.2s ease;
    }
    
    /* ===== BOTONES DE ACCIÓN MODERNOS ===== */
    .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
        align-items: center;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .action-btn:hover::before {
        opacity: 1;
    }
    
    /* Botón Editar */
    .edit-btn {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .edit-btn::before {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }
    
    .edit-btn:hover {
        transform: translateY(-2px) scale(1.05);
        color: #fff;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }
    
    /* Botón Eliminar */
    .delete-btn {
        background: #fef2f2;
        color: #ef4444;
    }
    
    .delete-btn::before {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    
    .delete-btn:hover {
        transform: translateY(-2px) scale(1.05);
        color: #fff;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }
    
    .action-btn:active {
        transform: scale(0.95);
    }
    
    .action-btn i {
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
    }
    
    .action-btn:hover i {
        transform: rotate(5deg) scale(1.1);
    }
    
    /* Tooltip elegante */
    .action-btn[title] {
        position: relative;
    }
    
    .action-btn[title]:hover::after {
        content: attr(title);
        position: absolute;
        bottom: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.3px;
        white-space: nowrap;
        z-index: 1000;
        animation: tooltipFade 0.2s ease;
    }
    
    @keyframes tooltipFade {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(5px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0) scale(1);
        }
    }
    
    /* ===== BADGES DE ESTADO ===== */
    .badge-status {
        padding: 0.4rem 0.9rem;
        font-weight: 500;
        font-size: 0.75rem;
        letter-spacing: 0.3px;
        border-radius: 50px;
    }
    
    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-secondary {
        background: #f3f4f6;
        color: #6b7280;
    }
    
    /* ===== MODAL MEJORADO ===== */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    
    .modal-header {
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-header.bg-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-radius: 0 0 16px 16px;
        padding: 1rem 1.5rem;
        background: #fafafa;
    }
    
    /* ===== FORMULARIO MEJORADO ===== */
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #374151;
        margin-bottom: 0.4rem;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #ef4444;
    }
    
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .invalid-feedback {
        font-size: 0.8rem;
        margin-top: 0.3rem;
    }
    
    /* Switch moderno */
    .form-switch .form-check-input {
        width: 2.5rem;
        height: 1.4rem;
        border-radius: 1rem;
        border: 2px solid #d1d5db;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .form-switch .form-check-input:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    
    .form-switch .form-check-label {
        font-weight: 500;
        color: #374151;
        margin-left: 0.5rem;
        cursor: pointer;
    }
    
    /* ===== BOTÓN NUEVO PROVEEDOR ===== */
    .btn-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
    }
    
    .btn-primary:active {
        transform: scale(0.95);
    }
    
    .btn-secondary {
        border-radius: 10px;
        font-weight: 500;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .table {
            font-size: 0.8rem;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
    }
    
    /* ===== ANIMACIONES ===== */
    .fade-in {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* ===== BREADCRUMBS ===== */
    .breadcrumb {
        background: transparent;
        padding: 0.5rem 0;
    }
    
    .breadcrumb-item a {
        text-decoration: none;
        color: #4f46e5;
        transition: color 0.2s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #7c3aed;
    }
    
    .breadcrumb-item.active {
        color: #6b7280;
        font-weight: 500;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('dashboard')); ?>">
            <i class="fas fa-home me-1"></i>Dashboard
        </a>
    </li>
    <li class="breadcrumb-item active">
        <i class="fas fa-truck me-1"></i>Proveedores
    </li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card fade-in">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h3 class="card-title mb-0">
            <i class="fas fa-truck text-primary me-2"></i>
            Listado de proveedores
            <span class="badge bg-primary ms-2" id="totalProveedores">0</span>
        </h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('proveedores.crear')): ?>
        <button class="btn btn-primary btn-sm" id="btnNuevoProveedor">
            <i class="fas fa-plus me-1"></i>Nuevo proveedor
        </button>
        <?php endif; ?>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover w-100" id="tablaProveedores">
                <thead>
                    <tr>
                        <th>RUC</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modalProveedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloModalProveedor">
                    <i class="fas fa-truck me-2"></i>Nuevo proveedor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formProveedor" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="id" id="proveedor_id">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-file-invoice me-1 text-primary"></i>RUC *
                            </label>
                            <input type="text" name="ruc" id="proveedor_ruc" 
                                   class="form-control" required maxlength="11"
                                   placeholder="11 dígitos">
                            <div class="invalid-feedback">El RUC es obligatorio y debe tener 11 dígitos</div>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label">
                                <i class="fas fa-building me-1 text-primary"></i>Nombre / Razón social *
                            </label>
                            <input type="text" name="nombre" id="proveedor_nombre" 
                                   class="form-control" required
                                   placeholder="Ingrese el nombre o razón social">
                            <div class="invalid-feedback">El nombre es obligatorio</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-boxes me-1 text-primary"></i>Productos / Servicios
                            </label>
                            <input type="text" name="productos" id="proveedor_productos" 
                                   class="form-control" placeholder="Ej: Materiales de construcción">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-user-tie me-1 text-primary"></i>Contacto
                            </label>
                            <input type="text" name="contacto" id="proveedor_contacto" 
                                   class="form-control" placeholder="Nombre del contacto principal">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-phone me-1 text-primary"></i>Teléfono
                            </label>
                            <input type="tel" name="telefono" id="proveedor_telefono" 
                                   class="form-control" placeholder="Número de teléfono">
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label">
                                <i class="fas fa-envelope me-1 text-primary"></i>Email
                            </label>
                            <input type="email" name="email" id="proveedor_email" 
                                   class="form-control" placeholder="correo@proveedor.com">
                            <div class="invalid-feedback">Ingresa un email válido</div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-map-pin me-1 text-primary"></i>Dirección
                            </label>
                            <input type="text" name="direccion" id="proveedor_direccion" 
                                   class="form-control" placeholder="Dirección completa">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-university me-1 text-primary"></i>Banco
                            </label>
                            <input type="text" name="banco" id="proveedor_banco" 
                                   class="form-control" placeholder="Nombre del banco">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-credit-card me-1 text-primary"></i>N° Cuenta
                            </label>
                            <input type="text" name="cuenta" id="proveedor_cuenta" 
                                   class="form-control" placeholder="Número de cuenta">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-qrcode me-1 text-primary"></i>CCI
                            </label>
                            <input type="text" name="cci" id="proveedor_cci" 
                                   class="form-control" placeholder="Código CCI">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-sticky-note me-1 text-primary"></i>Observaciones
                            </label>
                            <textarea name="observaciones" id="proveedor_observaciones" 
                                      class="form-control" rows="2" 
                                      placeholder="Observaciones adicionales"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="estado" id="proveedor_estado" 
                                       class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="proveedor_estado">
                                    <i class="fas fa-toggle-on me-1 text-success"></i>
                                    Proveedor activo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarProveedor">
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
    'use strict';

    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // ============================================
    // 1. CONFIGURACIÓN DE DATATABLE
    // ============================================
    var tabla = $('#tablaProveedores').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("proveedores.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                const data = json.data || [];
                // Actualizar contador
                const badge = document.getElementById('totalProveedores');
                if (badge) badge.textContent = data.length;
                return data;
            },
            error: function(xhr, error, thrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al cargar datos',
                    text: 'Por favor, recarga la página o contacta al administrador.',
                    confirmButtonColor: '#4f46e5'
                });
            }
        },
        columns: [
            { 
                data: 'ruc',
                render: function(data) {
                    return `<span class="fw-bold">${data}</span>`;
                }
            },
            { data: 'nombre' },
            { 
                data: 'contacto', 
                defaultContent: '—',
                render: function(data) {
                    return data ? `<span class="text-muted">${data}</span>` : '—';
                }
            },
            { 
                data: 'telefono', 
                defaultContent: '—',
                render: function(data) {
                    if (!data) return '—';
                    return `<a href="tel:${data}" class="text-decoration-none">
                        <i class="fas fa-phone me-1"></i>${data}
                    </a>`;
                }
            },
            {
                data: 'estado',
                render: function(data) {
                    return data ? 
                        `<span class="badge badge-status badge-success">
                            <i class="fas fa-check-circle me-1"></i>Activo
                        </span>` : 
                        `<span class="badge badge-status badge-secondary">
                            <i class="fas fa-circle me-1"></i>Inactivo
                        </span>`;
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function(data) {
                    return `
                        <div class="action-buttons">
                            <button class="action-btn edit-btn btnEditar" 
                                    data-id="${data.id}" 
                                    title="Editar proveedor">
                                <i class="fas fa-pen-fancy"></i>
                            </button>
                            <button class="action-btn delete-btn btnEliminar" 
                                    data-id="${data.id}" 
                                    title="Eliminar proveedor">
                                <i class="fas fa-trash-can"></i>
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
        order: [[1, 'asc']],
        responsive: true,
        stateSave: true
    });

    // ============================================
    // 2. FUNCIONES AUXILIARES
    // ============================================
    function resetFormulario() {
        const form = document.getElementById('formProveedor');
        form.reset();
        form.classList.remove('was-validated');
        document.getElementById('proveedor_id').value = '';
        document.getElementById('proveedor_estado').checked = true;
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    function mostrarModal(titulo, data = null) {
        resetFormulario();
        if (data) {
            const campos = ['id', 'ruc', 'nombre', 'productos', 'contacto', 'telefono', 
                           'email', 'direccion', 'banco', 'cuenta', 'cci', 'observaciones'];
            campos.forEach(function(campo) {
                const el = document.getElementById('proveedor_' + campo);
                if (el) {
                    el.value = data[campo] !== undefined && data[campo] !== null ? data[campo] : '';
                }
            });
            document.getElementById('proveedor_estado').checked = data.estado == 1 || data.estado == true;
        }
        document.getElementById('tituloModalProveedor').innerHTML = 
            `<i class="fas fa-truck me-2"></i>${titulo}`;
        $('#modalProveedor').modal('show');
    }

    // ============================================
    // 3. EVENTO: NUEVO PROVEEDOR
    // ============================================
    $('#btnNuevoProveedor').on('click', function() {
        mostrarModal('Nuevo proveedor');
    });

    // ============================================
    // 4. EVENTOS DE ACCIONES (Editar / Eliminar)
    // ============================================
    
    // Editar proveedor
    $(document).on('click', '.btnEditar', function() {
        var id = $(this).data('id');
        
        // Mostrar loading
        Swal.fire({
            title: 'Cargando datos...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: '/proveedores/' + id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                Swal.close();
                var data = response.data || response;
                mostrarModal('Editar proveedor', data);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los datos del proveedor',
                    confirmButtonColor: '#4f46e5'
                });
            }
        });
    });

    // Eliminar proveedor
    $(document).on('click', '.btnEliminar', function() {
        var id = $(this).data('id');
        var nombre = $(this).closest('tr').find('td:eq(1)').text();
        
        Swal.fire({
            title: '¿Eliminar proveedor?',
            html: `
                <div class="text-start">
                    <p>Estás a punto de eliminar a <strong class="text-danger">${nombre.trim()}</strong>.</p>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Podrás restaurarlo luego desde Auditoría.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash-can me-1"></i>Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then(function(result) {
            if (!result.isConfirmed) return;
            
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando proveedor...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '/proveedores/' + id,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: response.mensaje || 'Proveedor eliminado correctamente',
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                    tabla.ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al eliminar el proveedor',
                        confirmButtonColor: '#ef4444'
                    });
                }
            });
        });
    });

    // ============================================
    // 5. ENVÍO DEL FORMULARIO
    // ============================================
    $('#formProveedor').on('submit', function(e) {
        e.preventDefault();
        
        // Validación HTML5
        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        var id = $('#proveedor_id').val();
        var datos = {
            ruc: $('#proveedor_ruc').val().trim(),
            nombre: $('#proveedor_nombre').val().trim(),
            productos: $('#proveedor_productos').val().trim(),
            contacto: $('#proveedor_contacto').val().trim(),
            telefono: $('#proveedor_telefono').val().trim(),
            email: $('#proveedor_email').val().trim(),
            direccion: $('#proveedor_direccion').val().trim(),
            banco: $('#proveedor_banco').val().trim(),
            cuenta: $('#proveedor_cuenta').val().trim(),
            cci: $('#proveedor_cci').val().trim(),
            observaciones: $('#proveedor_observaciones').val().trim(),
            estado: $('#proveedor_estado').is(':checked') ? 1 : 0
        };

        var url = id ? '/proveedores/' + id : '/proveedores';
        var method = id ? 'PUT' : 'POST';

        // Deshabilitar botón
        var btnGuardar = $('#btnGuardarProveedor');
        var textoOriginal = btnGuardar.html();
        btnGuardar.prop('disabled', true);
        btnGuardar.html('<span class="spinner-border spinner-border-sm me-1" role="status"></span>Guardando...');

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
                    title: '¡Éxito!',
                    text: response.mensaje || `Proveedor ${id ? 'actualizado' : 'creado'} correctamente`,
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
                tabla.ajax.reload();
            },
            error: function(xhr) {
                var errorMsg = 'Ocurrió un error al guardar el proveedor';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        var firstError = Object.values(errors)[0];
                        if (firstError && firstError[0]) {
                            errorMsg = firstError[0];
                        }
                    } else if (xhr.responseJSON.mensaje) {
                        errorMsg = xhr.responseJSON.mensaje;
                    }
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error al guardar',
                    text: errorMsg,
                    confirmButtonColor: '#ef4444'
                });
            },
            complete: function() {
                btnGuardar.prop('disabled', false);
                btnGuardar.html(textoOriginal);
            }
        });
    });

    // ============================================
    // 6. LIMPIAR FORMULARIO AL CERRAR MODAL
    // ============================================
    $('#modalProveedor').on('hidden.bs.modal', function() {
        resetFormulario();
    });

    // ============================================
    // 7. VALIDACIÓN Y MÁSCARAS DE CAMPOS
    // ============================================
    
    // RUC: solo números, máximo 11 dígitos
    $('#proveedor_ruc').on('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
    });
    
    // Teléfono: solo números
    $('#proveedor_telefono').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
    
    // Cuenta: solo números
    $('#proveedor_cuenta').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
    
    // CCI: solo números
    $('#proveedor_cci').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // ============================================
    // 8. ATAJO DE TECLADO: ESC para cerrar modal
    // ============================================
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalProveedor'));
            if (modal) modal.hide();
        }
    });

    console.log('✅ Sistema de proveedores cargado exitosamente');
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/proveedores/index.blade.php ENDPATH**/ ?>