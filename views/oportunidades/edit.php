<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-pencil"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>oportunidades/view/<?php echo $id; ?>" class="btn btn-sm btn-outline-secondary">
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

<!-- Formulario de Edición -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editar Oportunidad</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>oportunidades/update/<?php echo $id; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de la Oportunidad *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="Oportunidad de ejemplo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="1" selected>Cliente de ejemplo</option>
                                    <!-- Options will be populated via AJAX or server-side -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="valor_estimado" class="form-label">Valor Estimado</label>
                                <input type="number" class="form-control" id="valor_estimado" name="valor_estimado" value="5000.00" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="probabilidad" class="form-label">Probabilidad (%)</label>
                                <input type="number" class="form-control" id="probabilidad" name="probabilidad" value="50" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_cierre_estimada" class="form-label">Fecha Cierre Estimada</label>
                                <input type="date" class="form-control" id="fecha_cierre_estimada" name="fecha_cierre_estimada" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="prospecto" selected>Prospecto</option>
                                    <option value="contactado">Contactado</option>
                                    <option value="calificado">Calificado</option>
                                    <option value="propuesta">Propuesta</option>
                                    <option value="negociacion">Negociación</option>
                                    <option value="ganado">Ganado</option>
                                    <option value="perdido">Perdido</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-select" id="prioridad" name="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4">Descripción de ejemplo de la oportunidad de negocio.</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas Adicionales</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3">Notas adicionales sobre la oportunidad.</textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Actualizar Oportunidad
                        </button>
                        <a href="<?php echo $baseUrl; ?>oportunidades/view/<?php echo $id; ?>" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>