@extends('layouts.app')

@section('titulo', 'Caja')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .small-box {
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .small-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .small-box .inner {
        padding: 18px 20px;
        position: relative;
        z-index: 1;
    }
    .small-box .inner h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2px;
        color: #fff;
    }
    .small-box .inner p {
        font-size: 0.85rem;
        font-weight: 500;
        opacity: 0.9;
        color: #fff;
        margin-bottom: 0;
    }
    .small-box .icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3.5rem;
        opacity: 0.15;
        color: #fff;
        transition: all 0.3s ease;
        z-index: 0;
    }
    .small-box:hover .icon {
        opacity: 0.25;
        transform: translateY(-50%) scale(1.1);
    }
</style>
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Caja</li>
@endsection

@section('contenido')
{{-- Tarjetas de resumen --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>S/ {{ number_format($saldo, 2) }}</h3>
                <p>Saldo actual</p>
            </div>
            <div class="icon"><i class="fas fa-wallet"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>S/ {{ number_format($ingresosMes, 2) }}</h3>
                <p>Ingresos del mes</p>
            </div>
            <div class="icon"><i class="fas fa-arrow-down"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>S/ {{ number_format($egresosMes, 2) }}</h3>
                <p>Egresos del mes</p>
            </div>
            <div class="icon"><i class="fas fa-arrow-up"></i></div>
        </div>
    </div>
</div>

{{-- Tabla de movimientos --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Movimientos de caja</h3>
        <div class="d-flex gap-2">
            @can('caja.crear')
            <button class="btn btn-success btn-sm" id="btnNuevoIngreso">
                <i class="fas fa-plus me-1"></i>Ingreso
            </button>
            <button class="btn btn-danger btn-sm" id="btnNuevoEgreso">
                <i class="fas fa-minus me-1"></i>Egreso
            </button>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaCaja">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Cliente/Proveedor</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal Ingreso --}}
<div class="modal fade" id="modalIngreso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formIngreso">
                <div class="modal-body">
                    <input type="hidden" name="tipo" value="ingreso">
                    <div class="mb-3">
                        <label class="form-label">Concepto *</label>
                        <input type="text" name="concepto" id="ingreso_concepto" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Monto *</label>
                            <input type="number" step="0.01" name="monto" id="ingreso_monto" class="form-control" required min="0.01">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="fecha" id="ingreso_fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select name="cliente_id" id="ingreso_cliente_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($clientes as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proveedor</label>
                        <select name="proveedor_id" id="ingreso_proveedor_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($proveedores as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Presupuesto</label>
                        <select name="presupuesto_id" id="ingreso_presupuesto_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($presupuestos as $p)
                                <option value="{{ $p->id }}">{{ $p->codigo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="ingreso_observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btnGuardarIngreso">Registrar ingreso</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Egreso --}}
<div class="modal fade" id="modalEgreso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar egreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEgreso">
                <div class="modal-body">
                    <input type="hidden" name="tipo" value="egreso">
                    <div class="mb-3">
                        <label class="form-label">Concepto *</label>
                        <input type="text" name="concepto" id="egreso_concepto" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Monto *</label>
                            <input type="number" step="0.01" name="monto" id="egreso_monto" class="form-control" required min="0.01">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="fecha" id="egreso_fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select name="cliente_id" id="egreso_cliente_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($clientes as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proveedor</label>
                        <select name="proveedor_id" id="egreso_proveedor_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($proveedores as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Presupuesto</label>
                        <select name="presupuesto_id" id="egreso_presupuesto_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($presupuestos as $p)
                                <option value="{{ $p->id }}">{{ $p->codigo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="egreso_observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="btnGuardarEgreso">Registrar egreso</button>
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
    let cacheData = [];

    // Inicializar DataTable
    var tabla = $('#tablaCaja').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("caja.datos") }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: 'fecha' },
            { 
                data: 'tipo',
                render: function(t) {
                    return t === 'ingreso' 
                        ? '<span class="badge bg-success">Ingreso</span>' 
                        : '<span class="badge bg-danger">Egreso</span>';
                }
            },
            { data: 'concepto' },
            { 
                data: 'monto',
                render: function(m) {
                    return 'S/ ' + parseFloat(m).toFixed(2);
                }
            },
            { 
                data: null,
                render: function(r) {
                    return r.cliente?.nombre || r.proveedor?.nombre || '—';
                }
            },
            { 
                data: 'usuario',
                render: function(u) {
                    return u?.name || '—';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(r) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-danger btnEliminarMovimiento" data-id="${r.id}" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
    });

    tabla.on('xhr', function() {
        cacheData = tabla.ajax.json().data || [];
    });

    // Botón Nuevo Ingreso
    $('#btnNuevoIngreso').on('click', function() {
        $('#formIngreso')[0].reset();
        $('#ingreso_fecha').val('{{ now()->format("Y-m-d") }}');
        $('#modalIngreso').modal('show');
    });

    // Botón Nuevo Egreso
    $('#btnNuevoEgreso').on('click', function() {
        $('#formEgreso')[0].reset();
        $('#egreso_fecha').val('{{ now()->format("Y-m-d") }}');
        $('#modalEgreso').modal('show');
    });

    // Enviar formulario Ingreso
    $('#formIngreso').on('submit', function(e) {
        e.preventDefault();
        guardarMovimiento('ingreso', 'modalIngreso', '#btnGuardarIngreso');
    });

    // Enviar formulario Egreso
    $('#formEgreso').on('submit', function(e) {
        e.preventDefault();
        guardarMovimiento('egreso', 'modalEgreso', '#btnGuardarEgreso');
    });

    function guardarMovimiento(tipo, modalId, btnId) {
        var datos = {
            tipo: tipo,
            concepto: $('#' + tipo + '_concepto').val(),
            monto: $('#' + tipo + '_monto').val(),
            fecha: $('#' + tipo + '_fecha').val(),
            cliente_id: $('#' + tipo + '_cliente_id').val(),
            proveedor_id: $('#' + tipo + '_proveedor_id').val(),
            presupuesto_id: $('#' + tipo + '_presupuesto_id').val(),
            observaciones: $('#' + tipo + '_observaciones').val()
        };

        $(btnId).prop('disabled', true);
        $(btnId).html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

        $.ajax({
            url: '{{ route("caja.store") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            contentType: 'application/json',
            data: JSON.stringify(datos),
            success: function(response) {
                $('#' + modalId).modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje || 'Operación exitosa',
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
                $(btnId).prop('disabled', false);
                $(btnId).text(tipo === 'ingreso' ? 'Registrar ingreso' : 'Registrar egreso');
            }
        });
    }

    // Eliminar movimiento
    $(document).on('click', '.btnEliminarMovimiento', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar movimiento?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/caja/' + id,
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
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
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

    // Limpiar formularios al cerrar modales
    $('#modalIngreso').on('hidden.bs.modal', function() {
        $('#formIngreso')[0].reset();
        $('#ingreso_fecha').val('{{ now()->format("Y-m-d") }}');
    });

    $('#modalEgreso').on('hidden.bs.modal', function() {
        $('#formEgreso')[0].reset();
        $('#egreso_fecha').val('{{ now()->format("Y-m-d") }}');
    });
});
</script>
@endpush