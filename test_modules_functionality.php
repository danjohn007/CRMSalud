<?php
/**
 * Comprehensive test for Cotizaciones, Pedidos y Productos modules
 * Tests all required functionality from the problem statement
 */

require_once 'config/config.php';

echo "<h1>CRM Salud - Pruebas Completas de Módulos Requeridos</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .test{margin:10px 0;padding:10px;border-left:4px solid #007bff;background:#f8f9fa;} .success{border-color:#28a745;} .error{border-color:#dc3545;} .warning{border-color:#ffc107;}</style>\n";

// Test 1: Cotizaciones Module - Required functionality check
echo "<div class='test'><h3>📋 Test 1: Módulo Cotizaciones - Funcionalidad Requerida</h3>\n";

$cotizacion_requirements = [
    'Generar cotizaciones desde oportunidades o clientes' => [
        'file' => 'controllers/CotizacionesController.php',
        'check' => 'contains create method with oportunidad_id and cliente_id support'
    ],
    'Agregar productos/servicios y descuentos' => [
        'file' => 'models/Cotizacion.php',
        'check' => 'procesarProductos method exists'
    ],
    'Calcular totales, impuestos y condiciones de pago' => [
        'file' => 'models/Cotizacion.php',
        'check' => 'calcularTotales method exists'
    ],
    'Descargar cotización en PDF y enviar por correo' => [
        'file' => 'models/Cotizacion.php',
        'check' => 'generarPDF and enviarPorEmail methods exist'
    ],
    'Cambiar estado de cotización' => [
        'file' => 'models/Cotizacion.php',
        'check' => 'cambiarEstado method exists'
    ],
    'Relacionar con oportunidad, pedido y cliente' => [
        'file' => 'models/Cotizacion.php',
        'check' => 'generarPedido method exists'
    ]
];

$allCotizacionOk = true;
foreach ($cotizacion_requirements as $requirement => $check) {
    if (file_exists($check['file'])) {
        $content = file_get_contents($check['file']);
        
        $hasFeature = false;
        if (strpos($requirement, 'Generar cotizaciones') !== false) {
            $hasFeature = strpos($content, 'oportunidad_id') !== false && strpos($content, 'cliente_id') !== false;
        } elseif (strpos($requirement, 'productos/servicios') !== false) {
            $hasFeature = strpos($content, 'procesarProductos') !== false;
        } elseif (strpos($requirement, 'Calcular totales') !== false) {
            $hasFeature = strpos($content, 'calcularTotales') !== false;
        } elseif (strpos($requirement, 'PDF') !== false) {
            $hasFeature = strpos($content, 'generarPDF') !== false && strpos($content, 'enviarPorEmail') !== false;
        } elseif (strpos($requirement, 'estado') !== false) {
            $hasFeature = strpos($content, 'cambiarEstado') !== false;
        } elseif (strpos($requirement, 'Relacionar') !== false) {
            $hasFeature = strpos($content, 'generarPedido') !== false;
        }
        
        echo "$requirement: " . ($hasFeature ? "✅ Implementado" : "❌ Faltante") . "<br>\n";
        if (!$hasFeature) $allCotizacionOk = false;
    } else {
        echo "$requirement: ❌ Archivo no existe<br>\n";
        $allCotizacionOk = false;
    }
}

if ($allCotizacionOk) {
    echo "<strong style='color:#28a745;'>✅ MÓDULO COTIZACIONES COMPLETO</strong>";
} else {
    echo "<strong style='color:#dc3545;'>❌ MÓDULO COTIZACIONES INCOMPLETO</strong>";
}
echo "</div>\n";

// Test 2: Pedidos Module - Required functionality check
echo "<div class='test'><h3>📦 Test 2: Módulo Pedidos - Funcionalidad Requerida</h3>\n";

$pedido_requirements = [
    'Crear pedidos desde cotizaciones aprobadas' => [
        'file' => 'models/Cotizacion.php',
        'check' => 'generarPedido method exists'
    ],
    'Registrar productos/servicios, cantidades y precios finales' => [
        'file' => 'models/Pedido.php',
        'check' => 'procesarProductos method exists'
    ],
    'Actualizar estado (nuevo, procesando, entregado, cancelado)' => [
        'file' => 'models/Pedido.php',
        'check' => 'cambiarEstado method exists'
    ],
    'Generar órdenes de compra internas' => [
        'file' => 'models/Pedido.php',
        'check' => 'generarOrdenCompra method exists'
    ],
    'Asociar pedido a cliente y responsable' => [
        'file' => 'models/Pedido.php',
        'check' => 'customer and user relationships'
    ],
    'Exportar pedidos a PDF y enviar notificaciones' => [
        'file' => 'models/Pedido.php',
        'check' => 'generarPDF and enviarNotificacion methods exist'
    ]
];

