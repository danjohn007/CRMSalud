<?php
/**
 * Router principal del sistema - maneja URLs amigables
 */

class Router {
    private $routes = [];
    private $controller = 'HomeController';
    private $action = 'index';
    private $params = [];
    
    public function __construct() {
        $this->parseUrl();
    }
    
    private function parseUrl() {
        $url = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remover la base URL del path
        $basePath = parse_url(BASE_URL, PHP_URL_PATH);
        if ($basePath && strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }
        
        // Remover query string
        $url = strtok($url, '?');
        
        // Limpiar URL
        $url = trim($url, '/');
        
        if (!empty($url)) {
            $urlParts = explode('/', $url);
            
            // Primer parte es el controlador
            if (isset($urlParts[0])) {
                $this->controller = ucfirst($urlParts[0]) . 'Controller';
            }
            
            // Segunda parte es la acción
            if (isset($urlParts[1])) {
                $this->action = $urlParts[1];
            }
            
            // Resto son parámetros
            $this->params = array_slice($urlParts, 2);
        }
    }
    
    public function dispatch() {
        // Verificar si el controlador existe
        $controllerFile = 'controllers/' . $this->controller . '.php';
        
        if (!file_exists($controllerFile)) {
            $this->controller = 'HomeController';
            $this->action = 'notFound';
            $this->params = [];
        }
        
        require_once $controllerFile;
        
        // Crear instancia del controlador
        $controllerInstance = new $this->controller();
        
        // Verificar si el método existe
        if (!method_exists($controllerInstance, $this->action)) {
            $this->action = 'notFound';
        }
        
        // Llamar al método con los parámetros
        call_user_func_array([$controllerInstance, $this->action], $this->params);
    }
    
    public function getController() {
        return $this->controller;
    }
    
    public function getAction() {
        return $this->action;
    }
    
    public function getParams() {
        return $this->params;
    }
}
?>