<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-eye"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>reportes/edit/<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <button type="button" class="btn btn-sm btn-outline-success">
                <i class="bi bi-download"></i> Descargar
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarReporte(<?php echo $id; ?>)">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
        <a href="<?php echo $baseUrl; ?>reportes" class="btn btn-sm btn-outline-secondary">
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
            <div class="card-header">
                <h5 class="mb-0">Reporte de Ventas - <?php echo date('F Y'); ?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Tipo:</strong> Reporte de Ventas</p>
                        <p><strong>Período:</strong> <?php echo date('d/m/Y', strtotime('first day of this month')); ?> - <?php echo date('d/m/Y', strtotime('last day of this month')); ?></p>
                        <p><strong>Estado:</strong> <span class="badge bg-success">Completado</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Generado:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                        <p><strong>Formato:</strong> PDF</p>
                        <p><strong>Tamaño:</strong> 2.5 MB</p>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Descripción:</strong>
                    <p class="text-muted">Reporte mensual de ventas con análisis detallado de productos y clientes.</p>
                </div>
                
                <div class="mb-3">
                    <strong>Resumen de Datos:</strong>
                    <div class="row text-center mt-3">
                        <div class="col-md-3">
                            <h4 class="text-primary">125</h4>
                            <small class="text-muted">Ventas Total</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">$45,250</h4>
                            <small class="text-muted">Ingresos</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">89</h4>
                            <small class="text-muted">Clientes</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">$362</h4>
                            <small class="text-muted">Promedio/Venta</small>
                        </div>
                    </div>
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
                    <button type="button" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-download"></i> Descargar PDF
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-envelope"></i> Enviar por Email
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-arrow-repeat"></i> Regenerar
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-star"></i> Marcar Favorito
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Configuración</h5>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <strong>Filtros aplicados:</strong><br>
                    • Incluir gráficos: Sí<br>
                    • Agrupar por mes: No<br>
                    • Formato: PDF
                </small>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarReporte(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este reporte?')) {
        fetch(`<?php echo $baseUrl; ?>reportes/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reporte eliminado correctamente');
                window.location.href = '<?php echo $baseUrl; ?>reportes';
            } else {
                alert('Error al eliminar el reporte');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el reporte');
        });
    }
}
</script>