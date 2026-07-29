<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaterialesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(protected array $filtros = [])
    {
    }

    public function collection()
    {
        return Material::with(['categoria', 'proveedor'])
            ->when(! empty($this->filtros['categoria_id']), fn ($q) => $q->where('categoria_id', $this->filtros['categoria_id']))
            ->get();
    }

    public function headings(): array
    {
        return [
            'Código', 'Nombre', 'Marca', 'Modelo', 'Categoría', 'Proveedor',
            'Unidad', 'Precio Compra', 'Precio Venta', 'Stock', 'Stock Mínimo', 'IVA (%)', 'Estado',
        ];
    }

    public function map($material): array
    {
        return [
            $material->codigo,
            $material->nombre,
            $material->marca,
            $material->modelo,
            $material->categoria?->nombre,
            $material->proveedor?->nombre,
            $material->unidad,
            $material->precio_compra,
            $material->precio_venta,
            $material->stock,
            $material->stock_minimo,
            $material->iva,
            $material->estado ? 'Activo' : 'Inactivo',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
