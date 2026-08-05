@extends('layouts.app')

@section('titulo', 'Categorías')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .table-actions {
        white-space: nowrap;
    }
    .badge-icon {
        font-size: 1.2rem;
        padding: 8px 12px;
        border-radius: 8px;
        display: inline-block;
        min-width: 40px;
        text-align: center;
    }
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        border-radius: 8px;
    }
    .filter-btn {
        transition: all 0.2s ease;
    }
    .filter-btn.active {
        font-weight: 600;
        box-shadow: inset 0 -2px 0 currentColor;
    }
    .status-badge {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .status-badge:hover {
        transform: scale(1.05);
    }
    .stat-card {
        transition: all 0.3s ease;
        cursor: default;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .required:after {
        content: ' *';
        color: #dc3545;
    }
    .form-control-color {
        height: 38px;
        padding: 2px;
    }
    .icon-preview {
        font-size: 1.2rem;
        min-width: 38px;
        text-align: center;
    }
</style>
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Categorías</li>
@endsection

@section('contenido')
<div class="row">
    <div class="col-12">
        <!-- Tarjetas de estadísticas -->
        <div class="row mb-4 g-3">
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-1">Total Categorías</h6>
                                <h3 class="mb-0" id="totalCategorias">0</h3>
                            </div>
                            <i class="fas fa-tags fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-1">Activas</h6>
                                <h3 class="mb-0" id="totalActivas">0</h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-1">Inactivas</h6>
                                <h3 class="mb-0" id="totalInactivas">0</h3>
                            </div>
                            <i class="fas fa-circle fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-1">Con Materiales</h6>
                                <h3 class="mb-0" id="totalConMateriales">0</h3>
                            </div>
                            <i class="fas fa-boxes fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta principal -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Listado de categorías
                    </h3>
                    <span class="badge bg-secondary" id="registrosMostrados">0 registros</span>
                </div>
                <div class="d-flex gap-2">
                    @can('categorias.crear')
                    <button class="btn btn-primary btn-sm" id="btnNuevaCategoria">
                        <i class="fas fa-plus me-1"></i>Nueva categoría
                    </button>
                    @endcan
                    <button class="btn btn-outline-secondary btn-sm" id="btnRefrescar" title="Refrescar datos">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filtros y búsqueda -->
                <div class="row mb-3 g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" 
                                   placeholder="Buscar por nombre o descripción...">
                            <button class="btn btn-outline-secondary" id="btnLimpiarBusqueda" type="button">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex justify-content-end gap-1 flex-wrap">
                            <button type="button" class="btn btn-outline-secondary btn-sm filter-btn active" data-filter="all">
                                <i class="fas fa-list"></i> Todas
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm filter-btn" data-filter="active">
                                <i class="fas fa-check-circle"></i> Activas
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm filter-btn" data-filter="inactive">
                                <i class="fas fa-circle"></i> Inactivas
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm filter-btn" data-filter="with-materials">
                                <i class="fas fa-boxes"></i> Con materiales
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive position-relative">
                    <div class="loading-overlay d-none" id="loadingOverlay">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando categorías...</p>
                        </div>
                    </div>

                    <table class="table table-striped table-hover table-bordered w-100" id="tablaCategorias">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th width="70">Icono</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th width="100">Estado</th>
                                <th width="150" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            <!-- Renderizado por JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" id="pageSize" style="width: auto;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-muted small" id="infoPaginacion">Mostrando 0-0 de 0</span>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="paginacion">
                            <!-- Renderizado por JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear/Editar --}}
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalCategoria">
                    <i class="fas fa-plus-circle me-2"></i>Nueva categoría
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCategoria" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="id" id="categoria_id">
                    
                    <div class="row">
                        <!-- Columna izquierda -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label required">Nombre</label>
                                <input type="text" name="nombre" id="categoria_nombre" 
                                       class="form-control" required 
                                       placeholder="Ej: Electrónica, Herramientas, etc."
                                       maxlength="100">
                                <div class="invalid-feedback" id="nombreError"></div>
                                <small class="text-muted" id="contadorNombre">0/100 caracteres</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" id="categoria_descripcion" 
                                          class="form-control" rows="3"
                                          placeholder="Descripción detallada de la categoría"
                                          maxlength="500"></textarea>
                                <small class="text-muted" id="contadorDescripcion">0/500 caracteres</small>
                            </div>
                        </div>
                        
                        <!-- Columna derecha -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Color</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" name="color" id="categoria_color" 
                                                   class="form-control form-control-color" 
                                                   style="width: 50px; padding: 2px;"
                                                   value="#2a5298">
                                            <span class="badge" id="previewColor" 
                                                  style="background-color: #2a5298; padding: 8px 12px; min-width: 80px;">
                                                #2a5298
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Ícono (Font Awesome)</label>
                                        <div class="input-group">
                                            <span class="input-group-text icon-preview" id="previewIcono">
                                                <i class="fas fa-cubes"></i>
                                            </span>
                                            <input type="text" name="icono" id="categoria_icono" 
                                                   class="form-control" value="fa-cubes"
                                                   placeholder="fa-cubes">
                                        </div>
                                        <small class="text-muted">Ej: fa-cubes, fa-tag, fa-folder</small>
                                    </div>
                                    
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="estado" id="categoria_estado" 
                                               class="form-check-input" value="1" checked>
                                        <label class="form-check-label" for="categoria_estado">
                                            <span id="estadoTexto" class="text-success">
                                                <i class="fas fa-check-circle"></i> Activo
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCategoria">
                        <i class="fas fa-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Confirmar Eliminación --}}
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de eliminar la categoría <strong id="deleteNombre"></strong>?</p>
                <p class="text-muted small">Esta acción no se puede deshacer.</p>
                <div id="deleteAdvertencia" class="alert alert-warning d-none">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Esta categoría tiene <span id="deleteMaterialesCount">0</span> materiales asociados. No se puede eliminar.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar" disabled>
                    <i class="fas fa-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    'use strict';

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let currentPage = 1;
    let pageSize = 10;
    let currentFilter = 'all';
    let searchTerm = '';
    let categoriasData = [];
    let deleteId = null;
    let isSaving = false;

    // ===== INICIALIZACIÓN =====
    function init() {
        cargarCategorias();
        setupEventListeners();
        setupFormValidation();
        setupPreviews();
        setupCharacterCounters();
        setupEstadoSwitch();
    }

    // ===== CARGA DE DATOS =====
    function cargarCategorias() {
        mostrarLoading(true);
        
        $.ajax({
            url: '/categorias/datos',
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Respuesta recibida:', response);
                
                if (response.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.error || 'Error al cargar datos'
                    });
                    return;
                }
                
                categoriasData = response.data || [];
                actualizarEstadisticas();
                renderizarTabla();
                mostrarLoading(false);
            },
            error: function(xhr) {
                console.error('Error AJAX:', xhr);
                mostrarLoading(false);
                
                let errorMsg = 'No se pudieron cargar las categorías.';
                
                if (xhr.status === 404) {
                    errorMsg = 'La ruta no existe. Verifica que el endpoint /categorias/datos esté disponible.';
                } else if (xhr.status === 500) {
                    errorMsg = 'Error del servidor. Revisa los logs de Laravel.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error de carga',
                    text: errorMsg,
                    confirmButtonText: 'Recargar',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
        });
    }

    // ===== RENDERIZADO DE TABLA =====
    function renderizarTabla() {
        let datosFiltrados = filtrarDatos();
        let paginados = paginarDatos(datosFiltrados);
        
        const tbody = $('#tablaBody');
        tbody.empty();

        if (paginados.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted d-block mb-3"></i>
                        <p class="text-muted mb-1">No hay categorías que coincidan con los filtros</p>
                        <button class="btn btn-sm btn-outline-primary mt-2" id="btnLimpiarFiltros">
                            <i class="fas fa-undo me-1"></i>Limpiar filtros
                        </button>
                    </td>
                </tr>
            `);
            $('#btnLimpiarFiltros').on('click', function() {
                limpiarFiltros();
            });
        } else {
            paginados.forEach((categoria, index) => {
                const row = crearFilaCategoria(categoria, index);
                tbody.append(row);
            });
        }

        actualizarPaginacion(datosFiltrados.length);
        actualizarInfoPaginacion(datosFiltrados.length);
        $('#registrosMostrados').text(`${datosFiltrados.length} registros`);
    }

    function crearFilaCategoria(categoria, index) {
        const num = ((currentPage - 1) * pageSize) + index + 1;
        const estadoClass = categoria.estado ? 'bg-success' : 'bg-secondary';
        const estadoIcono = categoria.estado ? 'fa-check-circle' : 'fa-circle';
        const estadoTexto = categoria.estado ? 'Activo' : 'Inactivo';
        const tieneMateriales = categoria.materiales_count > 0;
        
        const bgColor = categoria.color || '#2a5298';
        const textColor = esColorClaro(bgColor) ? '#000' : '#fff';
        
        return `
            <tr class="categoria-row" data-id="${categoria.id}">
                <td class="text-center align-middle">${num}</td>
                <td class="text-center align-middle">
                    <span class="badge badge-icon" 
                          style="background-color: ${bgColor}; color: ${textColor};">
                        <i class="fas ${categoria.icono || 'fa-cubes'}"></i>
                    </span>
                </td>
                <td class="align-middle">
                    <strong>${escapeHtml(categoria.nombre)}</strong>
                    ${tieneMateriales ? 
                        `<br><small class="text-muted"><i class="fas fa-boxes me-1"></i>${categoria.materiales_count} materiales</small>` : 
                        ''}
                </td>
                <td class="align-middle">
                    ${categoria.descripcion ? 
                        `<span class="text-muted small">${truncateText(escapeHtml(categoria.descripcion), 80)}</span>` : 
                        '<span class="text-muted fst-italic small">Sin descripción</span>'}
                </td>
                <td class="align-middle">
                    <span class="badge ${estadoClass} status-badge" 
                          style="cursor: pointer; padding: 6px 12px;" 
                          title="Click para cambiar estado"
                          data-id="${categoria.id}">
                        <i class="fas ${estadoIcono}"></i> ${estadoTexto}
                    </span>
                </td>
                <td class="table-actions align-middle text-center">
                    <div class="btn-group btn-group-sm" role="group">
                        @can('categorias.editar')
                        <button class="btn btn-outline-primary btnEditar" data-id="${categoria.id}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        @endcan
                        @can('categorias.eliminar')
                        <button class="btn btn-outline-danger btnEliminar" data-id="${categoria.id}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                        @can('categorias.editar')
                        <button class="btn ${categoria.estado ? 'btn-outline-secondary' : 'btn-outline-success'} btnToggleEstado" 
                                data-id="${categoria.id}" 
                                title="${categoria.estado ? 'Desactivar' : 'Activar'}">
                            <i class="fas ${categoria.estado ? 'fa-pause' : 'fa-play'}"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
        `;
    }

    // ===== FILTROS Y BÚSQUEDA =====
    function filtrarDatos() {
        let datos = [...categoriasData];
        
        switch(currentFilter) {
            case 'active':
                datos = datos.filter(c => c.estado === 1);
                break;
            case 'inactive':
                datos = datos.filter(c => c.estado === 0);
                break;
            case 'with-materials':
                datos = datos.filter(c => c.materiales_count > 0);
                break;
            default:
                break;
        }
        
        if (searchTerm.trim()) {
            const term = searchTerm.toLowerCase().trim();
            datos = datos.filter(c => 
                c.nombre.toLowerCase().includes(term) ||
                (c.descripcion && c.descripcion.toLowerCase().includes(term))
            );
        }
        
        return datos;
    }

    function paginarDatos(datos) {
        const start = (currentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, datos.length);
        return datos.slice(start, end);
    }

    function limpiarFiltros() {
        $('#searchInput').val('');
        searchTerm = '';
        currentFilter = 'all';
        $('.filter-btn').removeClass('active');
        $('.filter-btn[data-filter="all"]').addClass('active');
        currentPage = 1;
        renderizarTabla();
    }

    // ===== ESTADÍSTICAS =====
    function actualizarEstadisticas() {
        const total = categoriasData.length;
        const activas = categoriasData.filter(c => c.estado === 1).length;
        const inactivas = categoriasData.filter(c => c.estado === 0).length;
        const conMateriales = categoriasData.filter(c => c.materiales_count > 0).length;
        
        $('#totalCategorias').text(total);
        $('#totalActivas').text(activas);
        $('#totalInactivas').text(inactivas);
        $('#totalConMateriales').text(conMateriales);
    }

    // ===== PAGINACIÓN =====
    function actualizarPaginacion(totalItems) {
        const totalPages = Math.ceil(totalItems / pageSize) || 1;
        const pagination = $('#paginacion');
        pagination.empty();
        
        if (totalPages <= 1) {
            pagination.html('<li class="page-item disabled"><span class="page-link">1</span></li>');
            return;
        }
        
        pagination.append(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <button class="page-link" data-page="${currentPage - 1}" aria-label="Anterior">
                    <span aria-hidden="true">«</span>
                </button>
            </li>
        `);
        
        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        if (startPage > 1) {
            pagination.append(`<li class="page-item"><button class="page-link" data-page="1">1</button></li>`);
            if (startPage > 2) {
                pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            pagination.append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <button class="page-link" data-page="${i}">${i}</button>
                </li>
            `);
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
            pagination.append(`<li class="page-item"><button class="page-link" data-page="${totalPages}">${totalPages}</button></li>`);
        }
        
        pagination.append(`
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <button class="page-link" data-page="${currentPage + 1}" aria-label="Siguiente">
                    <span aria-hidden="true">»</span>
                </button>
            </li>
        `);
    }

    function actualizarInfoPaginacion(totalItems) {
        if (totalItems === 0) {
            $('#infoPaginacion').text('Mostrando 0-0 de 0');
            return;
        }
        const start = (currentPage - 1) * pageSize + 1;
        const end = Math.min(currentPage * pageSize, totalItems);
        $('#infoPaginacion').text(`Mostrando ${start}-${end} de ${totalItems}`);
    }

    // ===== OPERACIONES CRUD =====
    function guardarCategoria(formData) {
        if (isSaving) return;
        isSaving = true;
        
        const id = $('#categoria_id').val();
        const url = id ? `/categorias/${id}` : '/categorias';
        const method = id ? 'PUT' : 'POST';
        
        mostrarLoading(true);
        $('#btnGuardarCategoria').prop('disabled', true);
        $('#btnGuardarCategoria').html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');
        
        $.ajax({
            url: url,
            type: method,
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(formData),
            success: function(response) {
                $('#modalCategoria').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje || 'Operación exitosa',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                cargarCategorias();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    mostrarErroresValidacion(errors);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.mensaje || 'Ocurrió un error al guardar'
                    });
                }
            },
            complete: function() {
                isSaving = false;
                $('#btnGuardarCategoria').prop('disabled', false);
                $('#btnGuardarCategoria').html('<i class="fas fa-save me-1"></i>Guardar');
                mostrarLoading(false);
            }
        });
    }

    function eliminarCategoria(id) {
        mostrarLoading(true);
        
        $.ajax({
            url: `/categorias/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            success: function(response) {
                $('#modalConfirmarEliminar').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje || 'Categoría eliminada',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                cargarCategorias();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No se puede eliminar',
                        text: xhr.responseJSON?.mensaje || 'La categoría tiene materiales asociados'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.mensaje || 'Ocurrió un error al eliminar'
                    });
                }
            },
            complete: function() {
                mostrarLoading(false);
            }
        });
    }

    function toggleEstadoCategoria(id, nuevoEstado) {
        mostrarLoading(true);
        
        $.ajax({
            url: `/categorias/${id}/toggle-estado`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({ estado: nuevoEstado }),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: `Categoría ${nuevoEstado === 1 ? 'activada' : 'desactivada'}`,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                cargarCategorias();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.mensaje || 'No se pudo cambiar el estado'
                });
            },
            complete: function() {
                mostrarLoading(false);
            }
        });
    }

    // ===== EVENT LISTENERS =====
    function setupEventListeners() {
        $('#btnNuevaCategoria').on('click', function() {
            abrirModalNuevo();
        });

        $(document).on('click', '.btnEditar', function() {
            const id = $(this).data('id');
            abrirModalEditar(id);
        });

        $(document).on('click', '.btnEliminar', function() {
            const id = $(this).data('id');
            const $row = $(this).closest('tr');
            const nombre = $row.find('td:eq(2) strong').text();
            abrirModalEliminar(id, nombre);
        });

        $(document).on('click', '.btnToggleEstado', function() {
            const id = $(this).data('id');
            const $row = $(this).closest('tr');
            const esActivo = $row.find('.status-badge').text().trim() === 'Activo';
            const nuevoEstado = esActivo ? 0 : 1;
            
            Swal.fire({
                title: `¿${esActivo ? 'Desactivar' : 'Activar'} categoría?`,
                text: `La categoría quedará ${esActivo ? 'inactiva' : 'activa'}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Sí, ${esActivo ? 'desactivar' : 'activar'}`,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    toggleEstadoCategoria(id, nuevoEstado);
                }
            });
        });

        $(document).on('click', '.status-badge', function() {
            const id = $(this).data('id');
            const esActivo = $(this).text().trim() === 'Activo';
            const nuevoEstado = esActivo ? 0 : 1;
            
            Swal.fire({
                title: `¿${esActivo ? 'Desactivar' : 'Activar'} categoría?`,
                text: `La categoría quedará ${esActivo ? 'inactiva' : 'activa'}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Sí, ${esActivo ? 'desactivar' : 'activar'}`,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    toggleEstadoCategoria(id, nuevoEstado);
                }
            });
        });

        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            currentFilter = $(this).data('filter');
            currentPage = 1;
            renderizarTabla();
        });

        let searchTimeout;
        $('#searchInput').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                searchTerm = $('#searchInput').val();
                currentPage = 1;
                renderizarTabla();
            }, 300);
        });

        $('#btnLimpiarBusqueda').on('click', function() {
            $('#searchInput').val('');
            searchTerm = '';
            currentPage = 1;
            renderizarTabla();
            $('#searchInput').focus();
        });

        $('#pageSize').on('change', function() {
            pageSize = parseInt($(this).val());
            currentPage = 1;
            renderizarTabla();
        });

        $(document).on('click', '#paginacion .page-link', function() {
            const page = parseInt($(this).data('page'));
            if (page && page !== currentPage && page > 0) {
                currentPage = page;
                renderizarTabla();
                $('html, body').animate({
                    scrollTop: $('#tablaCategorias').offset().top - 80
                }, 300);
            }
        });

        $('#formCategoria').on('submit', function(e) {
            e.preventDefault();
            if (validarFormulario()) {
                const formData = obtenerDatosFormulario();
                guardarCategoria(formData);
            }
        });

        $('#modalCategoria').on('hidden.bs.modal', function() {
            resetFormulario();
            limpiarErroresValidacion();
            isSaving = false;
        });

        $('#btnConfirmarEliminar').on('click', function() {
            if (deleteId) {
                eliminarCategoria(deleteId);
            }
        });

        $('#btnRefrescar').on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            cargarCategorias();
            setTimeout(function() {
                $btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
            }, 1000);
        });
    }

    // ===== MODALES =====
    function abrirModalNuevo() {
        resetFormulario();
        $('#categoria_id').val('');
        $('#tituloModalCategoria').html('<i class="fas fa-plus-circle me-2"></i>Nueva categoría');
        $('#modalCategoria').modal('show');
        setTimeout(() => $('#categoria_nombre').focus(), 500);
    }

    function abrirModalEditar(id) {
        mostrarLoading(true);
        
        $.ajax({
            url: `/categorias/${id}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            success: function(response) {
                const data = response.data || response;
                llenarFormulario(data);
                $('#categoria_id').val(data.id);
                $('#tituloModalCategoria').html('<i class="fas fa-edit me-2"></i>Editar categoría');
                $('#modalCategoria').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los datos de la categoría'
                });
            },
            complete: function() {
                mostrarLoading(false);
            }
        });
    }

    function abrirModalEliminar(id, nombre) {
        deleteId = id;
        $('#deleteNombre').text(nombre);
        
        const categoria = categoriasData.find(c => c.id === id);
        const tieneMateriales = categoria && categoria.materiales_count > 0;
        
        if (tieneMateriales) {
            $('#deleteAdvertencia').removeClass('d-none');
            $('#deleteMaterialesCount').text(categoria.materiales_count);
            $('#btnConfirmarEliminar').prop('disabled', true);
        } else {
            $('#deleteAdvertencia').addClass('d-none');
            $('#btnConfirmarEliminar').prop('disabled', false);
        }
        
        $('#modalConfirmarEliminar').modal('show');
    }

    // ===== FORMULARIO =====
    function llenarFormulario(data) {
        $('#categoria_nombre').val(data.nombre || '');
        $('#categoria_color').val(data.color || '#2a5298');
        $('#categoria_icono').val(data.icono || 'fa-cubes');
        $('#categoria_descripcion').val(data.descripcion || '');
        $('#categoria_estado').prop('checked', data.estado === 1 || data.estado === true);
        actualizarPreviews();
        actualizarContadores();
        actualizarEstadoSwitch();
    }

    function obtenerDatosFormulario() {
        return {
            nombre: $('#categoria_nombre').val().trim(),
            color: $('#categoria_color').val(),
            icono: $('#categoria_icono').val().trim() || 'fa-cubes',
            descripcion: $('#categoria_descripcion').val().trim(),
            estado: $('#categoria_estado').is(':checked') ? 1 : 0
        };
    }

    function resetFormulario() {
        $('#formCategoria')[0].reset();
        $('#categoria_id').val('');
        $('#categoria_color').val('#2a5298');
        $('#categoria_icono').val('fa-cubes');
        $('#categoria_estado').prop('checked', true);
        actualizarPreviews();
        actualizarContadores();
        actualizarEstadoSwitch();
        limpiarErroresValidacion();
    }

    // ===== VALIDACIÓN =====
    function setupFormValidation() {
        $('#categoria_nombre').on('input', function() {
            validarCampoNombre();
            actualizarContadorNombre();
        });
        
        $('#categoria_nombre').on('blur', function() {
            validarCampoNombre();
        });
        
        $('#categoria_descripcion').on('input', function() {
            actualizarContadorDescripcion();
        });
    }

    function validarFormulario() {
        let valido = true;
        
        if (!validarCampoNombre()) {
            valido = false;
        }
        
        return valido;
    }

    function validarCampoNombre() {
        const nombre = $('#categoria_nombre').val().trim();
        const $input = $('#categoria_nombre');
        const $error = $('#nombreError');
        
        if (!nombre) {
            $input.addClass('is-invalid');
            $error.text('El nombre es obligatorio');
            return false;
        }
        
        if (nombre.length < 3) {
            $input.addClass('is-invalid');
            $error.text('El nombre debe tener al menos 3 caracteres');
            return false;
        }
        
        if (nombre.length > 100) {
            $input.addClass('is-invalid');
            $error.text('El nombre no puede exceder 100 caracteres');
            return false;
        }
        
        $input.removeClass('is-invalid');
        $error.text('');
        return true;
    }

    function mostrarErroresValidacion(errors) {
        limpiarErroresValidacion();
        
        if (errors.nombre) {
            $('#categoria_nombre').addClass('is-invalid');
            $('#nombreError').text(errors.nombre[0]);
        }
    }

    function limpiarErroresValidacion() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    // ===== PREVIEWS =====
    function setupPreviews() {
        $('#categoria_color').on('input', function() {
            actualizarPreviews();
        });
        
        $('#categoria_icono').on('input', function() {
            actualizarPreviews();
        });
    }

    function actualizarPreviews() {
        const color = $('#categoria_color').val();
        $('#previewColor').css('background-color', color).text(color);
        
        const icono = $('#categoria_icono').val().trim() || 'fa-cubes';
        $('#previewIcono').html(`<i class="fas ${icono}"></i>`);
    }

    // ===== CONTADORES =====
    function setupCharacterCounters() {
        $('#categoria_nombre').on('input', actualizarContadorNombre);
        $('#categoria_descripcion').on('input', actualizarContadorDescripcion);
    }

    function actualizarContadores() {
        actualizarContadorNombre();
        actualizarContadorDescripcion();
    }

    function actualizarContadorNombre() {
        const length = $('#categoria_nombre').val().length;
        const max = 100;
        $('#contadorNombre').text(`${length}/${max} caracteres`).toggleClass('text-danger', length > max);
    }

    function actualizarContadorDescripcion() {
        const length = $('#categoria_descripcion').val().length;
        const max = 500;
        $('#contadorDescripcion').text(`${length}/${max} caracteres`).toggleClass('text-danger', length > max);
    }

    // ===== ESTADO SWITCH =====
    function setupEstadoSwitch() {
        $('#categoria_estado').on('change', actualizarEstadoSwitch);
    }

    function actualizarEstadoSwitch() {
        const checked = $('#categoria_estado').is(':checked');
        const $texto = $('#estadoTexto');
        if (checked) {
            $texto.removeClass('text-secondary').addClass('text-success');
            $texto.html('<i class="fas fa-check-circle"></i> Activo');
        } else {
            $texto.removeClass('text-success').addClass('text-secondary');
            $texto.html('<i class="fas fa-circle"></i> Inactivo');
        }
    }

    // ===== UTILIDADES =====
    function mostrarLoading(show) {
        $('#loadingOverlay').toggleClass('d-none', !show);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function truncateText(text, length) {
        if (!text) return '';
        if (text.length <= length) return text;
        return text.substring(0, length) + '...';
    }

    function esColorClaro(hex) {
        if (!hex) return false;
        const hexClean = hex.replace('#', '');
        const r = parseInt(hexClean.substring(0, 2), 16);
        const g = parseInt(hexClean.substring(2, 4), 16);
        const b = parseInt(hexClean.substring(4, 6), 16);
        const brightness = (r * 299 + g * 587 + b * 114) / 1000;
        return brightness > 128;
    }

    // ===== INICIALIZAR =====
    init();
});
</script>
@endpush