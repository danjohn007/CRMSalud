<?php
/**
 * Controlador de pruebas del sistema
 */

require_once 'core/BaseController.php';

class TestController extends BaseController {
    
    public function __construct() {
        $this->db = Database::getInstance();
        // No llamamos checkAuth() para permitir acceso público a las pruebas
    }
    
    public function index() {
        $tests = [];
        
        // Test de conexión a base de datos
        $tests['database'] = $this->testDatabase();
        
        // Test de URL base
        $tests['base_url'] = $this->testBaseUrl();
        
        // Test de configuración
        $tests['config'] = $this->testConfig();
        
        // Test de permisos de directorios
        $tests['permissions'] = $this->testPermissions();
        
        // Test de módulos PHP
        $tests['php_modules'] = $this->testPhpModules();
        
        $this->view('test/index', [
            'title' => 'Test del Sistema',
            'tests' => $tests
        ]);
    }
    
    private function testDatabase() {
        $result = [
            'name' => 'Conexión a Base de Datos',
            'status' => 'error',
            'message' => '',
            'details' => []
        ];
        
        try {
            // Test de conexión
            if ($this->db->testConnection()) {
                $result['status'] = 'success';
                $result['message'] = 'Conexión exitosa a la base de datos';
                
                // Obtener información de la base de datos
                $dbInfo = $this->db->fetchOne("SELECT VERSION() as version");
                $result['details']['version'] = $dbInfo['version'] ?? 'Desconocida';
                
                // Test de charset
                $charset = $this->db->fetchOne("SELECT @@character_set_database as charset");
                $result['details']['charset'] = $charset['charset'] ?? 'Desconocido';
                
                // Verificar si las tablas existen
                $tables = $this->db->fetchAll("SHOW TABLES");
                $result['details']['tables_count'] = count($tables);
                
            } else {
                $result['message'] = 'No se pudo conectar a la base de datos';
            }
        } catch (Exception $e) {
            $result['message'] = 'Error: ' . $e->getMessage();
        }
        
        return $result;
    }
    
    private function testBaseUrl() {
        $result = [
            'name' => 'Configuración de URL Base',
            'status' => 'success',
            'message' => 'URL base configurada correctamente',
            'details' => [
                'base_url' => BASE_URL,
                'assets_url' => ASSETS_URL,
                'protocol' => parse_url(BASE_URL, PHP_URL_SCHEME),
                'host' => parse_url(BASE_URL, PHP_URL_HOST),
                'path' => parse_url(BASE_URL, PHP_URL_PATH)
            ]
        ];
        
        // Verificar que la URL base sea accesible
        if (!filter_var(BASE_URL, FILTER_VALIDATE_URL)) {
            $result['status'] = 'warning';
            $result['message'] = 'La URL base no parece ser válida';
        }
        
        return $result;
    }
    
    private function testConfig() {
        $result = [
            'name' => 'Configuración del Sistema',
            'status' => 'success',
            'message' => 'Configuración cargada correctamente',
            'details' => [
                'app_name' => APP_NAME,
                'app_version' => APP_VERSION,
                'timezone' => date_default_timezone_get(),
                'php_version' => PHP_VERSION,
                'max_upload_size' => ini_get('upload_max_filesize'),
                'session_lifetime' => ini_get('session.cookie_lifetime') . ' segundos'
            ]
        ];
        
        return $result;
    }
    
    private function testPermissions() {
        $result = [
            'name' => 'Permisos de Directorios',
            'status' => 'success',
            'message' => 'Todos los permisos están correctos',
            'details' => []
        ];
        
        $directories = [
            'uploads' => 'uploads/',
            'assets' => 'assets/',
            'views' => 'views/'
        ];
        
        foreach ($directories as $name => $path) {
            $fullPath = ROOT_PATH . $path;
            $writable = is_writable($fullPath);
            $result['details'][$name] = $writable ? 'Escribible' : 'No escribible';
            
            if (!$writable && $name === 'uploads') {
                $result['status'] = 'warning';
                $result['message'] = 'El directorio uploads no es escribible';
            }
        }
        
        return $result;
    }
    
    private function testPhpModules() {
        $result = [
            'name' => 'Módulos PHP Requeridos',
            'status' => 'success',
            'message' => 'Todos los módulos están disponibles',
            'details' => []
        ];
        
        $requiredModules = [
            'pdo' => 'PDO',
            'pdo_mysql' => 'PDO MySQL',
            'session' => 'Sessions',
            'json' => 'JSON',
            'mbstring' => 'Multibyte String',
            'gd' => 'GD (para imágenes)'
        ];
        
        foreach ($requiredModules as $module => $description) {
            $available = extension_loaded($module);
            $result['details'][$description] = $available ? 'Disponible' : 'No disponible';
            
            if (!$available) {
                $result['status'] = 'warning';
                $result['message'] = 'Algunos módulos PHP no están disponibles';
            }
        }
        
        return $result;
    }
}
?>