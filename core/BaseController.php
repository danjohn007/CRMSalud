<?php
/**
 * Controlador base del sistema
 */

class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->checkAuth();
    }
    
    protected function checkAuth() {
        // Rutas que no requieren autenticación
        $publicRoutes = ['auth', 'test'];
        $currentController = strtolower(str_replace('Controller', '', get_class($this)));
        
        if (!in_array($currentController, $publicRoutes) && !$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    protected function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'nombre' => $_SESSION['user_nombre'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'rol' => $_SESSION['user_rol'] ?? ''
        ];
    }
    
    protected function hasPermission($role) {
        $user = $this->getCurrentUser();
        if (!$user) return false;
        
        $roleHierarchy = ['admin', 'vendedor', 'marketing', 'inventarios'];
        $userRoleIndex = array_search($user['rol'], $roleHierarchy);
        $requiredRoleIndex = array_search($role, $roleHierarchy);
        
        return $userRoleIndex !== false && $requiredRoleIndex !== false && $userRoleIndex <= $requiredRoleIndex;
    }
    
    public function view($view, $data = []) {
        extract($data);
        
        // Variables globales para las vistas
        $currentUser = $this->getCurrentUser();
        $baseUrl = BASE_URL;
        $assetsUrl = ASSETS_URL;
        
        // Incluir header
        include VIEWS_PATH . 'layout/header.php';
        
        // Incluir vista
        $viewFile = VIEWS_PATH . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            include VIEWS_PATH . 'errors/404.php';
        }
        
        // Incluir footer
        include VIEWS_PATH . 'layout/footer.php';
    }
    
    protected function json($data, $httpCode = 200) {
        http_response_code($httpCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        if (strpos($url, 'http') !== 0) {
            $url = BASE_URL . $url;
        }
        header('Location: ' . $url);
        exit;
    }
    
    protected function flash($type, $message) {
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }
    
    protected function getFlashMessages() {
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $messages;
    }
    
    public function notFound() {
        http_response_code(404);
        $this->view('errors/404');
    }
}
?>