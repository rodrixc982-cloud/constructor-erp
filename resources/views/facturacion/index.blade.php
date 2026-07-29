@extends('layouts.app')

@section('titulo', 'Facturación')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Facturación</li>
@endsection

@section('contenido')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Documentos de venta</h3>
        @can('facturacion.crear')
        <button class="btn btn-primary btn-sm" id="btnNuevoDocumento">
            <i class="fas fa-plus me-1"></i>Nuevo documento
        </button>
        @endcan
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered w-100" id="tablaFacturacion">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal Crear --}}
<div class="modal fade" id="modalDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalDocumento">Nuevo documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDocumento">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" id="doc_tipo" class="form-select" required>
                            @foreach(['cotizacion'=>'Cotización','proforma'=>'Proforma','factura'=>'Factura','boleta'=>'Boleta','orden_servicio'=>'Orden de Servicio'] as $v=>$l)
                                <option value="{{ $v }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Serie</label>
                            <input type="text" name="serie" id="doc_serie" class="form-control" placeholder="F001">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="fecha" id="doc_fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select name="cliente_id" id="doc_cliente_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($clientes as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Presupuesto de referencia</label>
                        <select name="presupuesto_id" id="doc_presupuesto_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($presupuestos as $p)
                                <option value="{{ $p->id }}">{{ $p->codigo }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Subtotal *</label>
                            <input type="number" step="0.01" name="subtotal" id="doc_subtotal" class="form-control" required min="0">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">IGV *</label>
                            <input type="number" step="0.01" name="igv" id="doc_igv" class="form-control" required min="0">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Total *</label>
                            <input type="number" step="0.01" name="total" id="doc_total" class="form-control" required min="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="doc_observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarDocumento">Guardar</button>
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
        'emitido': 'info',
        'pagado': 'success',
        'anulado': 'danger'
    };

    const tipoLabels = {
        'cotizacion': 'Cotización',
        'proforma': 'Proforma',
        'factura': 'Factura',
        'boleta': 'Boleta',
        'orden_servicio': 'Orden de Servicio'
    };

    // Inicializar DataTable
    var tabla = $('#tablaFacturacion').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("facturacion.datos") }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { 
                data: 'tipo',
                render: function(t) {
                    return tipoLabels[t] || t;
                }
            },
            { data: 'numero_completo' },
            { 
                data: 'cliente',
                render: function(c) {
                    return c?.nombre || '—';
                }
            },
            { data: 'fecha' },
            { 
                data: 'total',
                render: function(t) {
                    return 'S/ ' + parseFloat(t).toFixed(2);
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
                orderable: false,
                render: function(r) {
                    var acciones = '';
                    
                    // Botón PDF
                    acciones += '<a href="/facturacion/' + r.id + '/exportar/pdf" target="_blank" class="btn btn-sm btn-outline-danger" title="Descargar PDF">';
                    acciones += '<i class="fas fa-file-pdf"></i></a>';
                    
                    // Botón Anular (solo si no está anulado)
                    if (r.estado !== 'anulado') {
                        acciones += ' <button class="btn btn-sm btn-outline-warning btnAnularDocumento" data-id="' + r.id + '" title="Anular">';
                        acciones += '<i class="fas fa-ban"></i></button>';
                    }
                    
                    // Botón Eliminar
                    acciones += ' <button class="btn btn-sm btn-outline-danger btnEliminarDocumento" data-id="' + r.id + '" title="Eliminar">';
                    acciones += '<i class="fas fa-trash"></i></button>';
                    
                    return acciones;
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

    // Botón Nuevo Documento
    $('#btnNuevoDocumento').on('click', function() {
        $('#formDocumento')[0].reset();
        $('#doc_fecha').val('{{ now()->format("Y-m-d") }}');
        $('#doc_tipo').val('factura');
        $('#tituloModalDocumento').text('Nuevo documento');
        $('#modalDocumento').modal('show');
    });

    // Anular Documento
    $(document).on('click', '.btnAnularDocumento', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Anular documento?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/facturacion/' + id + '/anular',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.mensaje || 'Documento anulado',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tabla.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al anular'
                        });
                    }
                });
            }
        });
    });

    // Eliminar Documento
    $(document).on('click', '.btnEliminarDocumento', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar documento?',
            text: 'Podrás restaurarlo luego.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/facturacion/' + id,
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
    $('#formDocumento').on('submit', function(e) {
        e.preventDefault();
        
        var datos = {
            tipo: $('#doc_tipo').val(),
            serie: $('#doc_serie').val(),
            fecha: $('#doc_fecha').val(),
            cliente_id: $('#doc_cliente_id').val(),
            presupuesto_id: $('#doc_presupuesto_id').val(),
            subtotal: $('#doc_subtotal').val(),
            igv: $('#doc_igv').val(),
            total: $('#doc_total').val(),
            observaciones: $('#doc_observaciones').val()
        };

        $('#btnGuardarDocumento').prop('disabled', true);
        $('#btnGuardarDocumento').html('<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...');

        $.ajax({
            url: '{{ route("facturacion.store") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            contentType: 'application/json',
            data: JSON.stringify(datos),
            success: function(response) {
                $('#modalDocumento').modal('hide');
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
                $('#btnGuardarDocumento').prop('disabled', false);
                $('#btnGuardarDocumento').text('Guardar');
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalDocumento').on('hidden.bs.modal', function() {
        $('#formDocumento')[0].reset();
        $('#doc_fecha').val('{{ now()->format("Y-m-d") }}');
        $('#doc_tipo').val('factura');
    });
});
</script>
@endpush