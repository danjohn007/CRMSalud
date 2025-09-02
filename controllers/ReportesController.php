<?php
/**
 * Controlador de Reportes
 */

require_once 'core/BaseController.php';

class ReportesController extends BaseController {
    
    public function index() {
        $this->view('reportes/index', [
            'title' => 'Centro de Reportes',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        $this->view('reportes/create', [
            'title' => 'Nuevo Reporte',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $this->view('reportes/view', [
            'title' => 'Detalle de Reporte',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $this->view('reportes/edit', [
            'title' => 'Editar Reporte',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function delete($id) {
        // Implementación del delete será manejada vía AJAX/API
        $this->json(['success' => true, 'message' => 'Reporte eliminado correctamente']);
    }
    
    // Métodos adicionales específicos del módulo
    public function ventas() {
        $this->view('reportes/ventas', [
            'title' => 'Reporte de Ventas',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function inventario() {
        $this->view('reportes/inventario', [
            'title' => 'Reporte de Inventario',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function clientes() {
        $this->view('reportes/clientes', [
            'title' => 'Reporte de Clientes',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>