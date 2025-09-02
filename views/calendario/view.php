<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-eye"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>calendario/edit/<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarEvento(<?php echo $id; ?>)">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
        <a href="<?php echo $baseUrl; ?>calendario" class="btn btn-sm btn-outline-secondary">
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

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Reunión con Cliente - Seguimiento</h5>
                <span class="badge bg-warning">Pendiente</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Tipo:</strong> <i class="bi bi-people"></i> Reunión</p>
                        <p><strong>Cliente:</strong> Cliente de ejemplo</p>
                        <p><strong>Prioridad:</strong> <span class="badge bg-info">Media</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> <?php echo date('d/m/Y'); ?></p>
                        <p><strong>Hora:</strong> 10:00 - 11:00</p>
                        <p><strong>Estado:</strong> <span class="badge bg-warning">Pendiente</span></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Descripción:</strong>
                    <p class="text-muted">Reunión de seguimiento para revisar el progreso del tratamiento y discutir próximos pasos. Revisar resultados de análisis y ajustar medicación si es necesario.</p>
                </div>
                
                <div class="mb-3">
                    <strong>Configuración:</strong>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-clock text-success"></i> Todo el día: No</li>
                        <li><i class="bi bi-bell text-primary"></i> Recordatorio: Activado</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="marcarCompletado(<?php echo $id; ?>)">
                        <i class="bi bi-check"></i> Marcar Completado
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-clock"></i> Reprogramar
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-envelope"></i> Enviar Recordatorio
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-copy"></i> Duplicar Evento
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Eventos Relacionados</h5>
            </div>
            <div class="card-body">
                <div class="text-center text-muted p-2">
                    <i class="bi bi-calendar-event"></i>
                    <small>No hay eventos relacionados</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarEvento(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
        fetch(`<?php echo $baseUrl; ?>calendario/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Evento eliminado correctamente');
                window.location.href = '<?php echo $baseUrl; ?>calendario';
            } else {
                alert('Error al eliminar el evento');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el evento');
        });
    }
}

function marcarCompletado(id) {
    if (confirm('¿Marcar este evento como completado?')) {
        fetch(`<?php echo $baseUrl; ?>calendario/completar/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Evento marcado como completado');
                location.reload();
            } else {
                alert('Error al marcar el evento como completado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al marcar el evento como completado');
        });
    }
}
</script>