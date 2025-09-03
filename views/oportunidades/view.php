<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-eye"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>oportunidades/edit/<?php echo $oportunidad['id']; ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarOportunidad(<?php echo $oportunidad['id']; ?>)">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
        <a href="<?php echo $baseUrl; ?>oportunidades" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- Flash Messages -->
<?php if (!empty($flashMessages)): ?>
    <?php foreach ($flashMessages as $flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($flash['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Detalle de Oportunidad -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de la Oportunidad</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> <span><?php echo htmlspecialchars($oportunidad['nombre']); ?></span></p>
                        <p><strong>Cliente:</strong> 
                            <span class="badge bg-<?php echo getClienteTypeColor($oportunidad['cliente_tipo']); ?>">
                                <?php echo ucfirst($oportunidad['cliente_tipo']); ?>
                            </span>
                            <?php echo htmlspecialchars($oportunidad['cliente_nombre']); ?>
                        </p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-<?php echo getEstadoColor($oportunidad['estado']); ?>">
                                <?php echo OPORTUNIDAD_ESTADOS[$oportunidad['estado']]; ?>
                            </span>
                        </p>
                        <p><strong>Responsable:</strong> <?php echo htmlspecialchars($oportunidad['usuario_nombre']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Valor Estimado:</strong> $<?php echo number_format($oportunidad['valor_estimado'], 2); ?></p>
                        <p><strong>Probabilidad:</strong> 
                            <div class="progress d-inline-block" style="width: 100px; height: 15px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?php echo $oportunidad['probabilidad']; ?>%">
                                </div>
                            </div>
                            <?php echo $oportunidad['probabilidad']; ?>%
                        </p>
                        <p><strong>Fecha Cierre Estimada:</strong> 
                            <?php if ($oportunidad['fecha_cierre_estimada']): ?>
                                <?php $fecha = new DateTime($oportunidad['fecha_cierre_estimada']); ?>
                                <?php $hoy = new DateTime(); ?>
                                <span class="<?php echo ($fecha < $hoy && !in_array($oportunidad['estado'], ['ganado', 'perdido'])) ? 'text-danger' : ''; ?>">
                                    <?php echo $fecha->format('d/m/Y'); ?>
                                    <?php if ($fecha < $hoy && !in_array($oportunidad['estado'], ['ganado', 'perdido'])): ?>
                                        <i class="bi bi-exclamation-triangle text-danger"></i>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Sin fecha establecida</span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Creada:</strong> 
                            <?php $fechaCreacion = new DateTime($oportunidad['created_at']); ?>
                            <?php echo $fechaCreacion->format('d/m/Y H:i'); ?>
                        </p>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Descripción:</strong>
                    <p class="text-muted">
                        <?php echo $oportunidad['descripcion'] ? htmlspecialchars($oportunidad['descripcion']) : 'Sin descripción disponible.'; ?>
                    </p>
                </div>
                
                <?php if ($oportunidad['motivo_perdida']): ?>
                    <div class="mb-3">
                        <strong>Motivo de Pérdida:</strong>
                        <p class="text-danger"><?php echo htmlspecialchars($oportunidad['motivo_perdida']); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Comunicaciones Recientes</h5>
                <small class="text-muted"><?php echo count($comunicaciones); ?> registros</small>
            </div>
            <div class="card-body">
                <?php if (empty($comunicaciones)): ?>
                    <div class="text-center text-muted p-3">
                        <i class="bi bi-chat-dots" style="font-size: 2rem;"></i>
                        <p class="mt-2">Sin comunicaciones registradas</p>
                    </div>
                <?php else: ?>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <?php foreach (array_slice($comunicaciones, 0, 5) as $comunicacion): ?>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">
                                        <i class="bi bi-<?php echo getTipoComunicacionIcon($comunicacion['tipo']); ?>"></i>
                                        <?php echo ucfirst($comunicacion['tipo']); ?>
                                    </small>
                                    <small class="text-muted">
                                        <?php $fechaComunicacion = new DateTime($comunicacion['fecha_comunicacion']); ?>
                                        <?php echo $fechaComunicacion->format('d/m/Y H:i'); ?>
                                    </small>
                                </div>
                                <p class="small mb-1"><?php echo htmlspecialchars($comunicacion['asunto'] ?? 'Sin asunto'); ?></p>
                                <?php if ($comunicacion['resultado']): ?>
                                    <span class="badge bg-<?php echo getResultadoColor($comunicacion['resultado']); ?> small">
                                        <?php echo ucfirst(str_replace('_', ' ', $comunicacion['resultado'])); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Actividades</h5>
                <small class="text-muted"><?php echo count($actividades); ?> registros</small>
            </div>
            <div class="card-body">
                <?php if (empty($actividades)): ?>
                    <div class="text-center text-muted p-3">
                        <i class="bi bi-calendar-event" style="font-size: 2rem;"></i>
                        <p class="mt-2">Sin actividades programadas</p>
                    </div>
                <?php else: ?>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <?php foreach (array_slice($actividades, 0, 3) as $actividad): ?>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">
                                        <i class="bi bi-<?php echo getTipoActividadIcon($actividad['tipo']); ?>"></i>
                                        <?php echo ucfirst($actividad['tipo']); ?>
                                    </small>
                                    <small class="text-muted">
                                        <?php $fechaActividad = new DateTime($actividad['fecha_inicio']); ?>
                                        <?php echo $fechaActividad->format('d/m/Y H:i'); ?>
                                    </small>
                                </div>
                                <p class="small mb-1"><?php echo htmlspecialchars($actividad['titulo']); ?></p>
                                <?php if ($actividad['completada']): ?>
                                    <span class="badge bg-success small">Completada</span>
                                <?php else: ?>
                                    <span class="badge bg-warning small">Pendiente</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-telephone"></i> Programar Llamada
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-envelope"></i> Enviar Email
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-file-earmark-text"></i> Crear Cotización
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarOportunidad(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta oportunidad?')) {
        fetch(`<?php echo $baseUrl; ?>oportunidades/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Oportunidad eliminada correctamente');
                window.location.href = '<?php echo $baseUrl; ?>oportunidades';
            } else {
                alert('Error al eliminar la oportunidad');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la oportunidad');
        });
    }
}
</script>

<?php
// Helper functions
function getEstadoColor($estado) {
    $colors = [
        'prospecto' => 'secondary',
        'contactado' => 'info',
        'calificado' => 'primary',
        'propuesta' => 'warning',
        'negociacion' => 'orange',
        'ganado' => 'success',
        'perdido' => 'danger'
    ];
    return $colors[$estado] ?? 'secondary';
}

function getClienteTypeColor($tipo) {
    $colors = [
        'doctor' => 'primary',
        'farmacia' => 'success',
        'hospital' => 'info'
    ];
    return $colors[$tipo] ?? 'secondary';
}

function getTipoComunicacionIcon($tipo) {
    $icons = [
        'email' => 'envelope',
        'whatsapp' => 'whatsapp',
        'sms' => 'chat-text',
        'llamada' => 'telephone',
        'visita' => 'person-check',
        'reunion' => 'people'
    ];
    return $icons[$tipo] ?? 'chat-dots';
}

function getResultadoColor($resultado) {
    $colors = [
        'exitoso' => 'success',
        'sin_respuesta' => 'warning',
        'ocupado' => 'info',
        'reagendar' => 'primary',
        'no_interesado' => 'danger'
    ];
    return $colors[$resultado] ?? 'secondary';
}

function getTipoActividadIcon($tipo) {
    $icons = [
        'visita' => 'person-check',
        'reunion' => 'people',
        'llamada' => 'telephone',
        'tarea' => 'check-square',
        'evento' => 'calendar-event'
    ];
    return $icons[$tipo] ?? 'calendar';
}
?>