<?php
/**
 * Controlador de Productos
 */

require_once 'core/BaseController.php';
require_once 'models/Producto.php';

class ProductosController extends BaseController {
    private $productoModel;
    
    public function __construct() {
        parent::__construct();
        $this->productoModel = new Producto();
    }
    
    public function index() {
        $categoria = $_GET['categoria'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        
        $conditions = ['activo' => 1];
        if ($categoria) {
            $conditions['categoria'] = $categoria;
        }
        
        if ($search) {
            $productos = $this->productoModel->searchProductos($search);
            $pagination = [
                'items' => $productos,
                'total' => count($productos),
                'page' => 1,
                'perPage' => count($productos),
                'totalPages' => 1
            ];
        } else {
            $pagination = $this->productoModel->paginate($page, 20, $conditions);
        }
        
        $categorias = $this->productoModel->getCategorias();
        $marcas = $this->productoModel->getMarcas();
        
        $this->view('productos/index', [
            'title' => 'Catálogo de Productos',
            'productos' => $pagination,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'currentFilters' => [
                'categoria' => $categoria,
                'search' => $search
            ],
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function stockBajo() {
        $productos = $this->productoModel->getProductosStockBajo();
        
        $this->view('productos/stock-bajo', [
            'title' => 'Productos con Stock Bajo',
            'productos' => $productos,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function proximosVencer() {
        $dias = (int)($_GET['dias'] ?? 30);
        $productos = $this->productoModel->getProductosProximosVencer($dias);
        
        $this->view('productos/proximos-vencer', [
            'title' => 'Productos Próximos a Vencer',
            'productos' => $productos,
            'dias' => $dias,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para realizar esta acción');
            $this->redirect('productos');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateProductoData($_POST);
            
            if (empty($this->errors)) {
                try {
                    $productoId = $this->productoModel->create($data);
                    $this->flash('success', 'Producto registrado exitosamente');
                    $this->redirect('productos/show/' . $productoId);
                } catch (Exception $e) {
                    $this->flash('error', 'Error al registrar producto: ' . $e->getMessage());
                }
            } else {
                foreach ($this->errors as $error) {
                    $this->flash('error', $error);
                }
            }
        }
        
        $categorias = $this->productoModel->getCategorias();
        $marcas = $this->productoModel->getMarcas();
        
        $this->view('productos/create', [
            'title' => 'Nuevo Producto',
            'categorias' => $categorias,
            'marcas' => $marcas,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $producto = $this->productoModel->find($id);
        
        if (!$producto) {
            $this->flash('error', 'Producto no encontrado');
            $this->redirect('productos');
        }
        
        $this->view('productos/view', [
            'title' => 'Detalle del Producto',
            'producto' => $producto,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para realizar esta acción');
            $this->redirect('productos');
        }
        
        $producto = $this->productoModel->find($id);
        
        if (!$producto) {
            $this->flash('error', 'Producto no encontrado');
            $this->redirect('productos');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateProductoData($_POST);
            
            if (empty($this->errors)) {
                try {
                    $this->productoModel->update($id, $data);
                    $this->flash('success', 'Producto actualizado exitosamente');
                    $this->redirect('productos/show/' . $id);
                } catch (Exception $e) {
                    $this->flash('error', 'Error al actualizar producto: ' . $e->getMessage());
                }
            } else {
                foreach ($this->errors as $error) {
                    $this->flash('error', $error);
                }
            }
        }
        
        $categorias = $this->productoModel->getCategorias();
        $marcas = $this->productoModel->getMarcas();
        
        $this->view('productos/edit', [
            'title' => 'Editar Producto',
            'producto' => $producto,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    private function validateProductoData($data) {
        $this->errors = [];
        $validatedData = [];
        
        // Validaciones requeridas
        if (empty($data['sku'])) {
            $this->errors[] = 'El SKU es requerido';
        } else {
            $validatedData['sku'] = strtoupper(trim($data['sku']));
        }
        
        if (empty($data['nombre'])) {
            $this->errors[] = 'El nombre es requerido';
        } else {
            $validatedData['nombre'] = trim($data['nombre']);
        }
        
        // Validar precios
        if (!empty($data['precio_base'])) {
            if (!is_numeric($data['precio_base']) || $data['precio_base'] < 0) {
                $this->errors[] = 'El precio base debe ser un número válido';
            } else {
                $validatedData['precio_base'] = (float)$data['precio_base'];
            }
        }
        
        if (!empty($data['precio_publico'])) {
            if (!is_numeric($data['precio_publico']) || $data['precio_publico'] < 0) {
                $this->errors[] = 'El precio público debe ser un número válido';
            } else {
                $validatedData['precio_publico'] = (float)$data['precio_publico'];
            }
        }
        
        // Resto de campos
        $campos = ['descripcion', 'categoria', 'marca', 'principio_activo', 'presentacion', 'stock_minimo'];
        
        foreach ($campos as $campo) {
            if (isset($data[$campo])) {
                $validatedData[$campo] = trim($data[$campo]);
            }
        }
        
        // Campos booleanos
        $validatedData['requiere_receta'] = isset($data['requiere_receta']) ? 1 : 0;
        $validatedData['controlado'] = isset($data['controlado']) ? 1 : 0;
        
        // Convertir stock_minimo a entero
        if (isset($validatedData['stock_minimo'])) {
            $validatedData['stock_minimo'] = (int)$validatedData['stock_minimo'];
        }
        
        return $validatedData;
    }
    
    public function delete($id) {
        if (!$this->hasPermission('admin')) {
            $this->json(['success' => false, 'message' => 'No tienes permisos para realizar esta acción'], 403);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $producto = $this->productoModel->find($id);
            if (!$producto) {
                $this->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
                return;
            }
            
            // En lugar de eliminar, marcar como inactivo
            $this->productoModel->update($id, ['activo' => 0]);
            $this->json(['success' => true, 'message' => 'Producto desactivado correctamente']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al eliminar el producto: ' . $e->getMessage()], 500);
        }
    }
    
    public function activar($id) {
        if (!$this->hasPermission('admin')) {
            $this->json(['success' => false, 'message' => 'No tienes permisos para realizar esta acción'], 403);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $producto = $this->productoModel->find($id);
            if (!$producto) {
                $this->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
                return;
            }
            
            $this->productoModel->update($id, ['activo' => 1]);
            $this->json(['success' => true, 'message' => 'Producto activado correctamente']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al activar el producto: ' . $e->getMessage()], 500);
        }
    }
    
    public function actualizarInventario($id) {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para realizar esta acción');
            $this->redirect('productos');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        try {
            $stock_actual = $_POST['stock_actual'] ?? 0;
            $lote = $_POST['lote'] ?? null;
            $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
            
            $this->productoModel->actualizarInventario($id, $stock_actual, $lote, $fecha_vencimiento);
            $this->json(['success' => true, 'message' => 'Inventario actualizado correctamente']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al actualizar inventario: ' . $e->getMessage()], 500);
        }
    }
    
    public function alertas() {
        $alertas = $this->productoModel->getAlertasInventario();
        
        $this->view('productos/alertas', [
            'title' => 'Alertas de Inventario',
            'alertas' => $alertas,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function inventario() {
        $categoria = $_GET['categoria'] ?? '';
        $search = $_GET['search'] ?? '';
        
        $conditions = [];
        if ($categoria) {
            $conditions['categoria'] = $categoria;
        }
        
        if ($search) {
            $productos = $this->productoModel->searchProductos($search);
        } else {
            $productos = $this->productoModel->getProductosConInventario($conditions);
        }
        
        $categorias = $this->productoModel->getCategorias();
        
        $this->view('productos/inventario', [
            'title' => 'Gestión de Inventario',
            'productos' => $productos,
            'categorias' => $categorias,
            'currentFilters' => [
                'categoria' => $categoria,
                'search' => $search
            ],
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function exportar() {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para realizar esta acción');
            $this->redirect('productos');
            return;
        }
        
        $productos = $this->productoModel->getProductosConInventario();
        
        // Generar CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="productos_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'SKU', 'Nombre', 'Descripción', 'Categoría', 'Marca', 
            'Precio Base', 'Precio Público', 'Stock Actual', 'Stock Mínimo',
            'Fecha Vencimiento', 'Lote', 'Estado'
        ]);
        
        // Datos
        foreach ($productos as $producto) {
            fputcsv($output, [
                $producto['sku'],
                $producto['nombre'],
                $producto['descripcion'],
                $producto['categoria'],
                $producto['marca'],
                $producto['precio_base'],
                $producto['precio_publico'],
                $producto['stock_actual'] ?? 0,
                $producto['stock_minimo'],
                $producto['fecha_vencimiento'],
                $producto['lote'],
                $producto['estado_stock'] ?? 'normal'
            ]);
        }
        
        fclose($output);
    }
}
?>