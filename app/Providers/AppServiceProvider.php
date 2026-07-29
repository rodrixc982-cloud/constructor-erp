<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProveedorRepositoryInterface;
use App\Repositories\ProveedorRepository;
use App\Repositories\Contracts\ClienteRepositoryInterface;
use App\Repositories\ClienteRepository;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Repositories\MaterialRepository;
use App\Repositories\Contracts\CategoriaRepositoryInterface;
use App\Repositories\CategoriaRepository;
use App\Repositories\Contracts\AlmacenRepositoryInterface;
use App\Repositories\AlmacenRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar repositorios
        $this->app->bind(
            ProveedorRepositoryInterface::class,
            ProveedorRepository::class
        );

        $this->app->bind(
            ClienteRepositoryInterface::class,
            ClienteRepository::class
        );

        $this->app->bind(
            MaterialRepositoryInterface::class,
            MaterialRepository::class
        );

        $this->app->bind(
            CategoriaRepositoryInterface::class,
            CategoriaRepository::class
        );

        $this->app->bind(
            AlmacenRepositoryInterface::class,
            AlmacenRepository::class
        );
    }

    public function boot(): void
    {
        // Directivas Blade personalizadas
        Blade::directive('moneda', function ($amount) {
            return "<?php echo 'S/ ' . number_format($amount, 2, '.', ','); ?>";
        });

        Blade::directive('estado', function ($estado) {
            return "<?php 
                if ($estado) {
                    echo '<span class=\"badge bg-success\">Activo</span>';
                } else {
                    echo '<span class=\"badge bg-secondary\">Inactivo</span>';
                }
            ?>";
        });

        // Directiva para obras
        Blade::directive('estadoObra', function ($estado) {
            $estados = [
                'cotizacion' => 'bg-secondary',
                'aprobado' => 'bg-primary',
                'en_progreso' => 'bg-warning',
                'completado' => 'bg-success',
                'cancelado' => 'bg-danger',
                'pausado' => 'bg-info',
            ];
            
            $colors = json_encode($estados);
            return "<?php 
                \$estados = $colors;
                \$color = \$estados[\$estado] ?? 'bg-secondary';
                \$texto = ucfirst(str_replace('_', ' ', \$estado));
                echo '<span class=\"badge ' . \$color . '\">' . \$texto . '</span>';
            ?>";
        });
    }
}