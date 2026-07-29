<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Guarda la preferencia de tema (claro/oscuro) del usuario en sesión.
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate(['theme' => 'required|in:light,dark']);
        session(['theme' => $request->input('theme')]);

        return response()->json(['ok' => true]);
    }
}
