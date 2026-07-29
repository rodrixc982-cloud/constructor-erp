@extends('layouts.app')

@section('titulo', 'Dashboard')

@push('estilos')
<style>
    .small-box {
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    .small-box .inner {
        padding: 20px 24px;
        position: relative;
        z-index: 1;
    }
    .small-box .inner h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 4px;
        color: #fff;
    }
    .small-box .inner p {
        font-size: 0.9rem;
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
        font-size: 4rem;
        opacity: 0.15;
        color: #fff;
        transition: all 0.3s ease;
        z-index: 0;
    }
    .small-box:hover .icon {
        opacity: 0.25;
        transform: translateY(-50%) scale(1.1);
    }
    .small-box .small-box-footer {
        padding: 8px 24px;
        background: rgba(0,0,0,0.08);
        color: #fff;
        display: block;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }
    .small-box .small-box-footer:hover {
        background: rgba(0,0,0,0.15);
        color: #fff;
    }
    .small-box .small-box-footer i {
        transition: transform 0.2s ease;
    }
    .small-box .small-box-footer:hover i {
        transform: translateX(4px);
    }
    .chart-card {
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
    }
    .chart-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        padding: 16px 24px;
    }
    .chart-card .card-header .card-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #2d3748;
    }
    .chart-card .card-body {
        padding: 20px 24px 24px;
    }
    .summary-card {
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
        height: 100%;
    }
    .summary-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        padding: 16px 24px;
    }
    .summary-card .card-header .card-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #2d3748;
    }
    .summary-card .card-body {
        padding: 20px 24px;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .summary-item:last-child {
        border-bottom: none;
    }
    .summary-item .label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }
    .summary-item .value {
        font-size: 1rem;
        font-weight: 700;
    }
    .summary-item .value.positive { color: #28a745; }
    .summary-item .value.negative { color: #dc3545; }
    .stat-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(255,255,255,0.2);
        color: #fff;
        margin-top: 4px;
    }
    @media (max-width: 768px) {
        .small-box .inner h3 {
            font-size: 1.6rem;
        }
        .small-box .icon {
            font-size: 3rem;
        }
    }
</style>
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('contenido')
<div class="row g-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['obras_activas'] ?? 0 }}</h3>
                <p>Obras activas</p>
                <span class="stat-badge">
                    <i class="fas fa-arrow-up me-1"></i>12% vs mes anterior
                </span>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
            <a href="{{ route('obras.index') }}" class="small-box-footer">
                Ver todas <i class="fas fa-arrow-circle-right ms-1"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['obras_terminadas'] ?? 0 }}</h3>
                <p>Obras terminadas</p>
                <span class="stat-badge">
                    <i class="fas fa-arrow-up me-1"></i>8% vs mes anterior
                </span>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('obras.index') }}" class="small-box-footer">
                Ver todas <i class="fas fa-arrow-circle-right ms-1"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['clientes'] ?? 0 }}</h3>
                <p>Clientes</p>
                <span class="stat-badge">
                    <i class="fas fa-arrow-up me-1"></i>5% vs mes anterior
                </span>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ route('clientes.index') }}" class="small-box-footer">
                Ver todos <i class="fas fa-arrow-circle-right ms-1"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['presupuestos'] ?? 0 }}</h3>
                <p>Presupuestos</p>
                <span class="stat-badge">
                    <i class="fas fa-minus me-1"></i>Sin cambios
                </span>
            </div>
            <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
            <a href="{{ route('presupuestos.index') }}" class="small-box-footer">
                Ver todos <i class="fas fa-arrow-circle-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-8">
        <div class="card chart-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Utilidad mensual
                </h3>
                <div>
                    <span class="badge bg-success me-1">
                        +{{ number_format($stats['utilidad_mes'] ?? 0, 2) }}
                    </span>
                    <span class="badge bg-secondary">{{ now()->year }}</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="graficoUtilidad" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card summary-card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>Resumen del mes
                </h3>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <span class="label"><i class="fas fa-arrow-up text-success me-2"></i>Ganancias</span>
                    <span class="value positive">S/ {{ number_format($stats['ganancias_mes'] ?? 0, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="label"><i class="fas fa-arrow-down text-danger me-2"></i>Gastos</span>
                    <span class="value negative">S/ {{ number_format($stats['gastos_mes'] ?? 0, 2) }}</span>
                </div>
                <div class="summary-item" style="border-bottom: 2px solid #e9ecef; padding-bottom: 16px;">
                    <span class="label" style="font-weight: 700;">
                        <i class="fas fa-coins me-2 text-warning"></i>Utilidad
                    </span>
                    <span class="value" style="font-size: 1.2rem; color: #28a745;">
                        S/ {{ number_format($stats['utilidad_mes'] ?? 0, 2) }}
                    </span>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Presupuestos aprobados</small>
                        <small class="fw-bold">{{ $stats['presupuestos_aprobados'] ?? 0 }}</small>
                    </div>
                    <div class="progress mb-2" style="height: 6px; border-radius: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ min(100, (($stats['presupuestos_aprobados'] ?? 0) / max(1, $stats['presupuestos'] ?? 1)) * 100) }}%; border-radius: 4px;"></div>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Obras activas</small>
                        <small class="fw-bold text-primary">{{ $stats['obras_activas'] ?? 0 }}</small>
                    </div>
                    <div class="progress mb-2" style="height: 6px; border-radius: 4px;">
                        <div class="progress-bar bg-primary" style="width: {{ min(100, (($stats['obras_activas'] ?? 0) / max(1, ($stats['obras_activas'] ?? 0) + ($stats['obras_terminadas'] ?? 0))) * 100) }}%; border-radius: 4px;"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Proveedores activos</small>
                        <small class="fw-bold text-info">{{ $stats['proveedores'] ?? 0 }}</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <small class="text-muted">
                        <i class="far fa-clock me-1"></i>
                        Actualizado: {{ now()->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('graficoUtilidad');
        
        if (ctx) {
            var graficoData = <?php echo json_encode($graficoUtilidadMensual ?? ['labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'], 'data' => [0, 0, 0, 0, 0, 0]], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
            var labels = graficoData.labels || ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
            var data = graficoData.data || [0, 0, 0, 0, 0, 0];

            var gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(42, 82, 152, 0.25)');
            gradient.addColorStop(1, 'rgba(42, 82, 152, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Utilidad (S/)',
                        data: data,
                        borderColor: '#2a5298',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2a5298',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.95)',
                            titleColor: '#333',
                            bodyColor: '#2a5298',
                            borderColor: '#e9ecef',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'S/ ' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'S/ ' + value.toFixed(0);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Animar números
        document.querySelectorAll('.small-box .inner h3').forEach(function(el) {
            var final = parseInt(el.textContent) || 0;
            if (final === 0) return;
            
            var current = 0;
            var duration = 1000;
            var steps = 40;
            var increment = final / steps;
            var step = 0;
            
            var timer = setInterval(function() {
                step++;
                current += increment;
                if (step >= steps) {
                    current = final;
                    clearInterval(timer);
                }
                el.textContent = Math.round(current);
            }, duration / steps);
        });
    });
</script>
@endpush