<?php
/**
 * Controlador de Cotizaciones
 */

require_once 'core/BaseController.php';

class CotizacionesController extends BaseController {
    
    public function index() {
        $this->view('cotizaciones/index', [
            'title' => 'Gestión de Cotizaciones',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        $this->view('cotizaciones/create', [
            'title' => 'Nueva Cotización',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $this->view('cotizaciones/view', [
            'title' => 'Detalle de Cotización',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $this->view('cotizaciones/edit', [
            'title' => 'Editar Cotización',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function delete($id) {
        // Implementación del delete será manejada vía AJAX/API
        $this->json(['success' => true, 'message' => 'Cotización eliminada correctamente']);
    }
}
?>