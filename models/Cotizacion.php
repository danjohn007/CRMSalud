<?php
/**
 * Modelo de Cotizaciones
 */

require_once 'core/BaseModel.php';

class Cotizacion extends BaseModel {
    protected $table = 'cotizaciones';
    
    public function getCotizacionesPorEstado($estado) {
        return $this->findAll(['estado' => $estado], 'created_at DESC');
    }
    
    public function getCotizacionesPorCliente($cliente_id) {
        return $this->findAll(['cliente_id' => $cliente_id], 'created_at DESC');
    }
    
    public function getCotizacionesVigentes() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha_vencimiento >= CURDATE() 
                AND estado = 'enviada'
                ORDER BY fecha_vencimiento ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getCotizacionesVencidas() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE fecha_vencimiento < CURDATE() 
                AND estado = 'enviada'
                ORDER BY fecha_vencimiento ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function getEstadisticasMensuales($mes, $año) {
        $sql = "SELECT COUNT(*) as total, SUM(total) as valor_total
                FROM {$this->table}
                WHERE MONTH(created_at) = :mes AND YEAR(created_at) = :año";
        return $this->db->fetchOne($sql, ['mes' => $mes, 'año' => $año]);
    }
    
    public function getCotizacionesConRelaciones($conditions = [], $orderBy = 'c.created_at DESC', $limit = null) {
        $sql = "SELECT c.*, 
                       cl.nombre as cliente_nombre, cl.tipo as cliente_tipo, cl.email as cliente_email,
                       u.nombre as usuario_nombre, u.email as usuario_email,
                       o.nombre as oportunidad_nombre
                FROM {$this->table} c
                LEFT JOIN clientes cl ON c.cliente_id = cl.id
                LEFT JOIN usuarios u ON c.usuario_id = u.id
                LEFT JOIN oportunidades o ON c.oportunidad_id = o.id";
        
        $params = [];
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "c.{$column} = :{$column}";
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
    
    public function getCotizacionConRelaciones($id) {
        $sql = "SELECT c.*, 
                       cl.nombre as cliente_nombre, cl.tipo as cliente_tipo, cl.email as cliente_email,
                       cl.telefono as cliente_telefono, cl.direccion as cliente_direccion,
                       u.nombre as usuario_nombre, u.email as usuario_email,
                       o.nombre as oportunidad_nombre
                FROM {$this->table} c
                LEFT JOIN clientes cl ON c.cliente_id = cl.id
                LEFT JOIN usuarios u ON c.usuario_id = u.id
                LEFT JOIN oportunidades o ON c.oportunidad_id = o.id
                WHERE c.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function getDetallesCotizacion($cotizacion_id) {
        $sql = "SELECT cd.*, p.nombre as producto_nombre, p.sku, p.descripcion as producto_descripcion,
                       p.precio_publico
                FROM cotizacion_detalles cd
                INNER JOIN productos p ON cd.producto_id = p.id
                WHERE cd.cotizacion_id = :cotizacion_id
                ORDER BY cd.id ASC";
        return $this->db->fetchAll($sql, ['cotizacion_id' => $cotizacion_id]);
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
        return 'COT-' . $año . '-' . str_pad($resultado['siguiente'], 4, '0', STR_PAD_LEFT);
    }
    
    public function procesarProductos($cotizacion_id, $productos) {
        // Eliminar detalles existentes
        $this->db->delete('cotizacion_detalles', 'cotizacion_id = :cotizacion_id', ['cotizacion_id' => $cotizacion_id]);
        
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
                'cotizacion_id' => $cotizacion_id,
                'producto_id' => $producto['producto_id'],
                'cantidad' => $cantidad,
                'precio_unitario' => $precio_unitario,
                'descuento_porcentaje' => $descuento_porcentaje,
                'descuento_importe' => $descuento_importe,
                'subtotal' => $subtotal
            ];
            
            $this->db->insert('cotizacion_detalles', $detalle);
        }
    }
    
    public function actualizarProductos($cotizacion_id, $productos) {
        $this->procesarProductos($cotizacion_id, $productos);
    }
    
    public function calcularTotales($cotizacion_id) {
        // Calcular subtotal desde los detalles
        $sql = "SELECT SUM(subtotal) as subtotal FROM cotizacion_detalles WHERE cotizacion_id = :cotizacion_id";
        $resultado = $this->db->fetchOne($sql, ['cotizacion_id' => $cotizacion_id]);
        $subtotal = $resultado['subtotal'] ?? 0;
        
        // Obtener descuentos e impuestos de la cotización principal
        $cotizacion = $this->find($cotizacion_id);
        $descuento_porcentaje = $cotizacion['descuento_porcentaje'] ?? 0;
        $descuento_importe = $cotizacion['descuento_importe'] ?? 0;
        $impuestos = $cotizacion['impuestos'] ?? 0;
        
        // Aplicar descuentos generales
        $descuento_total = $descuento_importe + ($subtotal * $descuento_porcentaje / 100);
        $subtotal_con_descuento = $subtotal - $descuento_total;
        
        // Calcular total final
        $total = $subtotal_con_descuento + $impuestos;
        
        // Actualizar la cotización
        $this->update($cotizacion_id, [
            'subtotal' => $subtotal,
            'total' => $total
        ]);
        
        return $total;
    }
    
