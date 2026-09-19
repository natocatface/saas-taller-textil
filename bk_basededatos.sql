-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para saas_taller_textil
CREATE DATABASE IF NOT EXISTS `saas_taller_textil` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `saas_taller_textil`;

-- Volcando estructura para tabla saas_taller_textil.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` enum('DNI','RUC','CE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DNI',
  `numero_documento` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.clientes: ~5 rows (aproximadamente)
DELETE FROM `clientes`;
INSERT INTO `clientes` (`id`, `nombre`, `tipo_documento`, `numero_documento`, `email`, `telefono`, `direccion`, `ciudad`, `contacto`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'Confecciones Andina S.A.C.', 'RUC', '20481234567', 'ventas@andina.com', '987654321', NULL, 'Lima', NULL, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(2, 'Textiles del Sur E.I.R.L.', 'RUC', '20512345678', 'contacto@textilsur.com', '956123456', NULL, 'Arequipa', NULL, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(3, 'Moda Express', 'RUC', '20601234567', 'info@modaexpress.pe', '999888777', NULL, 'Lima', NULL, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(4, 'Uniformes Perú', 'RUC', '20487654321', 'compras@uniformesperu.com', '944556677', NULL, 'Trujillo', NULL, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(5, 'Boutique Lima', 'DNI', '45678912', 'boutiquelima@gmail.com', '933221100', NULL, 'Lima', NULL, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07');

-- Volcando estructura para tabla saas_taller_textil.compras
CREATE TABLE IF NOT EXISTS `compras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proveedor_id` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('recibida','pendiente','anulada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `stock_aplicado` tinyint(1) NOT NULL DEFAULT '0',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `compras_codigo_unique` (`codigo`),
  KEY `compras_proveedor_id_foreign` (`proveedor_id`),
  CONSTRAINT `compras_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.compras: ~4 rows (aproximadamente)
