<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-plus-circle"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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

<!-- Formulario de Nueva Cotización -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Crear Nueva Cotización</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>cotizaciones/store">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numero" class="form-label">Número de Cotización</label>
                                <input type="text" class="form-control" id="numero" name="numero" value="COT-<?php echo date('Ymd-His'); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="">Seleccionar cliente...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento *</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="borrador">Borrador</option>
                                    <option value="vigente" selected>Vigente</option>
                                    <option value="aceptada">Aceptada</option>
                                    <option value="rechazada">Rechazada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Productos/Servicios</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center text-muted p-3">
                                <p>Los productos se agregarán después de crear la cotización</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Crear Cotización
                        </button>
                        <a href="<?php echo $baseUrl; ?>cotizaciones" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>