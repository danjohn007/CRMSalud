<?php
/**
 * Modelo de Usuarios
 */

require_once 'core/BaseModel.php';

class User extends BaseModel {
    protected $table = 'usuarios';
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email AND activo = 1";
        return $this->db->fetchOne($sql, ['email' => $email]);
    }
    
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            // Actualizar último acceso
            $this->update($user['id'], ['ultimo_acceso' => date('Y-m-d H:i:s')]);
            return $user;
        }
        
        return false;
    }
    
    public function createUser($data) {
        // Verificar si el email ya existe
        if ($this->findByEmail($data['email'])) {
            throw new Exception('El email ya está registrado');
        }
        
        // Hashear contraseña
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $this->create($data);
    }
    
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    public function verifyCurrentPassword($userId, $currentPassword) {
        $user = $this->find($userId);
        if ($user && password_verify($currentPassword, $user['password'])) {
            return true;
        }
        return false;
    }
    
    public function updateProfile($userId, $data) {
        // Validar que no se cambie el email a uno ya existente
        if (isset($data['email'])) {
            $existingUser = $this->findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $userId) {
                throw new Exception('El email ya está registrado por otro usuario');
            }
        }
        
        return $this->update($userId, $data);
    }
    
    public function uploadProfileImage($userId, $file) {
        // Validar el archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Solo se permiten JPG, PNG y GIF.');
        }
        
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            throw new Exception('El archivo es demasiado grande. Tamaño máximo: ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . 'MB');
        }
        
        // Generar nombre único para el archivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = UPLOADS_PATH . 'profiles/' . $filename;
        
        // Crear directorio si no existe
        if (!is_dir(dirname($uploadPath))) {
            mkdir(dirname($uploadPath), 0755, true);
        }
        
        // Mover el archivo
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Eliminar imagen anterior si existe
            $user = $this->find($userId);
            if ($user && $user['imagen_perfil']) {
                $oldImagePath = UPLOADS_PATH . 'profiles/' . $user['imagen_perfil'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Actualizar base de datos
            $this->update($userId, ['imagen_perfil' => $filename]);
            return $filename;
        } else {
            throw new Exception('Error al subir el archivo');
        }
    }
    
    public function getActiveUsers() {
        return $this->findAll(['activo' => 1], 'nombre ASC');
    }
    
    public function getUsersByRole($role) {
        return $this->findAll(['rol' => $role, 'activo' => 1], 'nombre ASC');
    }
}
?>