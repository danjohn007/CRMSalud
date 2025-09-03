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
        
        $data = $_POST;
        $userId = $_SESSION['user']['id'];
        
        try {
            // Handle profile image upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $imageData = $this->handleImageUpload($_FILES['profile_image']);
                if ($imageData) {
                    $data['profile_image'] = $imageData['path'];
                }
            }
            
            // Handle password change if provided
            if (!empty($data['password_actual']) && !empty($data['password_nueva'])) {
                if ($data['password_nueva'] !== $data['password_confirmar']) {
                    $this->flash('error', 'Las contraseñas nuevas no coinciden');
                    $this->redirect('perfil/editar');
                }
                
                $this->userModel->changePassword($userId, $data['password_actual'], $data['password_nueva']);
                $this->flash('success', 'Contraseña actualizada correctamente');
            }
            
            // Update profile data (excluding password fields)
            unset($data['password_actual'], $data['password_nueva'], $data['password_confirmar']);
            
            $this->userModel->updateProfile($userId, $data);
            
            // Update session data
            $_SESSION['user'] = $this->userModel->find($userId);
            
            $this->flash('success', 'Perfil actualizado correctamente');
            
        } catch (Exception $e) {
            $this->flash('error', 'Error al actualizar perfil: ' . $e->getMessage());
        }
        
        $this->redirect('perfil');
    }
    
    private function handleImageUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Solo se permiten imágenes JPEG, PNG y GIF.');
        }
        
        // Validate file size
        if ($file['size'] > $maxSize) {
            throw new Exception('El archivo es demasiado grande. Tamaño máximo: 5MB.');
        }
        
        // Create uploads directory if it doesn't exist
        $uploadsDir = 'uploads/profiles';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $_SESSION['user']['id'] . '_' . time() . '.' . $extension;
        $filepath = $uploadsDir . '/' . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return [
                'path' => $filepath,
                'filename' => $filename
            ];
        } else {
            throw new Exception('Error al subir el archivo');
        }
    }
}
?>