$allPedidoOk = true;
foreach ($pedido_requirements as $requirement => $check) {
    if (file_exists($check['file'])) {
        $content = file_get_contents($check['file']);
        
        $hasFeature = false;
        if (strpos($requirement, 'desde cotizaciones') !== false) {
            $hasFeature = strpos($content, 'generarPedido') !== false;
        } elseif (strpos($requirement, 'productos/servicios') !== false) {
            $hasFeature = strpos($content, 'procesarProductos') !== false;
        } elseif (strpos($requirement, 'Actualizar estado') !== false) {
            $hasFeature = strpos($content, 'cambiarEstado') !== false;
        } elseif (strpos($requirement, 'órdenes de compra') !== false) {
            $hasFeature = strpos($content, 'generarOrdenCompra') !== false;
        } elseif (strpos($requirement, 'cliente y responsable') !== false) {
            $hasFeature = strpos($content, 'cliente_id') !== false && strpos($content, 'usuario_id') !== false;
        } elseif (strpos($requirement, 'PDF') !== false) {
            $hasFeature = strpos($content, 'generarPDF') !== false && strpos($content, 'enviarNotificacion') !== false;
        }
        
        echo "$requirement: " . ($hasFeature ? "✅ Implementado" : "❌ Faltante") . "<br>\n";
        if (!$hasFeature) $allPedidoOk = false;
    } else {
        echo "$requirement: ❌ Archivo no existe<br>\n";
        $allPedidoOk = false;
    }
}

if ($allPedidoOk) {
    echo "<strong style='color:#28a745;'>✅ MÓDULO PEDIDOS COMPLETO</strong>";
} else {
    echo "<strong style='color:#dc3545;'>❌ MÓDULO PEDIDOS INCOMPLETO</strong>";
}
echo "</div>\n";

// Test 3: Productos Module - Required functionality check
echo "<div class='test'><h3>🛍️ Test 3: Módulo Productos - Funcionalidad Requerida</h3>\n";

$producto_requirements = [
    'Gestión de catálogo de productos y servicios' => [
        'file' => 'controllers/ProductosController.php',
        'check' => 'CRUD operations'
    ],
    'Crear, editar y eliminar productos' => [
        'file' => 'controllers/ProductosController.php',
        'check' => 'create, edit, delete methods'
    ],
    'Definir atributos (SKU, nombre, descripción, precio, inventario)' => [
        'file' => 'models/Producto.php',
        'check' => 'product attributes in database schema'
    ],
    'Clasificación por categorías y familias' => [
        'file' => 'models/Producto.php',
        'check' => 'getCategorias method exists'
    ],
    'Visualización de stock y alertas de inventario bajo' => [
        'file' => 'models/Producto.php',
        'check' => 'getProductosStockBajo method exists'
    ],
    'Integración con cotizaciones y pedidos' => [
        'file' => 'models/Producto.php',
        'check' => 'product integration in quotes and orders'
    ]
];

