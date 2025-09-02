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