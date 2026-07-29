<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Panel de configuración general del sistema: nombre, logo/colores
 * de la interfaz, SMTP, WhatsApp, formatos por defecto de PDF/Excel
 * y utilidad/IGV por defecto para nuevos presupuestos. Los datos de
 * la EMPRESA (RUC, dirección, etc.) se gestionan en el módulo Empresa;
 * aquí van los ajustes del SISTEMA en sí.
 */
class ConfiguracionController extends Controller implements HasMiddleware
{
    public function __construct()
    {
        // Los middlewares se definen en el método middleware() estático
    }

    /**
     * Define los middlewares del controlador
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:configuracion.ver', only: ['edit', 'index']),
            new Middleware('can:configuracion.editar', only: ['update']),
        ];
    }

    protected array $claves = [
        'sistema_nombre', 
        'color_primario', 
        'utilidad_defecto', 
        'igv_defecto',
        'formato_pdf_orientacion', 
        'smtp_host', 
        'smtp_puerto', 
        'smtp_usuario',
        'whatsapp_api_url', 
        'whatsapp_api_token',
        'logo_url',
        'favicon_url',
        'moneda_defecto',
        'zona_horaria',
        'formato_fecha',
        'notificaciones_email',
        'notificaciones_whatsapp',
    ];

    public function index(): View
    {
        return $this->edit();
    }

    public function edit(): View
    {
        $configuraciones = collect($this->claves)->mapWithKeys(
            fn ($clave) => [$clave => Configuracion::obtener($clave)]
        );

        return view('configuracion.edit', compact('configuraciones'));
    }

    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'sistema_nombre' => ['nullable', 'string', 'max:255'],
            'color_primario' => ['nullable', 'string', 'max:20'],
            'utilidad_defecto' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'igv_defecto' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'formato_pdf_orientacion' => ['nullable', 'in:portrait,landscape'],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_puerto' => ['nullable', 'numeric', 'min:1', 'max:65535'],
            'smtp_usuario' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl,null'],
            'whatsapp_api_url' => ['nullable', 'url'],
            'whatsapp_api_token' => ['nullable', 'string'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'favicon_url' => ['nullable', 'string', 'max:255'],
            'moneda_defecto' => ['nullable', 'string', 'max:10'],
            'zona_horaria' => ['nullable', 'string', 'max:50'],
            'formato_fecha' => ['nullable', 'string', 'max:20'],
            'notificaciones_email' => ['boolean'],
            'notificaciones_whatsapp' => ['boolean'],
        ]);

        foreach ($datos as $clave => $valor) {
            Configuracion::establecer($clave, $valor);
        }

        return back()->with('status', 'Configuración actualizada correctamente.');
    }

    /**
     * Obtener la configuración en formato JSON (para API)
     */
    public function obtenerConfiguracion(): JsonResponse
    {
        $configuraciones = collect($this->claves)->mapWithKeys(
            fn ($clave) => [$clave => Configuracion::obtener($clave)]
        );

        return response()->json(['data' => $configuraciones]);
    }

    /**
     * Obtener una configuración específica
     */
    public function obtenerClave(string $clave): JsonResponse
    {
        $valor = Configuracion::obtener($clave);
        
        return response()->json(['data' => ['clave' => $clave, 'valor' => $valor]]);
    }

    /**
     * Actualizar configuración desde API
     */
    public function actualizarApi(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'clave' => ['required', 'string', 'in:' . implode(',', $this->claves)],
            'valor' => ['required'],
        ]);

        Configuracion::establecer($datos['clave'], $datos['valor']);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Configuración actualizada correctamente.',
            'data' => [
                'clave' => $datos['clave'],
                'valor' => Configuracion::obtener($datos['clave'])
            ]
        ]);
    }

    /**
     * Restaurar configuración a valores por defecto
     */
    public function restaurarDefault(): RedirectResponse
    {
        $defaults = [
            'sistema_nombre' => 'Constructor ERP',
            'color_primario' => '#2a5298',
            'utilidad_defecto' => 10,
            'igv_defecto' => 18,
            'formato_pdf_orientacion' => 'portrait',
            'moneda_defecto' => 'PEN',
            'zona_horaria' => 'America/Lima',
            'formato_fecha' => 'd/m/Y',
            'notificaciones_email' => true,
            'notificaciones_whatsapp' => false,
        ];

        foreach ($defaults as $clave => $valor) {
            Configuracion::establecer($clave, $valor);
        }

        return back()->with('status', 'Configuración restaurada a valores por defecto.');
    }
}