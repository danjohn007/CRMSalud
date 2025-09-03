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

<!-- Tabla de Usuarios -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lista de Usuarios</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($usuarios)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Teléfono</th>
                                    <th>Último Acceso</th>
                                    <th>Estado</th>
                                    <th width="150">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($usuario['imagen_perfil'])): ?>
                                                    <img src="<?php echo BASE_URL; ?>uploads/profiles/<?php echo htmlspecialchars($usuario['imagen_perfil']); ?>" 
                                                         alt="Perfil" class="rounded-circle me-2" style="width: 32px; height: 32px;">
                                                <?php else: ?>
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 32px; height: 32px;">
                                                        <i class="bi bi-person-fill text-white" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $usuario['rol'] === 'admin' ? 'danger' : 'primary'; ?>">
                                                <?php echo htmlspecialchars($roles[$usuario['rol']] ?? ucfirst($usuario['rol'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo !empty($usuario['telefono']) ? htmlspecialchars($usuario['telefono']) : '<em class="text-muted">N/A</em>'; ?></td>
                                        <td>
                                            <?php 
                                            if (!empty($usuario['ultimo_acceso'])) {
                                                $lastAccess = new DateTime($usuario['ultimo_acceso']);
                                                echo $lastAccess->format('d/m/Y H:i');
                                            } else {
                                                echo '<em class="text-muted">Nunca</em>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($usuario['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo $baseUrl; ?>usuarios/show/<?php echo $usuario['id']; ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?php echo $baseUrl; ?>usuarios/edit/<?php echo $usuario['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php if ($usuario['activo']): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmarEliminacion(<?php echo $usuario['id']; ?>)" title="Desactivar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center p-4">
                        <i class="bi bi-person-gear" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h4 class="mt-3 text-muted">No hay usuarios registrados</h4>
                        <p class="text-muted">Crea el primer usuario para comenzar.</p>
                        <a href="<?php echo $baseUrl; ?>usuarios/create" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Crear Usuario
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas desactivar este usuario? Esta acción se puede revertir.
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