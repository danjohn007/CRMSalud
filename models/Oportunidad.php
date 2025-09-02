<?php
/**
 * Modelo de Oportunidades
 */

require_once 'core/BaseModel.php';

class Oportunidad extends BaseModel {
    protected $table = 'oportunidades';
    
    public function getOportunidadesPorEstado($estado) {
        return $this->findAll(['estado' => $estado], 'created_at DESC');
    }
    
    public function getOportunidadesPorCliente($cliente_id) {
        return $this->findAll(['cliente_id' => $cliente_id], 'created_at DESC');
    }
    
    public function getOportunidadesActivas() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE estado NOT IN ('ganado', 'perdido')
                ORDER BY fecha_cierre_estimada ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getEstadisticasPorEstado() {
        $sql = "SELECT estado, COUNT(*) as total, SUM(valor_estimado) as valor_total
                FROM {$this->table}
                GROUP BY estado";
        return $this->db->fetchAll($sql);
    }
    
    public function getOportunidadesVencidas() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha_cierre_estimada < CURDATE() 
                AND estado NOT IN ('ganado', 'perdido')
                ORDER BY fecha_cierre_estimada ASC";
        return $this->db->fetchAll($sql);
    }
}
?>