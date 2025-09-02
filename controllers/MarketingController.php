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