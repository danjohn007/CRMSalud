-- CRM Salud - Base de Datos
-- Sistema CRM especializado en el sector salud
-- Versión: 1.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- ======================================
-- CREAR BASE DE DATOS
-- ======================================

CREATE DATABASE IF NOT EXISTS `crm_salud` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `crm_salud`;

-- ======================================
-- TABLA: usuarios
-- ======================================

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','vendedor','marketing','inventarios') NOT NULL DEFAULT 'vendedor',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso` datetime NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: clientes
-- ======================================

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('doctor','farmacia','hospital') NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(100) NULL,
  `telefono` varchar(20) NULL,
  `direccion` text NULL,
  `ciudad` varchar(100) NULL,
  `estado` varchar(100) NULL,
  `codigo_postal` varchar(10) NULL,
  `especialidad` varchar(100) NULL,
  `cedula_profesional` varchar(50) NULL,
  `rfc` varchar(20) NULL,
  `volumen_compra` enum('bajo','medio','alto') DEFAULT 'medio',
  `descuento_autorizado` decimal(5,2) DEFAULT 0.00,
  `limite_credito` decimal(12,2) DEFAULT 0.00,
  `dias_credito` int(11) DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `notas` text NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tipo` (`tipo`),
  KEY `ciudad` (`ciudad`),
  KEY `especialidad` (`especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: productos
-- ======================================

CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NULL,
  `categoria` varchar(100) NULL,
  `marca` varchar(100) NULL,
  `principio_activo` varchar(200) NULL,
  `presentacion` varchar(100) NULL,
  `precio_base` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_publico` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_minimo` int(11) DEFAULT 0,
  `requiere_receta` tinyint(1) DEFAULT 0,
  `controlado` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `categoria` (`categoria`),
  KEY `marca` (`marca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: listas_precios
-- ======================================

CREATE TABLE `listas_precios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: precios_productos
-- ======================================

CREATE TABLE `precios_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `lista_precio_id` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descuento_maximo` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `producto_lista` (`producto_id`, `lista_precio_id`),
  CONSTRAINT `fk_precios_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_precios_lista` FOREIGN KEY (`lista_precio_id`) REFERENCES `listas_precios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: inventarios
-- ======================================

CREATE TABLE `inventarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `lote` varchar(50) NOT NULL,
  `fecha_vencimiento` date NULL,
  `stock_actual` int(11) NOT NULL DEFAULT 0,
  `costo_unitario` decimal(10,2) DEFAULT 0.00,
  `ubicacion` varchar(100) NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `producto_lote` (`producto_id`, `lote`),
  CONSTRAINT `fk_inventario_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: oportunidades
-- ======================================

CREATE TABLE `oportunidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NULL,
  `estado` enum('prospecto','contactado','calificado','propuesta','negociacion','ganado','perdido') NOT NULL DEFAULT 'prospecto',
  `valor_estimado` decimal(12,2) DEFAULT 0.00,
  `probabilidad` int(11) DEFAULT 0,
  `fecha_cierre_estimada` date NULL,
  `fecha_cierre_real` date NULL,
  `motivo_perdida` text NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `estado` (`estado`),
  KEY `fecha_cierre_estimada` (`fecha_cierre_estimada`),
  CONSTRAINT `fk_oportunidad_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_oportunidad_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: cotizaciones
-- ======================================

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `oportunidad_id` int(11) NULL,
  `fecha_cotizacion` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `descuento_porcentaje` decimal(5,2) DEFAULT 0.00,
  `descuento_importe` decimal(12,2) DEFAULT 0.00,
  `impuestos` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` enum('borrador','enviada','aceptada','rechazada','vencida') NOT NULL DEFAULT 'borrador',
  `notas` text NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`),
  KEY `estado` (`estado`),
  KEY `fecha_cotizacion` (`fecha_cotizacion`),
  CONSTRAINT `fk_cotizacion_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cotizacion_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cotizacion_oportunidad` FOREIGN KEY (`oportunidad_id`) REFERENCES `oportunidades` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: cotizacion_detalles
-- ======================================

CREATE TABLE `cotizacion_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento_porcentaje` decimal(5,2) DEFAULT 0.00,
  `descuento_importe` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_detalle_cotizacion` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: pedidos
-- ======================================

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) NOT NULL,
  `cotizacion_id` int(11) NULL,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_pedido` date NOT NULL,
  `fecha_entrega_estimada` date NULL,
  `fecha_entrega_real` date NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `descuento_porcentaje` decimal(5,2) DEFAULT 0.00,
  `descuento_importe` decimal(12,2) DEFAULT 0.00,
  `impuestos` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` enum('nuevo','confirmado','preparando','enviado','entregado','cancelado') NOT NULL DEFAULT 'nuevo',
  `forma_pago` enum('efectivo','transferencia','cheque','credito') DEFAULT 'efectivo',
  `notas` text NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`),
  KEY `estado` (`estado`),
  KEY `fecha_pedido` (`fecha_pedido`),
  CONSTRAINT `fk_pedido_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pedido_cotizacion` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: pedido_detalles
-- ======================================

CREATE TABLE `pedido_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `inventario_id` int(11) NULL,
  `cantidad` int(11) NOT NULL,
  `cantidad_entregada` int(11) DEFAULT 0,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento_porcentaje` decimal(5,2) DEFAULT 0.00,
  `descuento_importe` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_detalle_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_pedido_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_inventario` FOREIGN KEY (`inventario_id`) REFERENCES `inventarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: segmentos_marketing
-- ======================================

CREATE TABLE `segmentos_marketing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NULL,
  `condiciones` text NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: campanas_marketing
