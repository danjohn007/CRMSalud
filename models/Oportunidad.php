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
    
    public function getOportunidadConRelaciones($id) {
        $sql = "SELECT o.*, 
                       c.nombre as cliente_nombre, c.tipo as cliente_tipo, c.email as cliente_email,
                       u.nombre as usuario_nombre, u.email as usuario_email
                FROM {$this->table} o
                LEFT JOIN clientes c ON o.cliente_id = c.id
                LEFT JOIN usuarios u ON o.usuario_id = u.id
                WHERE o.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function getOportunidadesConRelaciones($conditions = [], $orderBy = 'o.created_at DESC', $limit = null) {
        $sql = "SELECT o.*, 
                       c.nombre as cliente_nombre, c.tipo as cliente_tipo, c.email as cliente_email,
                       u.nombre as usuario_nombre, u.email as usuario_email
                FROM {$this->table} o
                LEFT JOIN clientes c ON o.cliente_id = c.id
                LEFT JOIN usuarios u ON o.usuario_id = u.id";
        
        $params = [];
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $sql .= " ORDER BY {$orderBy}";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getEstadisticasPipeline() {
        $sql = "SELECT 
                    estado,
                    COUNT(*) as cantidad,
                    SUM(valor_estimado) as valor_total,
                    AVG(probabilidad) as probabilidad_promedio
                FROM {$this->table}
                WHERE estado NOT IN ('ganado', 'perdido')
                GROUP BY estado
                ORDER BY FIELD(estado, 'prospecto', 'contactado', 'calificado', 'propuesta', 'negociacion')";
        return $this->db->fetchAll($sql);
    }
    
    public function getComunicacionesOportunidad($oportunidad_id) {
        $sql = "SELECT c.*, u.nombre as usuario_nombre
                FROM comunicaciones c
                LEFT JOIN usuarios u ON c.usuario_id = u.id
                LEFT JOIN oportunidades o ON c.cliente_id = o.cliente_id
                WHERE o.id = :oportunidad_id
                ORDER BY c.fecha_comunicacion DESC";
        return $this->db->fetchAll($sql, ['oportunidad_id' => $oportunidad_id]);
    }
    
    public function getActividadesOportunidad($oportunidad_id) {
        $sql = "SELECT a.*, u.nombre as usuario_nombre
                FROM actividades_calendario a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                LEFT JOIN oportunidades o ON a.cliente_id = o.cliente_id
                WHERE o.id = :oportunidad_id
                ORDER BY a.fecha_inicio DESC";
        return $this->db->fetchAll($sql, ['oportunidad_id' => $oportunidad_id]);
    }
}
?>