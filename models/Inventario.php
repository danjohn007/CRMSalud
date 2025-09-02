<?php
/**
 * Modelo de Inventarios
 */

require_once 'core/BaseModel.php';

class Inventario extends BaseModel {
    protected $table = 'inventarios';
    
    public function getInventariosConProductos() {
        $sql = "SELECT i.*, p.nombre as producto_nombre, p.sku, p.stock_minimo
                FROM {$this->table} i 
                INNER JOIN productos p ON i.producto_id = p.id
                WHERE p.activo = 1
                ORDER BY p.nombre ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getInventariosStockBajo() {
        $sql = "SELECT i.*, p.nombre as producto_nombre, p.sku, p.stock_minimo
                FROM {$this->table} i 
                INNER JOIN productos p ON i.producto_id = p.id
                WHERE p.activo = 1 AND i.stock_actual <= p.stock_minimo
                ORDER BY i.stock_actual ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getInventariosProximosVencer($dias = 30) {
        $sql = "SELECT i.*, p.nombre as producto_nombre, p.sku
                FROM {$this->table} i 
                INNER JOIN productos p ON i.producto_id = p.id
                WHERE p.activo = 1 
                AND i.fecha_vencimiento <= DATE_ADD(CURRENT_DATE(), INTERVAL :dias DAY)
                AND i.fecha_vencimiento >= CURRENT_DATE()
                ORDER BY i.fecha_vencimiento ASC";
        return $this->db->fetchAll($sql, ['dias' => $dias]);
    }
}
?>