<?php
/**
 * Modelo de Pedidos
 */

require_once 'core/BaseModel.php';

class Pedido extends BaseModel {
    protected $table = 'pedidos';
    
    public function getPedidosPorEstado($estado) {
        return $this->findAll(['estado' => $estado], 'created_at DESC');
    }
    
    public function getPedidosPorCliente($cliente_id) {
        return $this->findAll(['cliente_id' => $cliente_id], 'created_at DESC');
    }
    
    public function getPedidosPendientes() {
        return $this->findAll(['estado' => 'pendiente'], 'created_at ASC');
    }
    
    public function getPedidosEnProceso() {
        return $this->findAll(['estado' => 'en_proceso'], 'created_at ASC');
    }
    
    public function getPedidosCompletados($limite = null) {
        return $this->findAll(['estado' => 'completado'], 'created_at DESC', $limite);
    }
    
    public function getEstadisticasVentas($fecha_inicio, $fecha_fin) {
        $sql = "SELECT COUNT(*) as total_pedidos, SUM(total) as valor_total
                FROM {$this->table}
                WHERE created_at BETWEEN :fecha_inicio AND :fecha_fin
                AND estado = 'completado'";
        return $this->db->fetchOne($sql, [
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ]);
    }
    
    public function getPedidosUrgentes() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE prioridad = 'alta' 
                AND estado IN ('pendiente', 'en_proceso')
                ORDER BY created_at ASC";
        return $this->db->fetchAll($sql);
    }
}
?>