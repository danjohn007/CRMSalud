<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-eye"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>comunicacion/edit/<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarComunicacion(<?php echo $id; ?>)">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
        <a href="<?php echo $baseUrl; ?>comunicacion" class="btn btn-sm btn-outline-secondary">
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
                <h5 class="mb-0">Detalle de Comunicación</h5>
                <span class="badge bg-success">Enviado</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Tipo:</strong> <i class="bi bi-envelope"></i> Email</p>
                        <p><strong>Destinatario:</strong> Cliente de ejemplo</p>
                        <p><strong>Prioridad:</strong> <span class="badge bg-info">Media</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha Envío:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                        <p><strong>Estado:</strong> <span class="badge bg-success">Entregado</span></p>
                        <p><strong>Leído:</strong> <span class="text-success">Sí</span></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Asunto:</strong>
                    <h6>Información sobre productos de salud</h6>
                </div>
                
                <div class="mb-3">
                    <strong>Mensaje:</strong>
                    <div class="border p-3 rounded bg-light">
                        <p>Estimado cliente,</p>
                        <p>Esperamos que se encuentre bien. Le escribimos para informarle sobre nuestros nuevos productos de salud que pueden ser de su interés.</p>
                        <p>Quedamos a su disposición para cualquier consulta.</p>
                        <p>Saludos cordiales,<br>Equipo CRM Salud</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estadísticas</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <h4 class="text-primary">1</h4>
                    <small class="text-muted">Enviado</small>
                </div>
                <div class="mb-3">
                    <h4 class="text-success">1</h4>
                    <small class="text-muted">Entregado</small>
                </div>
                <div class="mb-3">
                    <h4 class="text-info">1</h4>
                    <small class="text-muted">Abierto</small>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-reply"></i> Responder
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-forward"></i> Reenviar
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-copy"></i> Duplicar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarComunicacion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta comunicación?')) {
        fetch(`<?php echo $baseUrl; ?>comunicacion/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Comunicación eliminada correctamente');
                window.location.href = '<?php echo $baseUrl; ?>comunicacion';
            } else {
                alert('Error al eliminar la comunicación');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la comunicación');
        });
    }
}
</script>