<?php
/**
 * Modelo de Calendario
 */

require_once 'core/BaseModel.php';

class Calendario extends BaseModel {
    protected $table = 'eventos_calendario';
    
    public function getEventosPorFecha($fecha) {
        return $this->findAll(['fecha' => $fecha], 'hora_inicio ASC');
    }
    
    public function getEventosPorRango($fecha_inicio, $fecha_fin) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY fecha ASC, hora_inicio ASC";
        return $this->db->fetchAll($sql, [
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ]);
    }
    
    public function getEventosPorUsuario($usuario_id) {
        return $this->findAll(['creado_por' => $usuario_id], 'fecha DESC, hora_inicio ASC');
    }
    
    public function getEventosPorTipo($tipo) {
        return $this->findAll(['tipo' => $tipo], 'fecha ASC, hora_inicio ASC');
    }
    
    public function getEventosHoy() {
        return $this->getEventosPorFecha(date('Y-m-d'));
    }
    
    public function getEventosProximos($dias = 7) {
        $fecha_inicio = date('Y-m-d');
        $fecha_fin = date('Y-m-d', strtotime("+{$dias} days"));
        return $this->getEventosPorRango($fecha_inicio, $fecha_fin);
    }
    
    public function getEventosPendientes($usuario_id = null) {
        $conditions = ['estado' => 'pendiente'];
        if ($usuario_id) {
            $conditions['creado_por'] = $usuario_id;
        }
        return $this->findAll($conditions, 'fecha ASC, hora_inicio ASC');
    }
    
    public function marcarComoCompletado($id) {
        return $this->update($id, [
            'estado' => 'completado',
            'fecha_completado' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function getConflictosHorarios($fecha, $hora_inicio, $hora_fin, $excluir_id = null) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha = :fecha 
                AND ((hora_inicio <= :hora_inicio AND hora_fin > :hora_inicio) 
                     OR (hora_inicio < :hora_fin AND hora_fin >= :hora_fin)
                     OR (hora_inicio >= :hora_inicio AND hora_fin <= :hora_fin))";
        
        $params = [
            'fecha' => $fecha,
            'hora_inicio' => $hora_inicio,
            'hora_fin' => $hora_fin
        ];
        
        if ($excluir_id) {
            $sql .= " AND id != :excluir_id";
            $params['excluir_id'] = $excluir_id;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getEstadisticasEventos($mes = null, $año = null) {
        $whereClause = "";
        $params = [];
        
        if ($mes && $año) {
            $whereClause = "WHERE MONTH(fecha) = :mes AND YEAR(fecha) = :año";
            $params = ['mes' => $mes, 'año' => $año];
        }
        
        $sql = "SELECT 
                    tipo,
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) as completados,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes
                FROM {$this->table} 
                {$whereClause}
                GROUP BY tipo";
        return $this->db->fetchAll($sql, $params);
    }
}
?>