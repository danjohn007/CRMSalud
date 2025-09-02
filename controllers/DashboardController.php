<?php
/**
 * Controlador del Dashboard principal
 */

require_once 'core/BaseController.php';

class DashboardController extends BaseController {
    
    public function index() {
        // Obtener estadísticas del dashboard
        $stats = $this->getDashboardStats();
        
        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'flashMessages' => $this->getFlashMessages()
        ]);
    }
    
    private function getDashboardStats() {
        $stats = [];
        
        // Total de clientes
        $stats['total_clientes'] = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM clientes WHERE activo = 1"
        )['total'] ?? 0;
        
        // Total de productos
        $stats['total_productos'] = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM productos WHERE activo = 1"
        )['total'] ?? 0;
        
        // Oportunidades abiertas
        $stats['oportunidades_abiertas'] = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM oportunidades WHERE estado NOT IN ('ganado', 'perdido')"
        )['total'] ?? 0;
        
        // Ventas del mes
        $stats['ventas_mes'] = $this->db->fetchOne(
            "SELECT COALESCE(SUM(total), 0) as total FROM pedidos 
             WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
             AND YEAR(created_at) = YEAR(CURRENT_DATE())
             AND estado != 'cancelado'"
        )['total'] ?? 0;
        
        // Productos con stock bajo
        $stats['productos_stock_bajo'] = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM inventarios i
             INNER JOIN productos p ON i.producto_id = p.id
             WHERE i.stock_actual <= p.stock_minimo"
        )['total'] ?? 0;
        
        // Productos próximos a vencer (30 días)
        $stats['productos_por_vencer'] = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM inventarios 
             WHERE fecha_vencimiento <= DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)
             AND fecha_vencimiento >= CURRENT_DATE()"
        )['total'] ?? 0;
        
        return $stats;
    }
}
?>