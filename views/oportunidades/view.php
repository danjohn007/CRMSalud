<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-eye"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>oportunidades/edit/<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarOportunidad(<?php echo $id; ?>)">
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
                        <p><strong>Nombre:</strong> <span id="nombre">Oportunidad de ejemplo</span></p>
                        <p><strong>Cliente:</strong> <span id="cliente">Cliente de ejemplo</span></p>
                        <p><strong>Estado:</strong> <span id="estado" class="badge bg-primary">Prospecto</span></p>
                        <p><strong>Prioridad:</strong> <span id="prioridad" class="badge bg-info">Media</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Valor Estimado:</strong> <span id="valor_estimado">$0.00</span></p>
                        <p><strong>Probabilidad:</strong> <span id="probabilidad">0%</span></p>
                        <p><strong>Fecha Cierre:</strong> <span id="fecha_cierre">-</span></p>
                        <p><strong>Creada:</strong> <span id="fecha_creacion">-</span></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Descripción:</strong>
                    <p id="descripcion" class="text-muted">Sin descripción disponible.</p>
                </div>
                
                <div class="mb-3">
                    <strong>Notas:</strong>
                    <p id="notas" class="text-muted">Sin notas adicionales.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actividad Reciente</h5>
            </div>
            <div class="card-body">
                <div class="text-center text-muted p-3">
                    <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                    <p class="mt-2">Sin actividad registrada</p>
                </div>
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