<?php
/**
 * Modelo de Clientes
 */

require_once 'core/BaseModel.php';

class Cliente extends BaseModel {
    protected $table = 'clientes';
    
    public function getClientesByType($tipo) {
        return $this->findAll(['tipo' => $tipo, 'activo' => 1], 'nombre ASC');
    }
    
    public function searchClientes($search, $tipo = null) {
        $sql = "SELECT * FROM {$this->table} WHERE activo = 1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search OR email LIKE :search OR telefono LIKE :search)";
            $params['search'] = "%{$search}%";
        }
        
        if ($tipo) {
            $sql .= " AND tipo = :tipo";
            $params['tipo'] = $tipo;
        }
        
        $sql .= " ORDER BY nombre ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getClientesStats() {
        $stats = [];
        
        // Total por tipo
        foreach (TIPOS_CLIENTE as $key => $label) {
            $stats[$key] = $this->count(['tipo' => $key, 'activo' => 1]);
        }
        
        // Total general
        $stats['total'] = $this->count(['activo' => 1]);
        
        return $stats;
    }
    
    public function getClientesByVolumen($volumen) {
        return $this->findAll(['volumen_compra' => $volumen, 'activo' => 1], 'nombre ASC');
    }
    
    public function getClientesByCity($ciudad) {
        return $this->findAll(['ciudad' => $ciudad, 'activo' => 1], 'nombre ASC');
    }
    
    public function getClientesWithCredito() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE activo = 1 AND limite_credito > 0 
                ORDER BY limite_credito DESC";
        return $this->db->fetchAll($sql);
    }
}
?>