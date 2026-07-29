<?php $__env->startSection('titulo', 'Calculadora Inteligente'); ?>
<?php $__env->startSection('breadcrumbs'); ?><li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li><li class="breadcrumb-item active">Calculadora</li><?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="alert alert-info"><i class="fas fa-info-circle me-1"></i>Los resultados son estimados de referencia. Para el presupuesto definitivo usa el módulo de APU con los metrados reales del expediente técnico.</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Muro de albañilería</h3></div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label">Área (m²)</label><input type="number" step="0.01" id="muro_area" class="form-control"></div>
                <button class="btn btn-primary btn-sm" onclick="calcularMuro()">Calcular</button>
                <div class="mt-3" id="resultadoMuro"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Concreto (losa / columna)</h3></div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label">Volumen (m³)</label><input type="number" step="0.01" id="concreto_volumen" class="form-control"></div>
                <button class="btn btn-primary btn-sm" onclick="calcularConcreto()">Calcular</button>
                <div class="mt-3" id="resultadoConcreto"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Acero de refuerzo</h3></div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label">Volumen de concreto (m³)</label><input type="number" step="0.01" id="acero_volumen" class="form-control"></div>
                <div class="mb-2"><label class="form-label">Elemento</label>
                    <select id="acero_elemento" class="form-select">
                        <option value="zapata">Zapata</option><option value="columna">Columna</option><option value="viga">Viga</option><option value="losa">Losa</option><option value="muro">Muro</option>
                    </select>
                </div>
                <button class="btn btn-primary btn-sm" onclick="calcularAcero()">Calcular</button>
                <div class="mt-3" id="resultadoAcero"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Pintura</h3></div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label">Área a pintar (m²)</label><input type="number" step="0.01" id="pintura_area" class="form-control"></div>
                <div class="mb-2"><label class="form-label">N° de manos</label><input type="number" id="pintura_manos" class="form-control" value="2"></div>
                <button class="btn btn-primary btn-sm" onclick="calcularPintura()">Calcular</button>
                <div class="mt-3" id="resultadoPintura"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Cerámica / porcelanato</h3></div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label">Área (m²)</label><input type="number" step="0.01" id="ceramica_area" class="form-control"></div>
                <button class="btn btn-primary btn-sm" onclick="calcularCeramica()">Calcular</button>
                <div class="mt-3" id="resultadoCeramica"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Construcción completa (anteproyecto)</h3></div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label">Área por piso (m²)</label><input type="number" step="0.01" id="cc_area" class="form-control"></div>
                <div class="mb-2"><label class="form-label">N° de pisos</label><input type="number" id="cc_pisos" class="form-control" value="1"></div>
                <div class="mb-2"><label class="form-label">Tipo de acabado</label>
                    <select id="cc_tipo" class="form-select"><option value="economica">Económica</option><option value="estandar" selected>Estándar</option><option value="premium">Premium</option></select>
                </div>
                <button class="btn btn-primary btn-sm" onclick="calcularCompleta()">Calcular</button>
                <div class="mt-3" id="resultadoCompleta"></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function pintarResultado(destino, resultado) {
    const filas = Object.entries(resultado).filter(([k]) => k !== 'nota')
        .map(([k, v]) => `<tr><td>${k.replaceAll('_', ' ')}</td><td class="text-end fw-bold">${v}</td></tr>`).join('');
    document.getElementById(destino).innerHTML = `<table class="table table-sm mb-0">${filas}</table>` + (resultado.nota ? `<small class="text-muted">${resultado.nota}</small>` : '');
}

function calc(url, body, destino) {
    fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify(body) })
        .then(r => r.json()).then(({ data }) => pintarResultado(destino, data));
}

const calcularMuro = () => calc('<?php echo e(route('calculadora.muro')); ?>', { area: document.getElementById('muro_area').value }, 'resultadoMuro');
const calcularConcreto = () => calc('<?php echo e(route('calculadora.concreto')); ?>', { volumen: document.getElementById('concreto_volumen').value }, 'resultadoConcreto');
const calcularAcero = () => calc('<?php echo e(route('calculadora.acero')); ?>', { volumen: document.getElementById('acero_volumen').value, elemento: document.getElementById('acero_elemento').value }, 'resultadoAcero');
const calcularPintura = () => calc('<?php echo e(route('calculadora.pintura')); ?>', { area: document.getElementById('pintura_area').value, manos: document.getElementById('pintura_manos').value }, 'resultadoPintura');
const calcularCeramica = () => calc('<?php echo e(route('calculadora.ceramica')); ?>', { area: document.getElementById('ceramica_area').value }, 'resultadoCeramica');
const calcularCompleta = () => calc('<?php echo e(route('calculadora.completa')); ?>', { area_m2: document.getElementById('cc_area').value, pisos: document.getElementById('cc_pisos').value, tipo: document.getElementById('cc_tipo').value }, 'resultadoCompleta');
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/calculadora/index.blade.php ENDPATH**/ ?>