<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-pencil-square"></i> Editar Cliente</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>clientes/view/<?php echo $cliente['id']; ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Cliente</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>clientes/edit/<?php echo $cliente['id']; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Cliente *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <?php foreach ($tiposCliente as $key => $label): ?>
                                    <option value="<?php echo $key; ?>" <?php echo $cliente['tipo'] === $key ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($cliente['email'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($cliente['direccion'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                       value="<?php echo htmlspecialchars($cliente['ciudad'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estado" name="estado" 
                                       value="<?php echo htmlspecialchars($cliente['estado'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                       value="<?php echo htmlspecialchars($cliente['codigo_postal'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos específicos para doctores -->
                    <div id="campos-doctor" style="display: <?php echo $cliente['tipo'] === 'doctor' ? 'block' : 'none'; ?>;">
                        <h6 class="text-muted border-bottom pb-2 mb-3">Información Profesional</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="especialidad" class="form-label">Especialidad</label>
                                    <input type="text" class="form-control" id="especialidad" name="especialidad" 
                                           value="<?php echo htmlspecialchars($cliente['especialidad'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cedula_profesional" class="form-label">Cédula Profesional</label>
                                    <input type="text" class="form-control" id="cedula_profesional" name="cedula_profesional" 
                                           value="<?php echo htmlspecialchars($cliente['cedula_profesional'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información comercial -->
                    <h6 class="text-muted border-bottom pb-2 mb-3">Información Comercial</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="volumen_compra" class="form-label">Volumen de Compra</label>
                                <select class="form-select" id="volumen_compra" name="volumen_compra">
                                    <option value="bajo" <?php echo $cliente['volumen_compra'] === 'bajo' ? 'selected' : ''; ?>>Bajo</option>
                                    <option value="medio" <?php echo $cliente['volumen_compra'] === 'medio' ? 'selected' : ''; ?>>Medio</option>
                                    <option value="alto" <?php echo $cliente['volumen_compra'] === 'alto' ? 'selected' : ''; ?>>Alto</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rfc" class="form-label">RFC</label>
                                <input type="text" class="form-control" id="rfc" name="rfc" 
                                       value="<?php echo htmlspecialchars($cliente['rfc'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="descuento_autorizado" class="form-label">Descuento Autorizado (%)</label>
                                <input type="number" class="form-control" id="descuento_autorizado" name="descuento_autorizado" 
                                       min="0" max="100" step="0.01" 
                                       value="<?php echo $cliente['descuento_autorizado'] ?? 0; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="limite_credito" class="form-label">Límite de Crédito</label>
                                <input type="number" class="form-control" id="limite_credito" name="limite_credito" 
                                       min="0" step="0.01" 
                                       value="<?php echo $cliente['limite_credito'] ?? 0; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="dias_credito" class="form-label">Días de Crédito</label>
                                <input type="number" class="form-control" id="dias_credito" name="dias_credito" 
                                       min="0" value="<?php echo $cliente['dias_credito'] ?? 0; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3"><?php echo htmlspecialchars($cliente['notas'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo $baseUrl; ?>clientes/view/<?php echo $cliente['id']; ?>" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Actualizar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Cliente</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Estado:</strong><br>
                    <span class="badge <?php echo $cliente['activo'] ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo $cliente['activo'] ? 'Activo' : 'Inactivo'; ?>
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Registrado:</strong><br>
                    <?php echo date('d/m/Y H:i', strtotime($cliente['created_at'])); ?>
                </div>
                <?php if ($cliente['updated_at'] !== $cliente['created_at']): ?>
                <div class="mb-3">
                    <strong>Última actualización:</strong><br>
                    <?php echo date('d/m/Y H:i', strtotime($cliente['updated_at'])); ?>
                </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <form method="POST" action="<?php echo $baseUrl; ?>clientes/cambiarEstatus/<?php echo $cliente['id']; ?>">
                        <button type="submit" class="btn btn-outline-<?php echo $cliente['activo'] ? 'warning' : 'success'; ?> btn-sm w-100"
                                onclick="return confirm('¿Estás seguro de <?php echo $cliente['activo'] ? 'desactivar' : 'activar'; ?> este cliente?')">
                            <i class="bi bi-<?php echo $cliente['activo'] ? 'pause' : 'play'; ?>"></i> 
                            <?php echo $cliente['activo'] ? 'Desactivar Cliente' : 'Activar Cliente'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('tipo').addEventListener('change', function() {
    const camposDoctor = document.getElementById('campos-doctor');
    if (this.value === 'doctor') {
        camposDoctor.style.display = 'block';
    } else {
        camposDoctor.style.display = 'none';
    }
});
</script>