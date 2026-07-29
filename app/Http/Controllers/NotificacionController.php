<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    public function index(): View
    {
        return view('notificaciones.index');
    }

    public function datos(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()->notifications()->latest()->limit(100)->get()]);
    }

    public function marcarLeida(Request $request, string $id): JsonResponse
    {
        $notificacion = $request->user()->notifications()->findOrFail($id);
        $notificacion->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function marcarTodasLeidas(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['ok' => true, 'mensaje' => 'Todas las notificaciones marcadas como leídas.']);
    }
}
