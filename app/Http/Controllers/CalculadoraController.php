<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Calculadora inteligente de materiales de construcción. Los
 * coeficientes usados son valores de referencia estándar de la
 * industria (Reglamento Nacional de Edificaciones / prácticas
 * comunes de metrado); siempre deben verificarse contra el
 * expediente técnico real de cada obra.
 */
class CalculadoraController extends Controller
{
    public function index(): View
    {
        return view('calculadora.index');
    }

    /**
     * Calcula materiales para un muro de albañilería según su área.
     * Coeficientes de referencia para ladrillo king kong 18 huecos,
     * junta de 1.5cm, muro de soga.
     */
    public function muro(Request $request): JsonResponse
    {
        $datos = $request->validate(['area' => ['required', 'numeric', 'min:0']]);
        $area = $datos['area'];

        return response()->json(['data' => [
            'ladrillos' => round($area * 39, 0),
            'cemento_bolsas' => round($area * 0.20, 2),
            'arena_m3' => round($area * 0.023, 3),
        ]]);
    }

    /**
     * Calcula materiales para una losa/columna de concreto según su volumen,
     * usando la mezcla de referencia 1:2:3 (cemento:arena:piedra) f'c=210.
     */
    public function concreto(Request $request): JsonResponse
    {
        $datos = $request->validate(['volumen' => ['required', 'numeric', 'min:0']]);
        $volumen = $datos['volumen'];

        return response()->json(['data' => [
            'cemento_bolsas' => round($volumen * 9.73, 2),
            'arena_m3' => round($volumen * 0.52, 3),
            'piedra_m3' => round($volumen * 0.53, 3),
            'agua_litros' => round($volumen * 184, 1),
        ]]);
    }

    /**
     * Estima el acero de refuerzo (kg) según el volumen de concreto
     * y una cuantía de referencia en kg/m3 (varía según elemento).
     */
    public function acero(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'volumen' => ['required', 'numeric', 'min:0'],
            'elemento' => ['required', 'in:zapata,columna,viga,losa,muro'],
        ]);

        $cuantias = ['zapata' => 60, 'columna' => 130, 'viga' => 150, 'losa' => 90, 'muro' => 40];

        return response()->json(['data' => [
            'acero_kg' => round($datos['volumen'] * $cuantias[$datos['elemento']], 2),
        ]]);
    }

    /**
     * Calcula pintura según área a cubrir, con 2 manos y rendimiento
     * promedio de 35 m2 por galón por mano.
     */
    public function pintura(Request $request): JsonResponse
    {
        $datos = $request->validate(['area' => ['required', 'numeric', 'min:0'], 'manos' => ['nullable', 'integer', 'min:1', 'max:5']]);
        $manos = $datos['manos'] ?? 2;

        return response()->json(['data' => [
            'galones' => round(($datos['area'] * $manos) / 35, 2),
        ]]);
    }

    /**
     * Calcula cerámica/porcelanato y pegamento para pisos/paredes.
     * Incluye 10% de desperdicio estándar por cortes.
     */
    public function ceramica(Request $request): JsonResponse
    {
        $datos = $request->validate(['area' => ['required', 'numeric', 'min:0']]);
        $area = $datos['area'];

        return response()->json(['data' => [
            'ceramica_m2' => round($area * 1.10, 2),
            'pegamento_bolsas' => round($area / 5, 2),
            'fragua_kg' => round($area * 0.4, 2),
        ]]);
    }

    /**
     * Calcula materiales completos de una construcción a partir de
     * área techada y número de pisos, combinando todas las fórmulas
     * anteriores en un estimado preliminar de anteproyecto.
     */
    public function construccionCompleta(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'area_m2' => ['required', 'numeric', 'min:0'],
            'pisos' => ['required', 'integer', 'min:1', 'max:20'],
            'tipo' => ['required', 'in:economica,estandar,premium'],
        ]);

        $factoresPorTipo = ['economica' => 0.85, 'estandar' => 1.0, 'premium' => 1.3];
        $factor = $factoresPorTipo[$datos['tipo']];
        $areaTotal = $datos['area_m2'] * $datos['pisos'];
        $volumenConcretoEstimado = $areaTotal * 0.18; // m3 de concreto por m2 construido (estimado global)

        return response()->json(['data' => [
            'area_total_construida' => round($areaTotal, 2),
            'cemento_bolsas' => round($volumenConcretoEstimado * 9.73 * $factor, 0),
            'arena_m3' => round($volumenConcretoEstimado * 0.52 * $factor, 2),
            'piedra_m3' => round($volumenConcretoEstimado * 0.53 * $factor, 2),
            'ladrillos' => round($areaTotal * 2.5 * 39 * $factor, 0),
            'acero_kg' => round($volumenConcretoEstimado * 100 * $factor, 0),
            'nota' => 'Estimado preliminar de anteproyecto. Para el presupuesto definitivo, usar el módulo de APU y Presupuestos con metrados reales.',
        ]]);
    }
}
