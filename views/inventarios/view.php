<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-box"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>inventarios" class="btn btn-sm btn-secondary">
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

<!-- Detalle del Inventario -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Inventario</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($inventario['id'] ?? ''); ?></dd>
                            
                            <dt class="col-sm-4">Producto ID:</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($inventario['producto_id'] ?? ''); ?></dd>
                            
                            <dt class="col-sm-4">Stock Actual:</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-info fs-6">
                                    <?php echo $inventario['stock_actual'] ?? 0; ?>
                                </span>
                            </dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">Fecha Vencimiento:</dt>
                            <dd class="col-sm-8">
                                <?php if (!empty($inventario['fecha_vencimiento'])): ?>
                                    <?php echo date('d/m/Y', strtotime($inventario['fecha_vencimiento'])); ?>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </dd>
                            
                            <dt class="col-sm-4">Lote:</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($inventario['lote'] ?? 'N/A'); ?></dd>
                            
                            <dt class="col-sm-4">Ubicación:</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($inventario['ubicacion'] ?? 'N/A'); ?></dd>
                        </dl>
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
                    <a href="<?php echo $baseUrl; ?>inventarios" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a Inventarios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>