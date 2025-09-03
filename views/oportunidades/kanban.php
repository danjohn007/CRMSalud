<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-kanban"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>oportunidades" class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-list"></i> Vista Lista
        </a>
        <a href="<?php echo $baseUrl; ?>oportunidades/create" class="btn btn-sm btn-primary">
            <i class="bi bi-plus"></i> Nueva Oportunidad
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

<!-- Estadísticas del Pipeline -->
<div class="row mb-4">
    <?php foreach ($estadisticas as $stat): ?>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-<?php echo getEstadoColor($stat['estado']); ?>">
                        <?php echo $stat['cantidad']; ?>
                    </h5>
                    <p class="card-text small"><?php echo $estados[$stat['estado']]; ?></p>
                    <small class="text-muted">$<?php echo number_format($stat['valor_total'], 2); ?></small>
                    <br><small class="text-muted"><?php echo round($stat['probabilidad_promedio']); ?>% prob.</small>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Tablero Kanban -->
<div class="kanban-board">
    <div class="row">
        <?php foreach ($estados as $estado => $label): ?>
            <div class="col-lg-2 col-md-3 col-sm-6 mb-4">
                <div class="kanban-column">
                    <div class="kanban-header bg-<?php echo getEstadoColor($estado); ?> text-white p-3 rounded-top">
                        <h6 class="mb-1"><?php echo $label; ?></h6>
                        <small><?php echo count($oportunidadesPorEstado[$estado] ?? []); ?> oportunidades</small>
                    </div>
                    <div class="kanban-body bg-light p-2 rounded-bottom" style="min-height: 400px;">
                        <?php if (!empty($oportunidadesPorEstado[$estado])): ?>
                            <?php foreach ($oportunidadesPorEstado[$estado] as $oportunidad): ?>
                                <div class="kanban-card card mb-2" data-id="<?php echo $oportunidad['id']; ?>" data-estado="<?php echo $estado; ?>">
                                    <div class="card-body p-2">
                                        <h6 class="card-title small mb-1">
                                            <a href="<?php echo $baseUrl; ?>oportunidades/show/<?php echo $oportunidad['id']; ?>" 
                                               class="text-decoration-none">
                                                <?php echo htmlspecialchars($oportunidad['nombre']); ?>
                                            </a>
                                        </h6>
                                        <p class="card-text small text-muted mb-1">
                                            <i class="bi bi-building"></i>
                                            <?php echo htmlspecialchars($oportunidad['cliente_nombre']); ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-success">
                                                <i class="bi bi-currency-dollar"></i>
                                                <?php echo number_format($oportunidad['valor_estimado'], 0); ?>
                                            </small>
                                            <small class="text-primary">
                                                <?php echo $oportunidad['probabilidad']; ?>%
                                            </small>
                                        </div>
                                        <?php if ($oportunidad['fecha_cierre_estimada']): ?>
                                            <?php $fecha = new DateTime($oportunidad['fecha_cierre_estimada']); ?>
                                            <?php $hoy = new DateTime(); ?>
                                            <small class="text-<?php echo ($fecha < $hoy && !in_array($estado, ['ganado', 'perdido'])) ? 'danger' : 'muted'; ?>">
                                                <i class="bi bi-calendar"></i>
                                                <?php echo $fecha->format('d/m/Y'); ?>
                                            </small>
                                        <?php endif; ?>
                                        <div class="mt-2">
                                            <div class="btn-group btn-group-sm w-100">
                                                <a href="<?php echo $baseUrl; ?>oportunidades/show/<?php echo $oportunidad['id']; ?>" 
                                                   class="btn btn-outline-primary btn-sm" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?php echo $baseUrl; ?>oportunidades/edit/<?php echo $oportunidad['id']; ?>" 
                                                   class="btn btn-outline-warning btn-sm" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                            data-bs-toggle="dropdown" title="Mover">
                                                        <i class="bi bi-arrow-right"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php foreach ($estados as $nuevoEstado => $nuevoLabel): ?>
                                                            <?php if ($nuevoEstado !== $estado): ?>
                                                                <li>
                                                                    <a class="dropdown-item" href="#" 
                                                                       onclick="moverOportunidad(<?php echo $oportunidad['id']; ?>, '<?php echo $nuevoEstado; ?>')">
                                                                        <span class="badge bg-<?php echo getEstadoColor($nuevoEstado); ?> me-2"></span>
                                                                        <?php echo $nuevoLabel; ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted p-3">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="small mt-2">No hay oportunidades</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.kanban-board {
    overflow-x: auto;
}

.kanban-column {
    min-width: 250px;
}

.kanban-card {
    cursor: pointer;
    transition: all 0.2s ease;
}

.kanban-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.kanban-body {
    max-height: 500px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .kanban-column {
        min-width: 200px;
    }
}
</style>

<script>
function moverOportunidad(id, nuevoEstado) {
    if (confirm('¿Estás seguro de que deseas mover esta oportunidad?')) {
        // Crear un formulario temporal para enviar la actualización
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?php echo $baseUrl; ?>oportunidades/update/${id}`;
        
        const estadoInput = document.createElement('input');
        estadoInput.type = 'hidden';
        estadoInput.name = 'estado';
        estadoInput.value = nuevoEstado;
        
        form.appendChild(estadoInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
// Helper function for colors
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
?>