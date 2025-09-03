<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person"></i> Detalle de Usuario
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>usuarios/edit/<?php echo $usuario['id']; ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <?php if ($usuario['id'] != $currentUser['id']): ?>
                <button type="button" class="btn btn-sm btn-outline-<?php echo $usuario['activo'] ? 'warning' : 'success'; ?>"
                        onclick="toggleUserStatus(<?php echo $usuario['id']; ?>, <?php echo $usuario['activo'] ? 'false' : 'true'; ?>)">
                    <i class="bi bi-<?php echo $usuario['activo'] ? 'pause' : 'play'; ?>"></i>
                    <?php echo $usuario['activo'] ? 'Desactivar' : 'Activar'; ?>
                </button>
            <?php endif; ?>
        </div>
        <a href="<?php echo $baseUrl; ?>usuarios" class="btn btn-sm btn-outline-secondary">
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
    <!-- Información Principal -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Usuario</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nombre Completo</label>
                            <p class="form-control-plaintext"><strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="form-control-plaintext">
                                <i class="bi bi-envelope"></i> 
                                <a href="mailto:<?php echo htmlspecialchars($usuario['email']); ?>">
                                    <?php echo htmlspecialchars($usuario['email']); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Teléfono</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($usuario['telefono'])): ?>
                                    <i class="bi bi-phone"></i> 
                                    <a href="tel:<?php echo htmlspecialchars($usuario['telefono']); ?>">
                                        <?php echo htmlspecialchars($usuario['telefono']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No especificado</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Rol</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary fs-6"><?php echo ROLES[$usuario['rol']] ?? $usuario['rol']; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($usuario['direccion'])): ?>
                <div class="mb-3">
                    <label class="form-label text-muted">Dirección</label>
                    <p class="form-control-plaintext">
                        <i class="bi bi-geo-alt"></i> <?php echo nl2br(htmlspecialchars($usuario['direccion'])); ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Estado</label>
                            <p class="form-control-plaintext">
                                <?php if ($usuario['activo']): ?>
                                    <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> Inactivo</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Último Acceso</label>
                            <p class="form-control-plaintext">
                                <?php if ($usuario['ultimo_acceso']): ?>
                                    <i class="bi bi-clock"></i> 
                                    <?php echo date('d/m/Y H:i:s', strtotime($usuario['ultimo_acceso'])); ?>
                                <?php else: ?>
                                    <span class="text-muted">Nunca ha ingresado</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Fecha de Registro</label>
                            <p class="form-control-plaintext">
                                <i class="bi bi-calendar-plus"></i> 
                                <?php echo date('d/m/Y H:i:s', strtotime($usuario['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Última Actualización</label>
                            <p class="form-control-plaintext">
                                <i class="bi bi-calendar-check"></i> 
                                <?php echo date('d/m/Y H:i:s', strtotime($usuario['updated_at'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel Lateral -->
    <div class="col-lg-4">
        <!-- Foto de Perfil -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Foto de Perfil</h5>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($usuario['profile_image'])): ?>
                    <img src="<?php echo $baseUrl; ?>uploads/<?php echo $usuario['profile_image']; ?>" 
                         alt="Foto de perfil" class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 150px; height: 150px;">
                        <i class="bi bi-person" style="font-size: 4rem; color: white;"></i>
                    </div>
                <?php endif; ?>
                <p class="text-muted small">
                    <?php if (!empty($usuario['profile_image'])): ?>
                        Imagen de perfil personalizada
                    <?php else: ?>
                        Sin imagen de perfil
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <!-- Acciones Rápidas -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo $baseUrl; ?>usuarios/edit/<?php echo $usuario['id']; ?>" 
                       class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar Usuario
                    </a>
                    
                    <?php if ($usuario['id'] != $currentUser['id']): ?>
                        <button type="button" 
                                class="btn btn-outline-<?php echo $usuario['activo'] ? 'warning' : 'success'; ?>"
                                onclick="toggleUserStatus(<?php echo $usuario['id']; ?>, <?php echo $usuario['activo'] ? 'false' : 'true'; ?>)">
                            <i class="bi bi-<?php echo $usuario['activo'] ? 'pause' : 'play'; ?>"></i>
                            <?php echo $usuario['activo'] ? 'Desactivar Usuario' : 'Activar Usuario'; ?>
                        </button>
                    <?php else: ?>
                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle"></i>
                            No puedes desactivar tu propio usuario
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>