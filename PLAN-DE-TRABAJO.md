# Plan de Trabajo · Sistema Taller Textil

> Nota: el documento de Word con los requerimientos no estaba en la carpeta del proyecto al iniciar. Este plan se basa en las necesidades típicas de un taller textil para casos reales de negocio. Cuando compartas el Word, ajustamos alcance y prioridades.

## Objetivo

Construir un sistema web (Laravel + MySQL) para gestionar integralmente un taller textil: pedidos, producción, inventario, ventas, compras y administración, con un diseño profesional y moderno.

## Fase 0 — Base del sistema ✅ (entregado)

- Estructura del proyecto Laravel 12 + configuración MySQL.
- Autenticación (login, logout, sesiones, roles base, seeder de usuarios).
- Layout profesional con menú vertical y topbar responsive.
- Dashboard con KPIs y gráficos.
- Todos los módulos enlazados en el menú con pantalla base.

## Fase 1 — Datos maestros

| Módulo | Alcance |
|--------|---------|
| **Clientes** | CRUD, contacto, RUC/DNI, dirección, historial, cuentas por cobrar. |
| **Proveedores** | CRUD, evaluación, condiciones de pago. |
| **Productos / Catálogo** | Modelos, tallas, colores, precios, imágenes, categorías. |
| **Materia Prima / Insumos** | Telas, hilos, avíos; unidades, stock mínimo, costos. |
| **Empleados** | Operarios, áreas, cargos, tarifa; base para planilla y productividad. |
| **Usuarios y Roles** | Gestión de usuarios, roles y permisos por módulo. |

## Fase 2 — Operaciones (núcleo del taller)

| Módulo | Alcance |
|--------|---------|
| **Pedidos / Órdenes de trabajo** | Registro de pedido, detalle por producto/talla, fechas de entrega, estados. |
| **Producción** | Órdenes de producción, procesos (corte → confección → acabado → control de calidad), avance por etapa, asignación a operarios, mermas. |
| **Almacén / Kardex** | Movimientos de entrada/salida, ubicaciones, valorización de inventario. |

## Fase 3 — Comercial y compras

| Módulo | Alcance |
|--------|---------|
| **Ventas / Facturación** | Cotizaciones, boletas/facturas, cobranzas, ticket promedio. (Opcional: integración con facturación electrónica SUNAT.) |
| **Compras** | Órdenes de compra, recepción de insumos, cuentas por pagar. |

## Fase 4 — Inteligencia de negocio

- **Reportes** gerenciales: producción por período/área/operario, ventas, rentabilidad, rotación de inventario, insumos con stock bajo. Exportables a PDF/Excel.
- **Dashboard** conectado a datos reales (reemplaza los datos de ejemplo actuales).

## Fase 5 — Configuración y despliegue

- **Configuración**: datos de empresa, sucursales, moneda, numeración de comprobantes, parámetros del sistema.
- Notificaciones (stock bajo, pedidos atrasados).
- Optimización, migración de assets a Vite, backups y puesta en producción.

## Módulos del sistema (menú)

Principal: Dashboard · Operaciones: Pedidos, Producción, Clientes · Inventario: Productos, Materia Prima, Almacén · Comercial: Ventas, Compras, Proveedores · Administración: Empleados, Reportes, Usuarios, Configuración.

## Stack técnico

- Backend: Laravel 12 (PHP 8.2+)
- Base de datos: MySQL 8 / MariaDB
- Frontend: Blade + Tailwind CSS + Chart.js
- Entorno local: Laragon
