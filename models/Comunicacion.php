<?php
/**
 * Modelo de Comunicación
 */

require_once 'core/BaseModel.php';

class Comunicacion extends BaseModel {
    protected $table = 'comunicaciones';
    
    public function getComunicacionesPorTipo($tipo) {
        return $this->findAll(['tipo' => $tipo], 'created_at DESC');
    }
    
    public function getComunicacionesPorCliente($cliente_id) {
        return $this->findAll(['cliente_id' => $cliente_id], 'created_at DESC');
    }
    
    public function getComunicacionesNoLeidas($usuario_id = null) {
        $conditions = ['leido' => 0];
        if ($usuario_id) {
            $conditions['destinatario_id'] = $usuario_id;
        }
        return $this->findAll($conditions, 'created_at DESC');
    }
    
    public function marcarComoLeido($id) {
        return $this->update($id, ['leido' => 1, 'fecha_lectura' => date('Y-m-d H:i:s')]);
    }
    
    public function getMensajesEnviados($usuario_id) {
        return $this->findAll(['remitente_id' => $usuario_id], 'created_at DESC');
    }
    
    public function getMensajesRecibidos($usuario_id) {
        return $this->findAll(['destinatario_id' => $usuario_id], 'created_at DESC');
    }
    
    public function getNotificacionesSistema($limite = 10) {
        return $this->findAll(['tipo' => 'sistema'], 'created_at DESC', $limite);
    }
    
    public function getEstadisticasComunicacion($usuario_id = null) {
        $whereClause = $usuario_id ? "WHERE remitente_id = :usuario_id OR destinatario_id = :usuario_id" : "";
        $params = $usuario_id ? ['usuario_id' => $usuario_id] : [];
        
        $sql = "SELECT 
                    tipo,
                    COUNT(*) as total,
                    SUM(CASE WHEN leido = 1 THEN 1 ELSE 0 END) as leidos,
                    SUM(CASE WHEN leido = 0 THEN 1 ELSE 0 END) as no_leidos
                FROM {$this->table} 
                {$whereClause}
                GROUP BY tipo";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function buscarComunicaciones($termino, $usuario_id = null) {
        $whereClause = "WHERE (asunto LIKE :termino OR mensaje LIKE :termino)";
        $params = ['termino' => "%{$termino}%"];
        
        if ($usuario_id) {
            $whereClause .= " AND (remitente_id = :usuario_id OR destinatario_id = :usuario_id)";
            $params['usuario_id'] = $usuario_id;
        }
        
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }
}
?>