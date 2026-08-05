@extends('layouts.app')

@section('titulo', 'Clientes')

@push('estilos')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
    <li class="breadcrumb-item active">Clientes</li>
@endsection

@section('contenido')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h3 class="card-title mb-0">
            <i class="fas fa-users text-primary me-2"></i>Listado de clientes
        </h3>
        @can('clientes.crear')
        <button class="btn btn-primary btn-sm" id="btnNuevoCliente">
            <i class="fas fa-plus me-1"></i>Nuevo cliente
        </button>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover w-100" id="tablaClientes">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>DNI / RUC</th>
                        <th>Empresa</th>
                        <th>WhatsApp</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Modal Crear/Editar --}}
<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="formCliente" novalidate>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tituloModalCliente">
                        <i class="fas fa-user me-2"></i>Nuevo cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="cliente_id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre *</label>
                            <input type="text" name="nombre" id="cliente_nombre" class="form-control" required>
                            <div class="invalid-feedback">El nombre es obligatorio</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Empresa</label>
                            <input type="text" name="empresa" id="cliente_empresa" class="form-control">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">DNI</label>
                            <input type="text" name="dni" id="cliente_dni" class="form-control" maxlength="8">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">RUC</label>
                            <input type="text" name="ruc" id="cliente_ruc" class="form-control" maxlength="11">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">WhatsApp</label>
                            <input type="tel" name="whatsapp" id="cliente_whatsapp" class="form-control">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="cliente_email" class="form-control">
                            <div class="invalid-feedback">Ingresa un email válido</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dirección</label>
                            <input type="text" name="direccion" id="cliente_direccion" class="form-control">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Departamento</label>
                            <input type="text" name="departamento" id="cliente_departamento" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Provincia</label>
                            <input type="text" name="provincia" id="cliente_provincia" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Distrito</label>
                            <input type="text" name="distrito" id="cliente_distrito" class="form-control">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold">Referencia</label>
                            <input type="text" name="referencia" id="cliente_referencia" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Observaciones</label>
                            <textarea name="observaciones" id="cliente_observaciones" class="form-control" rows="2"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="estado" id="cliente_estado" class="form-check-input" value="1" checked>
                                <label class="form-check-label fw-bold" for="cliente_estado">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCliente">
                        <i class="fas fa-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    // --- Configuración de DataTable ---
    const tabla = new DataTable('#tablaClientes', {
        ajax: {
            url: '{{ route('clientes.datos') }}',
            dataSrc: 'data',
            error: function(xhr, error, thrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al cargar datos',
                    text: 'Por favor, recarga la página o contacta al administrador.'
                });
            }
        },
        columns: [
            { 
                data: 'nombre',
                render: function(data) {
                    return `<span class="fw-bold">${data}</span>`;
                }
            },
            { 
                data: null, 
                render: function(r) { 
                    return r.dni || r.ruc || '—'; 
                } 
            },
            { data: 'empresa', defaultContent: '—' },
            { 
                data: 'whatsapp', 
                defaultContent: '—',
                render: function(data) {
                    return data ? `<a href="https://wa.me/${data.replace(/\s/g, '')}" target="_blank" class="text-success">
                        <i class="fab fa-whatsapp"></i> ${data}</a>` : '—';
                }
            },
            { 
                data: 'email', 
                defaultContent: '—',
                render: function(data) {
                    return data ? `<a href="mailto:${data}">${data}</a>` : '—';
                }
            },
            { 
                data: 'estado', 
                render: function(e) { 
                    return e ? 
                        '<span class="badge bg-success rounded-pill px-3"><i class="fas fa-check-circle me-1"></i>Activo</span>' : 
                        '<span class="badge bg-secondary rounded-pill px-3"><i class="fas fa-times-circle me-1"></i>Inactivo</span>'; 
                } 
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function(r) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-primary btnEditar" data-id="${r.id}" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger btnEliminar" data-id="${r.id}" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            },
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-ES.json'
        },
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']]
    });

    // --- Cache de datos ---
    let clientesCache = [];
    tabla.on('xhr', function() {
        clientesCache = tabla.ajax.json().data || [];
    });

    // --- Funciones auxiliares ---
    function resetFormulario() {
        const form = document.getElementById('formCliente');
        form.reset();
        form.classList.remove('was-validated');
        document.getElementById('cliente_id').value = '';
        document.getElementById('cliente_estado').checked = true;
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    function cargarClienteEnForm(cliente) {
        const campos = ['id', 'nombre', 'empresa', 'dni', 'ruc', 'whatsapp', 'email', 
                       'direccion', 'departamento', 'provincia', 'distrito', 'referencia', 'observaciones'];
        
        campos.forEach(function(campo) {
            const el = document.getElementById('cliente_' + campo);
            if (el) {
                el.value = cliente[campo] !== undefined && cliente[campo] !== null ? cliente[campo] : '';
            }
        });
        
        document.getElementById('cliente_estado').checked = !!cliente.estado;
    }

    function mostrarModal(titulo, cliente = null) {
        resetFormulario();
        if (cliente) {
            cargarClienteEnForm(cliente);
        }
        document.getElementById('tituloModalCliente').textContent = titulo;
        const modal = new bootstrap.Modal(document.getElementById('modalCliente'));
        modal.show();
    }

    // --- Evento: Nuevo Cliente ---
    document.getElementById('btnNuevoCliente')?.addEventListener('click', function() {
        mostrarModal('Nuevo cliente');
    });

    // --- Eventos delegados para botones de acciones ---
    document.querySelector('#tablaClientes tbody')?.addEventListener('click', function(e) {
        const btnEditar = e.target.closest('.btnEditar');
        const btnEliminar = e.target.closest('.btnEliminar');

        if (btnEditar) {
            const cliente = clientesCache.find(c => c.id == btnEditar.dataset.id);
            if (!cliente) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error', 
                    text: 'No se encontraron datos del cliente' 
                });
                return;
            }
            mostrarModal('Editar cliente', cliente);
        }

        if (btnEliminar) {
            const id = btnEliminar.dataset.id;
            const cliente = clientesCache.find(c => c.id == id);
            
            Swal.fire({
                title: '¿Eliminar cliente?',
                html: `Estás a punto de eliminar a <strong>${cliente?.nombre || 'este cliente'}</strong>.<br>
                       Podrás restaurarlo luego desde Auditoría.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i>Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                reverseButtons: true
            }).then(function(result) {
                if (!result.isConfirmed) return;
                
                // Mostrar loading
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`/clientes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({ 
                        icon: 'success', 
                        title: '¡Eliminado!', 
                        text: data.mensaje || 'Cliente eliminado correctamente',
                        timer: 2000, 
                        showConfirmButton: false 
                    });
                    tabla.ajax.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error', 
                        text: 'Ocurrió un error al eliminar el cliente' 
                    });
                });
            });
        }
    });

    // --- Envío del formulario con validación ---
    const formCliente = document.getElementById('formCliente');
    if (formCliente) {
        formCliente.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validación HTML5
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            const id = document.getElementById('cliente_id').value;
            const formData = new FormData(this);
            const datos = {};
            formData.forEach(function(value, key) {
                datos[key] = value;
            });
            datos.estado = document.getElementById('cliente_estado').checked ? 1 : 0;

            const url = id ? `/clientes/${id}` : '/clientes';
            const method = id ? 'PUT' : 'POST';
            const mensaje = id ? 'actualizando' : 'creando';

            // Deshabilitar botón
            const btnGuardar = document.getElementById('btnGuardarCliente');
            const textoOriginal = btnGuardar.innerHTML;
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Guardando...';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(datos),
            })
            .then(async function(response) {
                const data = await response.json();
                if (!response.ok) {
                    let mensaje = data.mensaje || 'Ocurrió un error.';
                    if (data.errors) {
                        const errores = Object.values(data.errors).flat();
                        mensaje = errores.join('<br>');
                    }
                    throw new Error(mensaje);
                }
                return data;
            })
            .then(function(data) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalCliente'));
                if (modal) modal.hide();
                
                Swal.fire({ 
                    icon: 'success', 
                    title: '¡Éxito!', 
                    text: data.mensaje || `Cliente ${id ? 'actualizado' : 'creado'} correctamente`,
                    timer: 2000, 
                    showConfirmButton: false 
                });
                tabla.ajax.reload();
            })
            .catch(function(error) {
                console.error('Error:', error);
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error al guardar', 
                    html: error.message || 'Ocurrió un error al guardar el cliente',
                    confirmButtonColor: '#dc3545'
                });
            })
            .finally(function() {
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = textoOriginal;
            });
        });
    }

    // --- Limpiar formulario al cerrar modal ---
    document.getElementById('modalCliente')?.addEventListener('hidden.bs.modal', function() {
        resetFormulario();
    });

    // --- Validación de campos numéricos ---
    document.getElementById('cliente_dni')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 8);
    });
    
    document.getElementById('cliente_ruc')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
    });
    
    document.getElementById('cliente_whatsapp')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

});
</script>
@endpush