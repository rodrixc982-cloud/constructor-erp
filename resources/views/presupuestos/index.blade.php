@extends('layouts.app')

@section('titulo', 'Presupuestos')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Presupuestos</li>
@endsection

@section('contenido')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Presupuestos</h3>
        @can('presupuestos.crear')
        <button class="btn btn-primary btn-sm" id="btnNuevoPresupuesto">
            <i class="fas fa-plus me-1"></i>Nuevo presupuesto
        </button>
        @endcan
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaPresupuestos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Obra</th>
                    <th>Fecha</th>
                    <th>Versión</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal Nuevo Presupuesto --}}
<div class="modal fade" id="modalNuevoPresupuesto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo presupuesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoPresupuesto">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Código (autogenerado si vacío)</label>
                            <input type="text" name="codigo" id="presupuesto_codigo" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="fecha" id="presupuesto_fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Validez (días) *</label>
                            <input type="number" name="validez_dias" id="presupuesto_validez_dias" class="form-control" value="30" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="cliente_id" id="presupuesto_cliente_id" class="form-select">
                                <option value="">— Ninguno —</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Obra</label>
                            <select name="obra_id" id="presupuesto_obra_id" class="form-select">
                                <option value="">— Ninguna —</option>
                                @foreach($obras as $o)
                                    <option value="{{ $o->id }}">{{ $o->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Responsable</label>
                            <select name="responsable_id" id="presupuesto_responsable_id" class="form-select">
                                <option value="">— Ninguno —</option>
                                @foreach($responsables as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Moneda</label>
                            <input type="text" name="moneda" id="presupuesto_moneda" class="form-control" value="PEN">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">IGV %</label>
                            <input type="number" step="0.01" name="igv" id="presupuesto_igv" class="form-control" value="18">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Descuento %</label>
                            <input type="number" step="0.01" name="descuento_pct" id="presupuesto_descuento_pct" class="form-control" value="0">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="presupuesto_observaciones" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarPresupuesto">Crear y continuar</button>
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

    const badges = {
        'borrador': 'secondary',
        'aprobado': 'success',
        'rechazado': 'danger',
        'archivado': 'dark'
    };

    // Inicializar DataTable
    var tabla = $('#tablaPresupuestos').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("presupuestos.datos") }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { 
                data: null,
                render: function(r) {
                    return '<a href="/presupuestos/' + r.id + '/editar" class="fw-bold text-primary">' + r.codigo + '</a>';
                }
            },
            { 
                data: 'cliente',
                render: function(c) {
                    return c?.nombre || '—';
                }
            },
            { 
                data: 'obra',
                render: function(o) {
                    return o?.nombre || '—';
                }
            },
            { data: 'fecha' },
            { 
                data: 'version',
                render: function(v) {
                    return 'v' + v;
                }
            },
            { 
                data: 'estado',
                render: function(e) {
                    var color = badges[e] || 'secondary';
                    var label = e.charAt(0).toUpperCase() + e.slice(1);
                    return '<span class="badge bg-' + color + '">' + label + '</span>';
                }
            },
            { 
                data: null,
                render: function(r) {
                    return r.moneda + ' ' + parseFloat(r.total_general).toFixed(2);
                }
            },
            {
                data: null,
                orderable: false,
                render: function(r) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/presupuestos/${r.id}/editar" class="btn btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/presupuestos/${r.id}/exportar/pdf" class="btn btn-outline-danger" title="Exportar PDF" target="_blank">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <button class="btn btn-outline-info btnDuplicarPresupuesto" data-id="${r.id}" title="Duplicar">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="btn btn-outline-success btnAprobarPresupuesto" data-id="${r.id}" title="Aprobar">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-outline-warning btnRechazarPresupuesto" data-id="${r.id}" title="Rechazar">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="btn btn-outline-danger btnEliminarPresupuesto" data-id="${r.id}" title="Eliminar">
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

    // Botón Nuevo Presupuesto
    $('#btnNuevoPresupuesto').on('click', function() {
        $('#formNuevoPresupuesto')[0].reset();
        $('#presupuesto_fecha').val('{{ now()->format("Y-m-d") }}');
        $('#presupuesto_validez_dias').val('30');
        $('#presupuesto_igv').val('18');
        $('#presupuesto_descuento_pct').val('0');
        $('#presupuesto_moneda').val('PEN');
        $('#modalNuevoPresupuesto').modal('show');
    });

    // Enviar formulario de nuevo presupuesto
    $('#formNuevoPresupuesto').on('submit', function(e) {
        e.preventDefault();
        
        var datos = {
            codigo: $('#presupuesto_codigo').val(),
            fecha: $('#presupuesto_fecha').val(),
            validez_dias: $('#presupuesto_validez_dias').val(),
            cliente_id: $('#presupuesto_cliente_id').val(),
            obra_id: $('#presupuesto_obra_id').val(),
            responsable_id: $('#presupuesto_responsable_id').val(),
            moneda: $('#presupuesto_moneda').val(),
            igv: $('#presupuesto_igv').val(),
            descuento_pct: $('#presupuesto_descuento_pct').val(),
            observaciones: $('#presupuesto_observaciones').val()
        };

        $('#btnGuardarPresupuesto').prop('disabled', true);
        $('#btnGuardarPresupuesto').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

        $.ajax({
            url: '{{ route("presupuestos.store") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            contentType: 'application/json',
            data: JSON.stringify(datos),
            success: function(response) {
                if (response.data && response.data.id) {
                    window.location.href = '/presupuestos/' + response.data.id + '/editar';
                } else {
                    $('#modalNuevoPresupuesto').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: response.mensaje || 'Presupuesto creado',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    tabla.ajax.reload();
                }
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
                $('#btnGuardarPresupuesto').prop('disabled', false);
                $('#btnGuardarPresupuesto').text('Crear y continuar');
            }
        });
    });

    // Duplicar presupuesto
    $(document).on('click', '.btnDuplicarPresupuesto', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Duplicar presupuesto?',
            text: 'Se creará una copia exacta del presupuesto actual.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, duplicar',
            cancelButtonText: 'Cancelar',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/presupuestos/' + id + '/duplicar',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Presupuesto duplicado',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tabla.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al duplicar'
                        });
                    }
                });
            }
        });
    });

    // Aprobar presupuesto
    $(document).on('click', '.btnAprobarPresupuesto', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Aprobar presupuesto?',
            text: 'El presupuesto pasará a estado "aprobado".',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, aprobar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/presupuestos/' + id + '/aprobar',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Presupuesto aprobado',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tabla.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al aprobar'
                        });
                    }
                });
            }
        });
    });

    // Rechazar presupuesto
    $(document).on('click', '.btnRechazarPresupuesto', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Rechazar presupuesto?',
            text: 'El presupuesto pasará a estado "rechazado".',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/presupuestos/' + id + '/rechazar',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Presupuesto rechazado',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tabla.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al rechazar'
                        });
                    }
                });
            }
        });
    });

    // Eliminar presupuesto
    $(document).on('click', '.btnEliminarPresupuesto', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar presupuesto?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/presupuestos/' + id,
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

    // Limpiar formulario al cerrar modal
    $('#modalNuevoPresupuesto').on('hidden.bs.modal', function() {
        $('#formNuevoPresupuesto')[0].reset();
        $('#presupuesto_fecha').val('{{ now()->format("Y-m-d") }}');
        $('#presupuesto_validez_dias').val('30');
        $('#presupuesto_igv').val('18');
        $('#presupuesto_descuento_pct').val('0');
        $('#presupuesto_moneda').val('PEN');
    });
});
</script>
@endpush