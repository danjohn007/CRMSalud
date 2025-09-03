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
        
        // Obtener usuarios activos
        $usuarios = $this->userModel->getActiveUsers();
        
        $this->view('usuarios/index', [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $usuarios,
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
    
    public function store() {
        if (!$this->hasPermission('admin')) {
            $this->json(['error' => 'No tienes permisos para realizar esta acción'], 403);
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Método no permitido');
            $this->redirect('usuarios');
        }
        
        try {
            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? '') ?: null,
                'direccion' => trim($_POST['direccion'] ?? '') ?: null,
                'password' => $_POST['password'] ?? '',
                'rol' => $_POST['rol'] ?? 'vendedor'
            ];
            
            // Validar datos requeridos
            if (empty($data['nombre']) || empty($data['email']) || empty($data['password'])) {
                $this->flash('error', 'Nombre, email y contraseña son obligatorios');
                $this->redirect('usuarios/create');
            }
            
            // Validar email válido
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->flash('error', 'Email no válido');
                $this->redirect('usuarios/create');
            }
            
            // Validar rol válido
            if (!array_key_exists($data['rol'], ROLES)) {
                $this->flash('error', 'Rol no válido');
                $this->redirect('usuarios/create');
            }
            
            // Crear usuario
            $userId = $this->userModel->createUser($data);
            
            $this->flash('success', 'Usuario creado exitosamente');
            $this->redirect('usuarios/show/' . $userId);
            
        } catch (Exception $e) {
            $this->flash('error', 'Error al crear usuario: ' . $e->getMessage());
            $this->redirect('usuarios/create');
        }
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
        
        $this->view('usuarios/edit', [
            'title' => 'Editar Usuario',
            'usuario' => $usuario,
            'roles' => ROLES,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function update($id) {
        if (!$this->hasPermission('admin')) {
            $this->json(['error' => 'No tienes permisos para realizar esta acción'], 403);
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Método no permitido');
            $this->redirect('usuarios');
        }
        
        try {
            $usuario = $this->userModel->find($id);
            if (!$usuario) {
                $this->flash('error', 'Usuario no encontrado');
                $this->redirect('usuarios');
            }
            
            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? '') ?: null,
                'direccion' => trim($_POST['direccion'] ?? '') ?: null,
                'rol' => $_POST['rol'] ?? $usuario['rol']
            ];
            
            // Validar datos requeridos
            if (empty($data['nombre']) || empty($data['email'])) {
                $this->flash('error', 'Nombre y email son obligatorios');
                $this->redirect('usuarios/edit/' . $id);
            }
            
            // Validar email válido
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->flash('error', 'Email no válido');
                $this->redirect('usuarios/edit/' . $id);
            }
            
            // Validar rol válido
            if (!array_key_exists($data['rol'], ROLES)) {
                $this->flash('error', 'Rol no válido');
                $this->redirect('usuarios/edit/' . $id);
            }
            
            // Manejar upload de imagen de perfil
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $data['profile_image'] = $this->handleImageUpload($_FILES['profile_image'], $id);
            }
            
            // Actualizar usuario
            $this->userModel->updateProfile($id, $data);
            
            $this->flash('success', 'Usuario actualizado exitosamente');
            $this->redirect('usuarios/show/' . $id);
            
        } catch (Exception $e) {
            $this->flash('error', 'Error al actualizar usuario: ' . $e->getMessage());
            $this->redirect('usuarios/edit/' . $id);
        }
    }
    
    public function changePassword($id) {
        if (!$this->hasPermission('admin')) {
            $this->json(['error' => 'No tienes permisos para realizar esta acción'], 403);
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Método no permitido');
            $this->redirect('usuarios/edit/' . $id);
        }
        
        try {
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');
            
            if (empty($newPassword) || empty($confirmPassword)) {
                $this->flash('error', 'Debe especificar la nueva contraseña');
                $this->redirect('usuarios/edit/' . $id);
            }
            
            if ($newPassword !== $confirmPassword) {
                $this->flash('error', 'Las contraseñas no coinciden');
                $this->redirect('usuarios/edit/' . $id);
            }
            
            if (strlen($newPassword) < 6) {
                $this->flash('error', 'La contraseña debe tener al menos 6 caracteres');
                $this->redirect('usuarios/edit/' . $id);
            }
            
            $this->userModel->updatePassword($id, $newPassword);
            
            $this->flash('success', 'Contraseña actualizada exitosamente');
            $this->redirect('usuarios/show/' . $id);
            
        } catch (Exception $e) {
            $this->flash('error', 'Error al cambiar contraseña: ' . $e->getMessage());
            $this->redirect('usuarios/edit/' . $id);
        }
    }
    
    public function toggleStatus($id) {
        if (!$this->hasPermission('admin')) {
            $this->json(['error' => 'No tienes permisos para realizar esta acción'], 403);
        }
        
        try {
            $usuario = $this->userModel->find($id);
            if (!$usuario) {
                $this->json(['error' => 'Usuario no encontrado'], 404);
            }
            
            // No permitir desactivar el propio usuario
            $currentUser = $this->getCurrentUser();
            if ($currentUser['id'] == $id) {
                $this->json(['error' => 'No puedes desactivar tu propio usuario'], 400);
            }
            
            $newStatus = $usuario['activo'] ? 0 : 1;
            $this->userModel->update($id, ['activo' => $newStatus]);
            
            $status = $newStatus ? 'activado' : 'desactivado';
            $this->flash('success', "Usuario {$status} exitosamente");
            
            if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
                $this->json(['success' => true, 'status' => $newStatus]);
            } else {
                $this->redirect('usuarios');
            }
            
        } catch (Exception $e) {
            if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
                $this->json(['error' => $e->getMessage()], 500);
            } else {
                $this->flash('error', 'Error al cambiar estado: ' . $e->getMessage());
                $this->redirect('usuarios');
            }
        }
    }
    
    private function handleImageUpload($file, $userId) {
        $uploadDir = UPLOADS_PATH . 'profiles/';
        
        // Crear directorio si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Validar tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Solo se permiten imágenes JPG, PNG y GIF.');
        }
        
        // Validar tamaño del archivo (5MB máximo)
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            throw new Exception('El archivo es demasiado grande. Tamaño máximo: 5MB');
        }
        
        // Generar nombre único para el archivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $filePath = $uploadDir . $fileName;
        
        // Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Error al subir el archivo');
        }
        
        return 'profiles/' . $fileName;
    }
}
?>