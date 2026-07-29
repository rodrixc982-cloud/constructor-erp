@extends('layouts.app')

@section('titulo', 'Inventario')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Inventario</li>
@endsection

@section('contenido')
@if($alertas->count())
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-1"></i>
    <strong>{{ $alertas->count() }} material(es)</strong> por debajo del stock mínimo:
    {{ $alertas->pluck('nombre')->join(', ') }}
</div>
@endif

<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tabMovimientos">Kardex / Movimientos</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabAlmacenes">Almacenes</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tabMovimientos">
        <div class="card">
            <div class="card-header d-flex justify-content-between flex-wrap gap-2">
                <h3 class="card-title">Movimientos recientes</h3>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-success btn-sm" id="btnEntrada"><i class="fas fa-arrow-down me-1"></i>Entrada</button>
                    <button class="btn btn-danger btn-sm" id="btnSalida"><i class="fas fa-arrow-up me-1"></i>Salida</button>
                    <button class="btn btn-info btn-sm text-white" id="btnTransferencia"><i class="fas fa-exchange-alt me-1"></i>Transferencia</button>
                    <button class="btn btn-secondary btn-sm" id="btnAjuste"><i class="fas fa-balance-scale me-1"></i>Ajuste</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered w-100" id="tablaMovimientos">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Material</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Almacén</th>
                            <th>Motivo</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tabAlmacenes">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Almacenes</h3>
                <button class="btn btn-primary btn-sm" id="btnNuevoAlmacen"><i class="fas fa-plus me-1"></i>Nuevo almacén</button>
            </div>
            <div class="card-body row">
                @foreach($almacenes as $a)
                <div class="col-md-4 mb-3">
                    <div class="card border">
                        <div class="card-body">
                            <h6>{{ $a->nombre }}</h6>
                            <small class="text-muted d-block">{{ $a->ubicacion ?? 'Sin ubicación registrada' }}</small>
                            <small class="text-muted">Responsable: {{ $a->responsable ?? '—' }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Modal Entrada --}}
<div class="modal fade" id="modalEntrada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEntrada">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar entrada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Material *</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($materiales as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }} ({{ $m->codigo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén *</label>
                        <select name="almacen_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($almacenes as $a)
                                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad *</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required min="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <input type="text" name="motivo" class="form-control" placeholder="Ej: Compra, Devolución, etc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar entrada</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Salida --}}
<div class="modal fade" id="modalSalida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formSalida">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar salida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Material *</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($materiales as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }} ({{ $m->codigo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén *</label>
                        <select name="almacen_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($almacenes as $a)
                                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad *</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required min="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <input type="text" name="motivo" class="form-control" placeholder="Ej: Venta, Uso interno, etc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Registrar salida</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Transferencia --}}
<div class="modal fade" id="modalTransferencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTransferencia">
                <div class="modal-header">
                    <h5 class="modal-title">Transferencia entre almacenes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Material *</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($materiales as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén origen *</label>
                        <select name="almacen_origen_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($almacenes as $a)
                                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén destino *</label>
                        <select name="almacen_destino_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($almacenes as $a)
                                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad *</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required min="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white">Transferir</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Ajuste --}}
