@extends('layouts.app')

@section('titulo', 'Configuración de la Empresa')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Datos de la empresa</li>
@endsection

@section('contenido')
<div class="row">
    <div class="col-12">
        <!-- Card principal -->
        <div class="card">
            <div class="card-header" style="background: #1a2332; border-bottom: 3px solid #0d6efd;">
                <h3 class="card-title text-white">
                    <i class="fas fa-building me-2" style="color: #0d6efd;"></i>Configuración de la Empresa
                </h3>
                <div class="card-tools">
                    <span class="badge" style="background: #2d3748; color: #a0aec0; padding: 5px 10px;">
                        <i class="far fa-clock me-1" style="color: #0d6efd;"></i>{{ date('h:i:s a') }}
                    </span>
                    <span class="badge" style="background: #2d3748; color: #a0aec0; padding: 5px 10px; margin-left: 5px;">
                        <i class="far fa-calendar-alt me-1" style="color: #0d6efd;"></i>{{ date('d/m/Y') }}
                    </span>
                </div>
            </div>
            <!-- /.card-header -->
            
            <div class="card-body" style="background: #f8f9fa;">
                <form method="POST" action="{{ route('empresa.update') }}" enctype="multipart/form-data" id="empresaForm">
                    @csrf
                    @method('PUT')

                    <!-- Fila: Logo y datos principales -->
                    <div class="row">
                        <!-- Columna Logo -->
                        <div class="col-md-3">
                            <div class="card" style="border-left: 3px solid #0d6efd;">
                                <div class="card-header" style="background: #f1f3f5; border-bottom: 1px solid #dee2e6;">
                                    <h5 class="card-title mb-0" style="color: #1a2332;">
                                        <i class="fas fa-image me-1" style="color: #0d6efd;"></i>Logo
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="text-center mb-3">
                                        <img src="{{ $empresa->logo ? asset('storage/'.$empresa->logo) : asset('images/logo-default.png') }}" 
                                             alt="Logo de la empresa" 
                                             class="img-fluid img-thumbnail" 
                                             style="max-height:120px; width:auto; border-color: #dee2e6;">
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" 
                                               name="logo" 
                                               class="custom-file-input @error('logo') is-invalid @enderror" 
                                               id="logoInput"
                                               accept="image/*">
                                        <label class="custom-file-label" for="logoInput" style="background: #e9ecef; border-color: #ced4da;">Seleccionar logo</label>
                                        @error('logo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle" style="color: #0d6efd;"></i> Aparece en facturas y PDFs
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Datos Principales -->
                        <div class="col-md-9">
                            <div class="card" style="border-left: 3px solid #0d6efd;">
                                <div class="card-header" style="background: #f1f3f5; border-bottom: 1px solid #dee2e6;">
                                    <h5 class="card-title mb-0" style="color: #1a2332;">
                                        <i class="fas fa-edit me-1" style="color: #0d6efd;"></i>Datos Generales
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-store text-danger me-1"></i>Nombre *
                                                </label>
                                                <input type="text" 
                                                       name="nombre" 
                                                       id="nombre"
                                                       class="form-control @error('nombre') is-invalid @enderror" 
                                                       value="{{ old('nombre', $empresa->nombre) }}" 
                                                       placeholder="Nombre legal de la empresa"
                                                       style="border-color: #ced4da;"
                                                       required>
                                                @error('nombre')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ruc" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-id-card text-danger me-1"></i>RUC / NIT *
                                                </label>
                                                <input type="text" 
                                                       name="ruc" 
                                                       id="ruc"
                                                       class="form-control @error('ruc') is-invalid @enderror" 
                                                       value="{{ old('ruc', $empresa->ruc) }}" 
                                                       placeholder="20-12345678-9"
                                                       style="border-color: #ced4da;"
                                                       required>
                                                @error('ruc')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="direccion" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-map-marker-alt" style="color: #17a2b8;"></i> Dirección
                                                </label>
                                                <input type="text" 
                                                       name="direccion" 
                                                       id="direccion"
                                                       class="form-control" 
                                                       value="{{ old('direccion', $empresa->direccion) }}"
                                                       placeholder="Calle, número, ciudad"
                                                       style="border-color: #ced4da;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="telefono" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-phone" style="color: #28a745;"></i> Teléfono
                                                </label>
                                                <input type="text" 
                                                       name="telefono" 
                                                       id="telefono"
                                                       class="form-control" 
                                                       value="{{ old('telefono', $empresa->telefono) }}"
                                                       placeholder="+1 234 567 890"
                                                       style="border-color: #ced4da;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-envelope" style="color: #ffc107;"></i> Email
                                                </label>
                                                <input type="email" 
                                                       name="email" 
                                                       id="email"
                                                       class="form-control" 
                                                       value="{{ old('email', $empresa->email) }}"
                                                       placeholder="contacto@empresa.com"
                                                       style="border-color: #ced4da;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pagina_web" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-globe" style="color: #007bff;"></i> Sitio web
                                                </label>
                                                <input type="url" 
                                                       name="pagina_web" 
                                                       id="pagina_web"
                                                       class="form-control" 
                                                       value="{{ old('pagina_web', $empresa->pagina_web) }}"
                                                       placeholder="https://www.empresa.com"
                                                       style="border-color: #ced4da;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="igv" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-percent text-danger me-1"></i>IGV / IVA (%) *
                                                </label>
                                                <input type="number" 
                                                       step="0.01" 
                                                       name="igv" 
                                                       id="igv"
                                                       class="form-control @error('igv') is-invalid @enderror" 
                                                       value="{{ old('igv', $empresa->igv ?? 18) }}"
                                                       style="border-color: #ced4da;"
                                                       required>
                                                @error('igv')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="moneda" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-dollar-sign" style="color: #28a745;"></i> Moneda *
                                                </label>
                                                <input type="text" 
                                                       name="moneda" 
                                                       id="moneda"
                                                       class="form-control @error('moneda') is-invalid @enderror" 
                                                       value="{{ old('moneda', $empresa->moneda ?? 'PEN') }}"
                                                       placeholder="PEN, USD, EUR"
                                                       style="border-color: #ced4da;"
                                                       required>
                                                @error('moneda')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card" style="border-left: 3px solid #0d6efd;">
                                <div class="card-header" style="background: #f1f3f5; border-bottom: 1px solid #dee2e6;">
                                    <h5 class="card-title mb-0" style="color: #1a2332;">
                                        <i class="fas fa-share-alt me-1" style="color: #0d6efd;"></i>Redes Sociales
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="facebook" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fab fa-facebook" style="color: #1877f2;"></i> Facebook
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background: #e9ecef; border-color: #ced4da;">
                                                            <i class="fab fa-facebook-f" style="color: #1877f2;"></i>
                                                        </span>
                                                    </div>
                                                    <input type="url" 
                                                           name="redes_sociales[facebook]" 
                                                           id="facebook"
                                                           class="form-control" 
                                                           value="{{ old('redes_sociales.facebook', $empresa->redes_sociales['facebook'] ?? '') }}"
                                                           placeholder="https://facebook.com/tu-pagina"
                                                           style="border-color: #ced4da;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="instagram" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fab fa-instagram" style="color: #e4405f;"></i> Instagram
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background: #e9ecef; border-color: #ced4da;">
                                                            <i class="fab fa-instagram" style="color: #e4405f;"></i>
                                                        </span>
                                                    </div>
                                                    <input type="url" 
                                                           name="redes_sociales[instagram]" 
                                                           id="instagram"
                                                           class="form-control" 
                                                           value="{{ old('redes_sociales.instagram', $empresa->redes_sociales['instagram'] ?? '') }}"
                                                           placeholder="https://instagram.com/tu-perfil"
                                                           style="border-color: #ced4da;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tiktok" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fab fa-tiktok" style="color: #000000;"></i> TikTok
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background: #e9ecef; border-color: #ced4da;">
                                                            <i class="fab fa-tiktok" style="color: #000000;"></i>
                                                        </span>
                                                    </div>
                                                    <input type="url" 
                                                           name="redes_sociales[tiktok]" 
                                                           id="tiktok"
                                                           class="form-control" 
                                                           value="{{ old('redes_sociales.tiktok', $empresa->redes_sociales['tiktok'] ?? '') }}"
                                                           placeholder="https://tiktok.com/@tu-usuario"
                                                           style="border-color: #ced4da;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos PDF -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card" style="border-left: 3px solid #0d6efd;">
                                <div class="card-header" style="background: #f1f3f5; border-bottom: 1px solid #dee2e6;">
                                    <h5 class="card-title mb-0" style="color: #1a2332;">
                                        <i class="fas fa-file-pdf me-1" style="color: #dc3545;"></i>Configuración de Documentos PDF
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="firma" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-pen" style="color: #17a2b8;"></i> Firma digital
                                                </label>
                                                @if($empresa->firma)
                                                    <div class="mb-2 p-2 border rounded text-center" style="border-color: #ced4da !important; background: white;">
                                                        <img src="{{ asset('storage/'.$empresa->firma) }}" 
                                                             alt="Firma actual" 
                                                             class="img-fluid" 
                                                             style="max-height:60px;">
                                                    </div>
                                                @endif
                                                <div class="custom-file">
                                                    <input type="file" 
                                                           name="firma" 
                                                           class="custom-file-input" 
                                                           id="firmaInput"
                                                           accept="image/*">
                                                    <label class="custom-file-label" for="firmaInput" style="background: #e9ecef; border-color: #ced4da;">
                                                        {{ $empresa->firma ? 'Cambiar firma' : 'Subir firma' }}
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-info-circle" style="color: #0d6efd;"></i> Formatos: PNG, JPG (recomendado fondo transparente)
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="pie_pagina_pdf" style="color: #1a2332; font-weight: 500;">
                                                    <i class="fas fa-paragraph" style="color: #6c757d;"></i> Pie de página en PDFs
                                                </label>
                                                <textarea name="pie_pagina_pdf" 
                                                          id="pie_pagina_pdf"
                                                          class="form-control" 
                                                          rows="3"
                                                          placeholder="Ej: 'Gracias por su preferencia' | Tel: +1 234 567 890 | www.empresa.com"
                                                          style="border-color: #ced4da; resize: vertical;">{{ old('pie_pagina_pdf', $empresa->pie_pagina_pdf) }}</textarea>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-info-circle" style="color: #0d6efd;"></i> Este texto aparecerá al pie de todas las facturas y documentos PDF generados
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="float-right">
                                <a href="{{ route('dashboard') }}" class="btn" style="background: #6c757d; color: white; margin-right: 10px;">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn" style="background: #0d6efd; color: white;">
                                    <i class="fas fa-save me-1"></i>Guardar cambios
                                </button>
                            </div>
                            <div class="text-muted small mt-2">
                                <i class="fas fa-asterisk text-danger me-1"></i> Campos obligatorios
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <!-- /.card-body -->
            
            <div class="card-footer" style="background: #f1f3f5; border-top: 1px solid #dee2e6;">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color: #6c757d;">
                        <i class="fas fa-info-circle me-1" style="color: #0d6efd;"></i>
                        Última actualización: {{ $empresa->updated_at ? $empresa->updated_at->format('d/m/Y h:i:s a') : 'Nunca' }}
                    </span>
                    <span style="color: #6c757d;">
                        <i class="fas fa-user me-1" style="color: #0d6efd;"></i>
                        {{ auth()->user()->name ?? 'Usuario' }}
                    </span>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-label para inputs de archivo
    document.addEventListener('DOMContentLoaded', function() {
        // Logo
        document.getElementById('logoInput')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Seleccionar logo';
            const label = e.target.nextElementSibling;
            if (label) label.textContent = fileName;
        });

        // Firma
        document.getElementById('firmaInput')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Subir firma';
            const label = e.target.nextElementSibling;
            if (label) label.textContent = fileName;
        });
    });
</script>
@endpush