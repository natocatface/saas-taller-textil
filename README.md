# Sistema Taller Textil

Aplicación web de gestión para un taller textil, construida con **Laravel 12 + MySQL**. Esta primera entrega incluye el **login**, el **dashboard** y el **menú vertical con todos los módulos** del sistema, con un diseño profesional (tema índigo/morado inspirado en el mockup de referencia).

---

## Requisitos

- PHP 8.2 o superior (incluido en Laragon)
- Composer
- MySQL 8 / MariaDB (incluido en Laragon)

> Para la descarga de comprobantes/órdenes en **PDF** se usa la librería `barryvdh/laravel-dompdf` (ya declarada en `composer.json`). Se instala con `composer install`. Si actualizaste el proyecto, ejecuta `composer require barryvdh/laravel-dompdf` una vez. El botón *Imprimir* funciona sin esta librería (usa el navegador).

## Instalación (con Laragon)

1. Copia esta carpeta dentro de `C:\laragon\www\` (por ejemplo `C:\laragon\www\saas-taller-textil`).

2. Abre una terminal en la carpeta del proyecto e instala las dependencias:

   ```bash
   composer install
   ```

3. Genera la clave de la aplicación:

   ```bash
   php artisan key:generate
   ```

   > El archivo `.env` ya viene configurado para MySQL local (`root` sin contraseña, base `saas_taller_textil`). Si tu MySQL tiene contraseña, edítala en `.env` (`DB_PASSWORD`).

4. Crea la base de datos y sus tablas. La migración crea la base automáticamente si usas Laragon; si no, créala manualmente:

   ```sql
   CREATE DATABASE saas_taller_textil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

   Luego ejecuta:

   ```bash
   php artisan migrate --seed
   ```

5. Levanta el servidor:

   ```bash
   php artisan serve
   ```

   Abre **http://localhost:8000**

   > Con Laragon también puedes usar el host virtual `http://saas-taller-textil.test` (activa "Auto virtual hosts").

## Usuarios de prueba

| Rol         | Correo                         | Contraseña |
|-------------|--------------------------------|------------|
| Admin       | admin@tallertextil.com         | password   |
| Supervisor  | supervisor@tallertextil.com    | password   |
| Operario    | operario@tallertextil.com      | password   |

---

## Qué incluye el sistema

- **Login** funcional con validación, "recordarme", sesión segura y control de cuentas activas/inactivas.
- **Dashboard** con KPIs y 4 gráficos (Chart.js), responsive.
- **Menú vertical** con los 13 módulos organizados por grupos, estado activo y diseño responsive.
- **Layout** con topbar (búsqueda, notificaciones, menú de usuario), totalmente responsive.
- **Roles y permisos** base (middleware `admin`) y seeder de datos de ejemplo.

### Módulos funcionales (CRUD con datos reales en MySQL)

| Grupo | Módulos |
|-------|---------|
| Operaciones | **Pedidos** (con detalle de ítems), **Producción** (órdenes por etapa y avance), **Clientes** |
| Inventario | **Productos**, **Materia Prima**, **Almacén / Kardex** (movimientos que ajustan stock) |
| Comercial | **Ventas** (con IGV y comprobante), **Compras** (suman stock al recibir), **Proveedores** |
| Administración | **Empleados**, **Reportes** (gráficos reales + export CSV), **Usuarios y Roles**, **Configuración** |

Todos los módulos están operativos. Consulta `PLAN-DE-TRABAJO.md` para el detalle de fases y posibles mejoras futuras (facturación electrónica SUNAT, planilla, etc.).

## Notas técnicas

- El diseño usa **Tailwind CSS por CDN** y **Chart.js por CDN**, por lo que no necesitas `npm install`. En una fase posterior se puede migrar a Vite para compilar los assets.
- Estructura estándar de Laravel 12 (`app/`, `routes/`, `resources/views/`, `database/`, `config/`).
