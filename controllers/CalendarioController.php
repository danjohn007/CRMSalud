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
    
    public function create() {
        $this->view('calendario/create', [
            'title' => 'Nuevo Evento',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $this->view('calendario/view', [
            'title' => 'Detalle de Evento',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $this->view('calendario/edit', [
            'title' => 'Editar Evento',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function delete($id) {
        // Implementación del delete será manejada vía AJAX/API
        $this->json(['success' => true, 'message' => 'Evento eliminado correctamente']);
    }
    
    // Métodos adicionales específicos del módulo
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