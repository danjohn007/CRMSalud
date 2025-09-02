<?php
/**
 * Modelo de Cotizaciones
 */

require_once 'core/BaseModel.php';

class Cotizacion extends BaseModel {
    protected $table = 'cotizaciones';
    
    public function getCotizacionesPorEstado($estado) {
        return $this->findAll(['estado' => $estado], 'created_at DESC');
    }
    
    public function getCotizacionesPorCliente($cliente_id) {
        return $this->findAll(['cliente_id' => $cliente_id], 'created_at DESC');
    }
    
    public function getCotizacionesVigentes() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha_vencimiento >= CURDATE() 
                AND estado = 'vigente'
                ORDER BY fecha_vencimiento ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getCotizacionesVencidas() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha_vencimiento < CURDATE() 
                AND estado = 'vigente'
                ORDER BY fecha_vencimiento ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getEstadisticasMensuales($mes, $año) {
        $sql = "SELECT COUNT(*) as total, SUM(total) as valor_total
                FROM {$this->table}
                WHERE MONTH(created_at) = :mes AND YEAR(created_at) = :año";
        return $this->db->fetchOne($sql, ['mes' => $mes, 'año' => $año]);
    }
}
?>