<?php
/**
 * Configuración principal del sistema CRM Salud
 */

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de la aplicación
define('APP_NAME', 'CRM Salud');
define('APP_VERSION', '1.0.0');

// Detectar automáticamente la URL base
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$path = dirname($script);
$path = ($path === '/') ? '' : $path;

define('BASE_URL', $protocol . $host . $path . '/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Rutas del sistema
define('ROOT_PATH', dirname(__FILE__) . '/../');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'crm_salud');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de sesiones (solo si no hay sesión activa)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 3600 * 8); // 8 horas
    ini_set('session.gc_maxlifetime', 3600 * 8);
}

// Configuración de uploads
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// Roles del sistema
define('ROLES', [
    'admin' => 'Administrador',
    'vendedor' => 'Vendedor',
    'marketing' => 'Marketing',
    'inventarios' => 'Inventarios'
]);

// Estados de oportunidades
define('OPORTUNIDAD_ESTADOS', [
    'prospecto' => 'Prospecto',
    'contactado' => 'Contactado',
    'calificado' => 'Calificado',
    'propuesta' => 'Propuesta',
    'negociacion' => 'Negociación',
    'ganado' => 'Ganado',
    'perdido' => 'Perdido'
]);

// Tipos de cliente
define('TIPOS_CLIENTE', [
    'doctor' => 'Doctor',
    'farmacia' => 'Farmacia',
    'hospital' => 'Hospital'
]);
?>