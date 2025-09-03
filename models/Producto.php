<?php
/**
 * Modelo de Productos
 */

require_once 'core/BaseModel.php';

class Producto extends BaseModel {
    protected $table = 'productos';
    
    public function searchProductos($search) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE activo = 1 
                AND (nombre LIKE :search_nombre OR sku LIKE :search_sku OR principio_activo LIKE :search_principio)
                ORDER BY nombre ASC";
        $searchTerm = "%{$search}%";
        return $this->db->fetchAll($sql, [
            'search_nombre' => $searchTerm,
            'search_sku' => $searchTerm,
            'search_principio' => $searchTerm
        ]);
    }
    
    public function getProductosByCategoria($categoria) {
        return $this->findAll(['categoria' => $categoria, 'activo' => 1], 'nombre ASC');
    }
    
    public function getProductosControlados() {
        return $this->findAll(['controlado' => 1, 'activo' => 1], 'nombre ASC');
    }
    
    public function getProductosConReceta() {
        return $this->findAll(['requiere_receta' => 1, 'activo' => 1], 'nombre ASC');
    }
    
    public function getProductosStockBajo() {
        $sql = "SELECT p.*, i.stock_actual, i.fecha_vencimiento
                FROM {$this->table} p
                LEFT JOIN inventarios i ON p.id = i.producto_id
                WHERE p.activo = 1 
                AND (i.stock_actual <= p.stock_minimo OR i.stock_actual IS NULL)
                ORDER BY p.nombre ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getProductosProximosVencer($dias = 30) {
        $sql = "SELECT p.*, i.stock_actual, i.fecha_vencimiento, i.lote
                FROM {$this->table} p
                INNER JOIN inventarios i ON p.id = i.producto_id
                WHERE p.activo = 1 
                AND i.fecha_vencimiento <= DATE_ADD(CURRENT_DATE(), INTERVAL :dias DAY)
                AND i.fecha_vencimiento >= CURRENT_DATE()
                ORDER BY i.fecha_vencimiento ASC";
        return $this->db->fetchAll($sql, ['dias' => $dias]);
    }
    
    public function getCategorias() {
        $sql = "SELECT DISTINCT categoria FROM {$this->table} 
                WHERE activo = 1 AND categoria IS NOT NULL 
                ORDER BY categoria ASC";
        $result = $this->db->fetchAll($sql);
        return array_column($result, 'categoria');
    }
    
    public function getMarcas() {
        $sql = "SELECT DISTINCT marca FROM {$this->table} 
                WHERE activo = 1 AND marca IS NOT NULL 
                ORDER BY marca ASC";
        $result = $this->db->fetchAll($sql);
        return array_column($result, 'marca');
    }
}
?>