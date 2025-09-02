<?php
/**
 * Controlador de Comunicación
 */

require_once 'core/BaseController.php';

class ComunicacionController extends BaseController {
    
    public function index() {
        $this->view('comunicacion/index', [
            'title' => 'Centro de Comunicación',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function mensajes() {
        $this->view('comunicacion/mensajes', [
            'title' => 'Mensajes',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function notificaciones() {
        $this->view('comunicacion/notificaciones', [
            'title' => 'Notificaciones',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>