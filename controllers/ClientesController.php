<?php
/**
 * Controlador de Clientes
 */

require_once 'core/BaseController.php';
require_once 'models/Cliente.php';

class ClientesController extends BaseController {
    private $clienteModel;
    
    public function __construct() {
        parent::__construct();
        $this->clienteModel = new Cliente();
    }
    
    public function index() {
        $tipo = $_GET['tipo'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        
        $conditions = ['activo' => 1];
        if ($tipo && array_key_exists($tipo, TIPOS_CLIENTE)) {
            $conditions['tipo'] = $tipo;
        }
        
        if ($search) {
            $clientes = $this->clienteModel->searchClientes($search, $tipo);
            $pagination = [
                'items' => $clientes,
                'total' => count($clientes),
                'page' => 1,
                'perPage' => count($clientes),
                'totalPages' => 1
            ];
        } else {
            $pagination = $this->clienteModel->paginate($page, 20, $conditions);
        }
        
        $stats = $this->clienteModel->getClientesStats();
        
        $this->view('clientes/index', [
            'title' => 'Gestión de Clientes',
            'clientes' => $pagination,
            'stats' => $stats,
            'tiposCliente' => TIPOS_CLIENTE,
            'currentFilters' => [
                'tipo' => $tipo,
                'search' => $search
            ],
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateClienteData($_POST);
            
            if (empty($this->errors)) {
                try {
                    $clienteId = $this->clienteModel->create($data);
                    $this->flash('success', 'Cliente registrado exitosamente');
                    $this->redirect('clientes/show/' . $clienteId);
                } catch (Exception $e) {
                    $this->flash('error', 'Error al registrar cliente: ' . $e->getMessage());
                }
            } else {
                foreach ($this->errors as $error) {
                    $this->flash('error', $error);
                }
            }
        }
        
        $this->view('clientes/create', [
            'title' => 'Nuevo Cliente',
            'tiposCliente' => TIPOS_CLIENTE,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function show($id) {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $this->flash('error', 'Cliente no encontrado');
            $this->redirect('clientes');
        }
        
        // Obtener histórico de comunicaciones, oportunidades, etc.
        // TODO: Implementar cuando tengamos los modelos correspondientes
        
        $this->view('clientes/view', [
            'title' => 'Detalle del Cliente',
            'cliente' => $cliente,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $this->flash('error', 'Cliente no encontrado');
            $this->redirect('clientes');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateClienteData($_POST);
            
            if (empty($this->errors)) {
                try {
                    $this->clienteModel->update($id, $data);
                    $this->flash('success', 'Cliente actualizado exitosamente');
                    $this->redirect('clientes/show/' . $id);
                } catch (Exception $e) {
                    $this->flash('error', 'Error al actualizar cliente: ' . $e->getMessage());
                }
            } else {
                foreach ($this->errors as $error) {
                    $this->flash('error', $error);
                }
            }
        }
        
        $this->view('clientes/edit', [
            'title' => 'Editar Cliente',
            'cliente' => $cliente,
            'tiposCliente' => TIPOS_CLIENTE,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Soft delete - marcar como inactivo
                $this->clienteModel->update($id, ['activo' => 0]);
                $this->flash('success', 'Cliente eliminado exitosamente');
            } catch (Exception $e) {
                $this->flash('error', 'Error al eliminar cliente: ' . $e->getMessage());
            }
        }
        
        $this->redirect('clientes');
    }
    
    public function cambiarEstatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('clientes');
        }
        
        $cliente = $this->clienteModel->find($id);
        if (!$cliente) {
            $this->flash('error', 'Cliente no encontrado');
            $this->redirect('clientes');
        }
        
        try {
            $nuevoEstatus = $cliente['activo'] ? 0 : 1;
            $this->clienteModel->update($id, ['activo' => $nuevoEstatus]);
            
            $mensaje = $nuevoEstatus ? 'Cliente activado exitosamente' : 'Cliente desactivado exitosamente';
            $this->flash('success', $mensaje);
        } catch (Exception $e) {
            $this->flash('error', 'Error al cambiar estatus: ' . $e->getMessage());
        }
        
        $this->redirect('clientes');
    }
    
    private function validateClienteData($data) {
        $this->errors = [];
        $validatedData = [];
        
        // Validaciones requeridas
        if (empty($data['nombre'])) {
            $this->errors[] = 'El nombre es requerido';
        } else {
            $validatedData['nombre'] = trim($data['nombre']);
        }
        
        if (empty($data['tipo']) || !array_key_exists($data['tipo'], TIPOS_CLIENTE)) {
            $this->errors[] = 'El tipo de cliente es requerido';
        } else {
            $validatedData['tipo'] = $data['tipo'];
        }
        
        // Validaciones opcionales
        if (!empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'El email no es válido';
            } else {
                $validatedData['email'] = trim($data['email']);
            }
        }
        
        // Asignar resto de campos
        $campos = ['telefono', 'direccion', 'ciudad', 'estado', 'codigo_postal', 
                   'especialidad', 'cedula_profesional', 'rfc', 'volumen_compra',
                   'descuento_autorizado', 'limite_credito', 'dias_credito', 'notas'];
        
        foreach ($campos as $campo) {
            if (isset($data[$campo])) {
                $validatedData[$campo] = trim($data[$campo]);
            }
        }
        
        // Convertir valores numéricos
        if (isset($validatedData['descuento_autorizado'])) {
            $validatedData['descuento_autorizado'] = (float)$validatedData['descuento_autorizado'];
        }
        if (isset($validatedData['limite_credito'])) {
            $validatedData['limite_credito'] = (float)$validatedData['limite_credito'];
        }
        if (isset($validatedData['dias_credito'])) {
            $validatedData['dias_credito'] = (int)$validatedData['dias_credito'];
        }
        
        return $validatedData;
    }
    
    private $errors = [];
}
?>