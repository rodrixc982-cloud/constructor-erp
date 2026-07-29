<?php

namespace App\Imports;

use App\Models\Categoria;
use App\Models\Material;
use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

/**
 * Importa materiales desde un archivo Excel. Columnas esperadas
 * (encabezados en la primera fila, insensible a mayúsculas):
 * codigo, nombre, marca, modelo, categoria, unidad, precio_compra,
 * precio_venta, stock, stock_minimo, iva, proveedor.
 *
 * Categoría y Proveedor se resuelven por nombre; si no existen se
 * omite la relación (quedan nulos) en lugar de fallar la importación.
 */
class MaterialesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable;

    public function model(array $row): Material
    {
        $categoriaId = ! empty($row['categoria'])
            ? Categoria::firstOrCreate(['nombre' => trim($row['categoria'])])->id
            : null;

        $proveedorId = ! empty($row['proveedor'])
            ? Proveedor::where('nombre', trim($row['proveedor']))->value('id')
            : null;

        return new Material([
            'codigo' => $row['codigo'] ?? null,
            'nombre' => $row['nombre'],
            'marca' => $row['marca'] ?? null,
            'modelo' => $row['modelo'] ?? null,
            'categoria_id' => $categoriaId,
            'proveedor_id' => $proveedorId,
            'unidad' => $row['unidad'] ?? 'UND',
            'precio_compra' => $row['precio_compra'] ?? 0,
            'precio_venta' => $row['precio_venta'] ?? 0,
            'stock' => $row['stock'] ?? 0,
            'stock_minimo' => $row['stock_minimo'] ?? 0,
            'iva' => $row['iva'] ?? 18,
            'estado' => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'precio_compra' => ['nullable', 'numeric', 'min:0'],
            'precio_venta' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function onError(\Throwable $e): void
    {
        report($e);
    }
}
