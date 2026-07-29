<?php

namespace App\Services;

use App\Repositories\Contracts\MaterialRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MaterialService
{
    public function __construct(protected MaterialRepositoryInterface $repositorio)
    {
    }

    public function listar(array $filtros = [], int $porPagina = 15)
    {
        return $this->repositorio->paginate($porPagina, $filtros);
    }

    public function obtener(int $id): Model
    {
        return $this->repositorio->findOrFail($id);
    }

    /**
     * Crea un material. Si no se especifica código, autogenera uno
     * correlativo (MAT-000001) y procesa la imagen si fue subida.
     */
    public function crear(array $datos, ?UploadedFile $imagen = null): Model
    {
        if (empty($datos['codigo'])) {
            $datos['codigo'] = $this->repositorio->siguienteCodigo();
        }

        if ($imagen) {
            $datos['imagen'] = $imagen->store('materiales', 'public');
        }

        return $this->repositorio->create($datos);
    }

    public function actualizar(int $id, array $datos, ?UploadedFile $imagen = null): Model
    {
        $material = $this->repositorio->findOrFail($id);

        if ($imagen) {
            if ($material->imagen) {
                Storage::disk('public')->delete($material->imagen);
            }
            $datos['imagen'] = $imagen->store('materiales', 'public');
        }

        return $this->repositorio->update($id, $datos);
    }

    public function eliminar(int $id): bool
    {
        return $this->repositorio->delete($id);
    }

    public function restaurar(int $id): bool
    {
        return $this->repositorio->restore($id);
    }

    /**
     * Genera el código QR (en SVG) que representa el código del material,
     * usado en fichas técnicas y etiquetas para almacén.
     */
    public function generarQr(Model $material): string
    {
        return QrCode::size(180)->generate($material->codigo);
    }
}