DELETE FROM `compras`;
INSERT INTO `compras` (`id`, `codigo`, `proveedor_id`, `fecha`, `estado`, `stock_aplicado`, `total`, `observaciones`, `created_at`, `updated_at`) VALUES
	(1, 'COM-0001', 1, '2026-06-11', 'pendiente', 0, 4620.00, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(2, 'COM-0002', 3, '2026-06-25', 'pendiente', 0, 5619.85, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(3, 'COM-0003', 2, '2026-06-14', 'pendiente', 0, 9358.10, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(4, 'COM-0004', 3, '2026-06-28', 'pendiente', 0, 8569.00, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34');

-- Volcando estructura para tabla saas_taller_textil.compra_items
CREATE TABLE IF NOT EXISTS `compra_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `compra_id` bigint unsigned NOT NULL,
  `materia_prima_id` bigint unsigned NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT '1.00',
  `costo_unitario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compra_items_compra_id_foreign` (`compra_id`),
  KEY `compra_items_materia_prima_id_foreign` (`materia_prima_id`),
  CONSTRAINT `compra_items_compra_id_foreign` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  CONSTRAINT `compra_items_materia_prima_id_foreign` FOREIGN KEY (`materia_prima_id`) REFERENCES `materia_primas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.compra_items: ~10 rows (aproximadamente)
DELETE FROM `compra_items`;
INSERT INTO `compra_items` (`id`, `compra_id`, `materia_prima_id`, `cantidad`, `costo_unitario`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 308.00, 15.00, 4620.00, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(2, 2, 1, 371.00, 12.50, 4637.50, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(3, 2, 3, 117.00, 8.00, 936.00, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(4, 2, 4, 309.00, 0.15, 46.35, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(5, 3, 1, 364.00, 12.50, 4550.00, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(6, 3, 2, 319.00, 15.00, 4785.00, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(7, 3, 4, 154.00, 0.15, 23.10, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(8, 4, 1, 134.00, 12.50, 1675.00, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(9, 4, 2, 266.00, 15.00, 3990.00, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(10, 4, 3, 363.00, 8.00, 2904.00, '2026-07-04 16:34:34', '2026-07-04 16:34:34');

-- Volcando estructura para tabla saas_taller_textil.configuraciones
CREATE TABLE IF NOT EXISTS `configuraciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `empresa` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Taller Textil',
  `ruc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moneda` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PEN',
  `igv` decimal(5,2) NOT NULL DEFAULT '18.00',
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.configuraciones: ~1 rows (aproximadamente)
DELETE FROM `configuraciones`;
INSERT INTO `configuraciones` (`id`, `empresa`, `ruc`, `direccion`, `telefono`, `email`, `moneda`, `igv`, `logo`, `created_at`, `updated_at`) VALUES
	(1, 'Taller Textil Demo S.A.C.', '20481234567', 'Av. Industrial 1234, Lima', '(01) 555-1234', 'contacto@tallertextil.com', 'PEN', 18.00, NULL, '2026-07-04 16:41:00', '2026-07-04 16:41:00');

-- Volcando estructura para tabla saas_taller_textil.empleados
CREATE TABLE IF NOT EXISTS `empleados` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` enum('Corte','Confección','Acabado','Control de Calidad','Administración','Almacén') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Confección',
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `salario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.empleados: ~5 rows (aproximadamente)
DELETE FROM `empleados`;
INSERT INTO `empleados` (`id`, `nombre`, `dni`, `cargo`, `area`, `telefono`, `email`, `fecha_ingreso`, `salario`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'Carlos Mendoza', '40112233', 'Jefe de Producción', 'Administración', NULL, NULL, '2025-04-04', 2800.00, 1, '2026-07-04 16:17:07', '2026-07-04 16:41:00'),
	(2, 'María Torres', '41223344', 'Costurera', 'Confección', NULL, NULL, '2025-07-04', 1500.00, 1, '2026-07-04 16:17:07', '2026-07-04 16:41:00'),
	(3, 'Jorge Ramírez', '42334455', 'Cortador', 'Corte', NULL, NULL, '2025-07-04', 1600.00, 1, '2026-07-04 16:17:07', '2026-07-04 16:41:00'),
	(4, 'Ana Flores', '43445566', 'Control de Calidad', 'Control de Calidad', NULL, NULL, '2025-02-04', 1550.00, 1, '2026-07-04 16:17:07', '2026-07-04 16:41:00'),
	(5, 'Pedro Castro', '44556677', 'Acabador', 'Acabado', NULL, NULL, '2025-07-04', 1450.00, 1, '2026-07-04 16:17:07', '2026-07-04 16:41:00');

-- Volcando estructura para tabla saas_taller_textil.materia_primas
CREATE TABLE IF NOT EXISTS `materia_primas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('tela','hilo','avio','otro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tela',
  `unidad` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unidad',
  `stock` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costo_unitario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `proveedor_id` bigint unsigned DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materia_primas_codigo_unique` (`codigo`),
  KEY `materia_primas_proveedor_id_foreign` (`proveedor_id`),
  CONSTRAINT `materia_primas_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.materia_primas: ~4 rows (aproximadamente)
DELETE FROM `materia_primas`;
INSERT INTO `materia_primas` (`id`, `codigo`, `nombre`, `tipo`, `unidad`, `stock`, `stock_minimo`, `costo_unitario`, `proveedor_id`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'TEL-001', 'Tela algodón jersey', 'tela', 'metro', 850.00, 200.00, 12.50, 1, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(2, 'TEL-002', 'Tela drill', 'tela', 'metro', 120.00, 150.00, 15.00, 1, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(3, 'HIL-001', 'Hilo poliéster', 'hilo', 'cono', 60.00, 40.00, 8.00, 1, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(4, 'AVI-001', 'Botones', 'avio', 'unidad', 5000.00, 1000.00, 0.15, 1, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07');

-- Volcando estructura para tabla saas_taller_textil.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.migrations: ~12 rows (aproximadamente)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '2026_07_04_100001_create_clientes_table', 2),
	(3, '2026_07_04_100002_create_proveedores_table', 2),
	(4, '2026_07_04_100003_create_productos_table', 2),
	(5, '2026_07_04_100004_create_materia_primas_table', 2),
	(6, '2026_07_04_100005_create_empleados_table', 2),
	(7, '2026_07_04_100006_create_pedidos_table', 3),
	(8, '2026_07_04_100007_create_ordenes_produccion_table', 3),
	(9, '2026_07_04_100008_create_ventas_table', 4),
	(10, '2026_07_04_100009_create_compras_table', 4),
	(11, '2026_07_04_100010_create_movimientos_inventario_table', 5),
	(12, '2026_07_04_100011_create_configuraciones_table', 5);

-- Volcando estructura para tabla saas_taller_textil.movimientos_inventario
CREATE TABLE IF NOT EXISTS `movimientos_inventario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `materia_prima_id` bigint unsigned NOT NULL,
  `tipo` enum('entrada','salida','ajuste') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'entrada',
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_anterior` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_resultante` decimal(10,2) NOT NULL DEFAULT '0.00',
  `motivo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referencia` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `fecha` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_inventario_materia_prima_id_foreign` (`materia_prima_id`),
  KEY `movimientos_inventario_user_id_foreign` (`user_id`),
  CONSTRAINT `movimientos_inventario_materia_prima_id_foreign` FOREIGN KEY (`materia_prima_id`) REFERENCES `materia_primas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_inventario_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.movimientos_inventario: ~4 rows (aproximadamente)
DELETE FROM `movimientos_inventario`;
INSERT INTO `movimientos_inventario` (`id`, `materia_prima_id`, `tipo`, `cantidad`, `stock_anterior`, `stock_resultante`, `motivo`, `referencia`, `user_id`, `fecha`, `created_at`, `updated_at`) VALUES
	(1, 1, 'entrada', 850.00, 0.00, 850.00, 'Stock inicial', NULL, 1, '2026-05-25', '2026-07-04 16:41:00', '2026-07-04 16:41:00'),
	(2, 2, 'entrada', 120.00, 0.00, 120.00, 'Stock inicial', NULL, 1, '2026-06-12', '2026-07-04 16:41:00', '2026-07-04 16:41:00'),
	(3, 3, 'entrada', 60.00, 0.00, 60.00, 'Stock inicial', NULL, 1, '2026-06-12', '2026-07-04 16:41:00', '2026-07-04 16:41:00'),
	(4, 4, 'entrada', 5000.00, 0.00, 5000.00, 'Stock inicial', NULL, 1, '2026-05-29', '2026-07-04 16:41:00', '2026-07-04 16:41:00');

-- Volcando estructura para tabla saas_taller_textil.ordenes_produccion
CREATE TABLE IF NOT EXISTS `ordenes_produccion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pedido_id` bigint unsigned DEFAULT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `empleado_id` bigint unsigned DEFAULT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `etapa` enum('corte','confeccion','acabado','control','terminado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'corte',
  `avance` tinyint unsigned NOT NULL DEFAULT '0',
  `estado` enum('en_proceso','pausado','terminado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_proceso',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordenes_produccion_codigo_unique` (`codigo`),
  KEY `ordenes_produccion_pedido_id_foreign` (`pedido_id`),
  KEY `ordenes_produccion_producto_id_foreign` (`producto_id`),
  KEY `ordenes_produccion_empleado_id_foreign` (`empleado_id`),
  CONSTRAINT `ordenes_produccion_empleado_id_foreign` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ordenes_produccion_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ordenes_produccion_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.ordenes_produccion: ~8 rows (aproximadamente)
DELETE FROM `ordenes_produccion`;
INSERT INTO `ordenes_produccion` (`id`, `codigo`, `pedido_id`, `producto_id`, `empleado_id`, `cantidad`, `etapa`, `avance`, `estado`, `fecha_inicio`, `fecha_fin`, `observaciones`, `created_at`, `updated_at`) VALUES
	(1, 'OP-0001', 1, 2, 3, 66, 'acabado', 95, 'en_proceso', '2026-06-26', NULL, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(2, 'OP-0002', 1, 3, 4, 105, 'confeccion', 95, 'en_proceso', '2026-06-24', NULL, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(3, 'OP-0003', 2, 5, 1, 132, 'control', 5, 'en_proceso', '2026-06-28', NULL, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(4, 'OP-0004', 3, 2, 5, 114, 'corte', 15, 'en_proceso', '2026-07-01', NULL, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(5, 'OP-0005', 6, 3, 5, 77, 'control', 50, 'en_proceso', '2026-07-03', NULL, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(6, 'OP-0006', 5, 1, 5, 129, 'confeccion', 75, 'en_proceso', '2026-07-01', NULL, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(7, 'OP-0007', 1, 3, 3, 65, 'control', 10, 'en_proceso', '2026-07-01', NULL, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(8, 'OP-0008', 6, 2, 2, 107, 'acabado', 40, 'en_proceso', '2026-06-24', NULL, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29');

-- Volcando estructura para tabla saas_taller_textil.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.password_reset_tokens: ~0 rows (aproximadamente)
DELETE FROM `password_reset_tokens`;

-- Volcando estructura para tabla saas_taller_textil.pedidos
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente_id` bigint unsigned NOT NULL,
  `fecha_pedido` date NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `estado` enum('pendiente','en_produccion','acabado','entregado','anulado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pedidos_codigo_unique` (`codigo`),
  KEY `pedidos_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `pedidos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.pedidos: ~6 rows (aproximadamente)
DELETE FROM `pedidos`;
INSERT INTO `pedidos` (`id`, `codigo`, `cliente_id`, `fecha_pedido`, `fecha_entrega`, `estado`, `total`, `observaciones`, `created_at`, `updated_at`) VALUES
	(1, 'PED-0001', 4, '2026-06-30', '2026-07-20', 'pendiente', 26866.30, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(2, 'PED-0002', 2, '2026-06-16', '2026-07-11', 'en_produccion', 17254.40, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(3, 'PED-0003', 1, '2026-06-30', '2026-07-23', 'en_produccion', 12176.50, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(4, 'PED-0004', 5, '2026-06-08', '2026-07-20', 'en_produccion', 9818.50, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(5, 'PED-0005', 4, '2026-06-25', '2026-07-23', 'pendiente', 7301.70, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(6, 'PED-0006', 2, '2026-06-24', '2026-07-18', 'entregado', 14738.60, NULL, '2026-07-04 16:26:29', '2026-07-04 16:26:29');

-- Volcando estructura para tabla saas_taller_textil.pedido_items
CREATE TABLE IF NOT EXISTS `pedido_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio_unitario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_items_pedido_id_foreign` (`pedido_id`),
  KEY `pedido_items_producto_id_foreign` (`producto_id`),
  CONSTRAINT `pedido_items_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedido_items_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.pedido_items: ~13 rows (aproximadamente)
DELETE FROM `pedido_items`;
INSERT INTO `pedido_items` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 157, 29.90, 4694.30, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(2, 1, 3, 182, 34.90, 6351.80, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(3, 1, 4, 198, 79.90, 15820.20, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(4, 2, 2, 80, 39.90, 3192.00, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(5, 2, 4, 176, 79.90, 14062.40, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(6, 3, 1, 132, 29.90, 3946.80, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(7, 3, 4, 103, 79.90, 8229.70, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(8, 4, 2, 153, 39.90, 6104.70, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(9, 4, 5, 62, 59.90, 3713.80, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(10, 5, 2, 183, 39.90, 7301.70, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(11, 6, 2, 21, 39.90, 837.90, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(12, 6, 3, 146, 34.90, 5095.40, '2026-07-04 16:26:29', '2026-07-04 16:26:29'),
	(13, 6, 5, 147, 59.90, 8805.30, '2026-07-04 16:26:29', '2026-07-04 16:26:29');

-- Volcando estructura para tabla saas_taller_textil.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `talla` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_codigo_unique` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.productos: ~5 rows (aproximadamente)
DELETE FROM `productos`;
INSERT INTO `productos` (`id`, `codigo`, `nombre`, `categoria`, `talla`, `color`, `precio`, `costo`, `stock`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'POL-001', 'Polo cuello redondo', 'Polos', 'M', 'Blanco', 29.90, 14.50, 120, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(2, 'POL-002', 'Polo pique', 'Polos', 'L', 'Azul marino', 39.90, 19.00, 80, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(3, 'UNI-001', 'Camisa escolar', 'Uniformes', '12', 'Celeste', 34.90, 16.00, 45, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(4, 'CHO-001', 'Chompa lana', 'Chompas', 'S', 'Gris', 79.90, 38.00, 30, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(5, 'PAN-001', 'Pantalón drill', 'Pantalones', '32', 'Beige', 59.90, 27.00, 4, 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07');

-- Volcando estructura para tabla saas_taller_textil.proveedores
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('nacional','importado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nacional',
  `condicion_pago` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.proveedores: ~3 rows (aproximadamente)
DELETE FROM `proveedores`;
INSERT INTO `proveedores` (`id`, `razon_social`, `ruc`, `email`, `telefono`, `direccion`, `contacto`, `tipo`, `condicion_pago`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'Hilos y Telas Import S.A.C.', '20455566677', NULL, NULL, NULL, NULL, 'importado', 'Crédito 30 días', 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(2, 'Distribuidora Textil Nacional', '20466677788', NULL, NULL, NULL, NULL, 'nacional', 'Contado', 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07'),
	(3, 'Avíos y Botones Perú', '20477788899', NULL, NULL, NULL, NULL, 'nacional', 'Crédito 15 días', 1, '2026-07-04 16:17:07', '2026-07-04 16:17:07');

-- Volcando estructura para tabla saas_taller_textil.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.sessions: ~0 rows (aproximadamente)
DELETE FROM `sessions`;

-- Volcando estructura para tabla saas_taller_textil.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operario',
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.users: ~3 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `position`, `avatar`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Administrador', 'admin@tallertextil.com', '2026-07-04 16:40:59', '$2y$12$SjeMgdgOUkrx7abgmSF.Y.H/iCCtkcsydW.cOKXxuCAsK/fAC5LRq', 'admin', NULL, 'Gerente General', NULL, 1, NULL, '2026-07-04 15:58:33', '2026-07-04 16:40:59'),
	(2, 'Carlos Mendoza', 'supervisor@tallertextil.com', '2026-07-04 16:40:59', '$2y$12$yw0xeX4NfT8B60gn5NQbOOUa2o3LxfBgHGRepEHhP6P97Vj8VdYFy', 'supervisor', NULL, 'Jefe de Producción', NULL, 1, NULL, '2026-07-04 15:58:33', '2026-07-04 16:40:59'),
	(3, 'María Torres', 'operario@tallertextil.com', '2026-07-04 16:40:59', '$2y$12$B9aGeeMOmpUlOWmFr8wdK.0WckQnDlFXjG9cgXQm7SLRDq82rX8k6', 'operario', NULL, 'Costurera', NULL, 1, NULL, '2026-07-04 15:58:34', '2026-07-04 16:40:59');

-- Volcando estructura para tabla saas_taller_textil.ventas
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente_id` bigint unsigned NOT NULL,
  `pedido_id` bigint unsigned DEFAULT NULL,
  `fecha` date NOT NULL,
  `tipo_comprobante` enum('boleta','factura') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'boleta',
  `estado` enum('pagado','pendiente','anulado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pagado',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ventas_codigo_unique` (`codigo`),
  KEY `ventas_cliente_id_foreign` (`cliente_id`),
  KEY `ventas_pedido_id_foreign` (`pedido_id`),
  CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `ventas_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.ventas: ~5 rows (aproximadamente)
DELETE FROM `ventas`;
INSERT INTO `ventas` (`id`, `codigo`, `cliente_id`, `pedido_id`, `fecha`, `tipo_comprobante`, `estado`, `subtotal`, `igv`, `total`, `observaciones`, `created_at`, `updated_at`) VALUES
	(1, 'VEN-0001', 3, NULL, '2026-06-16', 'boleta', 'pendiente', 7142.00, 1285.56, 8427.56, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(2, 'VEN-0002', 3, NULL, '2026-06-28', 'factura', 'pagado', 3262.90, 587.32, 3850.22, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(3, 'VEN-0003', 5, NULL, '2026-06-28', 'boleta', 'pagado', 6089.60, 1096.13, 7185.73, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(4, 'VEN-0004', 2, NULL, '2026-06-28', 'boleta', 'pagado', 5622.20, 1012.00, 6634.20, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(5, 'VEN-0005', 5, NULL, '2026-06-20', 'boleta', 'pendiente', 2852.90, 513.52, 3366.42, NULL, '2026-07-04 16:34:34', '2026-07-04 16:34:34');

-- Volcando estructura para tabla saas_taller_textil.venta_items
CREATE TABLE IF NOT EXISTS `venta_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio_unitario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_items_venta_id_foreign` (`venta_id`),
  KEY `venta_items_producto_id_foreign` (`producto_id`),
  CONSTRAINT `venta_items_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `venta_items_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_taller_textil.venta_items: ~14 rows (aproximadamente)
DELETE FROM `venta_items`;
INSERT INTO `venta_items` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 17, 39.90, 678.30, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(2, 1, 3, 57, 34.90, 1989.30, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(3, 1, 4, 56, 79.90, 4474.40, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(4, 2, 2, 17, 39.90, 678.30, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(5, 2, 3, 26, 34.90, 907.40, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(6, 2, 5, 28, 59.90, 1677.20, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(7, 3, 2, 27, 39.90, 1077.30, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(8, 3, 4, 20, 79.90, 1598.00, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(9, 3, 5, 57, 59.90, 3414.30, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(10, 4, 1, 5, 29.90, 149.50, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(11, 4, 4, 55, 79.90, 4394.50, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(12, 4, 5, 18, 59.90, 1078.20, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(13, 5, 3, 56, 34.90, 1954.40, '2026-07-04 16:34:34', '2026-07-04 16:34:34'),
	(14, 5, 5, 15, 59.90, 898.50, '2026-07-04 16:34:34', '2026-07-04 16:34:34');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
