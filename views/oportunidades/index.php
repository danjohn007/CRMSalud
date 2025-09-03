<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-target"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    <?php 
    $estadisticasPorEstado = [];
    foreach ($estadisticas as $stat) {
        $estadisticasPorEstado[$stat['estado']] = $stat;
    }
    ?>
    <?php foreach (OPORTUNIDAD_ESTADOS as $estado => $label): ?>
        <?php $stat = $estadisticasPorEstado[$estado] ?? ['total' => 0, 'valor_total' => 0]; ?>
        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-<?php echo getEstadoColor($estado); ?>"><?php echo $stat['total']; ?></h5>
                    <p class="card-text small"><?php echo $label; ?></p>
                    <small class="text-muted">$<?php echo number_format($stat['valor_total'] ?? 0, 2); ?></small>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <div class="col-md-4 col-xl-2 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <a href="<?php echo $baseUrl; ?>oportunidades/kanban" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-kanban"></i> Ver Pipeline
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos los estados</option>
                    <?php foreach (OPORTUNIDAD_ESTADOS as $estado => $label): ?>
                        <option value="<?php echo $estado; ?>" <?php echo ($filtroEstado === $estado) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select class="form-select" id="cliente_id" name="cliente_id">
                    <option value="">Todos los clientes</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>" <?php echo ($filtroCliente == $cliente['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cliente['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
                <a href="<?php echo $baseUrl; ?>oportunidades" class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Oportunidades -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lista de Oportunidades (<?php echo count($oportunidades); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($oportunidades)): ?>
                    <div class="text-center p-4">
                        <i class="bi bi-target" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h4 class="mt-3 text-muted">No hay oportunidades</h4>
                        <p class="text-muted">
                            <?php if ($filtroEstado || $filtroCliente): ?>
                                No se encontraron oportunidades con los filtros seleccionados.
                            <?php else: ?>
                                Comienza creando tu primera oportunidad de negocio.
                            <?php endif; ?>
                        </p>
                        <a href="<?php echo $baseUrl; ?>oportunidades/create" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Nueva Oportunidad
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Oportunidad</th>
                                    <th>Cliente</th>
                                    <th>Estado</th>
                                    <th>Valor Estimado</th>
                                    <th>Probabilidad</th>
                                    <th>Fecha Cierre</th>
                                    <th>Responsable</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($oportunidades as $oportunidad): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($oportunidad['nombre']); ?></strong>
                                            <?php if ($oportunidad['descripcion']): ?>
                                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($oportunidad['descripcion'], 0, 60)); ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo getClienteTypeColor($oportunidad['cliente_tipo']); ?>">
                                                <?php echo ucfirst($oportunidad['cliente_tipo']); ?>
                                            </span><br>
                                            <?php echo htmlspecialchars($oportunidad['cliente_nombre']); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo getEstadoColor($oportunidad['estado']); ?>">
                                                <?php echo OPORTUNIDAD_ESTADOS[$oportunidad['estado']]; ?>
                                            </span>
                                        </td>
                                        <td>$<?php echo number_format($oportunidad['valor_estimado'], 2); ?></td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: <?php echo $oportunidad['probabilidad']; ?>%">
                                                </div>
                                            </div>
                                            <small><?php echo $oportunidad['probabilidad']; ?>%</small>
                                        </td>
                                        <td>
                                            <?php if ($oportunidad['fecha_cierre_estimada']): ?>
                                                <?php $fecha = new DateTime($oportunidad['fecha_cierre_estimada']); ?>
                                                <?php $hoy = new DateTime(); ?>
                                                <span class="<?php echo ($fecha < $hoy && !in_array($oportunidad['estado'], ['ganado', 'perdido'])) ? 'text-danger' : ''; ?>">
                                                    <?php echo $fecha->format('d/m/Y'); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Sin fecha</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($oportunidad['usuario_nombre']); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo $baseUrl; ?>oportunidades/show/<?php echo $oportunidad['id']; ?>" 
                                                   class="btn btn-outline-primary btn-sm" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?php echo $baseUrl; ?>oportunidades/edit/<?php echo $oportunidad['id']; ?>" 
                                                   class="btn btn-outline-warning btn-sm" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button onclick="eliminarOportunidad(<?php echo $oportunidad['id']; ?>)" 
                                                        class="btn btn-outline-danger btn-sm" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
                location.reload();
            } else {
                alert('Error: ' + data.message);
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
// Helper functions for colors
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
?>