<div class="modal fade" id="modalAjuste" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAjuste">
                <div class="modal-header">
                    <h5 class="modal-title">Ajuste de inventario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Material *</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($materiales as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Almacén *</label>
                        <select name="almacen_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($almacenes as $a)
                                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva cantidad de stock *</label>
                        <input type="number" step="0.01" name="nueva_cantidad" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-secondary">Ajustar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Nuevo Almacén --}}
<div class="modal fade" id="modalNuevoAlmacen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAlmacen">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo almacén</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Responsable</label>
                        <input type="text" name="responsable" class="form-control">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="estado" class="form-check-input" value="1" checked>
                        <label class="form-check-label">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
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
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Inicializar DataTable
    var tabla = $('#tablaMovimientos').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("inventario.datos") }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { 
                data: 'created_at',
                render: function(f) {
                    return f ? new Date(f).toLocaleString('es-PE') : '—';
                }
            },
            { 
                data: 'material',
                render: function(m) {
                    return m?.nombre || '—';
                }
            },
            { 
                data: 'tipo',
                render: function(t) {
                    var badges = {
                        'entrada': '<span class="badge bg-success">Entrada</span>',
                        'salida': '<span class="badge bg-danger">Salida</span>',
                        'ajuste': '<span class="badge bg-secondary">Ajuste</span>',
                        'transferencia': '<span class="badge bg-info text-white">Transferencia</span>'
                    };
                    return badges[t] || t;
                }
            },
            { data: 'cantidad' },
            { 
                data: 'almacen',
                render: function(a) {
                    return a?.nombre || '—';
                }
            },
            { data: 'motivo', defaultContent: '—' },
            { 
                data: 'usuario',
                render: function(u) {
                    return u?.name || '—';
                }
            }
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
    });

    // Función para enviar formularios
    function enviarFormulario(url, datos, modalId, callback) {
        $('#btnGuardar').prop('disabled', true);
        
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
                    title: response.mensaje || 'Operación exitosa',
                    timer: 2000,
                    showConfirmButton: false
                });
                tabla.ajax.reload();
                if (callback) callback(response);
            },
            error: function(xhr) {
                var errorMsg = 'Ocurrió un error';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    var firstError = Object.values(errors)[0];
                    if (firstError && firstError[0]) {
                        errorMsg = firstError[0];
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            }
        });
    }

    // Botón Entrada
    $('#btnEntrada').on('click', function() {
        $('#formEntrada')[0].reset();
        $('#modalEntrada').modal('show');
    });

    // Botón Salida
    $('#btnSalida').on('click', function() {
        $('#formSalida')[0].reset();
        $('#modalSalida').modal('show');
    });

    // Botón Transferencia
    $('#btnTransferencia').on('click', function() {
        $('#formTransferencia')[0].reset();
        $('#modalTransferencia').modal('show');
    });

    // Botón Ajuste
    $('#btnAjuste').on('click', function() {
        $('#formAjuste')[0].reset();
        $('#modalAjuste').modal('show');
    });

    // Botón Nuevo Almacén
    $('#btnNuevoAlmacen').on('click', function() {
        $('#formAlmacen')[0].reset();
        $('#formAlmacen input[name="estado"]').prop('checked', true);
        $('#modalNuevoAlmacen').modal('show');
    });

    // Formulario Entrada
    $('#formEntrada').on('submit', function(e) {
        e.preventDefault();
        var datos = $(this).serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        enviarFormulario('{{ route("inventario.entrada") }}', datos, 'modalEntrada');
    });

    // Formulario Salida
    $('#formSalida').on('submit', function(e) {
        e.preventDefault();
        var datos = $(this).serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        enviarFormulario('{{ route("inventario.salida") }}', datos, 'modalSalida');
    });

    // Formulario Transferencia
    $('#formTransferencia').on('submit', function(e) {
        e.preventDefault();
        var datos = $(this).serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        enviarFormulario('{{ route("inventario.transferencia") }}', datos, 'modalTransferencia');
    });

    // Formulario Ajuste
    $('#formAjuste').on('submit', function(e) {
        e.preventDefault();
        var datos = $(this).serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        enviarFormulario('{{ route("inventario.ajuste") }}', datos, 'modalAjuste');
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
        
        $.ajax({
            url: '{{ route("inventario.almacen.guardar") }}',
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
                    title: response.mensaje || 'Almacén guardado',
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                var errorMsg = 'Ocurrió un error al guardar';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
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
    });

    // Limpiar formularios al cerrar modales
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0]?.reset();
        $(this).find('select').val('');
    });
});
</script>
@endpush