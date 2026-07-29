<?php $__env->startSection('titulo', $obra->nombre); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .adjunto-item {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .adjunto-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .badge-estado {
        font-size: 0.9rem;
        padding: 8px 16px;
    }
    .icono-archivo {
        font-size: 3rem;
        color: #6c757d;
    }
    .btn-ver-archivo {
        margin-top: 5px;
    }
    .preview-image {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 8px;
        border: 1px solid #e9ecef;
    }
    .preview-pdf {
        width: 100%;
        height: 140px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e9ecef;
        margin-bottom: 8px;
    }
    .preview-pdf i {
        font-size: 4rem;
        color: #dc3545;
    }
    .preview-doc {
        width: 100%;
        height: 140px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e9ecef;
        margin-bottom: 8px;
    }
    .preview-doc i {
        font-size: 4rem;
        color: #0d6efd;
    }
    .preview-excel {
        width: 100%;
        height: 140px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e9ecef;
        margin-bottom: 8px;
    }
    .preview-excel i {
        font-size: 4rem;
        color: #198754;
    }
    .preview-zip {
        width: 100%;
        height: 140px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e9ecef;
        margin-bottom: 8px;
    }
    .preview-zip i {
        font-size: 4rem;
        color: #ffc107;
    }
    .nombre-archivo {
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 4px;
    }
    .modal-preview-img {
        max-width: 100%;
        max-height: 80vh;
        margin: 0 auto;
        display: block;
    }
    .modal-preview-iframe {
        width: 100%;
        height: 80vh;
        border: none;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('obras.index')); ?>">Obras</a></li>
    <li class="breadcrumb-item active"><?php echo e($obra->nombre); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="row">
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo e($obra->nombre); ?></h5>
                <hr>
                <p class="mb-2">
                    <i class="fas fa-user me-2 text-primary"></i>
                    <strong>Cliente:</strong> <?php echo e($obra->cliente?->nombre ?? '—'); ?>

                </p>
                <p class="mb-2">
                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                    <strong>Ubicación:</strong> <?php echo e($obra->ubicacion ?? 'Sin ubicación'); ?>

                </p>
                <p class="mb-2">
                    <i class="fas fa-user-hard-hat me-2 text-warning"></i>
                    <strong>Responsable:</strong> <?php echo e($obra->responsable?->name ?? '—'); ?>

                </p>
                <p class="mb-2">
                    <i class="fas fa-calendar me-2 text-success"></i>
                    <strong>Inicio:</strong> <?php echo e($obra->fecha_inicio ? \Carbon\Carbon::parse($obra->fecha_inicio)->format('d/m/Y') : '—'); ?>

                </p>
                <p class="mb-2">
                    <i class="fas fa-calendar-check me-2 text-info"></i>
                    <strong>Fin estimado:</strong> <?php echo e($obra->fecha_fin_estimada ? \Carbon\Carbon::parse($obra->fecha_fin_estimada)->format('d/m/Y') : '—'); ?>

                </p>
                <p class="mb-3">
                    <i class="fas fa-clock me-2 text-secondary"></i>
                    <strong>Estado:</strong>
                    <?php
                        $estados = [
                            'planificacion' => 'secondary',
                            'activa' => 'success',
                            'pausada' => 'warning',
                            'terminada' => 'info',
                            'cancelada' => 'danger'
                        ];
                        $estadoLabel = [
                            'planificacion' => 'Planificación',
                            'activa' => 'Activa',
                            'pausada' => 'Pausada',
                            'terminada' => 'Terminada',
                            'cancelada' => 'Cancelada'
                        ];
                    ?>
                    <span class="badge bg-<?php echo e($estados[$obra->estado] ?? 'secondary'); ?> badge-estado">
                        <?php echo e($estadoLabel[$obra->estado] ?? $obra->estado); ?>

                    </span>
                </p>
                <?php if($obra->observaciones): ?>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-comment me-1"></i>
                        <?php echo e($obra->observaciones); ?>

                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    <i class="fas fa-paperclip me-2"></i>Adjuntos
                    <span class="badge bg-secondary ms-2"><?php echo e($obra->adjuntos->count()); ?></span>
                </h3>
                <button class="btn btn-primary btn-sm" id="btnSubirAdjunto">
                    <i class="fas fa-upload me-1"></i>Subir archivo
                </button>
            </div>
            <div class="card-body">
                <input type="file" id="inputArchivo" class="d-none" accept=".pdf,.xlsx,.xls,.doc,.docx,.dwg,.jpg,.jpeg,.png,.zip">
                
                <div class="row" id="listaAdjuntos">
                    <?php $__empty_1 = true; $__currentLoopData = $obra->adjuntos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $extension = strtolower(pathinfo($adj->nombre_original, PATHINFO_EXTENSION));
                        $esImagen = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $esPdf = $extension === 'pdf';
                        $esDoc = in_array($extension, ['doc', 'docx']);
                        $esExcel = in_array($extension, ['xls', 'xlsx']);
                        $esZip = $extension === 'zip';
                    ?>
                    <div class="col-md-4 mb-3 adjunto-item" data-id="<?php echo e($adj->id); ?>">
                        <div class="card border h-100">
                            <div class="card-body text-center p-2">
                                <!-- Vista previa -->
                                <?php if($esImagen): ?>
                                    <img src="<?php echo e(asset('storage/'.$adj->ruta)); ?>" 
                                         alt="<?php echo e($adj->nombre_original); ?>" 
                                         class="preview-image"
                                         onclick="verImagen('<?php echo e(asset('storage/'.$adj->ruta)); ?>', '<?php echo e($adj->nombre_original); ?>')">
                                <?php elseif($esPdf): ?>
                                    <div class="preview-pdf" onclick="verPDF('<?php echo e(asset('storage/'.$adj->ruta)); ?>', '<?php echo e($adj->nombre_original); ?>')">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                <?php elseif($esDoc): ?>
                                    <div class="preview-doc" onclick="verPDF('<?php echo e(asset('storage/'.$adj->ruta)); ?>', '<?php echo e($adj->nombre_original); ?>')">
                                        <i class="fas fa-file-word"></i>
                                    </div>
                                <?php elseif($esExcel): ?>
                                    <div class="preview-excel" onclick="verPDF('<?php echo e(asset('storage/'.$adj->ruta)); ?>', '<?php echo e($adj->nombre_original); ?>')">
                                        <i class="fas fa-file-excel"></i>
                                    </div>
                                <?php elseif($esZip): ?>
                                    <div class="preview-zip" onclick="descargarArchivo('<?php echo e(asset('storage/'.$adj->ruta)); ?>', '<?php echo e($adj->nombre_original); ?>')">
                                        <i class="fas fa-file-archive"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="preview-doc" onclick="verPDF('<?php echo e(asset('storage/'.$adj->ruta)); ?>', '<?php echo e($adj->nombre_original); ?>')">
                                        <i class="fas fa-file"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Nombre del archivo -->
                                <p class="nombre-archivo text-truncate" title="<?php echo e($adj->nombre_original); ?>">
                                    <?php echo e($adj->nombre_original); ?>

                                </p>
                                
                                <!-- Tipo y tamaño -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary"><?php echo e($adj->tipo); ?></span>
                                    <span class="badge bg-light text-dark">
                                        <?php echo e(number_format($adj->ruta ? filesize(storage_path('app/public/'.$adj->ruta)) / 1024 : 0, 1)); ?> KB
                                    </span>
                                </div>

                                <!-- Botones de acción -->
                                <div class="mt-2">
                                    <?php if($esImagen): ?>
                                        <button class="btn btn-sm btn-outline-primary" onclick="verImagen('<?php echo e(asset('storage/'.$adj->ruta)); ?>', '<?php echo e($adj->nombre_original); ?>')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo e(asset('storage/'.$adj->ruta)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(asset('storage/'.$adj->ruta)); ?>" download class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger btn-eliminar-adjunto" data-id="<?php echo e($adj->id); ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay archivos adjuntos en esta obra.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalPreview" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPreviewTitle">Vista previa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" id="modalPreviewBody">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="modalPreviewDownload" target="_blank" class="btn btn-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Abrir en nueva ventana
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let tipoAdjunto = 'documento';

    // Función para ver imagen
    window.verImagen = function(url, nombre) {
        $('#modalPreviewTitle').text('📷 ' + nombre);
        $('#modalPreviewBody').html('<img src="' + url + '" class="modal-preview-img" alt="' + nombre + '">');
        $('#modalPreviewDownload').attr('href', url);
        $('#modalPreview').modal('show');
    };

    // Función para ver PDF/Documentos
    window.verPDF = function(url, nombre) {
        $('#modalPreviewTitle').text('📄 ' + nombre);
        $('#modalPreviewBody').html('<iframe src="' + url + '" class="modal-preview-iframe"></iframe>');
        $('#modalPreviewDownload').attr('href', url);
        $('#modalPreview').modal('show');
    };

    // Función para descargar archivo
    window.descargarArchivo = function(url, nombre) {
        window.open(url, '_blank');
    };

    // Botón Subir Adjunto
    $('#btnSubirAdjunto').on('click', function() {
        Swal.fire({
            title: 'Selecciona el tipo de archivo',
            input: 'select',
            inputOptions: {
                'foto': '📷 Foto',
                'plano': '📐 Plano',
                'documento': '📄 Documento',
                'contrato': '📋 Contrato'
            },
            inputPlaceholder: 'Selecciona un tipo...',
            showCancelButton: true,
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar',
            inputValidator: function(value) {
                if (!value) {
                    return 'Debes seleccionar un tipo de archivo';
                }
            }
        }).then(function(result) {
            if (result.isConfirmed && result.value) {
                tipoAdjunto = result.value;
                $('#inputArchivo').click();
            }
        });
    });

    // Input file change
    $('#inputArchivo').on('change', function() {
        const archivo = this.files[0];
        if (!archivo) return;

        // Validar tamaño (máximo 20MB)
        if (archivo.size > 20 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El archivo no debe superar los 20MB'
            });
            this.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('archivo', archivo);
        formData.append('tipo', tipoAdjunto);

        Swal.fire({
            title: 'Subiendo archivo...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?php echo e(route("obras.adjuntos.subir", $obra)); ?>',
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
                    title: response.mensaje || 'Archivo subido correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                var errorMsg = 'No se pudo subir el archivo';
                if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    errorMsg = xhr.responseJSON.mensaje;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    var firstError = Object.values(errors)[0];
                    if (firstError && firstError[0]) {
                        errorMsg = firstError[0];
                    }
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

    // Eliminar adjunto
    $(document).on('click', '.btn-eliminar-adjunto', function() {
        var id = $(this).data('id');
        var $this = $(this);

        Swal.fire({
            title: '¿Eliminar archivo?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/obras/adjuntos/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Archivo eliminado',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $this.closest('.adjunto-item').fadeOut(300, function() {
                            $(this).remove();
                            if ($('#listaAdjuntos .adjunto-item').length === 0) {
                                $('#listaAdjuntos').html(`
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No hay archivos adjuntos en esta obra.</p>
                                        </div>
                                    </div>
                                `);
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al eliminar el archivo'
                        });
                    }
                });
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/obras/show.blade.php ENDPATH**/ ?>