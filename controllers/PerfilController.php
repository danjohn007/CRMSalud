<?php
/**
 * Controlador de Perfil de Usuario
 */

require_once 'core/BaseController.php';

class PerfilController extends BaseController {
    
    public function index() {
        $this->view('perfil/index', [
            'title' => 'Mi Perfil',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function editar() {
        $this->view('perfil/editar', [
            'title' => 'Editar Perfil',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>