-- ======================================

CREATE TABLE `campanas_marketing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text NULL,
  `tipo` enum('email','whatsapp','sms','telefono') NOT NULL,
  `segmento_id` int(11) NULL,
  `plantilla` text NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NULL,
  `estado` enum('borrador','programada','enviando','completada','pausada') NOT NULL DEFAULT 'borrador',
  `total_envios` int(11) DEFAULT 0,
  `total_abiertos` int(11) DEFAULT 0,
  `total_clics` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_campana_segmento` FOREIGN KEY (`segmento_id`) REFERENCES `segmentos_marketing` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: comunicaciones
-- ======================================

CREATE TABLE `comunicaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('email','whatsapp','sms','llamada','visita','reunion') NOT NULL,
  `asunto` varchar(200) NULL,
  `mensaje` text NULL,
  `fecha_comunicacion` datetime NOT NULL,
  `duracion_minutos` int(11) NULL,
  `resultado` enum('exitoso','sin_respuesta','ocupado','reagendar','no_interesado') NULL,
  `seguimiento_requerido` tinyint(1) DEFAULT 0,
  `fecha_seguimiento` datetime NULL,
  `notas` text NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tipo` (`tipo`),
  KEY `fecha_comunicacion` (`fecha_comunicacion`),
  CONSTRAINT `fk_comunicacion_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comunicacion_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- TABLA: actividades_calendario
-- ======================================

CREATE TABLE `actividades_calendario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `cliente_id` int(11) NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NULL,
  `tipo` enum('visita','reunion','llamada','tarea','evento') NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `todo_el_dia` tinyint(1) DEFAULT 0,
  `recordatorio_minutos` int(11) DEFAULT 15,
  `completada` tinyint(1) DEFAULT 0,
  `notas` text NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fecha_inicio` (`fecha_inicio`),
  KEY `tipo` (`tipo`),
  CONSTRAINT `fk_actividad_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_actividad_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- INSERTAR DATOS DE EJEMPLO
-- ======================================