    public function generarPedido($cotizacion_id) {
        $cotizacion = $this->find($cotizacion_id);
        $detalles = $this->getDetallesCotizacion($cotizacion_id);
        
        if (!$cotizacion || empty($detalles)) {
            throw new Exception('Cotización no válida para generar pedido');
        }
        
        // Crear pedido
        $pedidoModel = new Pedido();
        $pedido_data = [
            'numero' => $pedidoModel->generarNumero(),
            'cotizacion_id' => $cotizacion_id,
            'cliente_id' => $cotizacion['cliente_id'],
            'usuario_id' => $cotizacion['usuario_id'],
            'fecha_pedido' => date('Y-m-d'),
            'fecha_entrega_estimada' => date('Y-m-d', strtotime('+7 days')),
            'subtotal' => $cotizacion['subtotal'],
            'descuento_porcentaje' => $cotizacion['descuento_porcentaje'],
            'descuento_importe' => $cotizacion['descuento_importe'],
            'impuestos' => $cotizacion['impuestos'],
            'total' => $cotizacion['total'],
            'estado' => 'nuevo'
        ];
        
        $pedido_id = $pedidoModel->create($pedido_data);
        
        // Copiar detalles
        foreach ($detalles as $detalle) {
            $pedido_detalle = [
                'pedido_id' => $pedido_id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'descuento_porcentaje' => $detalle['descuento_porcentaje'],
                'descuento_importe' => $detalle['descuento_importe'],
                'subtotal' => $detalle['subtotal']
            ];
            
            $this->db->insert('pedido_detalles', $pedido_detalle);
        }
        
        return $pedido_id;
    }
    
    public function generarPDF($cotizacion, $detalles) {
        // Crear PDF básico (en un entorno real usarías TCPDF o similar)
        $pdf_content = "
        COTIZACIÓN: {$cotizacion['numero']}
        
        Cliente: {$cotizacion['cliente_nombre']}
        Email: {$cotizacion['cliente_email']}
        Fecha: {$cotizacion['fecha_cotizacion']}
        Válida hasta: {$cotizacion['fecha_vencimiento']}
        
        PRODUCTOS:
        ";
        
        foreach ($detalles as $detalle) {
            $pdf_content .= "
            - {$detalle['producto_nombre']} | SKU: {$detalle['sku']}
              Cantidad: {$detalle['cantidad']} | Precio: $" . number_format($detalle['precio_unitario'], 2) . "
              Subtotal: $" . number_format($detalle['subtotal'], 2) . "
            ";
        }
        
        $pdf_content .= "
        
        SUBTOTAL: $" . number_format($cotizacion['subtotal'], 2) . "
        DESCUENTO: $" . number_format($cotizacion['descuento_importe'], 2) . "
        IMPUESTOS: $" . number_format($cotizacion['impuestos'], 2) . "
        TOTAL: $" . number_format($cotizacion['total'], 2) . "
        
        Notas: {$cotizacion['notas']}
        ";
        
        return $pdf_content;
    }
    
    public function enviarPorEmail($cotizacion_id, $email) {
        $cotizacion = $this->getCotizacionConRelaciones($cotizacion_id);
        $detalles = $this->getDetallesCotizacion($cotizacion_id);
        
        if (!$cotizacion) {
            return false;
        }
        
        // Generar PDF
        $pdf_content = $this->generarPDF($cotizacion, $detalles);
        
        // En un entorno real, aquí usarías PHPMailer o similar
        // Por ahora solo simulamos el envío
        
        $subject = "Cotización {$cotizacion['numero']} - CRM Salud";
        $message = "Estimado cliente,\n\nAdjuntamos la cotización solicitada.\n\nSaludos cordiales.";
        
        // Simular envío exitoso
        return true;
    }
    
    public function cambiarEstado($cotizacion_id, $nuevo_estado) {
        // Validar que el estado sea válido
        $estados_validos = ['borrador', 'enviada', 'aceptada', 'rechazada', 'vencida'];
        if (!in_array($nuevo_estado, $estados_validos)) {
            throw new Exception('Estado no válido');
        }
        
        $cotizacion = $this->find($cotizacion_id);
        if (!$cotizacion) {
            throw new Exception('Cotización no encontrada');
        }
        
        $estado_actual = $cotizacion['estado'];
        
        // Validar transiciones de estado válidas
        $transiciones_validas = [
            'borrador' => ['enviada', 'vencida'],
            'enviada' => ['aceptada', 'rechazada', 'vencida'],
            'aceptada' => [], // Estado final
            'rechazada' => [], // Estado final  
            'vencida' => [] // Estado final
        ];
        
        if (!in_array($nuevo_estado, $transiciones_validas[$estado_actual]) && $estado_actual !== $nuevo_estado) {
            throw new Exception("No se puede cambiar de estado '$estado_actual' a '$nuevo_estado'");
        }
        
        // Actualizar estado
        $data = ['estado' => $nuevo_estado];
        
        // Si se acepta la cotización, generar pedido automáticamente
        if ($nuevo_estado === 'aceptada') {
            try {
                $pedido_id = $this->generarPedido($cotizacion_id);
                $data['pedido_generado'] = $pedido_id;
            } catch (Exception $e) {
                // Log error but don't fail the state change
                error_log("Error generando pedido para cotización $cotizacion_id: " . $e->getMessage());
            }
        }
        
        return $this->update($cotizacion_id, $data);
    }
}
?>