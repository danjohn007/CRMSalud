<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-pencil"></i> <?php echo $title; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>usuarios/show/<?php echo $usuario['id']; ?>" class="btn btn-outline-info me-2">
            <i class="bi bi-eye"></i> Ver
        </a>
        <a href="<?php echo $baseUrl; ?>usuarios" class="btn btn-outline-secondary">
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
                <h5 class="card-title mb-0">Información del Usuario</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>usuarios/edit/<?php echo $usuario['id']; ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono (opcional)</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" 
                               value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección (opcional)</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                        <select class="form-select" id="rol" name="rol" required>
                            <?php foreach ($roles as $key => $label): ?>
                                <option value="<?php echo $key; ?>" <?php echo $usuario['rol'] === $key ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Nota:</strong> Para cambiar la contraseña de este usuario, utiliza la función de "Restablecer contraseña" o pide al usuario que use la función de cambio de contraseña en su perfil.
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo $baseUrl; ?>usuarios/show/<?php echo $usuario['id']; ?>" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Imagen Actual</h5>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($usuario['imagen_perfil'])): ?>
                    <img src="<?php echo BASE_URL; ?>uploads/profiles/<?php echo htmlspecialchars($usuario['imagen_perfil']); ?>" 
                         alt="Imagen de perfil" class="img-fluid rounded-circle mb-3" style="max-width: 150px; max-height: 150px;">
                    <p class="text-muted small">El usuario puede cambiar su imagen desde su perfil personal.</p>
                <?php else: ?>
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-person-fill text-white" style="font-size: 2rem;"></i>
                    </div>
                    <p class="text-muted small">Sin imagen de perfil</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Estado:</strong><br>
                    <?php if ($usuario['activo']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <strong>Último acceso:</strong><br>
                    <small class="text-muted">
                        <?php 
                        if (!empty($usuario['ultimo_acceso'])) {
                            $lastAccess = new DateTime($usuario['ultimo_acceso']);
                            echo $lastAccess->format('d/m/Y H:i');
                        } else {
                            echo 'Nunca';
                        }
                        ?>
                    </small>
                </div>
                
                <div class="mb-3">
                    <strong>Registrado:</strong><br>
                    <small class="text-muted">
                        <?php 
                        $created = new DateTime($usuario['created_at']);
                        echo $created->format('d/m/Y H:i');
                        ?>
                    </small>
                </div>
                
                <div>
                    <strong>Última modificación:</strong><br>
                    <small class="text-muted">
                        <?php 
                        $updated = new DateTime($usuario['updated_at']);
                        echo $updated->format('d/m/Y H:i');
                        ?>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Roles Disponibles</h5>
            </div>
            <div class="card-body">
                <small>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Administrador:</strong> Acceso completo</li>
                        <li><strong>Vendedor:</strong> Clientes y ventas</li>
                        <li><strong>Marketing:</strong> Campañas</li>
                        <li><strong>Inventarios:</strong> Productos</li>
                    </ul>
                </small>
            </div>
        </div>
    </div>
</div>