<?php
/**
 * Controlador principal del sistema
 */

require_once 'core/BaseController.php';

class HomeController extends BaseController {
    
    public function index() {
        // Si el usuario no está logueado, redirigir al login
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
        
        // Si está logueado, redirigir al dashboard
        $this->redirect('dashboard');
    }
}
?>