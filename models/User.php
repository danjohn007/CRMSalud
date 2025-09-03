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
    
    public function changePassword($userId, $currentPassword, $newPassword) {
        // Verify current password
        $user = $this->find($userId);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            throw new Exception('La contraseña actual es incorrecta');
        }
        
        // Update to new password
        return $this->updatePassword($userId, $newPassword);
    }
    
    public function updateProfile($userId, $data) {
        // Filter allowed fields for profile update
        $allowedFields = ['nombre', 'email', 'telefono', 'direccion', 'profile_image'];
        $updateData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        // Validate email uniqueness if changed
        if (isset($updateData['email'])) {
            $existingUser = $this->findByEmail($updateData['email']);
            if ($existingUser && $existingUser['id'] != $userId) {
                throw new Exception('El email ya está registrado por otro usuario');
            }
        }
        
        return $this->update($userId, $updateData);
    }
    
    public function getActiveUsers() {
        return $this->findAll(['activo' => 1], 'nombre ASC');
    }
    
    public function getUsersByRole($role) {
        return $this->findAll(['rol' => $role, 'activo' => 1], 'nombre ASC');
    }
}
?>