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
    
    public function create() {
        $this->view('comunicacion/create', [
            'title' => 'Nueva Comunicación',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $this->view('comunicacion/view', [
            'title' => 'Detalle de Comunicación',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $this->view('comunicacion/edit', [
            'title' => 'Editar Comunicación',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function delete($id) {
        // Implementación del delete será manejada vía AJAX/API
        $this->json(['success' => true, 'message' => 'Comunicación eliminada correctamente']);
    }
    
    // Métodos adicionales específicos del módulo
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