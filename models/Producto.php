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
    
    public function getFamilias() {
        $sql = "SELECT DISTINCT familia FROM {$this->table} 
                WHERE activo = 1 AND familia IS NOT NULL 
                ORDER BY familia ASC";
        $result = $this->db->fetchAll($sql);
        return array_column($result, 'familia');
    }
    
    public function getProductosConInventario($conditions = []) {
        $sql = "SELECT p.*, i.stock_actual, i.fecha_vencimiento, i.lote,
                       CASE 
                           WHEN i.stock_actual <= p.stock_minimo THEN 'bajo'
                           WHEN i.fecha_vencimiento <= DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY) THEN 'vence'
                           ELSE 'normal'
                       END as estado_stock
                FROM {$this->table} p
                LEFT JOIN inventarios i ON p.id = i.producto_id
                WHERE p.activo = 1";
        
        $params = [];
        if (!empty($conditions)) {
            foreach ($conditions as $column => $value) {
                $sql .= " AND p.{$column} = :{$column}";
                $params[$column] = $value;
            }
        }
        
        $sql .= " ORDER BY p.nombre ASC";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function actualizarInventario($producto_id, $stock_actual, $lote = null, $fecha_vencimiento = null) {
        // Buscar inventario existente
        $sql = "SELECT * FROM inventarios WHERE producto_id = :producto_id";
        $inventario = $this->db->fetchOne($sql, ['producto_id' => $producto_id]);
        
        if ($inventario) {
            // Actualizar inventario existente
            $data = ['stock_actual' => $stock_actual];
            if ($lote) $data['lote'] = $lote;
            if ($fecha_vencimiento) $data['fecha_vencimiento'] = $fecha_vencimiento;
            
            $this->db->update('inventarios', $data, 'producto_id = :producto_id', ['producto_id' => $producto_id]);
        } else {
            // Crear nuevo inventario
            $data = [
                'producto_id' => $producto_id,
                'stock_actual' => $stock_actual,
                'lote' => $lote,
                'fecha_vencimiento' => $fecha_vencimiento
            ];
            $this->db->insert('inventarios', $data);
        }
    }
    
    public function getAlertasInventario() {
        $sql = "SELECT p.nombre, p.sku, i.stock_actual, p.stock_minimo, i.fecha_vencimiento,
                       CASE 
                           WHEN i.stock_actual <= p.stock_minimo THEN 'Stock bajo'
                           WHEN i.fecha_vencimiento <= DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY) THEN 'Próximo a vencer'
                           ELSE 'Normal'
                       END as tipo_alerta
                FROM {$this->table} p
                INNER JOIN inventarios i ON p.id = i.producto_id
                WHERE p.activo = 1 
                AND (i.stock_actual <= p.stock_minimo 
                     OR i.fecha_vencimiento <= DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY))
                ORDER BY i.fecha_vencimiento ASC, i.stock_actual ASC";
        return $this->db->fetchAll($sql);
    }
}
?>