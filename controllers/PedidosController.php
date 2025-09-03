<?php
/**
 * Controlador de Pedidos
 */

require_once 'core/BaseController.php';

class PedidosController extends BaseController {
    private $pedidoModel;
    private $clienteModel;
    private $userModel;
    private $cotizacionModel;
    private $productoModel;
    
    public function __construct() {
        parent::__construct();
        $this->pedidoModel = new Pedido();
        $this->clienteModel = new Cliente();
        $this->userModel = new User();
        $this->cotizacionModel = new Cotizacion();
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
        
        $pedidos = $this->pedidoModel->getPedidosConRelaciones($conditions);
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        $estadisticas = $this->pedidoModel->getEstadisticasPorEstado();
        $pedidos_urgentes = $this->pedidoModel->getPedidosUrgentes();
        
        $this->view('pedidos/index', [
            'title' => 'Gestión de Pedidos',
            'pedidos' => $pedidos,
            'clientes' => $clientes,
            'estadisticas' => $estadisticas,
            'pedidos_urgentes' => $pedidos_urgentes,
            'filtroEstado' => $estado,
            'filtroCliente' => $cliente_id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        $usuarios = $this->userModel->findAll(['activo' => 1], 'nombre ASC');
        $productos = $this->productoModel->findAll(['activo' => 1], 'nombre ASC');
        $cotizaciones_aprobadas = $this->cotizacionModel->findAll(['estado' => 'aceptada'], 'created_at DESC');
        
        $cotizacion_id = $_GET['cotizacion_id'] ?? null;
        $cliente_id = $_GET['cliente_id'] ?? null;
        
        $this->view('pedidos/create', [
            'title' => 'Nuevo Pedido',
            'clientes' => $clientes,
            'usuarios' => $usuarios,
            'productos' => $productos,
            'cotizaciones_aprobadas' => $cotizaciones_aprobadas,
            'cotizacion_id' => $cotizacion_id,
            'cliente_id' => $cliente_id,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pedidos');
            return;
        }
        
        $data = [
            'numero' => $this->pedidoModel->generarNumero(),
            'cotizacion_id' => $_POST['cotizacion_id'] ?? null,
            'cliente_id' => $_POST['cliente_id'] ?? '',
            'usuario_id' => $_POST['usuario_id'] ?? $this->getCurrentUser()['id'],
            'fecha_pedido' => $_POST['fecha_pedido'] ?? date('Y-m-d'),
            'fecha_entrega_estimada' => $_POST['fecha_entrega_estimada'] ?? date('Y-m-d', strtotime('+7 days')),
            'descuento_porcentaje' => $_POST['descuento_porcentaje'] ?? 0,
            'descuento_importe' => $_POST['descuento_importe'] ?? 0,
            'impuestos' => $_POST['impuestos'] ?? 0,
            'forma_pago' => $_POST['forma_pago'] ?? 'efectivo',
            'estado' => $_POST['estado'] ?? 'nuevo',
            'notas' => $_POST['notas'] ?? ''
        ];
        
        // Validaciones básicas
        if (empty($data['cliente_id'])) {
            $this->flash('danger', 'El cliente es obligatorio.');
            $this->redirect('pedidos/create');
            return;
        }
        
        try {
            $pedido_id = $this->pedidoModel->create($data);
            
            // Procesar productos del pedido
            if (!empty($_POST['productos'])) {
                $this->pedidoModel->procesarProductos($pedido_id, $_POST['productos']);
            }
            
            // Calcular totales
            $this->pedidoModel->calcularTotales($pedido_id);
            
            $this->flash('success', 'Pedido creado exitosamente.');
            $this->redirect('pedidos/show/' . $pedido_id);
        } catch (Exception $e) {
            $this->flash('danger', 'Error al crear el pedido: ' . $e->getMessage());
            $this->redirect('pedidos/create');
        }
    }
    
    public function show($id) {
        $pedido = $this->pedidoModel->getPedidoConRelaciones($id);
        
        if (!$pedido) {
            $this->flash('danger', 'Pedido no encontrado.');
            $this->redirect('pedidos');
            return;
        }
        
        $detalles = $this->pedidoModel->getDetallesPedido($id);
        
        $this->view('pedidos/view', [
            'title' => 'Detalle de Pedido - ' . $pedido['numero'],
            'pedido' => $pedido,
            'detalles' => $detalles,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $pedido = $this->pedidoModel->find($id);
        
        if (!$pedido) {
            $this->flash('danger', 'Pedido no encontrado.');
            $this->redirect('pedidos');
            return;
        }
        
        $clientes = $this->clienteModel->findAll(['activo' => 1], 'nombre ASC');
        $usuarios = $this->userModel->findAll(['activo' => 1], 'nombre ASC');
        $productos = $this->productoModel->findAll(['activo' => 1], 'nombre ASC');
        $detalles = $this->pedidoModel->getDetallesPedido($id);
        
        $this->view('pedidos/edit', [
            'title' => 'Editar Pedido - ' . $pedido['numero'],
            'pedido' => $pedido,
            'detalles' => $detalles,
            'clientes' => $clientes,
            'usuarios' => $usuarios,
            'productos' => $productos,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pedidos');
            return;
        }
        
        $pedido = $this->pedidoModel->find($id);
        if (!$pedido) {
            $this->flash('danger', 'Pedido no encontrado.');
            $this->redirect('pedidos');
            return;
        }
        
        $data = [
            'cliente_id' => $_POST['cliente_id'] ?? $pedido['cliente_id'],
            'usuario_id' => $_POST['usuario_id'] ?? $pedido['usuario_id'],
            'fecha_pedido' => $_POST['fecha_pedido'] ?? $pedido['fecha_pedido'],
            'fecha_entrega_estimada' => $_POST['fecha_entrega_estimada'] ?? $pedido['fecha_entrega_estimada'],
            'descuento_porcentaje' => $_POST['descuento_porcentaje'] ?? $pedido['descuento_porcentaje'],
            'descuento_importe' => $_POST['descuento_importe'] ?? $pedido['descuento_importe'],
            'impuestos' => $_POST['impuestos'] ?? $pedido['impuestos'],
            'forma_pago' => $_POST['forma_pago'] ?? $pedido['forma_pago'],
            'estado' => $_POST['estado'] ?? $pedido['estado'],
            'notas' => $_POST['notas'] ?? $pedido['notas']
        ];
        
        // Si se marca como entregado, establecer fecha de entrega real
        if ($data['estado'] === 'entregado' && $pedido['estado'] !== 'entregado') {
            $data['fecha_entrega_real'] = date('Y-m-d');
        }
        
        try {
            $this->pedidoModel->update($id, $data);
            
            // Procesar productos actualizados
            if (!empty($_POST['productos'])) {
                $this->pedidoModel->actualizarProductos($id, $_POST['productos']);
            }
            
            // Recalcular totales
            $this->pedidoModel->calcularTotales($id);
            
            // Enviar notificación si cambió el estado
            if ($data['estado'] !== $pedido['estado']) {
                $this->pedidoModel->enviarNotificacion($id, $data['estado']);
            }
            
            $this->flash('success', 'Pedido actualizado exitosamente.');
            $this->redirect('pedidos/show/' . $id);
        } catch (Exception $e) {
            $this->flash('danger', 'Error al actualizar el pedido: ' . $e->getMessage());
            $this->redirect('pedidos/edit/' . $id);
        }
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $pedido = $this->pedidoModel->find($id);
            if (!$pedido) {
                $this->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
                return;
            }
            
            // Solo permitir eliminar pedidos en estado 'nuevo'
            if ($pedido['estado'] !== 'nuevo') {
                $this->json(['success' => false, 'message' => 'Solo se pueden eliminar pedidos en estado nuevo'], 400);
                return;
            }
            
            $this->pedidoModel->delete($id);
            $this->json(['success' => true, 'message' => 'Pedido eliminado correctamente']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al eliminar el pedido: ' . $e->getMessage()], 500);
        }
    }
    
    public function cambiarEstado($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        $estado = $_POST['estado'] ?? '';
        
        try {
            $pedido = $this->pedidoModel->find($id);
            if (!$pedido) {
                $this->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
                return;
            }
            
            $data = ['estado' => $estado];
            
            // Establecer fecha de entrega real si se marca como entregado
            if ($estado === 'entregado') {
                $data['fecha_entrega_real'] = date('Y-m-d');
            }
            
            $this->pedidoModel->update($id, $data);
            
            // Enviar notificación
            $this->pedidoModel->enviarNotificacion($id, $estado);
            
            $this->json(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al actualizar el estado: ' . $e->getMessage()], 500);
        }
    }
    
    public function marcarEntregado($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $producto_id = $_POST['producto_id'] ?? '';
            $cantidad_entregada = $_POST['cantidad_entregada'] ?? 0;
            
            if (empty($producto_id) || $cantidad_entregada <= 0) {
                $this->json(['success' => false, 'message' => 'Datos inválidos'], 400);
                return;
            }
            
            $this->pedidoModel->marcarProductoEntregado($id, $producto_id, $cantidad_entregada);
            $this->json(['success' => true, 'message' => 'Producto marcado como entregado']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al marcar entrega: ' . $e->getMessage()], 500);
        }
    }
    
    public function generarOrdenCompra($id) {
        try {
            $pedido = $this->pedidoModel->find($id);
            if (!$pedido) {
                $this->flash('danger', 'Pedido no encontrado.');
                $this->redirect('pedidos');
                return;
            }
            
            $orden_data = $this->pedidoModel->generarOrdenCompra($id);
            
            $this->flash('success', 'Orden de compra generada: ' . $orden_data['numero']);
            $this->redirect('pedidos/show/' . $id);
        } catch (Exception $e) {
            $this->flash('danger', 'Error al generar orden de compra: ' . $e->getMessage());
            $this->redirect('pedidos/show/' . $id);
        }
    }
    
    public function pdf($id) {
        $pedido = $this->pedidoModel->getPedidoConRelaciones($id);
        
        if (!$pedido) {
            $this->flash('danger', 'Pedido no encontrado.');
            $this->redirect('pedidos');
            return;
        }
        
        $detalles = $this->pedidoModel->getDetallesPedido($id);
        
        // Generar PDF
        $pdf = $this->pedidoModel->generarPDF($pedido, $detalles);
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="pedido_' . $pedido['numero'] . '.pdf"');
        echo $pdf;
    }
    
    public function enviarNotificacion($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $tipo = $_POST['tipo'] ?? 'confirmacion';
            $email = $_POST['email'] ?? null;
            
            $resultado = $this->pedidoModel->enviarNotificacion($id, $tipo, $email);
            
            if ($resultado) {
                $this->json(['success' => true, 'message' => 'Notificación enviada correctamente']);
            } else {
                $this->json(['success' => false, 'message' => 'Error al enviar la notificación'], 500);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al enviar notificación: ' . $e->getMessage()], 500);
        }
    }
}
?>