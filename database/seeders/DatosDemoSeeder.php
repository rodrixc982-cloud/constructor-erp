<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Empresa;
use Illuminate\Database\Seeder;

class DatosDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear empresa por defecto
        Empresa::firstOrCreate(
            ['ruc' => '20000000001'],
            [
                'nombre' => 'Mi Empresa Constructora S.A.C.',
                'direccion' => 'Av. Principal 123',
                'telefono' => '999999999',
                'email' => 'contacto@miempresa.test',
                'igv' => 18,
                'moneda' => 'PEN',
                'pie_pagina_pdf' => 'Documento generado por Sistema ERP',
            ]
        );

        // Crear almacén principal (usando el modelo)
        Almacen::firstOrCreate(
            ['nombre' => 'Almacén Principal'],
            [
                'ubicacion' => 'Sede central',
                'responsable' => 'Por asignar',
                'estado' => true,
            ]
        );
    }
}