<?php
/**
 * Controlador de autenticación
 */

require_once 'core/BaseController.php';

class AuthController extends BaseController {
    
    public function __construct() {
        $this->db = Database::getInstance();
        // No llamamos checkAuth() aquí porque este controlador maneja la autenticación
    }
    
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $this->flash('error', 'Email y contraseña son requeridos');
            } else {
                $user = $this->authenticateUser($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_nombre'] = $user['nombre'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_rol'] = $user['rol'];
                    
                    $this->flash('success', 'Bienvenido ' . $user['nombre']);
                    $this->redirect('dashboard');
                } else {
                    $this->flash('error', 'Credenciales inválidas');
                }
            }
        }
        
        $this->view('auth/login', [
            'title' => 'Iniciar Sesión',
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
    
    public function register() {
        // Solo administradores pueden registrar nuevos usuarios
        if (!$this->hasPermission('admin')) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? '';
            
            $errors = [];
            
            if (empty($nombre)) $errors[] = 'El nombre es requerido';
            if (empty($email)) $errors[] = 'El email es requerido';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
            if (empty($password) || strlen($password) < 6) $errors[] = 'La contraseña debe tener al menos 6 caracteres';
            if (!array_key_exists($rol, ROLES)) $errors[] = 'Rol inválido';
            
            // Verificar si el email ya existe
            if (empty($errors)) {
                $existingUser = $this->db->fetchOne(
                    "SELECT id FROM usuarios WHERE email = :email",
                    ['email' => $email]
                );
                if ($existingUser) {
                    $errors[] = 'El email ya está registrado';
                }
            }
            
            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $userId = $this->db->insert('usuarios', [
                    'nombre' => $nombre,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'rol' => $rol,
                    'activo' => 1
                ]);
                
                if ($userId) {
                    $this->flash('success', 'Usuario registrado exitosamente');
                    $this->redirect('usuarios');
                } else {
                    $this->flash('error', 'Error al registrar usuario');
                }
            } else {
                foreach ($errors as $error) {
                    $this->flash('error', $error);
                }
            }
        }
        
        $this->view('auth/register', [
            'title' => 'Registrar Usuario',
            'roles' => ROLES,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    private function authenticateUser($email, $password) {
        $user = $this->db->fetchOne(
            "SELECT * FROM usuarios WHERE email = :email AND activo = 1",
            ['email' => $email]
        );
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
}
?>