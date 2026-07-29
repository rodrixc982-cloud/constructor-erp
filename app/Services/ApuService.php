<?php

namespace App\Services;

use App\Models\Apu;
use App\Models\Material;
use App\Models\ManoObra;
use App\Models\Equipo;
use App\Models\Maquinaria;
use Illuminate\Support\Facades\DB;

/**
 * Gestiona la creación/edición de un APU junto con sus líneas de
 * materiales, mano de obra, equipos y maquinaria en una sola
 * transacción. Los precios unitarios se toman como "snapshot" del
 * catálogo en el momento de guardar, para que el histórico de un
 * APU no cambie si luego sube el precio de un material.
 */
class ApuService
{
    public function siguienteCodigo(): string
    {
        $ultimo = Apu::withTrashed()->orderByDesc('id')->first();

        return 'APU-'.str_pad((string) (($ultimo?->id ?? 0) + 1), 5, '0', STR_PAD_LEFT);
    }

    public function crear(array $datos): Apu
    {
        return DB::transaction(function () use ($datos) {
            if (empty($datos['codigo'])) {
                $datos['codigo'] = $this->siguienteCodigo();
            }

            $apu = Apu::create($this->camposPrincipales($datos));
            $this->sincronizarLineas($apu, $datos);

            return $apu->load(['materiales.material', 'manoObra.manoObra', 'equipos.equipo', 'maquinarias.maquinaria']);
        });
    }

    public function actualizar(Apu $apu, array $datos): Apu
    {
        return DB::transaction(function () use ($apu, $datos) {
            $apu->update($this->camposPrincipales($datos));
            $this->sincronizarLineas($apu, $datos);

            return $apu->load(['materiales.material', 'manoObra.manoObra', 'equipos.equipo', 'maquinarias.maquinaria']);
        });
    }

    protected function camposPrincipales(array $datos): array
    {
        return collect($datos)->only([
            'codigo', 'descripcion', 'unidad', 'rendimiento', 'porcentaje_herramientas',
            'porcentaje_costos_indirectos', 'porcentaje_utilidad', 'estado', 'observaciones',
        ])->toArray();
    }

    /**
     * Reemplaza todas las líneas del APU con las recibidas del formulario.
     * Se borra y recrea en cada guardado por simplicidad y consistencia.
     */
    protected function sincronizarLineas(Apu $apu, array $datos): void
    {
        $apu->materiales()->delete();
        foreach ($datos['materiales'] ?? [] as $linea) {
            $material = Material::find($linea['material_id']);
            $apu->materiales()->create([
                'material_id' => $linea['material_id'],
                'cantidad' => $linea['cantidad'],
                'desperdicio_pct' => $linea['desperdicio_pct'] ?? 5,
                'precio_unitario' => $material->precio_venta,
            ]);
        }

        $apu->manoObra()->delete();
        foreach ($datos['mano_obra'] ?? [] as $linea) {
            $mano = ManoObra::find($linea['mano_obra_id']);
            $apu->manoObra()->create([
                'mano_obra_id' => $linea['mano_obra_id'],
                'cantidad' => $linea['cantidad'],
                'costo_unitario' => $mano->costo,
            ]);
        }

        $apu->equipos()->delete();
        foreach ($datos['equipos'] ?? [] as $linea) {
            $equipo = Equipo::find($linea['equipo_id']);
            $apu->equipos()->create([
                'equipo_id' => $linea['equipo_id'],
                'cantidad' => $linea['cantidad'],
                'costo_unitario' => $equipo->costo_alquiler_dia,
            ]);
        }

        $apu->maquinarias()->delete();
        foreach ($datos['maquinarias'] ?? [] as $linea) {
            $maquinaria = Maquinaria::find($linea['maquinaria_id']);
            $apu->maquinarias()->create([
                'maquinaria_id' => $linea['maquinaria_id'],
                'cantidad' => $linea['cantidad'],
                'costo_unitario' => $maquinaria->costo_dia,
            ]);
        }
    }
}
