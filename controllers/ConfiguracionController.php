<?php
/**
 * Controlador de Configuración
 */

require_once 'core/BaseController.php';

class ConfiguracionController extends BaseController {
    
    public function index() {
        // Solo administradores pueden acceder a configuración
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $this->view('configuracion/index', [
            'title' => 'Configuración del Sistema',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function general() {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $this->view('configuracion/general', [
            'title' => 'Configuración General',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function sistema() {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $this->view('configuracion/sistema', [
            'title' => 'Configuración del Sistema',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>