-- Usuario administrador por defecto
INSERT INTO `usuarios` (`nombre`, `email`, `password`, `rol`) VALUES
('Administrador', 'admin@crmsalud.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Juan Vendedor', 'vendedor@crmsalud.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendedor'),
('Ana Marketing', 'marketing@crmsalud.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'marketing'),
('Pedro Inventarios', 'inventarios@crmsalud.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'inventarios');

-- Clientes de ejemplo
INSERT INTO `clientes` (`tipo`, `nombre`, `email`, `telefono`, `direccion`, `ciudad`, `especialidad`, `cedula_profesional`, `volumen_compra`) VALUES
('doctor', 'Dr. Juan Carlos Pérez', 'dr.perez@gmail.com', '5551234567', 'Av. Reforma 123, Col. Centro', 'Ciudad de México', 'Cardiología', '1234567', 'alto'),
('doctor', 'Dra. María González', 'dra.gonzalez@gmail.com', '5559876543', 'Calle 5 de Mayo 456, Col. Centro', 'Guadalajara', 'Pediatría', '2345678', 'medio'),
('farmacia', 'Farmacia San José', 'contacto@farmaciasanjose.com', '5555555555', 'Av. Juárez 789, Col. Centro', 'Monterrey', NULL, NULL, 'alto'),
('hospital', 'Hospital General Norte', 'compras@hospitalnorte.com', '5554444444', 'Blvd. Norte 1000, Col. Industrial', 'León', NULL, NULL, 'alto'),
('doctor', 'Dr. Carlos Rodríguez', 'dr.rodriguez@gmail.com', '5553333333', 'Calle Morelos 321, Col. Centro', 'Puebla', 'Ginecología', '3456789', 'medio');

-- Productos de ejemplo
INSERT INTO `productos` (`sku`, `nombre`, `descripcion`, `categoria`, `marca`, `principio_activo`, `presentacion`, `precio_base`, `precio_publico`, `stock_minimo`) VALUES
('PARA-500-20', 'Paracetamol 500mg', 'Analgésico y antipirético', 'Analgésicos', 'GenFarma', 'Paracetamol', 'Caja 20 tabletas', 25.00, 35.00, 100),
('IBUP-400-24', 'Ibuprofeno 400mg', 'Antiinflamatorio no esteroideo', 'Analgésicos', 'MedLab', 'Ibuprofeno', 'Caja 24 cápsulas', 45.00, 65.00, 50),
('AMOX-500-12', 'Amoxicilina 500mg', 'Antibiótico beta-lactámico', 'Antibióticos', 'BioFarma', 'Amoxicilina', 'Caja 12 cápsulas', 80.00, 120.00, 30),
('OMEP-20-14', 'Omeprazol 20mg', 'Inhibidor de la bomba de protones', 'Gastroenterología', 'PharmaPlus', 'Omeprazol', 'Caja 14 cápsulas', 95.00, 135.00, 25),
('METF-850-30', 'Metformina 850mg', 'Antidiabético oral', 'Diabetes', 'DiabetCare', 'Metformina', 'Caja 30 tabletas', 55.00, 85.00, 40);

-- Listas de precios
INSERT INTO `listas_precios` (`nombre`, `descripcion`) VALUES
('Precio General', 'Lista de precios estándar para todos los clientes'),
('Precio Mayorista', 'Lista de precios con descuento para clientes de alto volumen'),
('Precio Hospitales', 'Lista de precios especial para hospitales');

-- Precios por lista
INSERT INTO `precios_productos` (`producto_id`, `lista_precio_id`, `precio`, `descuento_maximo`) VALUES
(1, 1, 35.00, 5.00), (1, 2, 32.00, 8.00), (1, 3, 30.00, 10.00),
(2, 1, 65.00, 5.00), (2, 2, 58.00, 8.00), (2, 3, 55.00, 10.00),
(3, 1, 120.00, 5.00), (3, 2, 110.00, 8.00), (3, 3, 105.00, 10.00),
(4, 1, 135.00, 5.00), (4, 2, 125.00, 8.00), (4, 3, 120.00, 10.00),
(5, 1, 85.00, 5.00), (5, 2, 78.00, 8.00), (5, 3, 75.00, 10.00);

-- Inventarios de ejemplo
INSERT INTO `inventarios` (`producto_id`, `lote`, `fecha_vencimiento`, `stock_actual`, `costo_unitario`, `ubicacion`) VALUES
(1, 'PARA001', '2025-12-31', 500, 22.00, 'Estante A1'),
(2, 'IBUP001', '2025-06-30', 200, 40.00, 'Estante A2'),
(3, 'AMOX001', '2024-08-15', 150, 75.00, 'Estante B1'),
(4, 'OMEP001', '2025-03-20', 100, 85.00, 'Estante B2'),
(5, 'METF001', '2025-09-10', 250, 50.00, 'Estante C1');

-- Oportunidades de ejemplo
INSERT INTO `oportunidades` (`cliente_id`, `usuario_id`, `nombre`, `descripcion`, `estado`, `valor_estimado`, `probabilidad`, `fecha_cierre_estimada`) VALUES
(1, 2, 'Pedido mensual Dr. Pérez', 'Pedido recurrente de medicamentos cardiovasculares', 'propuesta', 15000.00, 80, '2024-02-15'),
(3, 2, 'Contrato anual Farmacia San José', 'Contrato de suministro anual de medicamentos básicos', 'negociacion', 120000.00, 60, '2024-03-01'),
(4, 2, 'Licitación Hospital Norte', 'Licitación para suministro de medicamentos del próximo año', 'calificado', 250000.00, 40, '2024-04-30');

-- Segmentos de marketing
INSERT INTO `segmentos_marketing` (`nombre`, `descripcion`, `condiciones`) VALUES
('Doctores Cardiología', 'Médicos especializados en cardiología', 'tipo = doctor AND especialidad = Cardiología'),
('Clientes Alto Volumen', 'Clientes con alto volumen de compra', 'volumen_compra = alto'),
('Farmacias Ciudad de México', 'Farmacias ubicadas en Ciudad de México', 'tipo = farmacia AND ciudad = Ciudad de México');

-- Actividades de calendario de ejemplo
INSERT INTO `actividades_calendario` (`usuario_id`, `cliente_id`, `titulo`, `descripcion`, `tipo`, `fecha_inicio`, `fecha_fin`) VALUES
(2, 1, 'Visita Dr. Pérez', 'Visita mensual para revisar pedidos y nuevos productos', 'visita', '2024-02-10 10:00:00', '2024-02-10 11:00:00'),
(2, 3, 'Reunión Farmacia San José', 'Reunión para discutir términos del contrato anual', 'reunion', '2024-02-12 14:00:00', '2024-02-12 15:30:00'),
(3, NULL, 'Campaña Email Febrero', 'Preparar y enviar campaña de email marketing de febrero', 'tarea', '2024-02-05 09:00:00', '2024-02-05 17:00:00');

COMMIT;

-- ======================================
-- NOTAS IMPORTANTES
-- ======================================
-- 
-- Usuario por defecto:
-- Email: admin@crmsalud.com
-- Contraseña: password
-- 
-- Todos los usuarios de ejemplo tienen la contraseña: password
-- 
-- ======================================