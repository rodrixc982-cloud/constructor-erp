<?php $__env->startSection('titulo', 'Calendario'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .calendario-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .calendario-header h3 {
        color: white;
    }
    .calendario-header .btn-outline-light:hover {
        background: rgba(255,255,255,0.2);
    }
    .dia-grid {
        min-height: 100px;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 6px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .dia-grid:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        z-index: 10;
        border-color: #667eea;
    }
    .dia-grid .dia-numero {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        display: block;
        margin-bottom: 4px;
    }
    .dia-grid .dia-numero.otro-mes {
        color: #adb5bd;
    }
    .dia-grid .dia-numero.hoy {
        background: #667eea;
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .dia-grid .evento-item {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .dia-grid .evento-item:hover {
        transform: scale(1.05);
        filter: brightness(1.1);
    }
    .dia-grid .evento-item .evento-titulo {
        font-weight: 500;
    }
    .dia-grid .evento-mas {
        font-size: 10px;
        color: #6c757d;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        background: #f8f9fa;
        text-align: center;
    }
    .dia-grid .evento-mas:hover {
        background: #e9ecef;
    }
    .dia-grid.activo {
        background: #f8f9fa;
        border-color: #667eea;
    }
    .dia-grid.otro-mes {
        background: #f8f9fa;
        opacity: 0.6;
    }
    .dia-grid.otro-mes:hover {
        opacity: 0.8;
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    .modal-title {
        color: white;
    }
    .evento-tooltip {
        position: fixed;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        padding: 15px;
        z-index: 9999;
        min-width: 200px;
        max-width: 300px;
        display: none;
        border: 1px solid #e9ecef;
    }
    .evento-tooltip .tooltip-titulo {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .evento-tooltip .tooltip-descripcion {
        font-size: 12px;
        color: #6c757d;
    }
    .evento-tooltip .tooltip-tipo {
        font-size: 11px;
        padding: 2px 10px;
        border-radius: 12px;
        display: inline-block;
        margin-top: 5px;
    }
    .header-leyenda {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }
    .header-leyenda .leyenda-item {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .header-leyenda .leyenda-item .color-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .btn-ver-evento {
        font-size: 11px;
        padding: 2px 10px;
        margin-top: 4px;
    }
    .calendario-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .calendario-actions .btn {
        border-radius: 8px;
        font-size: 13px;
    }
    .calendario-actions .btn i {
        margin-right: 5px;
    }
    @media (max-width: 768px) {
        .dia-grid {
            min-height: 70px;
            padding: 4px;
        }
        .dia-grid .dia-numero {
            font-size: 12px;
        }
        .dia-grid .evento-item {
            font-size: 8px;
            padding: 1px 4px;
        }
        .header-leyenda {
            gap: 8px;
        }
        .header-leyenda .leyenda-item {
            font-size: 10px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Calendario</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="calendario-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-light btn-sm rounded-circle" id="btnMesAnterior" style="width:40px;height:40px;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h3 class="mb-0" id="tituloMes" style="min-width:180px;text-align:center;">—</h3>
            <button class="btn btn-outline-light btn-sm rounded-circle" id="btnMesSiguiente" style="width:40px;height:40px;">
                <i class="fas fa-chevron-right"></i>
            </button>
            <button class="btn btn-outline-light btn-sm" id="btnHoy">
                <i class="fas fa-calendar-day me-1"></i>Hoy
            </button>
        </div>
        <div class="calendario-actions">
            <button class="btn btn-light btn-sm" id="btnNuevo">
                <i class="fas fa-plus me-1"></i>Nuevo evento
            </button>
            <button class="btn btn-outline-light btn-sm" id="btnVerLista">
                <i class="fas fa-list me-1"></i>Lista
            </button>
        </div>
    </div>
    <div class="header-leyenda mt-3">
        <span class="leyenda-item"><span class="color-dot" style="background:#6c757d;"></span> Agenda</span>
        <span class="leyenda-item"><span class="color-dot" style="background:#ffc107;"></span> Recordatorio</span>
        <span class="leyenda-item"><span class="color-dot" style="background:#0dcaf0;"></span> Visita</span>
        <span class="leyenda-item"><span class="color-dot" style="background:#0d6efd;"></span> Reunión</span>
        <span class="leyenda-item"><span class="color-dot" style="background:#dc3545;"></span> Inspección</span>
    </div>
</div>

<div class="card">
    <div class="card-body p-2 p-md-3">
        <div id="calendarioGrid" class="row g-1"></div>
    </div>
</div>


<div class="modal fade" id="modalEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalEvento">Nuevo evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEvento">
                <div class="modal-body">
                    <input type="hidden" name="id" id="evento_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" id="evento_titulo" class="form-control" required placeholder="Escribe un título">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" id="evento_tipo" class="form-select" required>
                            <option value="agenda">📋 Agenda</option>
                            <option value="recordatorio">⏰ Recordatorio</option>
                            <option value="visita">👷 Visita</option>
                            <option value="reunion">🤝 Reunión</option>
                            <option value="inspeccion">🔍 Inspección</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Fecha/hora inicio *</label>
                            <input type="datetime-local" name="fecha_inicio" id="evento_fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Fecha/hora fin</label>
                            <input type="datetime-local" name="fecha_fin" id="evento_fecha_fin" class="form-control">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Obra relacionada</label>
                        <select name="obra_id" id="evento_obra_id" class="form-select">
                            <option value="">— Ninguna —</option>
                            <?php $__currentLoopData = $obras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($o->id); ?>"><?php echo e($o->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="evento_descripcion" class="form-control" rows="3" placeholder="Descripción del evento..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" id="evento_color" class="form-control form-control-color" value="#667eea">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarEvento">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalVerEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="modalVerHeader">
                <h5 class="modal-title" id="modalVerTitulo">Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalVerBody">
                <div id="verEventoContenido"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnEditarEventoVer">
                    <i class="fas fa-edit me-1"></i>Editar
                </button>
                <button type="button" class="btn btn-danger" id="btnEliminarEventoVer">
                    <i class="fas fa-trash me-1"></i>Eliminar
                </button>
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
    let fechaActual = new Date();
    let eventosCache = [];
    let eventoSeleccionado = null;

    const colores = { 
        agenda: '#6c757d', 
        recordatorio: '#ffc107', 
        visita: '#0dcaf0', 
        reunion: '#0d6efd', 
        inspeccion: '#dc3545' 
    };

    const iconos = {
        agenda: 'fa-calendar',
        recordatorio: 'fa-bell',
        visita: 'fa-hard-hat',
        reunion: 'fa-users',
        inspeccion: 'fa-clipboard-check'
    };

    const tiposLabel = {
        agenda: 'Agenda',
        recordatorio: 'Recordatorio',
        visita: 'Visita',
        reunion: 'Reunión',
        inspeccion: 'Inspección'
    };

    // Navegación
    $('#btnMesAnterior').on('click', function() {
        fechaActual.setMonth(fechaActual.getMonth() - 1);
        cargarMes();
    });

    $('#btnMesSiguiente').on('click', function() {
        fechaActual.setMonth(fechaActual.getMonth() + 1);
        cargarMes();
    });

    $('#btnHoy').on('click', function() {
        fechaActual = new Date();
        cargarMes();
    });

    // Botón Nuevo Evento
    $('#btnNuevo').on('click', function() {
        limpiarFormulario();
        $('#evento_fecha_inicio').val('');
        $('#evento_fecha_fin').val('');
        $('#tituloModalEvento').text('Nuevo evento');
        $('#modalEvento').modal('show');
    });

    // Botón Ver Lista
    $('#btnVerLista').on('click', function() {
        // Redirigir a lista o mostrar modal con lista
        Swal.fire({
            title: 'Próximos eventos',
            html: generarListaEventos(),
            confirmButtonText: 'Cerrar',
            width: '600px'
        });
    });

    function generarListaEventos() {
        if (!eventosCache || eventosCache.length === 0) {
            return '<p class="text-muted text-center">No hay eventos programados.</p>';
        }
        
        let html = '<div style="max-height:400px;overflow-y:auto;">';
        eventosCache.sort((a, b) => new Date(a.fecha_inicio) - new Date(b.fecha_inicio));
        eventosCache.forEach(function(ev) {
            const fecha = new Date(ev.fecha_inicio);
            html += `
                <div class="d-flex align-items-center gap-3 p-2 border-bottom" style="cursor:pointer;" onclick="verEventoDetalle(${ev.id})">
                    <span class="badge" style="background:${colores[ev.tipo] || '#6c757d'};width:10px;height:40px;border-radius:4px;"></span>
                    <div class="flex-grow-1">
                        <strong>${ev.titulo}</strong>
                        <div class="small text-muted">${tiposLabel[ev.tipo] || ev.tipo} - ${fecha.toLocaleDateString('es-PE')} ${fecha.toLocaleTimeString('es-PE', {hour:'2-digit',minute:'2-digit'})}</div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        return html;
    }

    function cargarMes() {
        const mes = fechaActual.getMonth() + 1;
        const anio = fechaActual.getFullYear();
        
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $('#tituloMes').text(meses[fechaActual.getMonth()] + ' ' + anio);

        $.ajax({
            url: '<?php echo e(route("calendario.datos")); ?>',
            type: 'GET',
            data: { mes: mes, anio: anio },
            success: function(response) {
                eventosCache = response.data || [];
                pintarCalendario(mes, anio, eventosCache);
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los eventos' });
            }
        });
    }

    function pintarCalendario(mes, anio, eventos) {
        const primerDia = new Date(anio, mes - 1, 1).getDay();
        const diasEnMes = new Date(anio, mes, 0).getDate();
        const diasMesAnterior = new Date(anio, mes - 1, 0).getDate();
        const hoy = new Date();
        const hoyFecha = hoy.getFullYear() + '-' + String(hoy.getMonth() + 1).padStart(2, '0') + '-' + String(hoy.getDate()).padStart(2, '0');

        const grid = document.getElementById('calendarioGrid');
        grid.innerHTML = '';

        // Días de la semana
        const diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        diasSemana.forEach(d => {
            grid.innerHTML += `<div class="col text-center fw-bold small text-muted py-2">${d}</div>`;
        });

        // Días del mes anterior
        const diasDesdeLunes = primerDia === 0 ? 6 : primerDia - 1;
        for (let i = diasDesdeLunes - 1; i >= 0; i--) {
            const dia = diasMesAnterior - i;
            grid.innerHTML += `
                <div class="col dia-grid otro-mes" data-fecha="${anio}-${String(mes-1).padStart(2,'0')}-${String(dia).padStart(2,'0')}">
                    <span class="dia-numero otro-mes">${dia}</span>
                </div>
            `;
        }

        // Días del mes actual
        for (let dia = 1; dia <= diasEnMes; dia++) {
            const fechaStr = anio + '-' + String(mes).padStart(2, '0') + '-' + String(dia).padStart(2, '0');
            const eventosDia = eventos.filter(ev => {
                const fechaEv = new Date(ev.fecha_inicio);
                return fechaEv.getDate() === dia && fechaEv.getMonth() === mes - 1 && fechaEv.getFullYear() === anio;
            });
            const esHoy = fechaStr === hoyFecha;
            
            let badges = '';
            const maxMostrar = 3;
            eventosDia.slice(0, maxMostrar).forEach(ev => {
                const bgColor = colores[ev.tipo] || '#6c757d';
                badges += `
                    <div class="evento-item" style="background:${bgColor};color:white;" onclick="event.stopPropagation();verEventoDetalle(${ev.id})">
                        <i class="fas ${iconos[ev.tipo] || 'fa-calendar'} me-1"></i>
                        <span class="evento-titulo">${ev.titulo}</span>
                    </div>
                `;
            });

            if (eventosDia.length > maxMostrar) {
                badges += `<div class="evento-mas" onclick="event.stopPropagation();verEventosDia(${dia})">+${eventosDia.length - maxMostrar} más</div>`;
            }

            grid.innerHTML += `
                <div class="col dia-grid ${esHoy ? 'activo' : ''}" data-fecha="${fechaStr}" onclick="seleccionarDia('${fechaStr}')">
                    <span class="dia-numero ${esHoy ? 'hoy' : ''}">${dia}</span>
                    ${badges}
                </div>
            `;
        }

        // Días del mes siguiente para completar la semana
        const totalDias = diasDesdeLunes + diasEnMes;
        const diasRestantes = (7 - (totalDias % 7)) % 7;
        for (let dia = 1; dia <= diasRestantes; dia++) {
            grid.innerHTML += `
                <div class="col dia-grid otro-mes" data-fecha="${anio}-${String(mes+1).padStart(2,'0')}-${String(dia).padStart(2,'0')}">
                    <span class="dia-numero otro-mes">${dia}</span>
                </div>
            `;
        }
    }

    window.seleccionarDia = function(fecha) {
        Swal.fire({
            title: 'Eventos del día',
            html: generarEventosDia(fecha),
            confirmButtonText: 'Cerrar',
            showCancelButton: true,
            cancelButtonText: 'Nuevo evento',
            width: '500px'
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                $('#evento_fecha_inicio').val(fecha + 'T09:00');
                $('#evento_fecha_fin').val(fecha + 'T10:00');
                limpiarFormulario();
                $('#tituloModalEvento').text('Nuevo evento');
                $('#modalEvento').modal('show');
            }
        });
    };

    function generarEventosDia(fecha) {
        const eventosDia = eventosCache.filter(ev => {
            const fechaEv = new Date(ev.fecha_inicio);
            const fechaStr = fechaEv.getFullYear() + '-' + String(fechaEv.getMonth() + 1).padStart(2, '0') + '-' + String(fechaEv.getDate()).padStart(2, '0');
            return fechaStr === fecha;
        });

        if (eventosDia.length === 0) {
            return '<p class="text-muted text-center">No hay eventos para este día.</p>';
        }

        let html = '<div style="max-height:300px;overflow-y:auto;">';
        eventosDia.sort((a, b) => new Date(a.fecha_inicio) - new Date(b.fecha_inicio));
        eventosDia.forEach(function(ev) {
            const fecha = new Date(ev.fecha_inicio);
            html += `
                <div class="d-flex align-items-center gap-3 p-2 border-bottom" style="cursor:pointer;" onclick="verEventoDetalle(${ev.id})">
                    <span class="badge" style="background:${colores[ev.tipo] || '#6c757d'};width:8px;height:35px;border-radius:4px;"></span>
                    <div class="flex-grow-1">
                        <strong>${ev.titulo}</strong>
                        <div class="small text-muted">${tiposLabel[ev.tipo] || ev.tipo} - ${fecha.toLocaleTimeString('es-PE', {hour:'2-digit',minute:'2-digit'})}</div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        return html;
    }

    window.verEventosDia = function(dia) {
        const fecha = fechaActual.getFullYear() + '-' + String(fechaActual.getMonth() + 1).padStart(2, '0') + '-' + String(dia).padStart(2, '0');
        seleccionarDia(fecha);
    };

    window.verEventoDetalle = function(id) {
        const evento = eventosCache.find(ev => ev.id === id);
        if (!evento) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Evento no encontrado' });
            return;
        }

        eventoSeleccionado = evento;
        const fecha = new Date(evento.fecha_inicio);
        const fechaFin = evento.fecha_fin ? new Date(evento.fecha_fin) : null;
        const bgColor = colores[evento.tipo] || '#6c757d';

        let html = `
            <div class="text-center mb-3">
                <span class="badge" style="background:${bgColor};font-size:16px;padding:8px 20px;">
                    <i class="fas ${iconos[evento.tipo] || 'fa-calendar'} me-2"></i>
                    ${tiposLabel[evento.tipo] || evento.tipo}
                </span>
            </div>
            <h5 class="text-center">${evento.titulo}</h5>
            <hr>
            <div class="row">
                <div class="col-6">
                    <strong>Inicio:</strong>
                    <div class="text-muted">${fecha.toLocaleDateString('es-PE')} ${fecha.toLocaleTimeString('es-PE', {hour:'2-digit',minute:'2-digit'})}</div>
                </div>
                <div class="col-6">
                    <strong>Fin:</strong>
                    <div class="text-muted">${fechaFin ? fechaFin.toLocaleDateString('es-PE') + ' ' + fechaFin.toLocaleTimeString('es-PE', {hour:'2-digit',minute:'2-digit'}) : '—'}</div>
                </div>
            </div>
            ${evento.obra ? `<div class="mt-2"><strong>Obra:</strong> ${evento.obra.nombre}</div>` : ''}
            ${evento.descripcion ? `<div class="mt-2"><strong>Descripción:</strong><br><span class="text-muted">${evento.descripcion}</span></div>` : ''}
        `;

        $('#modalVerTitulo').text(evento.titulo);
        $('#modalVerHeader').css('background', `linear-gradient(135deg, ${bgColor}, ${bgColor}dd)`);
        $('#modalVerBody #verEventoContenido').html(html);
        $('#btnEditarEventoVer').data('id', evento.id);
        $('#btnEliminarEventoVer').data('id', evento.id);
        $('#modalVerEvento').modal('show');
    };

    // Editar evento desde modal de ver
    $('#btnEditarEventoVer').on('click', function() {
        const id = $(this).data('id');
        const evento = eventosCache.find(ev => ev.id === id);
        if (!evento) return;

        $('#modalVerEvento').modal('hide');
        
        $('#evento_id').val(evento.id);
        $('#evento_titulo').val(evento.titulo);
        $('#evento_tipo').val(evento.tipo);
        $('#evento_fecha_inicio').val(evento.fecha_inicio.replace(' ', 'T'));
        $('#evento_fecha_fin').val(evento.fecha_fin ? evento.fecha_fin.replace(' ', 'T') : '');
        $('#evento_obra_id').val(evento.obra_id || '');
        $('#evento_descripcion').val(evento.descripcion || '');
        $('#evento_color').val(evento.color || '#667eea');
        $('#tituloModalEvento').text('Editar evento');
        $('#modalEvento').modal('show');
    });

    // Eliminar evento desde modal de ver
    $('#btnEliminarEventoVer').on('click', function() {
        const id = $(this).data('id');
        eliminarEvento(id);
    });

    function eliminarEvento(id) {
        Swal.fire({
            title: '¿Eliminar evento?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/calendario/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        $('#modalVerEvento').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Eliminado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        cargarMes();
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al eliminar' });
                    }
                });
            }
        });
    }

    function limpiarFormulario() {
        $('#formEvento')[0].reset();
        $('#evento_id').val('');
        $('#evento_color').val('#667eea');
        $('#tituloModalEvento').text('Nuevo evento');
    }

    // Enviar formulario
    $('#formEvento').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#evento_id').val();
        const datos = {
            titulo: $('#evento_titulo').val(),
            tipo: $('#evento_tipo').val(),
            fecha_inicio: $('#evento_fecha_inicio').val(),
            fecha_fin: $('#evento_fecha_fin').val(),
            obra_id: $('#evento_obra_id').val(),
            descripcion: $('#evento_descripcion').val(),
            color: $('#evento_color').val()
        };

        const url = id ? '/calendario/' + id : '/calendario';
        const method = id ? 'PUT' : 'POST';

        $('#btnGuardarEvento').prop('disabled', true);
        $('#btnGuardarEvento').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

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
                $('#modalEvento').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje || 'Operación exitosa',
                    timer: 2000,
                    showConfirmButton: false
                });
                cargarMes();
            },
            error: function(xhr) {
                let errorMsg = 'Ocurrió un error al guardar';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    const firstError = Object.values(errors)[0];
                    if (firstError && firstError[0]) {
                        errorMsg = firstError[0];
                    }
                }
                Swal.fire({ icon: 'error', title: 'Error', text: errorMsg });
            },
            complete: function() {
                $('#btnGuardarEvento').prop('disabled', false);
                $('#btnGuardarEvento').text('Guardar');
            }
        });
    });

    // Cargar mes inicial
    cargarMes();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/calendario/index.blade.php ENDPATH**/ ?>