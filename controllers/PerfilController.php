<?php
/**
 * Controlador de Perfil de Usuario
 */

require_once 'core/BaseController.php';
require_once 'models/User.php';

class PerfilController extends BaseController {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function index() {
        $this->view('perfil/index', [
            'title' => 'Mi Perfil',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function editar() {
        $this->view('perfil/editar', [
            'title' => 'Editar Perfil',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('perfil');
        }
        
        $userId = $_SESSION['user_id'];
        $data = $this->validateProfileData($_POST);
        
        try {
            // Manejar subida de imagen de perfil
            if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
                $filename = $this->userModel->uploadProfileImage($userId, $_FILES['imagen_perfil']);
                $data['imagen_perfil'] = $filename;
            }
            
            // Manejar cambio de contraseña
            if (!empty($_POST['password_nueva'])) {
                if (empty($_POST['password_actual'])) {
                    $this->flash('error', 'Debes ingresar tu contraseña actual para cambiarla');
                    $this->redirect('perfil/editar');
                }
                
                if (!$this->userModel->verifyCurrentPassword($userId, $_POST['password_actual'])) {
                    $this->flash('error', 'La contraseña actual es incorrecta');
                    $this->redirect('perfil/editar');
                }
                
                if ($_POST['password_nueva'] !== $_POST['password_confirmar']) {
                    $this->flash('error', 'Las contraseñas nuevas no coinciden');
                    $this->redirect('perfil/editar');
                }
                
                if (strlen($_POST['password_nueva']) < 6) {
                    $this->flash('error', 'La nueva contraseña debe tener al menos 6 caracteres');
                    $this->redirect('perfil/editar');
                }
                
                $this->userModel->updatePassword($userId, $_POST['password_nueva']);
            }
            
            // Actualizar otros datos del perfil
            if (!empty($data)) {
                $this->userModel->updateProfile($userId, $data);
            }
            
            // Actualizar datos en sesión
            $updatedUser = $this->userModel->find($userId);
            $_SESSION['user_nombre'] = $updatedUser['nombre'];
            $_SESSION['user_email'] = $updatedUser['email'];
            
            $this->flash('success', 'Perfil actualizado exitosamente');
            
        } catch (Exception $e) {
            $this->flash('error', 'Error al actualizar perfil: ' . $e->getMessage());
        }
        
        $this->redirect('perfil');
    }
    
    private function validateProfileData($data) {
        $validatedData = [];
        
        // Validar nombre
        if (!empty($data['nombre'])) {
            $validatedData['nombre'] = trim($data['nombre']);
        }
        
        // Validar email
        if (!empty($data['email'])) {
            if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $validatedData['email'] = trim($data['email']);
            } else {
                throw new Exception('El email no es válido');
            }
        }
        
        // Validar teléfono (opcional)
        if (!empty($data['telefono'])) {
            $validatedData['telefono'] = trim($data['telefono']);
        }
        
        // Validar dirección (opcional)
        if (!empty($data['direccion'])) {
            $validatedData['direccion'] = trim($data['direccion']);
        }
        
        return $validatedData;
    }
}
?>