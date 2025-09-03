<?php
/**
 * Controlador de Usuarios
 */

require_once 'core/BaseController.php';
require_once 'models/User.php';

class UsuariosController extends BaseController {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function index() {
        // Solo administradores pueden gestionar usuarios
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $usuarios = $this->userModel->getActiveUsers();
        
        $this->view('usuarios/index', [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $usuarios,
            'roles' => ROLES,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function create() {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateUserData($_POST);
            
            if (empty($this->errors)) {
                try {
                    $userId = $this->userModel->createUser($data);
                    $this->flash('success', 'Usuario creado exitosamente');
                    $this->redirect('usuarios/show/' . $userId);
                } catch (Exception $e) {
                    $this->flash('error', 'Error al crear usuario: ' . $e->getMessage());
                }
            } else {
                foreach ($this->errors as $error) {
                    $this->flash('error', $error);
                }
            }
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
        
        $usuario = $this->userModel->find($id);
        
        if (!$usuario) {
            $this->flash('error', 'Usuario no encontrado');
            $this->redirect('usuarios');
        }
        
        $this->view('usuarios/view', [
            'title' => 'Detalle de Usuario',
            'usuario' => $usuario,
            'roles' => ROLES,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function edit($id) {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        $usuario = $this->userModel->find($id);
        
        if (!$usuario) {
            $this->flash('error', 'Usuario no encontrado');
            $this->redirect('usuarios');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateUserData($_POST, $id);
            
            if (empty($this->errors)) {
                try {
                    $this->userModel->updateProfile($id, $data);
                    $this->flash('success', 'Usuario actualizado exitosamente');
                    $this->redirect('usuarios/show/' . $id);
                } catch (Exception $e) {
                    $this->flash('error', 'Error al actualizar usuario: ' . $e->getMessage());
                }
            } else {
                foreach ($this->errors as $error) {
                    $this->flash('error', $error);
                }
            }
        }
        
        $this->view('usuarios/edit', [
            'title' => 'Editar Usuario',
            'usuario' => $usuario,
            'roles' => ROLES,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function delete($id) {
        if (!$this->hasPermission('admin')) {
            $this->flash('error', 'No tienes permisos para acceder a esta sección');
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Soft delete - marcar como inactivo
                $this->userModel->update($id, ['activo' => 0]);
                $this->flash('success', 'Usuario eliminado exitosamente');
            } catch (Exception $e) {
                $this->flash('error', 'Error al eliminar usuario: ' . $e->getMessage());
            }
        }
        
        $this->redirect('usuarios');
    }
    
    private function validateUserData($data, $userId = null) {
        $this->errors = [];
        $validatedData = [];
        
        // Validaciones requeridas
        if (empty($data['nombre'])) {
            $this->errors[] = 'El nombre es requerido';
        } else {
            $validatedData['nombre'] = trim($data['nombre']);
        }
        
        if (empty($data['email'])) {
            $this->errors[] = 'El email es requerido';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'El email no es válido';
        } else {
            $validatedData['email'] = trim($data['email']);
        }
        
        if (empty($data['rol']) || !array_key_exists($data['rol'], ROLES)) {
            $this->errors[] = 'El rol es requerido y debe ser válido';
        } else {
            $validatedData['rol'] = $data['rol'];
        }
        
        // Validar contraseña solo para nuevos usuarios o si se proporciona
        if ($userId === null) { // Nuevo usuario
            if (empty($data['password'])) {
                $this->errors[] = 'La contraseña es requerida';
            } elseif (strlen($data['password']) < 6) {
                $this->errors[] = 'La contraseña debe tener al menos 6 caracteres';
            } else {
                $validatedData['password'] = $data['password'];
            }
        }
        
        // Campos opcionales
        if (!empty($data['telefono'])) {
            $validatedData['telefono'] = trim($data['telefono']);
        }
        
        if (!empty($data['direccion'])) {
            $validatedData['direccion'] = trim($data['direccion']);
        }
        
        return $validatedData;
    }
    
    private $errors = [];
}
?>