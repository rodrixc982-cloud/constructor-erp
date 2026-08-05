<!DOCTYPE html>
<html lang="es" data-bs-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('titulo', 'Dashboard')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.0/sweetalert2.min.css">
    @stack('estilos')

    <style>
        /* ===== Variables ===== */
        :root {
            --sidebar-width: 250px;
            --navbar-height: 56px;
            --transition-speed: 0.3s;
        }

        /* ===== Tema Oscuro ===== */
        [data-bs-theme="dark"] {
            --bs-body-bg: #0f1117;
            --bs-body-color: #e8eaed;
        }
        [data-bs-theme="dark"] body {
            background-color: #0f1117;
            color: #e8eaed;
        }
        [data-bs-theme="dark"] .content-wrapper {
            background: linear-gradient(135deg, #0f1117 0%, #1a1d2e 100%);
        }
        [data-bs-theme="dark"] .card {
            background-color: #1e2128;
            border-color: #2d3038;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }
        [data-bs-theme="dark"] .card-header {
            background-color: #1a1d24;
            border-bottom-color: #2d3038;
        }
        [data-bs-theme="dark"] .table {
            color: #e8eaed;
        }
        [data-bs-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255,255,255,0.03);
        }
        [data-bs-theme="dark"] .table-bordered {
            border-color: #2d3038;
        }
        [data-bs-theme="dark"] .table-bordered td,
        [data-bs-theme="dark"] .table-bordered th {
            border-color: #2d3038;
        }
        [data-bs-theme="dark"] .modal-content {
            background-color: #1e2128;
            border-color: #2d3038;
        }
        [data-bs-theme="dark"] .modal-header,
        [data-bs-theme="dark"] .modal-footer {
            border-color: #2d3038;
        }
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #0f1117;
            border-color: #2d3038;
            color: #e8eaed;
        }
        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #0f1117;
            color: #e8eaed;
        }
        [data-bs-theme="dark"] .breadcrumb-item a {
            color: #8ab4f8;
        }
        [data-bs-theme="dark"] .breadcrumb-item.active {
            color: #9aa0a6;
        }
        [data-bs-theme="dark"] .small-box {
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        /* ===== Sidebar Mejorado ===== */
        .main-sidebar {
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            box-shadow: 2px 0 20px rgba(0,0,0,0.3);
        }
        [data-bs-theme="dark"] .main-sidebar {
            background: linear-gradient(180deg, #0f1117 0%, #1a1d2e 50%, #0f3460 100%);
        }
        .main-sidebar .brand-link {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .main-sidebar .brand-link:hover {
            background: rgba(0,0,0,0.25);
        }
        .main-sidebar .brand-link .brand-text {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .main-sidebar .brand-link i {
            font-size: 1.8rem;
            color: #667eea;
            -webkit-text-fill-color: #667eea;
        }
        .sidebar .nav-header {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.4);
            padding: 12px 20px 6px;
            font-weight: 700;
        }
        .sidebar .nav-link {
            padding: 10px 20px;
            margin: 2px 8px;
            border-radius: 10px;
            transition: all 0.3s ease;
            color: rgba(255,255,255,0.75);
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .sidebar .nav-link.active i {
            color: #fff;
        }
        .sidebar .nav-link i {
            color: rgba(255,255,255,0.5);
            transition: all 0.3s ease;
        }
        .sidebar .nav-link.active i {
            color: #fff;
        }
        .sidebar .nav-link:hover i {
            color: #fff;
        }

        /* ===== Navbar ===== */
        .main-header.navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 20px rgba(102, 126, 234, 0.3);
            border: none;
        }
        [data-bs-theme="dark"] .main-header.navbar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }
        .main-header.navbar .nav-link {
            color: rgba(255,255,255,0.85) !important;
            transition: all 0.3s ease;
        }
        .main-header.navbar .nav-link:hover {
            color: #fff !important;
            transform: scale(1.1);
        }
        .main-header.navbar .breadcrumb-item a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
        }
        .main-header.navbar .breadcrumb-item a:hover {
            color: #fff;
        }
        .main-header.navbar .breadcrumb-item.active {
            color: rgba(255,255,255,0.6);
        }
        .main-header.navbar .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.4);
        }
        .main-header.navbar .dropdown-menu {
            background: #1e2128;
            border: 1px solid #2d3038;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .main-header.navbar .dropdown-menu .dropdown-item {
            color: #e8eaed;
            transition: all 0.2s ease;
        }
        .main-header.navbar .dropdown-menu .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.15);
            color: #667eea;
        }
        .main-header.navbar .dropdown-menu .dropdown-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }
        .main-header.navbar .dropdown-toggle::after {
            display: none;
        }
        .main-header.navbar .badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0.6rem;
            padding: 3px 6px;
            border-radius: 50%;
            background: #dc3545;
            border: 2px solid #667eea;
        }
        [data-bs-theme="dark"] .main-header.navbar .badge {
            border-color: #1a1a2e;
        }
        .main-header.navbar .nav-link .fas.fa-bell {
            font-size: 1.2rem;
        }
        .main-header.navbar .nav-link .fas.fa-moon {
            font-size: 1.1rem;
        }

        /* ===== Breadcrumbs ===== */
        .content-header {
            padding: 20px 24px 0;
        }
        .content-header h1 {
            font-weight: 700;
            font-size: 1.8rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        [data-bs-theme="dark"] .content-header h1 {
            background: linear-gradient(135deg, #8ab4f8 0%, #667eea 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== Footer ===== */
        .main-footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            padding: 16px 24px;
        }
        [data-bs-theme="dark"] .main-footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        .main-footer strong {
            font-weight: 600;
        }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        [data-bs-theme="dark"] ::-webkit-scrollbar-track {
            background: #1a1d24;
        }

        /* ===== Cards ===== */
        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        }
        .card-header {
            border-radius: 16px 16px 0 0;
            padding: 16px 24px;
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .card-header .card-title {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .card-body {
            padding: 20px 24px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .main-sidebar {
                width: 220px;
            }
            .content-header h1 {
                font-size: 1.4rem;
            }
            .card-body {
                padding: 16px;
            }
            .card-header {
                padding: 12px 16px;
            }
        }

        /* ===== Animación de carga ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .content-wrapper {
            animation: fadeInUp 0.5s ease;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <ol class="breadcrumb bg-transparent mb-0 ms-2">
                    @yield('breadcrumbs')
                </ol>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto align-items-center gap-2">
            <li class="nav-item position-relative">
                <a class="nav-link" href="{{ route('notificaciones.index') }}" title="Notificaciones">
                    <i class="fas fa-bell"></i>
                    @if(auth()->user()->unreadNotifications->count())
                    <span class="badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link theme-toggle" id="btnToggleTema" href="#" title="Cambiar tema">
                    <i class="fas fa-moon"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" href="#">
                    <img src="{{ auth()->user()->avatarUrl() }}" class="img-circle elevation-2" style="width:32px;height:32px;object-fit:cover;border:2px solid rgba(255,255,255,0.3);border-radius:50%;" alt="avatar">
                    <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user me-2 text-primary"></i>Mi perfil
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <i class="fas fa-hard-hat brand-image ms-3 mt-2"></i>
            <span class="brand-text ms-2">ConstruTianz ERP</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">EMPRESA</li>
                    @can('empresa.ver')
                    <li class="nav-item">
                        <a href="{{ route('empresa.edit') }}" class="nav-link {{ request()->routeIs('empresa.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Datos de la empresa</p>
                        </a>
                    </li>
                    @endcan

                    <li class="nav-header">CATÁLOGOS</li>
                    @can('clientes.ver')
                    <li class="nav-item">
                        <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-address-book"></i><p>Clientes</p>
                        </a>
                    </li>
                    @endcan
                    @can('proveedores.ver')
                    <li class="nav-item">
                        <a href="{{ route('proveedores.index') }}" class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i><p>Proveedores</p>
                        </a>
                    </li>
                    @endcan
                    @can('categorias.ver')
                    <li class="nav-item">
                        <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i><p>Categorías</p>
                        </a>
                    </li>
                    @endcan
                    @can('materiales.ver')
                    <li class="nav-item">
                        <a href="{{ route('materiales.index') }}" class="nav-link {{ request()->routeIs('materiales.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i><p>Materiales</p>
                        </a>
                    </li>
                    @endcan
                    @can('inventario.ver')
                    <li class="nav-item">
                        <a href="{{ route('inventario.index') }}" class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i><p>Inventario</p>
                        </a>
                    </li>
                    @endcan

                    <li class="nav-header">RECURSOS DE OBRA</li>
                    @can('mano_obra.ver')
                    <li class="nav-item">
                        <a href="{{ route('mano-obra.index') }}" class="nav-link {{ request()->routeIs('mano-obra.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hard-hat"></i><p>Mano de obra</p>
                        </a>
                    </li>
                    @endcan
                    @can('equipos.ver')
                    <li class="nav-item">
                        <a href="{{ route('equipos.index') }}" class="nav-link {{ request()->routeIs('equipos.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tools"></i><p>Equipos</p>
                        </a>
                    </li>
                    @endcan
                    @can('maquinaria.ver')
                    <li class="nav-item">
                        <a href="{{ route('maquinaria.index') }}" class="nav-link {{ request()->routeIs('maquinaria.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck-monster"></i><p>Maquinaria</p>
                        </a>
                    </li>
                    @endcan
                    @can('obras.ver')
                    <li class="nav-item">
                        <a href="{{ route('obras.index') }}" class="nav-link {{ request()->routeIs('obras.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i><p>Obras</p>
                        </a>
                    </li>
                    @endcan

                    <li class="nav-header">PRESUPUESTOS</li>
                    @can('apu.ver')
                    <li class="nav-item">
                        <a href="{{ route('apu.index') }}" class="nav-link {{ request()->routeIs('apu.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calculator"></i><p>APU</p>
                        </a>
                    </li>
                    @endcan
                    @can('presupuestos.ver')
                    <li class="nav-item">
                        <a href="{{ route('presupuestos.index') }}" class="nav-link {{ request()->routeIs('presupuestos.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i><p>Presupuestos</p>
                        </a>
                    </li>
                    @endcan
                    @can('calculadora.ver')
                    <li class="nav-item">
                        <a href="{{ route('calculadora.index') }}" class="nav-link {{ request()->routeIs('calculadora.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-square-root-alt"></i><p>Calculadora</p>
                        </a>
                    </li>
                    @endcan

                    <li class="nav-header">ADMINISTRACIÓN</li>
                    @can('compras.ver')
                    <li class="nav-item">
                        <a href="{{ route('compras.index') }}" class="nav-link {{ request()->routeIs('compras.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i><p>Compras</p>
                        </a>
                    </li>
                    @endcan
                    @can('caja.ver')
                    <li class="nav-item">
                        <a href="{{ route('caja.index') }}" class="nav-link {{ request()->routeIs('caja.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i><p>Caja</p>
                        </a>
                    </li>
                    @endcan
                    @can('facturacion.ver')
                    <li class="nav-item">
                        <a href="{{ route('facturacion.index') }}" class="nav-link {{ request()->routeIs('facturacion.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i><p>Facturación</p>
                        </a>
                    </li>
                    @endcan
                    @can('calendario.ver')
                    <li class="nav-item">
                        <a href="{{ route('calendario.index') }}" class="nav-link {{ request()->routeIs('calendario.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i><p>Calendario</p>
                        </a>
                    </li>
                    @endcan
                    @can('reportes.ver')
                    <li class="nav-item">
                        <a href="{{ route('reportes.index') }}" class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i><p>Reportes</p>
                        </a>
                    </li>
                    @endcan
                    @can('auditoria.ver')
                    <li class="nav-item">
                        <a href="{{ route('auditoria.index') }}" class="nav-link {{ request()->routeIs('auditoria.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i><p>Auditoría</p>
                        </a>
                    </li>
                    @endcan
                    @can('configuracion.ver')
                    <li class="nav-item">
                        <a href="{{ route('configuracion.edit') }}" class="nav-link {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i><p>Configuración</p>
                        </a>
                    </li>
                    @endcan

                    @role('Super Administrador')
                    <li class="nav-header">USUARIOS</li>
                    <li class="nav-item">
                        <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Usuarios y Roles</p>
                        </a>
                    </li>
                    @endrole
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Contenido --}}
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('titulo', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <span class="text-muted" id="reloj"></span>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @yield('contenido')
            </div>
        </section>
    </div>

    <!-- ===== FOOTER MODIFICADO ===== -->
    <footer class="main-footer">
        <div class="d-flex justify-content-between align-items-center">
            <span>
                <strong>&copy; {{ date('Y') }} Rodrixc Tianz.</strong> Todos los derechos reservados.
            </span>
            <span class="text-muted" style="font-size:0.85rem;">
                <i class="fas fa-code me-1"></i> Versión 2.0
            </span>
        </div>
    </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.0/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>

<script>
    $(document).ready(function() {
        // ===== Reloj en tiempo real =====
        function actualizarReloj() {
            const ahora = new Date();
            const opciones = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            $('#reloj').text(ahora.toLocaleTimeString('es-PE', opciones));
        }
        actualizarReloj();
        setInterval(actualizarReloj, 1000);

        // ===== Toggle de tema =====
        $('#btnToggleTema').on('click', function(e) {
            e.preventDefault();
            const html = document.documentElement;
            const actual = html.getAttribute('data-bs-theme');
            const nuevo = actual === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', nuevo);

            // Cambiar ícono
            $(this).find('i').removeClass('fa-moon fa-sun').addClass(nuevo === 'dark' ? 'fa-sun' : 'fa-moon');

            $.ajax({
                url: '{{ route('theme.toggle') }}',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: JSON.stringify({ theme: nuevo }),
            });
        });

        // ===== Mostrar notificación de sesión =====
        @if(session('status'))
            Swal.fire({
                icon: 'success',
                title: '{{ session('status') }}',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
            });
        @endif

        // ===== Mostrar errores de validación en toast =====
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                text: '{{ $errors->first() }}',
                toast: true,
                position: 'top-end',
                timer: 5000,
                showConfirmButton: false,
                timerProgressBar: true,
            });
        @endif

        // ===== Activar tooltips =====
        $('[data-bs-toggle="tooltip"]').tooltip();

        // ===== Activar popovers =====
        $('[data-bs-toggle="popover"]').popover();

        // ===== Cerrar modales con ESC =====
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.modal.show').modal('hide');
            }
        });
    });

    // ===== Función global para manejar errores AJAX =====
    window.handleAjaxError = function(xhr) {
        let mensaje = 'Ocurrió un error inesperado.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            mensaje = xhr.responseJSON.message;
        } else if (xhr.responseJSON && xhr.responseJSON.mensaje) {
            mensaje = xhr.responseJSON.mensaje;
        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
            const errors = xhr.responseJSON.errors;
            const firstError = Object.values(errors)[0];
            if (firstError && firstError[0]) {
                mensaje = firstError[0];
            }
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: mensaje,
            toast: true,
            position: 'top-end',
            timer: 5000,
            showConfirmButton: false,
            timerProgressBar: true,
        });
    };
</script>
@stack('scripts')
</body>
</html>