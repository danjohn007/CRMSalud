<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-plus"></i> <?php echo $title; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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
                <form method="POST" action="<?php echo $baseUrl; ?>usuarios/create">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono (opcional)</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección (opcional)</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="">Seleccionar rol...</option>
                            <?php foreach ($roles as $key => $label): ?>
                                <option value="<?php echo $key; ?>"><?php echo htmlspecialchars($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                        <div class="form-text">Mínimo 6 caracteres</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo $baseUrl; ?>usuarios" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información</h5>
            </div>
            <div class="card-body">
                <h6>Roles Disponibles:</h6>
                <ul class="list-unstyled">
                    <li><strong>Administrador:</strong> Acceso completo al sistema</li>
                    <li><strong>Vendedor:</strong> Gestión de clientes y ventas</li>
                    <li><strong>Marketing:</strong> Gestión de campañas y comunicaciones</li>
                    <li><strong>Inventarios:</strong> Gestión de productos e inventarios</li>
                </ul>
                
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Nota:</strong> El usuario recibirá sus credenciales y deberá cambiar su contraseña en el primer acceso.
                </div>
            </div>
        </div>
    </div>
</div>