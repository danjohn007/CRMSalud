<?php
/**
 * Controlador de Pedidos
 */

require_once 'core/BaseController.php';

class PedidosController extends BaseController {
    
    public function index() {
        $this->view('pedidos/index', [
            'title' => 'Gestión de Pedidos',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        $this->view('pedidos/create', [
            'title' => 'Nuevo Pedido',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $this->view('pedidos/view', [
            'title' => 'Detalle de Pedido',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $this->view('pedidos/edit', [
            'title' => 'Editar Pedido',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>