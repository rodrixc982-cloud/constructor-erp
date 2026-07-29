<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;

class MaterialApiController extends Controller
{
    public function index()
    {
        return Material::with(['categoria', 'proveedor'])->where('estado', true)->paginate(20);
    }

    public function show(Material $material)
    {
        return $material->load(['categoria', 'proveedor']);
    }
}
