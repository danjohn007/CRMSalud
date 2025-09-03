<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-gear"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>usuarios/create" class="btn btn-sm btn-primary">
            <i class="bi bi-plus"></i> Nuevo Usuario
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

<!-- Lista de Usuarios -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Usuarios del Sistema</h5>
            </div>
            <div class="card-body">
                <?php if (empty($usuarios)): ?>
                    <div class="text-center p-4">
                        <i class="bi bi-person-plus" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h4 class="mt-3 text-muted">No hay usuarios registrados</h4>
                        <p class="text-muted">
                            Comienza creando el primer usuario del sistema.
                        </p>
                        <a href="<?php echo $baseUrl; ?>usuarios/create" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Crear Primer Usuario
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Último Acceso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($usuario['profile_image'])): ?>
                                            <img src="<?php echo $baseUrl; ?>uploads/<?php echo $usuario['profile_image']; ?>" 
                                                 alt="Avatar" class="rounded-circle" width="40" height="40">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                                        <?php if ($usuario['id'] == $currentUser['id']): ?>
                                            <span class="badge bg-info ms-1">Tú</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td>
                                        <?php if (!empty($usuario['telefono'])): ?>
                                            <i class="bi bi-phone"></i> <?php echo htmlspecialchars($usuario['telefono']); ?>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo ROLES[$usuario['rol']] ?? $usuario['rol']; ?></span>
                                    </td>
                                    <td>
                                        <?php if ($usuario['activo']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($usuario['ultimo_acceso']): ?>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])); ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">Nunca</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?php echo $baseUrl; ?>usuarios/show/<?php echo $usuario['id']; ?>" 
                                               class="btn btn-outline-primary" title="Ver detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?php echo $baseUrl; ?>usuarios/edit/<?php echo $usuario['id']; ?>" 
                                               class="btn btn-outline-secondary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if ($usuario['id'] != $currentUser['id']): ?>
                                                <button type="button" class="btn btn-outline-<?php echo $usuario['activo'] ? 'warning' : 'success'; ?>" 
                                                        title="<?php echo $usuario['activo'] ? 'Desactivar' : 'Activar'; ?>"
                                                        onclick="toggleUserStatus(<?php echo $usuario['id']; ?>, <?php echo $usuario['activo'] ? 'false' : 'true'; ?>)">
                                                    <i class="bi bi-<?php echo $usuario['activo'] ? 'pause' : 'play'; ?>"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleUserStatus(userId, activate) {
    if (confirm('¿Estás seguro de que quieres ' + (activate ? 'activar' : 'desactivar') + ' este usuario?')) {
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