<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-eye"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>cotizaciones/edit/<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <button type="button" class="btn btn-sm btn-outline-success">
                <i class="bi bi-download"></i> PDF
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarCotizacion(<?php echo $id; ?>)">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
        <a href="<?php echo $baseUrl; ?>cotizaciones" class="btn btn-sm btn-outline-secondary">
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

<!-- Detalle de Cotización -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Cotización COT-<?php echo date('Ymd'); ?>-001</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Cliente:</strong> Cliente de ejemplo</p>
                        <p><strong>Estado:</strong> <span class="badge bg-success">Vigente</span></p>
                        <p><strong>Fecha Creación:</strong> <?php echo date('d/m/Y'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha Vencimiento:</strong> <?php echo date('d/m/Y', strtotime('+30 days')); ?></p>
                        <p><strong>Total:</strong> <span class="h5 text-primary">$0.00</span></p>
                    </div>
                </div>
                
                <hr>
                
                <h6>Productos/Servicios</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No se han agregado productos a esta cotización
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <strong>Observaciones:</strong>
                    <p class="text-muted">Sin observaciones adicionales.</p>
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
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus"></i> Agregar Productos
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-check"></i> Marcar como Aceptada
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-envelope"></i> Enviar por Email
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
function eliminarCotizacion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta cotización?')) {
        fetch(`<?php echo $baseUrl; ?>cotizaciones/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cotización eliminada correctamente');
                window.location.href = '<?php echo $baseUrl; ?>cotizaciones';
            } else {
                alert('Error al eliminar la cotización');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la cotización');
        });
    }
}
</script>