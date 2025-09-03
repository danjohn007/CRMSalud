<?php
/**
 * Controlador de Oportunidades
 */

require_once 'core/BaseController.php';

class OportunidadesController extends BaseController {
    private $oportunidadModel;
    private $clienteModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->oportunidadModel = new Oportunidad();
        $this->clienteModel = new Cliente();
        $this->userModel = new User();
    }
    
    public function index() {
        $estado = $_GET['estado'] ?? '';
        $cliente_id = $_GET['cliente_id'] ?? '';
        
        $conditions = [];
        if ($estado) {
            $conditions['o.estado'] = $estado;
        }
        if ($cliente_id) {
            $conditions['o.cliente_id'] = $cliente_id;
        }
        
        $oportunidades = $this->oportunidadModel->getOportunidadesConRelaciones($conditions);
        $estadisticas = $this->oportunidadModel->getEstadisticasPorEstado();
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        
        $this->view('oportunidades/index', [
            'title' => 'Gestión de Oportunidades',
            'oportunidades' => $oportunidades,
            'estadisticas' => $estadisticas,
            'clientes' => $clientes,
            'filtroEstado' => $estado,
            'filtroCliente' => $cliente_id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        $usuarios = $this->userModel->findAll(['activo' => 1], 'nombre ASC');
        
        $this->view('oportunidades/create', [
            'title' => 'Nueva Oportunidad',
            'clientes' => $clientes,
            'usuarios' => $usuarios,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('oportunidades');
            return;
        }
        
        $data = [
            'cliente_id' => $_POST['cliente_id'] ?? '',
            'usuario_id' => $_POST['usuario_id'] ?? $this->getCurrentUser()['id'],
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'estado' => $_POST['estado'] ?? 'prospecto',
            'valor_estimado' => $_POST['valor_estimado'] ?? 0,
            'probabilidad' => $_POST['probabilidad'] ?? 0,
            'fecha_cierre_estimada' => $_POST['fecha_cierre_estimada'] ?? null,
            'prioridad' => $_POST['prioridad'] ?? 'media'
        ];
        
        // Validaciones básicas
        if (empty($data['nombre']) || empty($data['cliente_id'])) {
            $this->flash('danger', 'El nombre y cliente son obligatorios.');
            $this->redirect('oportunidades/create');
            return;
        }
        
        try {
            $id = $this->oportunidadModel->create($data);
            $this->flash('success', 'Oportunidad creada exitosamente.');
            $this->redirect('oportunidades/show/' . $id);
        } catch (Exception $e) {
            $this->flash('danger', 'Error al crear la oportunidad: ' . $e->getMessage());
            $this->redirect('oportunidades/create');
        }
    }
    
    public function show($id) {
        $oportunidad = $this->oportunidadModel->getOportunidadConRelaciones($id);
        
        if (!$oportunidad) {
            $this->flash('danger', 'Oportunidad no encontrada.');
            $this->redirect('oportunidades');
            return;
        }
        
        $comunicaciones = $this->oportunidadModel->getComunicacionesOportunidad($id);
        $actividades = $this->oportunidadModel->getActividadesOportunidad($id);
        
        $this->view('oportunidades/view', [
            'title' => 'Detalle de Oportunidad - ' . $oportunidad['nombre'],
            'oportunidad' => $oportunidad,
            'comunicaciones' => $comunicaciones,
            'actividades' => $actividades,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $oportunidad = $this->oportunidadModel->find($id);
        
        if (!$oportunidad) {
            $this->flash('danger', 'Oportunidad no encontrada.');
            $this->redirect('oportunidades');
            return;
        }
        
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        $usuarios = $this->userModel->findAll(['activo' => 1], 'nombre ASC');
        
        $this->view('oportunidades/edit', [
            'title' => 'Editar Oportunidad - ' . $oportunidad['nombre'],
            'oportunidad' => $oportunidad,
            'clientes' => $clientes,
            'usuarios' => $usuarios,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('oportunidades');
            return;
        }
        
        $oportunidad = $this->oportunidadModel->find($id);
        if (!$oportunidad) {
            $this->flash('danger', 'Oportunidad no encontrada.');
            $this->redirect('oportunidades');
            return;
        }
        
        $data = [
            'cliente_id' => $_POST['cliente_id'] ?? $oportunidad['cliente_id'],
            'usuario_id' => $_POST['usuario_id'] ?? $oportunidad['usuario_id'],
            'nombre' => $_POST['nombre'] ?? $oportunidad['nombre'],
            'descripcion' => $_POST['descripcion'] ?? $oportunidad['descripcion'],
            'estado' => $_POST['estado'] ?? $oportunidad['estado'],
            'valor_estimado' => $_POST['valor_estimado'] ?? $oportunidad['valor_estimado'],
            'probabilidad' => $_POST['probabilidad'] ?? $oportunidad['probabilidad'],
            'fecha_cierre_estimada' => $_POST['fecha_cierre_estimada'] ?? $oportunidad['fecha_cierre_estimada'],
            'prioridad' => $_POST['prioridad'] ?? $oportunidad['prioridad'],
            'motivo_perdida' => $_POST['motivo_perdida'] ?? $oportunidad['motivo_perdida']
        ];
        
        // Si se marca como ganada o perdida, establecer fecha de cierre real
        if (in_array($data['estado'], ['ganado', 'perdido'])) {
            $data['fecha_cierre_real'] = date('Y-m-d');
        }
        
        try {
            $this->oportunidadModel->update($id, $data);
            $this->flash('success', 'Oportunidad actualizada exitosamente.');
            $this->redirect('oportunidades/show/' . $id);
        } catch (Exception $e) {
            $this->flash('danger', 'Error al actualizar la oportunidad: ' . $e->getMessage());
            $this->redirect('oportunidades/edit/' . $id);
        }
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $oportunidad = $this->oportunidadModel->find($id);
            if (!$oportunidad) {
                $this->json(['success' => false, 'message' => 'Oportunidad no encontrada'], 404);
                return;
            }
            
            $this->oportunidadModel->delete($id);
            $this->json(['success' => true, 'message' => 'Oportunidad eliminada correctamente']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al eliminar la oportunidad: ' . $e->getMessage()], 500);
        }
    }
    
    public function kanban() {
        $estadisticas = $this->oportunidadModel->getEstadisticasPipeline();
        $oportunidadesPorEstado = [];
        
        foreach (OPORTUNIDAD_ESTADOS as $estado => $label) {
            $oportunidadesPorEstado[$estado] = $this->oportunidadModel->getOportunidadesConRelaciones(['o.estado' => $estado]);
        }
        
        $this->view('oportunidades/kanban', [
            'title' => 'Pipeline de Ventas - Tablero Kanban',
            'estadisticas' => $estadisticas,
            'oportunidadesPorEstado' => $oportunidadesPorEstado,
            'estados' => OPORTUNIDAD_ESTADOS,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
}
?>