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
    
    public function getActiveUsers() {
        return $this->findAll(['activo' => 1], 'nombre ASC');
    }
    
    public function getUsersByRole($role) {
        return $this->findAll(['rol' => $role, 'activo' => 1], 'nombre ASC');
    }
}
?>