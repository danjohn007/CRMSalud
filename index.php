<?php
/**
 * CRM Salud - Sistema CRM especializado en el sector salud
 * Punto de entrada principal del sistema
 */

// Iniciamos sesión
session_start();

// Configuración de la aplicación
require_once 'config/config.php';
require_once 'config/database.php';

// Autoload de clases
spl_autoload_register(function($class) {
    $paths = [
        'models/',
        'controllers/',
        'core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Inicializar el router
$router = new Router();
$router->dispatch();
?>