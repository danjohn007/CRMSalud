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
        return $this->findAll(['estado' => 'nuevo'], 'created_at ASC');
    }
    
    public function getPedidosEnProceso() {
        return $this->findAll(['estado' => 'confirmado'], 'created_at ASC');
    }
    
    public function getPedidosCompletados($limite = null) {
        return $this->findAll(['estado' => 'entregado'], 'created_at DESC', $limite);
    }
    
    public function getEstadisticasVentas($fecha_inicio, $fecha_fin) {
        $sql = "SELECT COUNT(*) as total_pedidos, SUM(total) as valor_total
                FROM {$this->table}
                WHERE created_at BETWEEN :fecha_inicio AND :fecha_fin
                AND estado = 'entregado'";
        return $this->db->fetchOne($sql, [
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ]);
    }
    
    public function getPedidosUrgentes() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha_entrega_estimada <= DATE_ADD(CURDATE(), INTERVAL 2 DAY)
                AND estado IN ('nuevo', 'confirmado', 'preparando')
                ORDER BY fecha_entrega_estimada ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getPedidosConRelaciones($conditions = [], $orderBy = 'p.created_at DESC', $limit = null) {
        $sql = "SELECT p.*, 
                       cl.nombre as cliente_nombre, cl.tipo as cliente_tipo, cl.email as cliente_email,
                       u.nombre as usuario_nombre, u.email as usuario_email,
                       c.numero as cotizacion_numero
                FROM {$this->table} p
                LEFT JOIN clientes cl ON p.cliente_id = cl.id
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                LEFT JOIN cotizaciones c ON p.cotizacion_id = c.id";
        
        $params = [];
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "p.{$column} = :{$column}";
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
    
    public function getPedidoConRelaciones($id) {
        $sql = "SELECT p.*, 
                       cl.nombre as cliente_nombre, cl.tipo as cliente_tipo, cl.email as cliente_email,
                       cl.telefono as cliente_telefono, cl.direccion as cliente_direccion,
                       u.nombre as usuario_nombre, u.email as usuario_email,
                       c.numero as cotizacion_numero
                FROM {$this->table} p
                LEFT JOIN clientes cl ON p.cliente_id = cl.id
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                LEFT JOIN cotizaciones c ON p.cotizacion_id = c.id
                WHERE p.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function getDetallesPedido($pedido_id) {
        $sql = "SELECT pd.*, p.nombre as producto_nombre, p.sku, p.descripcion as producto_descripcion,
                       p.precio_publico, i.lote, i.fecha_vencimiento
                FROM pedido_detalles pd
                INNER JOIN productos p ON pd.producto_id = p.id
                LEFT JOIN inventarios i ON pd.inventario_id = i.id
                WHERE pd.pedido_id = :pedido_id
                ORDER BY pd.id ASC";
        return $this->db->fetchAll($sql, ['pedido_id' => $pedido_id]);
    }
    
    public function getEstadisticasPorEstado() {
        $sql = "SELECT estado, COUNT(*) as total, SUM(total) as valor_total
                FROM {$this->table}
                GROUP BY estado";
        return $this->db->fetchAll($sql);
    }
    
    public function generarNumero() {
        $año = date('Y');
        $sql = "SELECT COUNT(*) + 1 as siguiente FROM {$this->table} WHERE YEAR(created_at) = :año";
        $resultado = $this->db->fetchOne($sql, ['año' => $año]);
        return 'PED-' . $año . '-' . str_pad($resultado['siguiente'], 4, '0', STR_PAD_LEFT);
    }
    
    public function procesarProductos($pedido_id, $productos) {
        // Eliminar detalles existentes
        $this->db->delete('pedido_detalles', 'pedido_id = :pedido_id', ['pedido_id' => $pedido_id]);
        
        // Insertar nuevos detalles
        foreach ($productos as $producto) {
            if (empty($producto['producto_id']) || empty($producto['cantidad'])) {
                continue;
            }
            
            $precio_unitario = $producto['precio_unitario'] ?? 0;
            $cantidad = $producto['cantidad'];
            $descuento_porcentaje = $producto['descuento_porcentaje'] ?? 0;
            $descuento_importe = $producto['descuento_importe'] ?? 0;
            
            // Calcular subtotal
            $subtotal_bruto = $precio_unitario * $cantidad;
            $descuento_total = $descuento_importe + ($subtotal_bruto * $descuento_porcentaje / 100);
            $subtotal = $subtotal_bruto - $descuento_total;
            
            $detalle = [
                'pedido_id' => $pedido_id,
                'producto_id' => $producto['producto_id'],
                'inventario_id' => $producto['inventario_id'] ?? null,
                'cantidad' => $cantidad,
                'cantidad_entregada' => 0,
                'precio_unitario' => $precio_unitario,
                'descuento_porcentaje' => $descuento_porcentaje,
                'descuento_importe' => $descuento_importe,
                'subtotal' => $subtotal
            ];
            
            $this->db->insert('pedido_detalles', $detalle);
        }
    }
    
    public function actualizarProductos($pedido_id, $productos) {
        $this->procesarProductos($pedido_id, $productos);
    }
    
    public function calcularTotales($pedido_id) {
        // Calcular subtotal desde los detalles
        $sql = "SELECT SUM(subtotal) as subtotal FROM pedido_detalles WHERE pedido_id = :pedido_id";
        $resultado = $this->db->fetchOne($sql, ['pedido_id' => $pedido_id]);
        $subtotal = $resultado['subtotal'] ?? 0;
        
        // Obtener descuentos e impuestos del pedido principal
        $pedido = $this->find($pedido_id);
        $descuento_porcentaje = $pedido['descuento_porcentaje'] ?? 0;
        $descuento_importe = $pedido['descuento_importe'] ?? 0;
        $impuestos = $pedido['impuestos'] ?? 0;
        
        // Aplicar descuentos generales
        $descuento_total = $descuento_importe + ($subtotal * $descuento_porcentaje / 100);
        $subtotal_con_descuento = $subtotal - $descuento_total;
        
        // Calcular total final
        $total = $subtotal_con_descuento + $impuestos;
        
        // Actualizar el pedido
        $this->update($pedido_id, [
            'subtotal' => $subtotal,
            'total' => $total
        ]);
        
        return $total;
    }
    
    public function marcarProductoEntregado($pedido_id, $producto_id, $cantidad_entregada) {
        $sql = "UPDATE pedido_detalles 
                SET cantidad_entregada = cantidad_entregada + :cantidad
                WHERE pedido_id = :pedido_id AND producto_id = :producto_id";
        
        $this->db->execute($sql, [
            'cantidad' => $cantidad_entregada,
            'pedido_id' => $pedido_id,
            'producto_id' => $producto_id
        ]);
        
        // Verificar si el pedido está completamente entregado
        $this->verificarEntregaCompleta($pedido_id);
    }
    
    public function verificarEntregaCompleta($pedido_id) {
        $sql = "SELECT COUNT(*) as total,
                       SUM(CASE WHEN cantidad_entregada >= cantidad THEN 1 ELSE 0 END) as entregados
                FROM pedido_detalles 
                WHERE pedido_id = :pedido_id";
        
        $resultado = $this->db->fetchOne($sql, ['pedido_id' => $pedido_id]);
        
        if ($resultado['total'] > 0 && $resultado['total'] == $resultado['entregados']) {
            $this->update($pedido_id, [
                'estado' => 'entregado',
                'fecha_entrega_real' => date('Y-m-d')
            ]);
            return true;
        }
        
        return false;
    }
    
    public function generarOrdenCompra($pedido_id) {
        $pedido = $this->getPedidoConRelaciones($pedido_id);
        $detalles = $this->getDetallesPedido($pedido_id);
        
        if (!$pedido || empty($detalles)) {
            throw new Exception('Pedido no válido para generar orden de compra');
        }
        
        // Generar número de orden de compra
        $año = date('Y');
        $sql = "SELECT COUNT(*) + 1 as siguiente FROM ordenes_compra WHERE YEAR(created_at) = :año";
        $resultado = $this->db->fetchOne($sql, ['año' => $año]);
        $numero_oc = 'OC-' . $año . '-' . str_pad($resultado['siguiente'], 4, '0', STR_PAD_LEFT);
        
        // Crear orden de compra
        $orden_data = [
            'numero' => $numero_oc,
            'pedido_id' => $pedido_id,
            'proveedor_id' => null, // Se debe asignar según el producto
            'fecha_orden' => date('Y-m-d'),
            'fecha_entrega_solicitada' => $pedido['fecha_entrega_estimada'],
            'estado' => 'pendiente',
            'total' => $pedido['total'],
            'usuario_id' => $pedido['usuario_id']
        ];
        
        // En un entorno real, aquí se insertaría en la tabla ordenes_compra
        // Por ahora solo retornamos los datos
        
        return $orden_data;
    }
    
    public function generarPDF($pedido, $detalles) {
        // Crear PDF básico (en un entorno real usarías TCPDF o similar)
        $pdf_content = "
        PEDIDO: {$pedido['numero']}
        
        Cliente: {$pedido['cliente_nombre']}
        Email: {$pedido['cliente_email']}
        Teléfono: {$pedido['cliente_telefono']}
        Dirección: {$pedido['cliente_direccion']}
        
        Fecha del Pedido: {$pedido['fecha_pedido']}
        Fecha de Entrega Estimada: {$pedido['fecha_entrega_estimada']}
        Estado: " . strtoupper($pedido['estado']) . "
        
        PRODUCTOS:
        ";
        
        foreach ($detalles as $detalle) {
            $pdf_content .= "
            - {$detalle['producto_nombre']} | SKU: {$detalle['sku']}
              Cantidad: {$detalle['cantidad']} | Precio: $" . number_format($detalle['precio_unitario'], 2) . "
              Entregado: {$detalle['cantidad_entregada']} | Pendiente: " . ($detalle['cantidad'] - $detalle['cantidad_entregada']) . "
              Subtotal: $" . number_format($detalle['subtotal'], 2) . "
            ";
        }
        
        $pdf_content .= "
        
        SUBTOTAL: $" . number_format($pedido['subtotal'], 2) . "
        DESCUENTO: $" . number_format($pedido['descuento_importe'], 2) . "
        IMPUESTOS: $" . number_format($pedido['impuestos'], 2) . "
        TOTAL: $" . number_format($pedido['total'], 2) . "
        
        Forma de Pago: " . strtoupper($pedido['forma_pago']) . "
        ";
        
        return $pdf_content;
    }
    
    public function enviarNotificacion($pedido_id, $tipo, $destinatario = null) {
        $pedido = $this->getPedidoConRelaciones($pedido_id);
        
        if (!$pedido) {
            return false;
        }
        
        $email = $destinatario ?? $pedido['cliente_email'];
        
        switch ($tipo) {
            case 'confirmacion':
                $subject = "Confirmación de Pedido {$pedido['numero']} - CRM Salud";
                $message = "Su pedido ha sido confirmado y está siendo procesado.";
                break;
            case 'envio':
                $subject = "Pedido {$pedido['numero']} Enviado - CRM Salud";
                $message = "Su pedido ha sido enviado y llegará en la fecha estimada.";
                break;
            case 'entrega':
                $subject = "Pedido {$pedido['numero']} Entregado - CRM Salud";
                $message = "Su pedido ha sido entregado exitosamente.";
                break;
            default:
                $subject = "Actualización del Pedido {$pedido['numero']} - CRM Salud";
                $message = "Su pedido ha sido actualizado.";
        }
        
        // En un entorno real, aquí usarías PHPMailer o similar
        // Por ahora solo simulamos el envío
        
        return true;
    }
    
    public function cambiarEstado($pedido_id, $nuevo_estado) {
        // Validar que el estado sea válido
        $estados_validos = ['nuevo', 'confirmado', 'preparando', 'enviado', 'entregado', 'cancelado'];
        if (!in_array($nuevo_estado, $estados_validos)) {
            throw new Exception('Estado no válido');
        }
        
        $pedido = $this->find($pedido_id);
        if (!$pedido) {
            throw new Exception('Pedido no encontrado');
        }
        
        $estado_actual = $pedido['estado'];
        
        // Validar transiciones de estado válidas
        $transiciones_validas = [
            'nuevo' => ['confirmado', 'cancelado'],
            'confirmado' => ['preparando', 'cancelado'],
            'preparando' => ['enviado', 'cancelado'],
            'enviado' => ['entregado'],
            'entregado' => [], // Estado final
            'cancelado' => [] // Estado final
        ];
        
        if (!in_array($nuevo_estado, $transiciones_validas[$estado_actual]) && $estado_actual !== $nuevo_estado) {
            throw new Exception("No se puede cambiar de estado '$estado_actual' a '$nuevo_estado'");
        }
        
        // Actualizar estado
        $data = ['estado' => $nuevo_estado];
        
        // Agregar fechas automáticas según el estado
        if ($nuevo_estado === 'entregado') {
            $data['fecha_entrega_real'] = date('Y-m-d');
        }
        
        // Actualizar el pedido
        $resultado = $this->update($pedido_id, $data);
        
        // Enviar notificación automática
        if ($resultado) {
            try {
                $this->enviarNotificacion($pedido_id, $nuevo_estado);
            } catch (Exception $e) {
                // Log error but don't fail the state change
                error_log("Error enviando notificación para pedido $pedido_id: " . $e->getMessage());
            }
            
            // Si se marca como entregado, verificar entrega completa
            if ($nuevo_estado === 'entregado') {
                $this->verificarEntregaCompleta($pedido_id);
            }
        }
        
        return $resultado;
    }
}
?>