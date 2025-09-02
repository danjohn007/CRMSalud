<?php
/**
 * Controlador de Oportunidades
 */

require_once 'core/BaseController.php';

class OportunidadesController extends BaseController {
    
    public function index() {
        $this->view('oportunidades/index', [
            'title' => 'Gestión de Oportunidades',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        $this->view('oportunidades/create', [
            'title' => 'Nueva Oportunidad',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $this->view('oportunidades/view', [
            'title' => 'Detalle de Oportunidad',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $this->view('oportunidades/edit', [
            'title' => 'Editar Oportunidad',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>