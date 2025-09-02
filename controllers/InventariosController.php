<?php
/**
 * Controlador de Inventarios
 */

require_once 'core/BaseController.php';
require_once 'models/Inventario.php';

class InventariosController extends BaseController {
    private $inventarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->inventarioModel = new Inventario();
    }
    
    public function index() {
        $filter = $_GET['filter'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        
        if ($filter === 'stock_bajo') {
            $inventarios = $this->inventarioModel->getInventariosStockBajo();
            $title = 'Inventarios con Stock Bajo';
        } elseif ($filter === 'vencimiento') {
            $dias = (int)($_GET['dias'] ?? 30);
            $inventarios = $this->inventarioModel->getInventariosProximosVencer($dias);
            $title = 'Inventarios Próximos a Vencer';
        } else {
            $inventarios = $this->inventarioModel->getInventariosConProductos();
            $title = 'Gestión de Inventarios';
        }
        
        $this->view('inventarios/index', [
            'title' => $title,
            'inventarios' => $inventarios,
            'currentFilter' => $filter,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $inventario = $this->inventarioModel->find($id);
        
        if (!$inventario) {
            $this->flash('error', 'Registro de inventario no encontrado');
            $this->redirect('inventarios');
        }
        
        $this->view('inventarios/view', [
            'title' => 'Detalle del Inventario',
            'inventario' => $inventario,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>