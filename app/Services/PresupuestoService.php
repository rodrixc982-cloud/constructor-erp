<?php

namespace App\Services;

use App\Models\Apu;
use App\Models\Presupuesto;
use App\Models\PresupuestoGasto;
use App\Models\PresupuestoPartida;
use Illuminate\Support\Facades\DB;

class PresupuestoService
{
    public function siguienteCodigo(): string
    {
        $ultimo = Presupuesto::withTrashed()->orderByDesc('id')->first();

        return 'PPTO-'.date('Y').'-'.str_pad((string) (($ultimo?->id ?? 0) + 1), 5, '0', STR_PAD_LEFT);
    }

    public function crear(array $datos): Presupuesto
    {
        if (empty($datos['codigo'])) {
            $datos['codigo'] = $this->siguienteCodigo();
        }

        return Presupuesto::create($datos);
    }

    public function actualizar(Presupuesto $presupuesto, array $datos): Presupuesto
    {
        $presupuesto->update($datos);

        return $presupuesto;
    }

    /**
     * Agrega una partida al presupuesto tomando el precio unitario
     * vigente del APU seleccionado (se puede editar después manualmente).
     */
    public function agregarPartidaDesdeApu(Presupuesto $presupuesto, int $apuId, float $metrado): PresupuestoPartida
    {
        $apu = Apu::with(['materiales', 'manoObra', 'equipos', 'maquinarias'])->findOrFail($apuId);
        $orden = ($presupuesto->partidas()->max('orden') ?? 0) + 1;

        return $presupuesto->partidas()->create([
            'apu_id' => $apu->id,
            'orden' => $orden,
            'descripcion' => $apu->descripcion,
            'unidad' => $apu->unidad,
            'metrado' => $metrado,
            'precio_unitario' => $apu->precio_unitario,
        ]);
    }

    public function agregarPartidaManual(Presupuesto $presupuesto, array $datos): PresupuestoPartida
    {
        $orden = ($presupuesto->partidas()->max('orden') ?? 0) + 1;

        return $presupuesto->partidas()->create([
            'apu_id' => null,
            'orden' => $orden,
            'descripcion' => $datos['descripcion'],
            'unidad' => $datos['unidad'],
            'metrado' => $datos['metrado'],
            'precio_unitario' => $datos['precio_unitario'],
        ]);
    }

    public function actualizarPartida(PresupuestoPartida $partida, array $datos): PresupuestoPartida
    {
        $partida->update(collect($datos)->only(['descripcion', 'unidad', 'metrado', 'precio_unitario'])->toArray());

        return $partida;
    }

    public function eliminarPartida(PresupuestoPartida $partida): void
    {
        $partida->delete();
    }

    public function agregarGasto(Presupuesto $presupuesto, array $datos): PresupuestoGasto
    {
        return $presupuesto->gastos()->create($datos);
    }

    public function actualizarGasto(PresupuestoGasto $gasto, array $datos): PresupuestoGasto
    {
        $gasto->update($datos);

        return $gasto;
    }

    public function eliminarGasto(PresupuestoGasto $gasto): void
    {
        $gasto->delete();
    }

    /**
     * Duplica el presupuesto como uno completamente independiente
     * (nuevo código, versión 1, sin relación de "padre").
     */
    public function duplicar(Presupuesto $original): Presupuesto
    {
        return DB::transaction(function () use ($original) {
            $copia = $original->replicate(['codigo']);
            $copia->codigo = $this->siguienteCodigo();
            $copia->version = 1;
            $copia->presupuesto_padre_id = null;
            $copia->estado = 'borrador';
            $copia->save();

            $this->copiarPartidasYGastos($original, $copia);

            return $copia;
        });
    }

    /**
     * Crea una nueva versión del mismo presupuesto (mismo código base,
     * incrementa el número de versión y queda enlazada al original).
     */
    public function nuevaVersion(Presupuesto $original): Presupuesto
    {
        return DB::transaction(function () use ($original) {
            $padreId = $original->presupuesto_padre_id ?? $original->id;
            $ultimaVersion = Presupuesto::where('presupuesto_padre_id', $padreId)->max('version') ?? $original->version;

            $nueva = $original->replicate(['codigo']);
            $nueva->codigo = $original->codigo;
            $nueva->version = max($ultimaVersion, $original->version) + 1;
            $nueva->presupuesto_padre_id = $padreId;
            $nueva->estado = 'borrador';
            $nueva->save();

            $this->copiarPartidasYGastos($original, $nueva);

            return $nueva;
        });
    }

    protected function copiarPartidasYGastos(Presupuesto $origen, Presupuesto $destino): void
    {
        foreach ($origen->partidas as $partida) {
            $destino->partidas()->create($partida->only(['apu_id', 'orden', 'descripcion', 'unidad', 'metrado', 'precio_unitario']));
        }

        foreach ($origen->gastos as $gasto) {
            $destino->gastos()->create($gasto->only(['tipo', 'concepto', 'cantidad', 'precio_unitario']));
        }
    }

    public function aprobar(Presupuesto $presupuesto): Presupuesto
    {
        $presupuesto->update(['estado' => 'aprobado']);

        return $presupuesto;
    }

    public function rechazar(Presupuesto $presupuesto): Presupuesto
    {
        $presupuesto->update(['estado' => 'rechazado']);

        return $presupuesto;
    }

    public function archivar(Presupuesto $presupuesto): Presupuesto
    {
        $presupuesto->update(['estado' => 'archivado']);

        return $presupuesto;
    }
}
