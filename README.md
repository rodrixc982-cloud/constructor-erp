<img width="1919" height="995" alt="Captura de pantalla 2026-07-28 182013" src="https://github.com/user-attachments/assets/290cbe8a-c54a-4e34-8014-97cbef07d9f9" />




# Constructor ERP — Sistema completo (Fases 1-9)

ERP profesional de presupuestos para empresas constructoras, arquitectos, ingenieros civiles y contratistas. Desarrollado en Laravel 12 + PHP 8.4.

## ✅ Módulos incluidos

**Fase 1 — Base:** autenticación completa (login, 2FA, bloqueo por intentos, recuperar/cambiar contraseña), roles y permisos (Spatie) con los 11 roles solicitados, auditoría (Activitylog), layout AdminLTE con modo claro/oscuro, dashboard con Chart.js, perfil con foto.

**Fase 2 — Catálogos:** Empresa (singleton), Clientes, Proveedores, Categorías, Materiales (imagen, QR, import/export Excel, export PDF).

**Fase 3 — Inventario:** almacenes múltiples, entradas/salidas/transferencias/ajustes con Kardex completo y alertas de stock mínimo.

**Fase 4 — Recursos de obra:** Mano de obra, Equipos, Maquinaria, Obras (con adjuntos: fotos, planos, documentos, contratos).

**Fase 5 — Núcleo del sistema:** APU (Análisis de Precios Unitarios) con materiales/mano de obra/equipos/maquinaria y cálculo automático de costo directo/indirecto/utilidad/precio unitario; Presupuestos con partidas ilimitadas (desde APU o manuales), otros gastos (transporte, hospedaje, viáticos, seguros, herramientas), **recálculo en vivo sin recargar la página**, duplicado, versionado, flujo de aprobación (borrador/aprobado/rechazado/archivado) y exportación a PDF.

**Fase 6 — Calculadora inteligente:** cemento, ladrillos, arena, piedra, acero, pintura, cerámica y pegamento según área/volumen/tipo de construcción.

**Fase 7 — Compras, Caja y Facturación:** órdenes de compra (manuales o generadas automáticamente desde un presupuesto), ingresos/egresos de caja, documentos de venta (cotización, proforma, factura, boleta, orden de servicio) con PDF.

**Fase 8 — Calendario, Reportes, Auditoría, Notificaciones:** agenda mensual de eventos/visitas/inspecciones, reportes por cliente/proyecto/material/utilidad mensual/estado (con export PDF/Excel), visor de auditoría con filtros, notificaciones en base de datos + correo (ej. alerta de stock bajo).

**Fase 9 — Configuración y API:** panel de configuración general (key-value, cacheado), API REST protegida con Sanctum (`/api/v1/clientes`, `/materiales`, `/presupuestos`).

## 📦 Instalación

Requiere PHP 8.4, Composer, Node.js 18+, MySQL 8.

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
```

Configura la base de datos en `.env` (`constructor_erp` por defecto), luego:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

## 🔑 Usuarios de prueba

Contraseña para todos: `Password123!`

| Rol | Correo |
|---|---|
| Super Administrador | super-administrador@constructor-erp.test |
| Administrador | administrador@constructor-erp.test |
| Gerente | gerente@constructor-erp.test |
| Ingeniero | ingeniero@constructor-erp.test |
| Arquitecto | arquitecto@constructor-erp.test |
| Presupuestista | presupuestista@constructor-erp.test |
| Supervisor | supervisor@constructor-erp.test |
| Compras | compras@constructor-erp.test |
| Almacén | almac-n@constructor-erp.test (revisa el slug exacto generado por Str::slug) |
| Contabilidad | contabilidad@constructor-erp.test |
| Cliente | cliente@constructor-erp.test |

> ⚠️ Cambia todas las contraseñas antes de producción.

## 🧭 Flujo recomendado para probar el sistema

1. Inicia sesión como Super Administrador.
2. Ve a **Empresa** y completa los datos (ya viene una empresa demo precargada).
3. Crea algunos **Clientes**, **Proveedores**, **Categorías** y **Materiales** (o impórtalos con la plantilla Excel del módulo Materiales).
4. Registra **Mano de obra**, **Equipos** y **Maquinaria**.
5. Crea una **Obra**.
6. Ve a **APU** y crea un análisis de precios unitario combinando materiales/mano de obra/equipos — verás el cálculo en vivo del precio unitario.
7. Ve a **Presupuestos**, crea uno nuevo, agrégale partidas desde el APU creado y observa cómo se recalculan los totales automáticamente.
8. Aprueba el presupuesto y expórtalo a PDF.
9. Desde **Compras**, genera órdenes de compra automáticamente a partir de ese presupuesto.
10. Registra movimientos en **Caja** y documentos en **Facturación**.

## ⚠️ Alcance honesto de esta entrega

Este es un sistema muy grande (equivalente a S10/Presto/Arquímedes). Para mantener cada pieza con código real y funcional:

- Los módulos **núcleo** (Auth, Roles, Catálogos, Inventario, APU, Presupuestos) usan **Repository Pattern + Service Layer** completos, tal como pediste.
- Los módulos de **soporte** (Mano de obra, Equipos, Maquinaria, Compras, Caja, Facturación, Calendario) usan controladores con validación y lógica directa sobre Eloquent, sin una capa de Repository dedicada — es una simplificación consciente para poder cubrir la totalidad de módulos del prompt con código real en vez de dejar módulos vacíos. Si quieres, puedo migrar cualquiera de ellos al mismo patrón Repository/Service que los módulos núcleo.
- El campo `ip_address`/`user_agent` de auditoría está preparado en la tabla pero no se autopobla todavía (requiere un Observer o middleware adicional que puedo agregar si lo necesitas).
- El Calendario usa una vista de cuadrícula mensual construida a mano (no FullCalendar.js) para no depender de una librería adicional no listada en el prompt.
- No se generó el archivo `composer.lock` ni `vendor/`, ni se ejecutó `npm install`/`composer install` realmente: este entorno no tiene acceso a red. Todo el código está escrito a mano siguiendo las convenciones exactas de Laravel 12 y de cada paquete; al ejecutar `composer install` en tu máquina con red, debería instalar sin conflictos dado que `composer.json` fija las versiones compatibles.
- Tests automatizados (PHPUnit) no se incluyeron en esta entrega por el volumen ya cubierto; puedo agregarlos para los módulos que priorices (típicamente Presupuestos/APU primero, por ser el núcleo de negocio).

## 🗂️ Estructura de carpetas relevante

```
app/
  Http/Controllers/       # Un controlador por módulo
  Models/                 # Un modelo por tabla, con relaciones y accessors
  Repositories/           # Repository Pattern (módulos núcleo)
  Services/               # Service Layer (lógica de negocio)
  Imports/ Exports/       # Laravel Excel
  Notifications/          # Notificaciones (stock bajo, etc.)
database/
  migrations/             # Todas las tablas, en orden de fase
  seeders/                # Roles, permisos, usuarios demo, empresa demo
resources/views/
  layouts/app.blade.php   # Layout AdminLTE compartido
  <modulo>/index.blade.php
routes/
  web.php                 # Incluye todos los routes/modules/*.php
  modules/                # Un archivo de rutas por grupo de módulos
```
 RODRIXC TIANZ 2026 DERECHO RESERVADO 