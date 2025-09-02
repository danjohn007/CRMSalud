<?php
/**
 * Controlador de Marketing
 */

require_once 'core/BaseController.php';

class MarketingController extends BaseController {
    
    public function index() {
        $this->view('marketing/index', [
            'title' => 'Gestión de Marketing',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        $this->view('marketing/create', [
            'title' => 'Nueva Campaña de Marketing',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function view($id) {
        $this->view('marketing/view', [
            'title' => 'Detalle de Campaña',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $this->view('marketing/edit', [
            'title' => 'Editar Campaña',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function delete($id) {
        // Implementación del delete será manejada vía AJAX/API
        $this->json(['success' => true, 'message' => 'Campaña eliminada correctamente']);
    }
    
    // Métodos adicionales específicos del módulo
    public function campanas() {
        $this->view('marketing/campanas', [
            'title' => 'Campañas de Marketing',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function analytics() {
        $this->view('marketing/analytics', [
            'title' => 'Analytics de Marketing',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>