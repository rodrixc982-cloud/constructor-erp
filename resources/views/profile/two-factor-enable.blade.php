@extends('layouts.app')

@section('titulo', 'Activar 2FA')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Escanea el código QR</h3></div>
            <div class="card-body text-center">
                <p class="text-muted small">Usa Google Authenticator, Authy o similar para escanear este código.</p>
                {!! QrCode::size(200)->generate($qrCodeUrl) !!}
                <p class="mt-2 small text-muted">Clave manual: <code>{{ $secreto }}</code></p>

                <form method="POST" action="{{ route('two-factor.confirm') }}" class="mt-3">
                    @csrf
                    <input type="text" name="code" maxlength="6" class="form-control text-center mb-2 @error('code') is-invalid @enderror" placeholder="Código de 6 dígitos" required>
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <button class="btn btn-primary w-100">Confirmar y activar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
