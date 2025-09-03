<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-pencil"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>oportunidades/show/<?php echo $id; ?>" class="btn btn-sm btn-outline-secondary">
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
                <form method="POST" action="<?php echo $baseUrl; ?>oportunidades/update/<?php echo $oportunidad['id']; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de la Oportunidad *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($oportunidad['nombre']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?php echo $cliente['id']; ?>" 
                                                <?php echo ($cliente['id'] == $oportunidad['cliente_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cliente['nombre']); ?> 
                                            (<?php echo ucfirst($cliente['tipo']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="valor_estimado" class="form-label">Valor Estimado ($)</label>
                                <input type="number" class="form-control" id="valor_estimado" name="valor_estimado" 
                                       value="<?php echo $oportunidad['valor_estimado']; ?>" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="probabilidad" class="form-label">Probabilidad (%)</label>
                                <input type="number" class="form-control" id="probabilidad" name="probabilidad" 
                                       value="<?php echo $oportunidad['probabilidad']; ?>" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_cierre_estimada" class="form-label">Fecha Cierre Estimada</label>
                                <input type="date" class="form-control" id="fecha_cierre_estimada" name="fecha_cierre_estimada" 
                                       value="<?php echo $oportunidad['fecha_cierre_estimada']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <?php foreach (OPORTUNIDAD_ESTADOS as $estado => $label): ?>
                                        <option value="<?php echo $estado; ?>" 
                                                <?php echo ($estado === $oportunidad['estado']) ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="usuario_id" class="form-label">Responsable</label>
                                <select class="form-select" id="usuario_id" name="usuario_id">
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?php echo $usuario['id']; ?>" 
                                                <?php echo ($usuario['id'] == $oportunidad['usuario_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($usuario['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-select" id="prioridad" name="prioridad">
                                    <option value="baja" <?php echo ($oportunidad['prioridad'] === 'baja') ? 'selected' : ''; ?>>Baja</option>
                                    <option value="media" <?php echo ($oportunidad['prioridad'] === 'media') ? 'selected' : ''; ?>>Media</option>
                                    <option value="alta" <?php echo ($oportunidad['prioridad'] === 'alta') ? 'selected' : ''; ?>>Alta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($oportunidad['descripcion'] ?? ''); ?></textarea>
                    </div>
                    
                    <?php if (in_array($oportunidad['estado'], ['perdido'])): ?>
                        <div class="mb-3">
                            <label for="motivo_perdida" class="form-label">Motivo de Pérdida</label>
                            <textarea class="form-control" id="motivo_perdida" name="motivo_perdida" rows="3"><?php echo htmlspecialchars($oportunidad['motivo_perdida'] ?? ''); ?></textarea>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Actualizar Oportunidad
                        </button>
                        <a href="<?php echo $baseUrl; ?>oportunidades/show/<?php echo $oportunidad['id']; ?>" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>