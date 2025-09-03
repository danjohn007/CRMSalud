<?php
/**
 * Controlador de Cotizaciones
 */

require_once 'core/BaseController.php';

class CotizacionesController extends BaseController {
    private $cotizacionModel;
    private $clienteModel;
    private $userModel;
    private $oportunidadModel;
    private $productoModel;
    
    public function __construct() {
        parent::__construct();
        $this->cotizacionModel = new Cotizacion();
        $this->clienteModel = new Cliente();
        $this->userModel = new User();
        $this->oportunidadModel = new Oportunidad();
        $this->productoModel = new Producto();
    }
    
    public function index() {
        $estado = $_GET['estado'] ?? '';
        $cliente_id = $_GET['cliente_id'] ?? '';
        
        $conditions = [];
        if ($estado) {
            $conditions['estado'] = $estado;
        }
        if ($cliente_id) {
            $conditions['cliente_id'] = $cliente_id;
        }
        
        $cotizaciones = $this->cotizacionModel->getCotizacionesConRelaciones($conditions);
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        $estadisticas = $this->cotizacionModel->getEstadisticasPorEstado();
        
        $this->view('cotizaciones/index', [
            'title' => 'Gestión de Cotizaciones',
            'cotizaciones' => $cotizaciones,
            'clientes' => $clientes,
            'estadisticas' => $estadisticas,
            'filtroEstado' => $estado,
            'filtroCliente' => $cliente_id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        $usuarios = $this->userModel->findAll(['activo' => 1], 'nombre ASC');
        $productos = $this->productoModel->findAll(['activo' => 1], 'nombre ASC');
        $oportunidades = $this->oportunidadModel->getOportunidadesActivas();
        
        $oportunidad_id = $_GET['oportunidad_id'] ?? null;
        $cliente_id = $_GET['cliente_id'] ?? null;
        
        $this->view('cotizaciones/create', [
            'title' => 'Nueva Cotización',
            'clientes' => $clientes,
            'usuarios' => $usuarios,
            'productos' => $productos,
            'oportunidades' => $oportunidades,
            'oportunidad_id' => $oportunidad_id,
            'cliente_id' => $cliente_id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cotizaciones');
            return;
        }
        
        $data = [
            'numero' => $this->cotizacionModel->generarNumero(),
            'cliente_id' => $_POST['cliente_id'] ?? '',
            'usuario_id' => $_POST['usuario_id'] ?? $this->getCurrentUser()['id'],
            'oportunidad_id' => $_POST['oportunidad_id'] ?? null,
            'fecha_cotizacion' => $_POST['fecha_cotizacion'] ?? date('Y-m-d'),
            'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? date('Y-m-d', strtotime('+30 days')),
            'descuento_porcentaje' => $_POST['descuento_porcentaje'] ?? 0,
            'descuento_importe' => $_POST['descuento_importe'] ?? 0,
            'impuestos' => $_POST['impuestos'] ?? 0,
            'estado' => $_POST['estado'] ?? 'borrador',
            'notas' => $_POST['notas'] ?? ''
        ];
        
        // Validaciones básicas
        if (empty($data['cliente_id'])) {
            $this->flash('danger', 'El cliente es obligatorio.');
            $this->redirect('cotizaciones/create');
            return;
        }
        
        try {
            $cotizacion_id = $this->cotizacionModel->create($data);
            
            // Procesar productos de la cotización
            if (!empty($_POST['productos'])) {
                $this->cotizacionModel->procesarProductos($cotizacion_id, $_POST['productos']);
            }
            
            // Calcular totales
            $this->cotizacionModel->calcularTotales($cotizacion_id);
            
            $this->flash('success', 'Cotización creada exitosamente.');
            $this->redirect('cotizaciones/show/' . $cotizacion_id);
        } catch (Exception $e) {
            $this->flash('danger', 'Error al crear la cotización: ' . $e->getMessage());
            $this->redirect('cotizaciones/create');
        }
    }
    
    public function show($id) {
        $cotizacion = $this->cotizacionModel->getCotizacionConRelaciones($id);
        
        if (!$cotizacion) {
            $this->flash('danger', 'Cotización no encontrada.');
            $this->redirect('cotizaciones');
            return;
        }
        
        $detalles = $this->cotizacionModel->getDetallesCotizacion($id);
        
        $this->view('cotizaciones/view', [
            'title' => 'Detalle de Cotización - ' . $cotizacion['numero'],
            'cotizacion' => $cotizacion,
            'detalles' => $detalles,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $cotizacion = $this->cotizacionModel->find($id);
        
        if (!$cotizacion) {
            $this->flash('danger', 'Cotización no encontrada.');
            $this->redirect('cotizaciones');
            return;
        }
        
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        $usuarios = $this->userModel->findAll(['activo' => 1], 'nombre ASC');
        $productos = $this->productoModel->findAll(['activo' => 1], 'nombre ASC');
        $detalles = $this->cotizacionModel->getDetallesCotizacion($id);
        
        $this->view('cotizaciones/edit', [
            'title' => 'Editar Cotización - ' . $cotizacion['numero'],
            'cotizacion' => $cotizacion,
            'detalles' => $detalles,
            'clientes' => $clientes,
            'usuarios' => $usuarios,
            'productos' => $productos,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cotizaciones');
            return;
        }
        
        $cotizacion = $this->cotizacionModel->find($id);
        if (!$cotizacion) {
            $this->flash('danger', 'Cotización no encontrada.');
            $this->redirect('cotizaciones');
            return;
        }
        
        $data = [
            'cliente_id' => $_POST['cliente_id'] ?? $cotizacion['cliente_id'],
            'usuario_id' => $_POST['usuario_id'] ?? $cotizacion['usuario_id'],
            'oportunidad_id' => $_POST['oportunidad_id'] ?? $cotizacion['oportunidad_id'],
            'fecha_cotizacion' => $_POST['fecha_cotizacion'] ?? $cotizacion['fecha_cotizacion'],
            'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? $cotizacion['fecha_vencimiento'],
            'descuento_porcentaje' => $_POST['descuento_porcentaje'] ?? $cotizacion['descuento_porcentaje'],
            'descuento_importe' => $_POST['descuento_importe'] ?? $cotizacion['descuento_importe'],
            'impuestos' => $_POST['impuestos'] ?? $cotizacion['impuestos'],
            'estado' => $_POST['estado'] ?? $cotizacion['estado'],
            'notas' => $_POST['notas'] ?? $cotizacion['notas']
        ];
        
        try {
            $this->cotizacionModel->update($id, $data);
            
            // Procesar productos actualizados
            if (!empty($_POST['productos'])) {
                $this->cotizacionModel->actualizarProductos($id, $_POST['productos']);
            }
            
            // Recalcular totales
            $this->cotizacionModel->calcularTotales($id);
            
            $this->flash('success', 'Cotización actualizada exitosamente.');
            $this->redirect('cotizaciones/show/' . $id);
        } catch (Exception $e) {
            $this->flash('danger', 'Error al actualizar la cotización: ' . $e->getMessage());
            $this->redirect('cotizaciones/edit/' . $id);
        }
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $cotizacion = $this->cotizacionModel->find($id);
            if (!$cotizacion) {
                $this->json(['success' => false, 'message' => 'Cotización no encontrada'], 404);
                return;
            }
            
            $this->cotizacionModel->delete($id);
            $this->json(['success' => true, 'message' => 'Cotización eliminada correctamente']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al eliminar la cotización: ' . $e->getMessage()], 500);
        }
    }
    
    public function cambiarEstado($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        $estado = $_POST['estado'] ?? '';
        
        try {
            $cotizacion = $this->cotizacionModel->find($id);
            if (!$cotizacion) {
                $this->json(['success' => false, 'message' => 'Cotización no encontrada'], 404);
                return;
            }
            
            $this->cotizacionModel->update($id, ['estado' => $estado]);
            
            // Si se acepta, generar pedido automáticamente
            if ($estado === 'aceptada') {
                $this->cotizacionModel->generarPedido($id);
            }
            
            $this->json(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al actualizar el estado: ' . $e->getMessage()], 500);
        }
    }
    
    public function pdf($id) {
        $cotizacion = $this->cotizacionModel->getCotizacionConRelaciones($id);
        
        if (!$cotizacion) {
            $this->flash('danger', 'Cotización no encontrada.');
            $this->redirect('cotizaciones');
            return;
        }
        
        $detalles = $this->cotizacionModel->getDetallesCotizacion($id);
        
        // Generar PDF
        $pdf = $this->cotizacionModel->generarPDF($cotizacion, $detalles);
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="cotizacion_' . $cotizacion['numero'] . '.pdf"');
        echo $pdf;
    }
    
    public function email($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $cotizacion = $this->cotizacionModel->getCotizacionConRelaciones($id);
            
            if (!$cotizacion) {
                $this->json(['success' => false, 'message' => 'Cotización no encontrada'], 404);
                return;
            }
            
            $resultado = $this->cotizacionModel->enviarPorEmail($id, $_POST['email'] ?? $cotizacion['cliente_email']);
            
            if ($resultado) {
                $this->json(['success' => true, 'message' => 'Cotización enviada por email correctamente']);
            } else {
                $this->json(['success' => false, 'message' => 'Error al enviar el email'], 500);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al enviar el email: ' . $e->getMessage()], 500);
        }
    }
}
?>