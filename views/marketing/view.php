<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-eye"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>marketing/edit/<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarCampana(<?php echo $id; ?>)">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
        <a href="<?php echo $baseUrl; ?>marketing" class="btn btn-sm btn-outline-secondary">
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

<!-- Detalle de Campaña -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de la Campaña</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> Campaña de ejemplo</p>
                        <p><strong>Tipo:</strong> Email Marketing</p>
                        <p><strong>Estado:</strong> <span class="badge bg-success">Activa</span></p>
                        <p><strong>Objetivo:</strong> Generación de Leads</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha Inicio:</strong> <?php echo date('d/m/Y'); ?></p>
                        <p><strong>Fecha Fin:</strong> <?php echo date('d/m/Y', strtotime('+30 days')); ?></p>
                        <p><strong>Presupuesto:</strong> $5,000.00</p>
                        <p><strong>ROI:</strong> <span class="text-success">+25%</span></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Descripción:</strong>
                    <p class="text-muted">Campaña de marketing digital enfocada en la promoción de productos de salud.</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Métricas de Rendimiento</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h4 class="text-primary">1,250</h4>
                        <small class="text-muted">Impresiones</small>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-info">95</h4>
                        <small class="text-muted">Clicks</small>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-success">12</h4>
                        <small class="text-muted">Conversiones</small>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-warning">7.6%</h4>
                        <small class="text-muted">CTR</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-play"></i> Activar Campaña
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-pause"></i> Pausar Campaña
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-graph-up"></i> Ver Analytics
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-copy"></i> Duplicar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarCampana(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta campaña?')) {
        fetch(`<?php echo $baseUrl; ?>marketing/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Campaña eliminada correctamente');
                window.location.href = '<?php echo $baseUrl; ?>marketing';
            } else {
                alert('Error al eliminar la campaña');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la campaña');
        });
    }
}
</script>