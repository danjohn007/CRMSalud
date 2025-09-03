<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person"></i> <?php echo $title; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>usuarios/edit/<?php echo $usuario['id']; ?>" class="btn btn-primary me-2">
            <i class="bi bi-pencil"></i> Editar
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
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Imagen de Perfil</h5>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($usuario['imagen_perfil'])): ?>
                    <img src="<?php echo BASE_URL; ?>uploads/profiles/<?php echo htmlspecialchars($usuario['imagen_perfil']); ?>" 
                         alt="Imagen de perfil" class="img-fluid rounded-circle mb-3" style="max-width: 200px; max-height: 200px;">
                <?php else: ?>
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 120px; height: 120px;">
                        <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
                <h5><?php echo htmlspecialchars($usuario['nombre']); ?></h5>
                <p class="text-muted"><?php echo htmlspecialchars($roles[$usuario['rol']] ?? ucfirst($usuario['rol'])); ?></p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Estado</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Estado de la cuenta:</strong><br>
                    <?php if ($usuario['activo']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <strong>Último acceso:</strong><br>
                    <?php 
                    if (!empty($usuario['ultimo_acceso'])) {
                        $lastAccess = new DateTime($usuario['ultimo_acceso']);
                        echo $lastAccess->format('d/m/Y H:i');
                    } else {
                        echo '<em class="text-muted">Nunca</em>';
                    }
                    ?>
                </div>
                
                <div>
                    <strong>Registrado:</strong><br>
                    <?php 
                    $created = new DateTime($usuario['created_at']);
                    echo $created->format('d/m/Y H:i');
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Nombre:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($usuario['nombre']); ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-sm-9">
                        <a href="mailto:<?php echo htmlspecialchars($usuario['email']); ?>">
                            <?php echo htmlspecialchars($usuario['email']); ?>
                        </a>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Teléfono:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php if (!empty($usuario['telefono'])): ?>
                            <a href="tel:<?php echo htmlspecialchars($usuario['telefono']); ?>">
                                <?php echo htmlspecialchars($usuario['telefono']); ?>
                            </a>
                        <?php else: ?>
                            <em class="text-muted">No especificado</em>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Dirección:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php if (!empty($usuario['direccion'])): ?>
                            <?php echo nl2br(htmlspecialchars($usuario['direccion'])); ?>
                        <?php else: ?>
                            <em class="text-muted">No especificada</em>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Rol:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-<?php echo $usuario['rol'] === 'admin' ? 'danger' : 'primary'; ?>">
                            <?php echo htmlspecialchars($roles[$usuario['rol']] ?? ucfirst($usuario['rol'])); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Acciones -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex">
                    <a href="<?php echo $baseUrl; ?>usuarios/edit/<?php echo $usuario['id']; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar Usuario
                    </a>
                    
                    <?php if ($usuario['activo']): ?>
                        <button type="button" class="btn btn-danger" onclick="confirmarEliminacion(<?php echo $usuario['id']; ?>)">
                            <i class="bi bi-ban"></i> Desactivar Usuario
                        </button>
                    <?php else: ?>
                        <form method="POST" action="<?php echo $baseUrl; ?>usuarios/activate/<?php echo $usuario['id']; ?>" style="display: inline;">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Activar Usuario
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Desactivación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas desactivar este usuario? El usuario no podrá acceder al sistema hasta que sea reactivado.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Desactivar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(userId) {
    const form = document.getElementById('deleteForm');
    form.action = '<?php echo $baseUrl; ?>usuarios/delete/' + userId;
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
}
</script>