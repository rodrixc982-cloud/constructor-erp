<?php $__env->startSection('titulo', 'Reportes'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .report-nav {
        border-radius: 16px;
        background: #f8f9fa;
        padding: 8px;
        border: 1px solid #e9ecef;
        flex-wrap: wrap;
    }
    [data-bs-theme="dark"] .report-nav {
        background: #1e2128;
        border-color: #2d3038;
    }
    .report-nav .nav-link {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        background: transparent;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .report-nav .nav-link:hover {
        background: rgba(102, 126, 234, 0.08);
        color: #667eea;
        transform: translateY(-2px);
    }
    .report-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .report-nav .nav-link.active i {
        color: #fff;
    }
    .report-nav .nav-link i {
        font-size: 1rem;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .report-nav .nav-link.active i {
        color: #fff;
    }
    .report-card {
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: none;
        overflow: hidden;
    }
    .report-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        padding: 16px 24px;
    }
    .report-card .card-body {
        padding: 20px 24px;
    }
    .report-card .card-header .card-title {
        font-weight: 600;
        font-size: 1.1rem;
    }
    .btn-export {
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 500;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    .btn-export:hover {
        transform: translateY(-2px);
    }
    .btn-export-pdf {
        background: #dc3545;
        color: #fff;
        border: none;
    }
    .btn-export-pdf:hover {
        background: #c82333;
        color: #fff;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }
    .btn-export-excel {
        background: #28a745;
        color: #fff;
        border: none;
    }
    .btn-export-excel:hover {
        background: #218838;
        color: #fff;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-top: 20px;
    }
    .chart-container canvas {
        width: 100% !important;
        height: 100% !important;
    }
    .reporte-tabla {
        margin-top: 20px;
    }
    .reporte-tabla table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    .reporte-tabla th {
        background: #f8f9fa;
        font-weight: 600;
        padding: 12px 16px;
        text-align: left;
        border-bottom: 2px solid #e9ecef;
    }
    [data-bs-theme="dark"] .reporte-tabla th {
        background: #1e2128;
        border-bottom-color: #2d3038;
    }
    .reporte-tabla td {
        padding: 10px 16px;
        border-bottom: 1px solid #e9ecef;
    }
    [data-bs-theme="dark"] .reporte-tabla td {
        border-bottom-color: #2d3038;
    }
    .reporte-tabla tr:hover td {
        background: rgba(102, 126, 234, 0.04);
    }
    .stat-badge-report {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .stat-badge-report.primary {
        background: rgba(102, 126, 234, 0.15);
        color: #667eea;
    }
    .stat-badge-report.success {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }
    .stat-badge-report.warning {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }
    .stat-badge-report.danger {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }
    .stat-badge-report.info {
        background: rgba(23, 162, 184, 0.15);
        color: #17a2b8;
    }
    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 60px 0;
    }
    .loading-spinner .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e9ecef;
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .empty-state {
        text-align: center;
        padding: 60px 0;
    }
    .empty-state i {
        font-size: 4rem;
        color: #e9ecef;
        margin-bottom: 16px;
    }
    .empty-state h5 {
        color: #6c757d;
        font-weight: 500;
    }
    @media (max-width: 768px) {
        .report-nav .nav-link {
            padding: 8px 14px;
            font-size: 0.85rem;
        }
        .report-nav .nav-link span {
            display: none;
        }
        .chart-container {
            height: 200px;
        }
        .btn-export {
            padding: 6px 14px;
            font-size: 0.75rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Reportes</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>

<div class="report-nav nav mb-3" role="tablist">
    <button class="nav-link active" data-reporte="por-cliente">
        <i class="fas fa-users"></i>
        <span>Por cliente</span>
    </button>
    <button class="nav-link" data-reporte="por-proyecto">
        <i class="fas fa-project-diagram"></i>
        <span>Por proyecto</span>
    </button>
    <button class="nav-link" data-reporte="por-material">
        <i class="fas fa-boxes"></i>
        <span>Por material</span>
    </button>
    <button class="nav-link" data-reporte="utilidad-mensual">
        <i class="fas fa-chart-line"></i>
        <span>Utilidad mensual</span>
    </button>
    <button class="nav-link" data-reporte="por-estado">
        <i class="fas fa-circle"></i>
        <span>Por estado</span>
    </button>
</div>


<div class="card report-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0" id="tituloReporte">
            <i class="fas fa-file-alt me-2 text-primary"></i>Reporte por cliente
        </h3>
        <div class="d-flex gap-2">
            <button class="btn btn-export btn-export-pdf" id="btnExportarPdf">
                <i class="fas fa-file-pdf me-1"></i>PDF
            </button>
            <button class="btn btn-export btn-export-excel" id="btnExportarExcel">
                <i class="fas fa-file-excel me-1"></i>Excel
            </button>
        </div>
    </div>
    <div class="card-body" id="contenidoReporte">
        <div class="loading-spinner">
            <div class="spinner"></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let chartInstance = null;
    let currentReporte = 'por-cliente';

    const rutas = {
        'por-cliente': '<?php echo e(route("reportes.por-cliente")); ?>',
        'por-proyecto': '<?php echo e(route("reportes.por-proyecto")); ?>',
        'por-material': '<?php echo e(route("reportes.por-material")); ?>',
        'utilidad-mensual': '<?php echo e(route("reportes.utilidad-mensual")); ?>',
        'por-estado': '<?php echo e(route("reportes.por-estado")); ?>',
    };

    const titulos = {
        'por-cliente': 'Reporte por cliente',
        'por-proyecto': 'Reporte por proyecto',
        'por-material': 'Reporte por material',
        'utilidad-mensual': 'Utilidad mensual',
        'por-estado': 'Presupuestos por estado',
    };

    const iconos = {
        'por-cliente': 'fa-users',
        'por-proyecto': 'fa-project-diagram',
        'por-material': 'fa-boxes',
        'utilidad-mensual': 'fa-chart-line',
        'por-estado': 'fa-circle',
    };

    const colores = {
        'por-cliente': ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#fee140', '#a8edea', '#f5576c', '#ffecd2'],
        'por-proyecto': ['#11998e', '#38ef7d', '#f093fb', '#4facfe', '#fa709a', '#fee140', '#43e97b', '#a8edea', '#f5576c', '#764ba2'],
        'por-material': ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#fee140', '#a8edea', '#f5576c', '#ffecd2'],
        'utilidad-mensual': ['#667eea'],
        'por-estado': ['#6c757d', '#28a745', '#dc3545', '#ffc107', '#17a2b8'],
    };

    function cargarReporte(clave) {
        currentReporte = clave;
        const titulo = titulos[clave] || 'Reporte';
        const icono = iconos[clave] || 'fa-file-alt';
        
        $('#tituloReporte').html(`<i class="fas ${icono} me-2 text-primary"></i>${titulo}`);
        
        // Mostrar loading
        $('#contenidoReporte').html(`
            <div class="loading-spinner">
                <div class="spinner"></div>
            </div>
        `);

        // Actualizar botones de exportación
        const urlBase = rutas[clave];
        $('#btnExportarPdf').off('click').on('click', function() {
            window.open(urlBase + '?formato=pdf', '_blank');
        });
        $('#btnExportarExcel').off('click').on('click', function() {
            window.open(urlBase + '?formato=excel', '_blank');
        });

        // Cargar datos del reporte
        $.ajax({
            url: rutas[clave],
            type: 'GET',
            success: function(html) {
                $('#contenidoReporte').html(html);
                
                // Si el reporte tiene datos, intentar generar gráfico
                setTimeout(function() {
                    generarGrafico(clave);
                }, 100);
            },
            error: function() {
                $('#contenidoReporte').html(`
                    <div class="empty-state">
                        <i class="fas fa-exclamation-circle text-danger"></i>
                        <h5>Error al cargar el reporte</h5>
                        <p class="text-muted">Por favor, intenta nuevamente.</p>
                    </div>
                `);
            }
        });
    }

    function generarGrafico(clave) {
        // Destruir gráfico anterior si existe
        if (chartInstance) {
            chartInstance.destroy();
            chartInstance = null;
        }

        // Buscar datos en la tabla del reporte
        const tabla = document.querySelector('#contenidoReporte table');
        if (!tabla) return;

        const tbody = tabla.querySelector('tbody');
        if (!tbody || tbody.children.length === 0) {
            mostrarSinDatos();
            return;
        }

        const filas = tbody.querySelectorAll('tr');
        const labels = [];
        const data = [];
        const coloresUsar = colores[clave] || ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#fee140', '#a8edea', '#f5576c', '#ffecd2'];

        filas.forEach(function(fila, index) {
            const celdas = fila.querySelectorAll('td');
            if (celdas.length >= 2) {
                // Intentar obtener el primer valor como label y el segundo como dato
                let label = celdas[0].textContent.trim();
                let value = parseFloat(celdas[1].textContent.replace(/[^0-9.-]+/g, '').trim()) || 0;
                labels.push(label);
                data.push(value);
            }
        });

        if (labels.length === 0 || data.length === 0) {
            mostrarSinDatos();
            return;
        }

        // Determinar tipo de gráfico según el reporte
        let tipo = 'bar';
        let titulo = titulos[clave] || 'Reporte';

        if (clave === 'utilidad-mensual') {
            tipo = 'line';
        } else if (clave === 'por-estado') {
            tipo = 'doughnut';
        } else if (clave === 'por-cliente') {
            tipo = 'bar';
        } else {
            tipo = 'bar';
        }

        // Crear contenedor del gráfico
        const container = document.createElement('div');
        container.className = 'chart-container';
        container.innerHTML = `<canvas id="graficoReporte"></canvas>`;
        
        // Insertar después de la tabla
        const contenido = document.getElementById('contenidoReporte');
        contenido.appendChild(container);

        // Configurar el gráfico
        const ctx = document.getElementById('graficoReporte').getContext('2d');

        let config = {
            type: tipo,
            data: {
                labels: labels,
                datasets: [{
                    label: titulo,
                    data: data,
                    backgroundColor: tipo === 'doughnut' ? coloresUsar.slice(0, labels.length) : coloresUsar.slice(0, labels.length).map(c => c + '80'),
                    borderColor: tipo === 'doughnut' ? coloresUsar.slice(0, labels.length) : coloresUsar.slice(0, labels.length),
                    borderWidth: 1,
                    tension: 0.4,
                    fill: tipo === 'line' ? true : false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)',
                        titleColor: '#333',
                        bodyColor: '#667eea',
                        borderColor: '#e9ecef',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed.y || context.parsed;
                                if (clave === 'por-cliente' || clave === 'por-proyecto') {
                                    return label + ': ' + value;
                                }
                                return label + ': S/ ' + value.toFixed(2);
                            }
                        }
                    }
                },
                scales: tipo !== 'doughnut' ? {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                if (clave === 'por-cliente' || clave === 'por-proyecto' || clave === 'por-estado') {
                                    return value;
                                }
                                return 'S/ ' + value.toFixed(0);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                } : undefined,
                cutout: tipo === 'doughnut' ? '60%' : undefined,
            }
        };

        chartInstance = new Chart(ctx, config);
    }

    function mostrarSinDatos() {
        const contenido = document.getElementById('contenidoReporte');
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'empty-state';
        emptyDiv.innerHTML = `
            <i class="fas fa-chart-pie text-muted"></i>
            <h5>No hay datos disponibles</h5>
            <p class="text-muted">No se encontraron datos para este reporte.</p>
        `;
        contenido.appendChild(emptyDiv);
    }

    // Navegación de reportes
    $('.report-nav .nav-link').on('click', function() {
        const clave = $(this).data('reporte');
        if (!clave) return;

        $('.report-nav .nav-link').removeClass('active');
        $(this).addClass('active');

        cargarReporte(clave);
    });

    // Cargar el primer reporte
    cargarReporte('por-cliente');
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\constructor-erp\resources\views/reportes/index.blade.php ENDPATH**/ ?>