<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-gear"></i> Editar Usuario
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>usuarios/show/<?php echo $usuario['id']; ?>" class="btn btn-outline-secondary">
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
    <!-- Formulario Principal -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Usuario</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>usuarios/update/<?php echo $usuario['id']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required
                                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?php echo htmlspecialchars($usuario['email']); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                       value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol *</label>
                                <select class="form-select" id="rol" name="rol" required>
                                    <?php foreach ($roles as $key => $label): ?>
                                    <option value="<?php echo $key; ?>" 
                                            <?php echo ($usuario['rol'] === $key) ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Imagen de Perfil</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                        <div class="form-text">Tamaño máximo: 5MB. Formatos permitidos: JPG, PNG, GIF</div>
                        <?php if (!empty($usuario['profile_image'])): ?>
                            <div class="mt-2">
                                <small class="text-muted">Imagen actual:</small><br>
                                <img src="<?php echo $baseUrl; ?>uploads/<?php echo $usuario['profile_image']; ?>" 
                                     alt="Imagen actual" class="rounded" style="max-width: 100px; max-height: 100px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo $baseUrl; ?>usuarios/show/<?php echo $usuario['id']; ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Panel Lateral -->
    <div class="col-lg-4">
        <!-- Cambio de Contraseña -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Cambiar Contraseña</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>usuarios/changePassword/<?php echo $usuario['id']; ?>">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                        <div class="form-text">Mínimo 6 caracteres</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key"></i> Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Estado del Usuario -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Estado del Usuario</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Estado Actual</label>
                    <p>
                        <?php if ($usuario['activo']): ?>
                            <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> Inactivo</span>
                        <?php endif; ?>
                    </p>
                </div>
                
                <?php if ($usuario['id'] != $currentUser['id']): ?>
                    <div class="d-grid">
                        <button type="button" 
                                class="btn btn-outline-<?php echo $usuario['activo'] ? 'warning' : 'success'; ?>"
                                onclick="toggleUserStatus(<?php echo $usuario['id']; ?>, <?php echo $usuario['activo'] ? 'false' : 'true'; ?>)">
                            <i class="bi bi-<?php echo $usuario['activo'] ? 'pause' : 'play'; ?>"></i>
                            <?php echo $usuario['activo'] ? 'Desactivar Usuario' : 'Activar Usuario'; ?>
                        </button>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle"></i>
                        No puedes cambiar el estado de tu propio usuario
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Información del Sistema -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">ID de Usuario:</small><br>
                    <strong><?php echo $usuario['id']; ?></strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Registrado:</small><br>
                    <strong><?php echo date('d/m/Y H:i', strtotime($usuario['created_at'])); ?></strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Última actualización:</small><br>
                    <strong><?php echo date('d/m/Y H:i', strtotime($usuario['updated_at'])); ?></strong>
                </div>
                <?php if ($usuario['ultimo_acceso']): ?>
                <div class="mb-0">
                    <small class="text-muted">Último acceso:</small><br>
                    <strong><?php echo date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])); ?></strong>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Validación de contraseñas
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (password && confirmPassword && password !== confirmPassword) {
        this.setCustomValidity('Las contraseñas no coinciden');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('new_password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword.value && this.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Las contraseñas no coinciden');
    } else {
        confirmPassword.setCustomValidity('');
    }
});

// Función para cambiar estado del usuario
function toggleUserStatus(userId, activate) {
    const action = activate ? 'activar' : 'desactivar';
    if (confirm('¿Estás seguro de que quieres ' + action + ' este usuario?')) {
        fetch('<?php echo $baseUrl; ?>usuarios/toggleStatus/' + userId, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            alert('Error de conexión: ' + error.message);
        });
    }
}

// Preview de imagen
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validar tamaño (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('El archivo es demasiado grande. Tamaño máximo: 5MB');
            this.value = '';
            return;
        }
        
        // Validar tipo
        if (!file.type.match('image.*')) {
            alert('Solo se permiten archivos de imagen');
            this.value = '';
            return;
        }
    }
});
</script>