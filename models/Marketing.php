<?php
/**
 * Modelo de Marketing
 */

require_once 'core/BaseModel.php';

class Marketing extends BaseModel {
    protected $table = 'campanas_marketing';
    
    public function getCampanasActivas() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE estado = 'activa' 
                AND fecha_inicio <= CURDATE() 
                AND fecha_fin >= CURDATE()
                ORDER BY fecha_inicio DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function getCampanasPorTipo($tipo) {
        return $this->findAll(['tipo' => $tipo], 'created_at DESC');
    }
    
    public function getCampanasPorEstado($estado) {
        return $this->findAll(['estado' => $estado], 'created_at DESC');
    }
    
    public function getEstadisticasCampana($campana_id) {
        $sql = "SELECT 
                    c.*,
                    COALESCE(SUM(e.impresiones), 0) as total_impresiones,
                    COALESCE(SUM(e.clicks), 0) as total_clicks,
                    COALESCE(SUM(e.conversiones), 0) as total_conversiones
                FROM {$this->table} c
                LEFT JOIN estadisticas_marketing e ON c.id = e.campana_id
                WHERE c.id = :campana_id
                GROUP BY c.id";
        return $this->db->fetchOne($sql, ['campana_id' => $campana_id]);
    }
    
    public function getCampanasProximasVencer($dias = 7) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :dias DAY)
                AND estado = 'activa'
                ORDER BY fecha_fin ASC";
        return $this->db->fetchAll($sql, ['dias' => $dias]);
    }
    
    public function getROICampanas($año = null) {
        $whereClause = $año ? "WHERE YEAR(created_at) = :año" : "";
        $params = $año ? ['año' => $año] : [];
        
        $sql = "SELECT 
                    tipo,
                    COUNT(*) as total_campanas,
                    SUM(presupuesto) as inversion_total,
                    SUM(retorno) as retorno_total,
                    (SUM(retorno) - SUM(presupuesto)) / SUM(presupuesto) * 100 as roi
                FROM {$this->table} 
                {$whereClause}
                GROUP BY tipo";
        return $this->db->fetchAll($sql, $params);
    }
}
?>