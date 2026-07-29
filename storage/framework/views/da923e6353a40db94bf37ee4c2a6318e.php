<?php $__env->startSection('titulo', 'Análisis de Precios Unitarios (APU)'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">APU</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Análisis de Precios Unitarios</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('apu.crear')): ?>
        <button class="btn btn-primary btn-sm" id="btnNuevoApu">
            <i class="fas fa-plus me-1"></i>Nuevo APU
        </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaApu">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Costo directo</th>
                    <th>Precio unitario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalApu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formApu">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalApu">Nuevo APU</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="apu_id">
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Código (autogenerado si vacío)</label>
                            <input type="text" id="apu_codigo" class="form-control">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Descripción *</label>
                            <input type="text" id="apu_descripcion" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Unidad *</label>
                            <input type="text" id="apu_unidad" class="form-control" placeholder="m2, m3, und" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Rendimiento *</label>
                            <input type="number" step="0.0001" id="apu_rendimiento" class="form-control" value="1" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">% Herramientas (sobre M.O.)</label>
                            <input type="number" step="0.01" id="apu_pct_herramientas" class="form-control" value="3">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">% Costos indirectos</label>
                            <input type="number" step="0.01" id="apu_pct_indirectos" class="form-control" value="10">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">% Utilidad</label>
                            <input type="number" step="0.01" id="apu_pct_utilidad" class="form-control" value="10">
                        </div>
                    </div>

                    <ul class="nav nav-tabs mt-2">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tabMateriales">Materiales</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabManoObra">Mano de obra</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabEquipos">Equipos</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabMaquinaria">Maquinaria</a></li>
                    </ul>
                    
                    <div class="tab-content border border-top-0 p-3">
                        <div class="tab-pane fade show active" id="tabMateriales">
                            <table class="table table-sm" id="tablaLineasMateriales">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th style="width:110px">Cantidad</th>
                                        <th style="width:110px">Desperdicio %</th>
                                        <th style="width:110px">P. Unit.</th>
                                        <th style="width:110px">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAgregarMaterial">
                                <i class="fas fa-plus"></i> Agregar material
                            </button>
                        </div>
                        
                        <div class="tab-pane fade" id="tabManoObra">
                            <table class="table table-sm" id="tablaLineasManoObra">
                                <thead>
                                    <tr>
                                        <th>Trabajador / cuadrilla</th>
                                        <th style="width:130px">Cantidad (jornales)</th>
                                        <th style="width:110px">Costo unit.</th>
                                        <th style="width:110px">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAgregarManoObra">
                                <i class="fas fa-plus"></i> Agregar mano de obra
                            </button>
                        </div>
                        
                        <div class="tab-pane fade" id="tabEquipos">
                            <table class="table table-sm" id="tablaLineasEquipos">
                                <thead>
                                    <tr>
                                        <th>Equipo</th>
                                        <th style="width:110px">Cantidad</th>
                                        <th style="width:110px">Costo unit.</th>
                                        <th style="width:110px">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAgregarEquipo">
                                <i class="fas fa-plus"></i> Agregar equipo
                            </button>
                        </div>
                        
                        <div class="tab-pane fade" id="tabMaquinaria">
                            <table class="table table-sm" id="tablaLineasMaquinaria">
                                <thead>
                                    <tr>
                                        <th>Maquinaria</th>
                                        <th style="width:110px">Cantidad</th>
                                        <th style="width:110px">Costo unit.</th>
                                        <th style="width:110px">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAgregarMaquinaria">
                                <i class="fas fa-plus"></i> Agregar maquinaria
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr><td>Costo materiales</td><td class="text-end" id="r_materiales">0.00</td></tr>
                                <tr><td>Costo mano de obra</td><td class="text-end" id="r_mano_obra">0.00</td></tr>
                                <tr><td>Costo herramientas</td><td class="text-end" id="r_herramientas">0.00</td></tr>
                                <tr><td>Costo equipos</td><td class="text-end" id="r_equipos">0.00</td></tr>
                                <tr><td>Costo maquinaria</td><td class="text-end" id="r_maquinaria">0.00</td></tr>
                                <tr class="fw-bold"><td>Costo directo</td><td class="text-end" id="r_directo">0.00</td></tr>
                                <tr><td>Costo indirecto</td><td class="text-end" id="r_indirecto">0.00</td></tr>
                                <tr><td>Utilidad</td><td class="text-end" id="r_utilidad">0.00</td></tr>
                                <tr class="fw-bold table-primary"><td>PRECIO UNITARIO</td><td class="text-end" id="r_precio">0.00</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarApu">Guardar APU</button>
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
    let cacheData = [];

    const CATALOGO = {
        materiales: <?php echo json_encode($materiales->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'precio' => (float) $m->precio_venta])) ?>,
        manoObra: <?php echo json_encode($manoObra->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre.' ('.$m->especialidad.')', 'costo' => (float) $m->costo])) ?>,
        equipos: <?php echo json_encode($equipos->map(fn($e) => ['id' => $e->id, 'nombre' => $e->nombre, 'costo' => (float) $e->costo_alquiler_dia])) ?>,
        maquinarias: <?php echo json_encode($maquinarias->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'costo' => (float) $m->costo_dia])) ?>,
    };

    // Inicializar DataTable
    var tabla = $('#tablaApu').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?php echo e(route("apu.datos")); ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: 'codigo' },
            { data: 'descripcion' },
            { data: 'unidad' },
            { 
                data: 'costo_directo',
                render: function(c) {
                    return parseFloat(c).toFixed(2);
                }
            },
            { 
                data: 'precio_unitario',
                render: function(c) {
                    return '<strong>' + parseFloat(c).toFixed(2) + '</strong>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(r) {
                    return `
                        <button class="btn btn-sm btn-outline-primary btnEditarApu" data-id="${r.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarApu" data-id="${r.id}">
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

    tabla.on('xhr', function() {
        cacheData = tabla.ajax.json().data || [];
    });

    // Funciones para agregar líneas
    function selectOptions(items, valueKey = 'id', labelKey = 'nombre') {
        return items.map(i => `<option value="${i[valueKey]}">${i[labelKey]}</option>`).join('');
    }

    function agregarLineaMaterial(linea = null) {
        const tbody = document.querySelector('#tablaLineasMateriales tbody');
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td><select class="form-select form-select-sm sel-material">${selectOptions(CATALOGO.materiales)}</select></td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm inp-cantidad" value="${linea?.cantidad || 1}"></td>
            <td><input type="number" step="0.01" class="form-control form-control-sm inp-desperdicio" value="${linea?.desperdicio_pct || 5}"></td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm inp-precio" value="0" readonly></td>
            <td class="text-end subtotal">0.00</td>
            <td><button type="button" class="btn btn-sm btn-outline-danger btn-remover-fila"><i class="fas fa-times"></i></button></td>
        `;
        tbody.appendChild(fila);
        
        if (linea && linea.material_id) {
            fila.querySelector('.sel-material').value = linea.material_id;
        }
        
        fila.querySelectorAll('select, input').forEach(el => el.addEventListener('input', calcularTodo));
        fila.querySelector('.btn-remover-fila').addEventListener('click', function() {
            this.closest('tr').remove();
            calcularTodo();
        });
        calcularTodo();
    }

    function agregarLineaGenerica(tablaId, catalogo, selectClass) {
        const tbody = document.querySelector(`#${tablaId} tbody`);
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td><select class="form-select form-select-sm ${selectClass}">${selectOptions(catalogo)}</select></td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm inp-cantidad" value="1"></td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm inp-precio" value="0" readonly></td>
            <td class="text-end subtotal">0.00</td>
            <td><button type="button" class="btn btn-sm btn-outline-danger btn-remover-fila"><i class="fas fa-times"></i></button></td>
        `;
        tbody.appendChild(fila);
        fila.querySelectorAll('select, input').forEach(el => el.addEventListener('input', calcularTodo));
        fila.querySelector('.btn-remover-fila').addEventListener('click', function() {
            this.closest('tr').remove();
            calcularTodo();
        });
        calcularTodo();
    }

    // Botones para agregar líneas
    $('#btnAgregarMaterial').on('click', function() { agregarLineaMaterial(); });
    $('#btnAgregarManoObra').on('click', function() { agregarLineaGenerica('tablaLineasManoObra', CATALOGO.manoObra, 'sel-mano-obra'); });
    $('#btnAgregarEquipo').on('click', function() { agregarLineaGenerica('tablaLineasEquipos', CATALOGO.equipos, 'sel-equipo'); });
    $('#btnAgregarMaquinaria').on('click', function() { agregarLineaGenerica('tablaLineasMaquinaria', CATALOGO.maquinarias, 'sel-maquinaria'); });

    // Calcular todo
    function calcularTodo() {
        let totalMateriales = 0;
        document.querySelectorAll('#tablaLineasMateriales tbody tr').forEach(fila => {
            const id = parseInt(fila.querySelector('.sel-material').value);
            const item = CATALOGO.materiales.find(m => m.id === id);
            const precio = item ? item.precio : 0;
            const cantidad = parseFloat(fila.querySelector('.inp-cantidad').value) || 0;
            const desperdicio = parseFloat(fila.querySelector('.inp-desperdicio').value) || 0;
            fila.querySelector('.inp-precio').value = precio.toFixed(4);
            const subtotal = cantidad * (1 + desperdicio / 100) * precio;
            fila.querySelector('.subtotal').textContent = subtotal.toFixed(2);
            totalMateriales += subtotal;
        });

        function calcularGenerico(tablaId, catalogo) {
            let total = 0;
            document.querySelectorAll(`#${tablaId} tbody tr`).forEach(fila => {
                const id = parseInt(fila.querySelector('select').value);
                const item = catalogo.find(c => c.id === id);
                const precio = item ? (item.costo || item.precio || 0) : 0;
                const cantidad = parseFloat(fila.querySelector('.inp-cantidad').value) || 0;
                fila.querySelector('.inp-precio').value = precio.toFixed(4);
                const subtotal = cantidad * precio;
                fila.querySelector('.subtotal').textContent = subtotal.toFixed(2);
                total += subtotal;
            });
            return total;
        }

        const totalManoObra = calcularGenerico('tablaLineasManoObra', CATALOGO.manoObra);
        const totalEquipos = calcularGenerico('tablaLineasEquipos', CATALOGO.equipos);
        const totalMaquinaria = calcularGenerico('tablaLineasMaquinaria', CATALOGO.maquinarias);

        const pctHerramientas = parseFloat(document.getElementById('apu_pct_herramientas').value) || 0;
        const pctIndirectos = parseFloat(document.getElementById('apu_pct_indirectos').value) || 0;
        const pctUtilidad = parseFloat(document.getElementById('apu_pct_utilidad').value) || 0;

        const costoHerramientas = totalManoObra * (pctHerramientas / 100);
        const costoDirecto = totalMateriales + totalManoObra + costoHerramientas + totalEquipos + totalMaquinaria;
        const costoIndirecto = costoDirecto * (pctIndirectos / 100);
        const utilidad = (costoDirecto + costoIndirecto) * (pctUtilidad / 100);
        const precioUnitario = costoDirecto + costoIndirecto + utilidad;

        document.getElementById('r_materiales').textContent = totalMateriales.toFixed(2);
        document.getElementById('r_mano_obra').textContent = totalManoObra.toFixed(2);
        document.getElementById('r_herramientas').textContent = costoHerramientas.toFixed(2);
        document.getElementById('r_equipos').textContent = totalEquipos.toFixed(2);
        document.getElementById('r_maquinaria').textContent = totalMaquinaria.toFixed(2);
        document.getElementById('r_directo').textContent = costoDirecto.toFixed(2);
        document.getElementById('r_indirecto').textContent = costoIndirecto.toFixed(2);
        document.getElementById('r_utilidad').textContent = utilidad.toFixed(2);
        document.getElementById('r_precio').textContent = precioUnitario.toFixed(2);
    }

    // Eventos de porcentajes
    ['apu_pct_herramientas', 'apu_pct_indirectos', 'apu_pct_utilidad'].forEach(id => {
        $(`#${id}`).on('input', calcularTodo);
    });

    // Limpiar formulario
    function limpiarFormulario() {
        $('#formApu')[0].reset();
        $('#apu_id').val('');
        $('#apu_codigo').val('');
        $('#apu_descripcion').val('');
        $('#apu_unidad').val('');
        $('#apu_rendimiento').val('1');
        $('#apu_pct_herramientas').val('3');
        $('#apu_pct_indirectos').val('10');
        $('#apu_pct_utilidad').val('10');
        $('#tablaLineasMateriales tbody').empty();
        $('#tablaLineasManoObra tbody').empty();
        $('#tablaLineasEquipos tbody').empty();
        $('#tablaLineasMaquinaria tbody').empty();
        calcularTodo();
    }

    // Botón Nuevo
    $('#btnNuevoApu').on('click', function() {
        limpiarFormulario();
        $('#tituloModalApu').text('Nuevo APU');
        $('#modalApu').modal('show');
    });

    // Editar
    $(document).on('click', '.btnEditarApu', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/apu/' + id,
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
                        text: 'No se encontraron datos del APU'
                    });
                    return;
                }

                limpiarFormulario();
                
                $('#apu_id').val(data.id);
                $('#apu_codigo').val(data.codigo || '');
                $('#apu_descripcion').val(data.descripcion || '');
                $('#apu_unidad').val(data.unidad || '');
                $('#apu_rendimiento').val(data.rendimiento || 1);
                $('#apu_pct_herramientas').val(data.porcentaje_herramientas || 3);
                $('#apu_pct_indirectos').val(data.porcentaje_costos_indirectos || 10);
                $('#apu_pct_utilidad').val(data.porcentaje_utilidad || 10);

                // Cargar materiales
                if (data.materiales && data.materiales.length > 0) {
                    data.materiales.forEach(function(m) {
                        agregarLineaMaterial({
                            material_id: m.material_id,
                            cantidad: m.cantidad,
                            desperdicio_pct: m.desperdicio_pct
                        });
                    });
                }

                // Cargar mano de obra
                if (data.mano_obra && data.mano_obra.length > 0) {
                    data.mano_obra.forEach(function(m) {
                        agregarLineaGenerica('tablaLineasManoObra', CATALOGO.manoObra, 'sel-mano-obra');
                        var rows = document.querySelectorAll('#tablaLineasManoObra tbody tr');
                        var lastRow = rows[rows.length - 1];
                        lastRow.querySelector('select').value = m.mano_obra_id;
                        lastRow.querySelector('.inp-cantidad').value = m.cantidad;
                    });
                }

                // Cargar equipos
                if (data.equipos && data.equipos.length > 0) {
                    data.equipos.forEach(function(e) {
                        agregarLineaGenerica('tablaLineasEquipos', CATALOGO.equipos, 'sel-equipo');
                        var rows = document.querySelectorAll('#tablaLineasEquipos tbody tr');
                        var lastRow = rows[rows.length - 1];
                        lastRow.querySelector('select').value = e.equipo_id;
                        lastRow.querySelector('.inp-cantidad').value = e.cantidad;
                    });
                }

                // Cargar maquinarias
                if (data.maquinarias && data.maquinarias.length > 0) {
                    data.maquinarias.forEach(function(m) {
                        agregarLineaGenerica('tablaLineasMaquinaria', CATALOGO.maquinarias, 'sel-maquinaria');
                        var rows = document.querySelectorAll('#tablaLineasMaquinaria tbody tr');
                        var lastRow = rows[rows.length - 1];
                        lastRow.querySelector('select').value = m.maquinaria_id;
                        lastRow.querySelector('.inp-cantidad').value = m.cantidad;
                    });
                }

                calcularTodo();
                $('#tituloModalApu').text('Editar APU');
                $('#modalApu').modal('show');
            },
            error: function(xhr) {
                var errorMsg = 'No se pudieron cargar los datos del APU';
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
                console.error('Error al cargar:', xhr.responseText);
            }
        });
    });

    // Eliminar
    $(document).on('click', '.btnEliminarApu', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar APU?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/apu/' + id,
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

    // Enviar formulario
    $('#formApu').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#apu_id').val();
        
        // Recopilar materiales
        var materiales = [];
        document.querySelectorAll('#tablaLineasMateriales tbody tr').forEach(function(fila) {
            materiales.push({
                material_id: fila.querySelector('.sel-material').value,
                cantidad: fila.querySelector('.inp-cantidad').value,
                desperdicio_pct: fila.querySelector('.inp-desperdicio').value
            });
        });

        // Recopilar mano de obra
        var manoObra = [];
        document.querySelectorAll('#tablaLineasManoObra tbody tr').forEach(function(fila) {
            manoObra.push({
                mano_obra_id: fila.querySelector('select').value,
                cantidad: fila.querySelector('.inp-cantidad').value
            });
        });

        // Recopilar equipos
        var equipos = [];
        document.querySelectorAll('#tablaLineasEquipos tbody tr').forEach(function(fila) {
            equipos.push({
                equipo_id: fila.querySelector('select').value,
                cantidad: fila.querySelector('.inp-cantidad').value
            });
        });

        // Recopilar maquinarias
        var maquinarias = [];
        document.querySelectorAll('#tablaLineasMaquinaria tbody tr').forEach(function(fila) {
            maquinarias.push({
                maquinaria_id: fila.querySelector('select').value,
                cantidad: fila.querySelector('.inp-cantidad').value
            });
        });

        var datos = {
            codigo: $('#apu_codigo').val(),
            descripcion: $('#apu_descripcion').val(),
            unidad: $('#apu_unidad').val(),
            rendimiento: $('#apu_rendimiento').val(),
            porcentaje_herramientas: $('#apu_pct_herramientas').val(),
            porcentaje_costos_indirectos: $('#apu_pct_indirectos').val(),
            porcentaje_utilidad: $('#apu_pct_utilidad').val(),
            estado: 1,
            materiales: materiales,
            mano_obra: manoObra,
            equipos: equipos,
            maquinarias: maquinarias
        };

        var url = id ? '/apu/' + id : '/apu';
        var method = id ? 'PUT' : 'POST';

        $('#btnGuardarApu').prop('disabled', true);
        $('#btnGuardarApu').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

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
                $('#modalApu').modal('hide');
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
                $('#btnGuardarApu').prop('disabled', false);
                $('#btnGuardarApu').text('Guardar APU');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalApu').on('hidden.bs.modal', function() {
        limpiarFormulario();
    });

    // Agregar línea de material por defecto al abrir modal
    $('#modalApu').on('shown.bs.modal', function() {
        if ($('#tablaLineasMateriales tbody tr').length === 0) {
            agregarLineaMaterial();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/apu/index.blade.php ENDPATH**/ ?>