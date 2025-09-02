<?php
/**
 * Controlador de Usuarios
 */

require_once 'core/BaseController.php';

class UsuariosController extends BaseController {
    
    public function index() {
        // Solo administradores pueden gestionar usuarios
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $this->view('usuarios/index', [
            'title' => 'Gestión de Usuarios',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $this->view('usuarios/create', [
            'title' => 'Nuevo Usuario',
            'roles' => ROLES,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $this->view('usuarios/view', [
            'title' => 'Detalle de Usuario',
            'id' => $id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $this->view('usuarios/edit', [
            'title' => 'Editar Usuario',
            'id' => $id,
            'roles' => ROLES,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>