<?php $__env->startSection('titulo', 'Inventario'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Estilos modernos y mejorados */
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-card .icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .stat-card .number {
        font-size: 2rem;
        font-weight: bold;
        margin: 0.5rem 0;
    }
    .stat-card .label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .stat-card.green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .stat-card.red {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    }
    .stat-card.blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stat-card.orange {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 0.8rem 1.5rem;
        font-weight: 500;
        border-radius: 10px 10px 0 0;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover {
        color: #495057;
        background: #f8f9fa;
    }
    .nav-tabs .nav-link.active {
        color: #667eea;
        background: white;
        border-bottom: 3px solid #667eea;
        box-shadow: 0 -3px 10px rgba(102, 126, 234, 0.1);
    }
    
    .action-btn {
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    
    .alert-modern {
        border: none;
        border-radius: 15px;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
    }
    .alert-modern i {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
    
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 500;
    }
    
    .warehouse-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
    }
    .warehouse-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: transparent;
    }
    .warehouse-card .warehouse-icon {
        font-size: 2rem;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .stock-low {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
    
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-header {
        border-bottom: none;
        padding: 1.5rem 1.5rem 0.5rem 1.5rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        border-top: none;
        padding: 0.5rem 1.5rem 1.5rem 1.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    .btn {
        border-radius: 10px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .table-modern {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .table-modern thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        color: #495057;
    }
    .table-modern tbody tr:hover {
        background: #f8f9fa;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.4rem 1rem;
        margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    
    @media (max-width: 768px) {
        .stat-card .number {
            font-size: 1.5rem;
        }
        .action-btn {
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><i class="fas fa-home"></i> Dashboard</a></li>
    <li class="breadcrumb-item active"><i class="fas fa-warehouse"></i> Inventario</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="label">Total Materiales</div>
                    <div class="number"><?php echo e($totalMateriales ?? 0); ?></div>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card green">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="label">Entradas (Mes)</div>
                    <div class="number"><?php echo e($entradasMes ?? 0); ?></div>
                </div>
                <div class="icon"><i class="fas fa-arrow-down"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card red">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="label">Salidas (Mes)</div>
                    <div class="number"><?php echo e($salidasMes ?? 0); ?></div>
                </div>
                <div class="icon"><i class="fas fa-arrow-up"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card orange">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="label">Stock Bajo</div>
                    <div class="number"><?php echo e($alertas->count() ?? 0); ?></div>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($alertas) && $alertas->count()): ?>
<div class="alert-modern alert alert-warning mb-4 d-flex align-items-center">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <div>
        <strong><?php echo e($alertas->count()); ?> material(es)</strong> por debajo del stock mínimo:
        <span class="fw-bold"><?php echo e($alertas->pluck('nombre')->join(', ')); ?></span>
    </div>
</div>
<?php endif; ?>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tabMovimientos">
            <i class="fas fa-history me-2"></i>Kardex / Movimientos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tabAlmacenes">
            <i class="fas fa-warehouse me-2"></i>Almacenes
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tabMovimientos">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-list-ul me-2 text-primary"></i>Movimientos recientes
                </h5>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-success action-btn btn-sm" id="btnEntrada">
                        <i class="fas fa-arrow-down me-1"></i>Entrada
                    </button>
                    <button class="btn btn-danger action-btn btn-sm" id="btnSalida">
                        <i class="fas fa-arrow-up me-1"></i>Salida
                    </button>
                    <button class="btn btn-info action-btn btn-sm text-white" id="btnTransferencia">
                        <i class="fas fa-exchange-alt me-1"></i>Transferencia
                    </button>
                    <button class="btn btn-secondary action-btn btn-sm" id="btnAjuste">
                        <i class="fas fa-balance-scale me-1"></i>Ajuste
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern table-hover w-100" id="tablaMovimientos">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar-alt me-1"></i>Fecha</th>
                                <th><i class="fas fa-cube me-1"></i>Material</th>
                                <th><i class="fas fa-tag me-1"></i>Tipo</th>
                                <th><i class="fas fa-sort-amount-up me-1"></i>Cantidad</th>
                                <th><i class="fas fa-store me-1"></i>Almacén</th>
                                <th><i class="fas fa-comment me-1"></i>Motivo</th>
                                <th><i class="fas fa-user me-1"></i>Usuario</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tabAlmacenes">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-warehouse me-2 text-primary"></i>Almacenes
                </h5>
                <button class="btn btn-primary action-btn btn-sm" id="btnNuevoAlmacen">
                    <i class="fas fa-plus me-1"></i>Nuevo almacén
                </button>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <?php $__empty_1 = true; $__currentLoopData = $almacenes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="warehouse-card">
                            <div class="warehouse-icon">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <h6 class="fw-bold mb-1"><?php echo e($a->nombre); ?></h6>
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                <?php echo e($a->ubicacion ?? 'Sin ubicación'); ?>

                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-user me-1"></i>
                                <?php echo e($a->responsable ?? 'Sin responsable'); ?>

                            </small>
                            <div class="mt-2">
                                <span class="badge bg-success badge-modern">
                                    <i class="fas fa-check-circle me-1"></i>Activo
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay almacenes registrados</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>






<div class="modal fade" id="modalEntrada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEntrada">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-arrow-down text-success me-2"></i>Registrar entrada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Material *</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $materiales ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>"><?php echo e($m->nombre); ?> (<?php echo e($m->codigo); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén *</label>
                        <select name="almacen_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $almacenes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($a->id); ?>"><?php echo e($a->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad *</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required min="0.01" placeholder="Ingrese la cantidad">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <input type="text" name="motivo" class="form-control" placeholder="Ej: Compra, Devolución, etc.">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Registrar entrada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalSalida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formSalida">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-arrow-up text-danger me-2"></i>Registrar salida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Material *</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $materiales ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>"><?php echo e($m->nombre); ?> (<?php echo e($m->codigo); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén *</label>
                        <select name="almacen_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $almacenes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($a->id); ?>"><?php echo e($a->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad *</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required min="0.01" placeholder="Ingrese la cantidad">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <input type="text" name="motivo" class="form-control" placeholder="Ej: Venta, Uso interno, etc.">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check me-1"></i>Registrar salida
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalTransferencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formTransferencia">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt text-info me-2"></i>Transferencia entre almacenes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Material *</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $materiales ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>"><?php echo e($m->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén origen *</label>
                        <select name="almacen_origen_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $almacenes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($a->id); ?>"><?php echo e($a->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén destino *</label>
                        <select name="almacen_destino_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $almacenes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($a->id); ?>"><?php echo e($a->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad *</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required min="0.01" placeholder="Ingrese la cantidad">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white">
                        <i class="fas fa-check me-1"></i>Transferir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalAjuste" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formAjuste">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-balance-scale text-secondary me-2"></i>Ajuste de inventario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Material *</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $materiales ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>"><?php echo e($m->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén *</label>
                        <select name="almacen_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php $__currentLoopData = $almacenes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($a->id); ?>"><?php echo e($a->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva cantidad de stock *</label>
                        <input type="number" step="0.01" name="nueva_cantidad" class="form-control" required min="0" placeholder="Ingrese la nueva cantidad">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-check me-1"></i>Ajustar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalNuevoAlmacen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formAlmacen">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-warehouse text-primary me-2"></i>Nuevo almacén</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required placeholder="Ingrese el nombre del almacén">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" placeholder="Ej: Zona Norte, Estante 3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Responsable</label>
                        <input type="text" name="responsable" class="form-control" placeholder="Nombre del responsable">
                    </div>
                    <div class="mb-0 form-check">
                        <input type="checkbox" name="estado" class="form-check-input" value="1" checked id="estadoAlmacen">
                        <label class="form-check-label" for="estadoAlmacen">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
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
    
    // Inicializar DataTable con mejoras
    var tabla = $('#tablaMovimientos').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("inventario.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { 
                data: 'created_at',
                render: function(f) {
                    if (!f) return '—';
                    const date = new Date(f);
                    return date.toLocaleString('es-PE', {
                        year: 'numeric',
                        month: 'short',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            },
            { 
                data: 'material',
                render: function(m) {
                    return m ? `<strong>${m.nombre}</strong><br><small class="text-muted">${m.codigo || ''}</small>` : '—';
                }
            },
            { 
                data: 'tipo',
                render: function(t) {
                    const badges = {
                        'entrada': '<span class="badge bg-success badge-modern"><i class="fas fa-arrow-down me-1"></i>Entrada</span>',
                        'salida': '<span class="badge bg-danger badge-modern"><i class="fas fa-arrow-up me-1"></i>Salida</span>',
                        'ajuste': '<span class="badge bg-secondary badge-modern"><i class="fas fa-balance-scale me-1"></i>Ajuste</span>',
                        'transferencia': '<span class="badge bg-info text-white badge-modern"><i class="fas fa-exchange-alt me-1"></i>Transferencia</span>'
                    };
                    return badges[t] || t;
                }
            },
            { 
                data: 'cantidad',
                render: function(c) {
                    return `<span class="fw-bold">${c || 0}</span>`;
                }
            },
            { 
                data: 'almacen',
                render: function(a) {
                    return a ? `<i class="fas fa-store me-1"></i>${a.nombre}` : '—';
                }
            },
            { data: 'motivo', defaultContent: '<span class="text-muted">—</span>' },
            { 
                data: 'usuario',
                render: function(u) {
                    return u ? `<i class="fas fa-user-circle me-1"></i>${u.name}` : '—';
                }
            }
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 15,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        drawCallback: function() {
            $('.dataTables_paginate .paginate_button').addClass('btn btn-outline-primary btn-sm');
        }
    });

    // Función mejorada para enviar formularios
    function enviarFormulario(url, datos, modalId, callback) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Deseas realizar esta operación?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify(datos),
                    success: function(response) {
                        if (modalId) {
                            $('#' + modalId).modal('hide');
                        }
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.mensaje || 'Operación realizada correctamente',
                            timer: 3000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                        tabla.ajax.reload();
                        if (callback) callback(response);
                    },
                    error: function(xhr) {
                        var errorMsg = 'Ocurrió un error inesperado';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = Object.values(xhr.responseJSON.errors);
                            if (errors.length > 0 && errors[0][0]) {
                                errorMsg = errors[0][0];
                            }
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg,
                            confirmButtonColor: '#667eea'
                        });
                    }
                });
            }
        });
    }

    // Configuración de botones
    const actions = [
        { id: 'btnEntrada', modal: 'modalEntrada', form: 'formEntrada', route: '<?php echo e(route("inventario.entrada")); ?>' },
        { id: 'btnSalida', modal: 'modalSalida', form: 'formSalida', route: '<?php echo e(route("inventario.salida")); ?>' },
        { id: 'btnTransferencia', modal: 'modalTransferencia', form: 'formTransferencia', route: '<?php echo e(route("inventario.transferencia")); ?>' },
        { id: 'btnAjuste', modal: 'modalAjuste', form: 'formAjuste', route: '<?php echo e(route("inventario.ajuste")); ?>' }
    ];

    actions.forEach(action => {
        $(`#${action.id}`).on('click', function() {
            $(`#${action.form}`)[0].reset();
            $(`#${action.modal}`).modal('show');
        });

        $(`#${action.form}`).on('submit', function(e) {
            e.preventDefault();
            var datos = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            enviarFormulario(action.route, datos, action.modal);
        });
    });

    // Formulario Almacén
    $('#formAlmacen').on('submit', function(e) {
        e.preventDefault();
        var datos = $(this).serializeArray().reduce(function(obj, item) {
            if (item.name === 'estado') {
                obj[item.name] = 1;
            } else {
                obj[item.name] = item.value;
            }
            return obj;
        }, {});
        
        Swal.fire({
            title: 'Guardar almacén',
            text: '¿Deseas crear este nuevo almacén?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Guardando...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?php echo e(route("inventario.almacen.guardar")); ?>',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify(datos),
                    success: function(response) {
                        $('#modalNuevoAlmacen').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Guardado!',
                            text: response.mensaje || 'Almacén creado correctamente',
                            timer: 2000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        var errorMsg = 'Ocurrió un error al guardar';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = Object.values(xhr.responseJSON.errors);
                            if (errors.length > 0 && errors[0][0]) {
                                errorMsg = errors[0][0];
                            }
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg,
                            confirmButtonColor: '#667eea'
                        });
                    }
                });
            }
        });
    });

    // Limpiar formularios al cerrar modales
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0]?.reset();
        $(this).find('select').val('');
        $(this).find('.is-invalid').removeClass('is-invalid');
    });

    // Botón Nuevo Almacén
    $('#btnNuevoAlmacen').on('click', function() {
        $('#formAlmacen')[0].reset();
        $('#formAlmacen input[name="estado"]').prop('checked', true);
        $('#modalNuevoAlmacen').modal('show');
    });

    // Animación de entrada para las tarjetas
    $('.stat-card').each(function(index) {
        $(this).css('opacity', '0');
        setTimeout(() => {
            $(this).css('transition', 'opacity 0.5s ease, transform 0.3s ease');
            $(this).css('opacity', '1');
        }, 100 * (index + 1));
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/inventario/index.blade.php ENDPATH**/ ?>