<?php
/**
 * Modelo de Reportes
 */

require_once 'core/BaseModel.php';

class Reporte extends BaseModel {
    protected $table = 'reportes';
    
    public function getReportesPorTipo($tipo) {
        return $this->findAll(['tipo' => $tipo], 'created_at DESC');
    }
    
    public function getReportesPorUsuario($usuario_id) {
        return $this->findAll(['creado_por' => $usuario_id], 'created_at DESC');
    }
    
    public function getReportesRecientes($limite = 10) {
        return $this->findAll([], 'created_at DESC', $limite);
    }
    
    public function getReportesFavoritos($usuario_id) {
        $sql = "SELECT r.* FROM {$this->table} r
                INNER JOIN reportes_favoritos rf ON r.id = rf.reporte_id
                WHERE rf.usuario_id = :usuario_id
                ORDER BY rf.created_at DESC";
        return $this->db->fetchAll($sql, ['usuario_id' => $usuario_id]);
    }
    
    public function marcarComoFavorito($reporte_id, $usuario_id) {
        $sql = "INSERT INTO reportes_favoritos (reporte_id, usuario_id, created_at) 
                VALUES (:reporte_id, :usuario_id, :created_at)";
        return $this->db->execute($sql, [
            'reporte_id' => $reporte_id,
            'usuario_id' => $usuario_id,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function quitarDeFavoritos($reporte_id, $usuario_id) {
        $sql = "DELETE FROM reportes_favoritos 
                WHERE reporte_id = :reporte_id AND usuario_id = :usuario_id";
        return $this->db->execute($sql, [
            'reporte_id' => $reporte_id,
            'usuario_id' => $usuario_id
        ]);
    }
    
    public function getEstadisticasReportes() {
        $sql = "SELECT 
                    tipo,
                    COUNT(*) as total,
                    AVG(tiempo_generacion) as tiempo_promedio
                FROM {$this->table}
                GROUP BY tipo";
        return $this->db->fetchAll($sql);
    }
    
    public function buscarReportes($termino) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE nombre LIKE :termino OR descripcion LIKE :termino
                ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['termino' => "%{$termino}%"]);
    }
    
    public function actualizarEstadoReporte($id, $estado) {
        return $this->update($id, [
            'estado' => $estado,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }
}
?>