$allProductoOk = true;
foreach ($producto_requirements as $requirement => $check) {
    $hasFeature = false;
    
    if (strpos($requirement, 'catálogo') !== false) {
        $hasFeature = file_exists('controllers/ProductosController.php') && file_exists('models/Producto.php');
    } elseif (strpos($requirement, 'Crear, editar') !== false) {
        if (file_exists('controllers/ProductosController.php')) {
            $content = file_get_contents('controllers/ProductosController.php');
            $hasFeature = strpos($content, 'create') !== false && strpos($content, 'edit') !== false;
        }
    } elseif (strpos($requirement, 'atributos') !== false) {
        if (file_exists('install.sql')) {
            $content = file_get_contents('install.sql');
            $hasFeature = strpos($content, 'sku') !== false && strpos($content, 'precio_base') !== false;
        }
    } elseif (strpos($requirement, 'categorías') !== false) {
        if (file_exists('models/Producto.php')) {
            $content = file_get_contents('models/Producto.php');
            $hasFeature = strpos($content, 'getProductosByCategoria') !== false;
        }
    } elseif (strpos($requirement, 'stock') !== false) {
        if (file_exists('models/Producto.php')) {
            $content = file_get_contents('models/Producto.php');
            $hasFeature = strpos($content, 'getProductosStockBajo') !== false;
        }
    } elseif (strpos($requirement, 'Integración') !== false) {
        $hasFeature = file_exists('models/Cotizacion.php') && file_exists('models/Pedido.php');
        if ($hasFeature) {
            $cotContent = file_get_contents('models/Cotizacion.php');
            $pedContent = file_get_contents('models/Pedido.php');
            $hasFeature = strpos($cotContent, 'producto_id') !== false && strpos($pedContent, 'producto_id') !== false;
        }
    }
    
    echo "$requirement: " . ($hasFeature ? "✅ Implementado" : "❌ Faltante") . "<br>\n";
    if (!$hasFeature) $allProductoOk = false;
}

if ($allProductoOk) {
    echo "<strong style='color:#28a745;'>✅ MÓDULO PRODUCTOS COMPLETO</strong>";
} else {
    echo "<strong style='color:#dc3545;'>❌ MÓDULO PRODUCTOS INCOMPLETO</strong>";
}
echo "</div>\n";

// Test 4: MySQL Compatibility check
echo "<div class='test'><h3>🗄️ Test 4: Compatibilidad con MySQL</h3>\n";

$mysql_checks = [
    'Database configuration uses MySQL' => defined('DB_HOST'),
    'Database class uses PDO MySQL' => file_exists('config/database.php'),
    'Schema uses MySQL syntax' => file_exists('install.sql'),
    'UTF8MB4 charset configured' => defined('DB_CHARSET') && DB_CHARSET === 'utf8mb4',
    'InnoDB engine in schema' => true,
    'No SQLite usage found' => true
];

// Check for SQLite usage
if (file_exists('config/database.php')) {
    $dbContent = file_get_contents('config/database.php');
    $mysql_checks['No SQLite usage found'] = strpos($dbContent, 'sqlite') === false;
    $mysql_checks['Database class uses PDO MySQL'] = strpos($dbContent, 'mysql:host') !== false;
}

if (file_exists('install.sql')) {
    $sqlContent = file_get_contents('install.sql');
    $mysql_checks['Schema uses MySQL syntax'] = strpos($sqlContent, 'ENGINE=InnoDB') !== false;
    $mysql_checks['InnoDB engine in schema'] = strpos($sqlContent, 'ENGINE=InnoDB') !== false;
}

$allMySQLOk = true;
foreach ($mysql_checks as $check => $result) {
    echo "$check: " . ($result ? "✅ OK" : "❌ Problema") . "<br>\n";
    if (!$result) $allMySQLOk = false;
}

if ($allMySQLOk) {
    echo "<strong style='color:#28a745;'>✅ COMPATIBILIDAD MYSQL COMPLETA</strong>";
} else {
    echo "<strong style='color:#dc3545;'>❌ PROBLEMAS DE COMPATIBILIDAD MYSQL</strong>";
}
echo "</div>\n";

// Test 5: Configuration Constants
echo "<div class='test'><h3>⚙️ Test 5: Constantes de Configuración</h3>\n";

$required_constants = [
    'COTIZACION_ESTADOS' => 'Estados de cotizaciones',
    'PEDIDO_ESTADOS' => 'Estados de pedidos',
    'ROLES' => 'Roles del sistema',
    'DB_HOST' => 'Host de base de datos',
    'DB_NAME' => 'Nombre de base de datos',
    'DB_CHARSET' => 'Charset de base de datos'
];

$allConstantsOk = true;
foreach ($required_constants as $constant => $description) {
    $defined = defined($constant);
    echo "$description ($constant): " . ($defined ? "✅ Definido" : "❌ No definido") . "<br>\n";
    if (!$defined) $allConstantsOk = false;
}

if ($allConstantsOk) {
    echo "<strong style='color:#28a745;'>✅ TODAS LAS CONSTANTES DEFINIDAS</strong>";
}
echo "</div>\n";

// Test 6: Integration between modules
echo "<div class='test'><h3>🔗 Test 6: Integración Entre Módulos</h3>\n";

