<?php
/**
 * Controlador de Calendario
 */

require_once 'core/BaseController.php';

class CalendarioController extends BaseController {
    
    public function index() {
        $this->view('calendario/index', [
            'title' => 'Calendario',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function eventos() {
        $this->view('calendario/eventos', [
            'title' => 'Gestión de Eventos',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function crear_evento() {
        $this->view('calendario/crear_evento', [
            'title' => 'Crear Nuevo Evento',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>