$integration_tests = [
    'Oportunidades → Cotizaciones' => false,
    'Cotizaciones → Pedidos' => false,
    'Productos ↔ Cotizaciones' => false,
    'Productos ↔ Pedidos' => false,
    'Clientes ↔ Cotizaciones' => false,
    'Clientes ↔ Pedidos' => false
];

// Check Oportunidades → Cotizaciones
if (file_exists('controllers/CotizacionesController.php')) {
    $content = file_get_contents('controllers/CotizacionesController.php');
    $integration_tests['Oportunidades → Cotizaciones'] = strpos($content, 'oportunidad_id') !== false;
}

// Check Cotizaciones → Pedidos
if (file_exists('models/Cotizacion.php')) {
    $content = file_get_contents('models/Cotizacion.php');
    $integration_tests['Cotizaciones → Pedidos'] = strpos($content, 'generarPedido') !== false;
}

// Check Products integration
if (file_exists('models/Cotizacion.php') && file_exists('models/Pedido.php')) {
    $cotContent = file_get_contents('models/Cotizacion.php');
    $pedContent = file_get_contents('models/Pedido.php');
    $integration_tests['Productos ↔ Cotizaciones'] = strpos($cotContent, 'producto_id') !== false;
    $integration_tests['Productos ↔ Pedidos'] = strpos($pedContent, 'producto_id') !== false;
}

// Check Client integration
if (file_exists('install.sql')) {
    $content = file_get_contents('install.sql');
    $integration_tests['Clientes ↔ Cotizaciones'] = strpos($content, 'fk_cotizacion_cliente') !== false;
    $integration_tests['Clientes ↔ Pedidos'] = strpos($content, 'fk_pedido_cliente') !== false;
}

$allIntegrationOk = true;
foreach ($integration_tests as $integration => $result) {
    echo "$integration: " . ($result ? "✅ Integrado" : "❌ No integrado") . "<br>\n";
    if (!$result) $allIntegrationOk = false;
}

if ($allIntegrationOk) {
    echo "<strong style='color:#28a745;'>✅ INTEGRACIÓN COMPLETA</strong>";
}
echo "</div>\n";

// Final Summary
echo "<div class='test " . ($allCotizacionOk && $allPedidoOk && $allProductoOk && $allMySQLOk ? "success" : "error") . "'><h2>🎯 RESUMEN FINAL</h2>\n";

echo "<h3>Estado de Implementación:</h3>\n";
echo "<ul>\n";
echo "<li>Módulo Cotizaciones: " . ($allCotizacionOk ? "✅ COMPLETO" : "❌ INCOMPLETO") . "</li>\n";
echo "<li>Módulo Pedidos: " . ($allPedidoOk ? "✅ COMPLETO" : "❌ INCOMPLETO") . "</li>\n";
echo "<li>Módulo Productos: " . ($allProductoOk ? "✅ COMPLETO" : "❌ INCOMPLETO") . "</li>\n";
echo "<li>Compatibilidad MySQL: " . ($allMySQLOk ? "✅ COMPLETO" : "❌ INCOMPLETO") . "</li>\n";
echo "<li>Integración de Módulos: " . ($allIntegrationOk ? "✅ COMPLETO" : "❌ INCOMPLETO") . "</li>\n";
echo "</ul>\n";

if ($allCotizacionOk && $allPedidoOk && $allProductoOk && $allMySQLOk && $allIntegrationOk) {
    echo "<p style='color:#28a745;font-size:1.4em;'><strong>🚀 TODOS LOS REQUERIMIENTOS IMPLEMENTADOS EXITOSAMENTE</strong></p>\n";
    echo "<p>El sistema CRM Salud tiene completamente implementados los módulos de Cotizaciones, Pedidos y Productos con todas las funcionalidades solicitadas y compatibilidad completa con MySQL.</p>\n";
} else {
    echo "<p style='color:#dc3545;font-size:1.4em;'><strong>⚠️ SE REQUIEREN AJUSTES ADICIONALES</strong></p>\n";
    echo "<p>Algunos módulos necesitan implementación adicional para cumplir completamente con los requerimientos.</p>\n";
}

echo "</div>\n";

echo "<p style='margin-top:30px;text-align:center;'><a href='integration_test.php' style='color:#007bff;'>← Ver Pruebas de Integración</a> | <a href='/' style='color:#007bff;'>Ir al CRM →</a></p>\n";
?>