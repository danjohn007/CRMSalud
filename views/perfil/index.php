<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-circle"></i> Mi Perfil</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Imagen de Perfil</h5>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($currentUser['imagen_perfil'])): ?>
                    <img src="<?php echo BASE_URL; ?>uploads/profiles/<?php echo htmlspecialchars($currentUser['imagen_perfil']); ?>" 
                         alt="Imagen de perfil" class="img-fluid rounded-circle mb-3" style="max-width: 200px; max-height: 200px;">
                <?php else: ?>
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 120px; height: 120px;">
                        <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
                <br>
                <a href="<?php echo $baseUrl; ?>perfil/editar" class="btn btn-outline-primary">
                    <i class="bi bi-camera"></i> Cambiar Foto
                </a>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Configuración</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo $baseUrl; ?>perfil/editar" class="btn btn-outline-primary">
                        <i class="bi bi-gear"></i> Cambiar Datos
                    </a>
                    <a href="<?php echo $baseUrl; ?>perfil/editar" class="btn btn-outline-secondary">
                        <i class="bi bi-key"></i> Cambiar Contraseña
                    </a>
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
                        <?php echo htmlspecialchars($currentUser['nombre']); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($currentUser['email']); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Teléfono:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php echo !empty($currentUser['telefono']) ? htmlspecialchars($currentUser['telefono']) : '<em class="text-muted">No especificado</em>'; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Dirección:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php echo !empty($currentUser['direccion']) ? nl2br(htmlspecialchars($currentUser['direccion'])) : '<em class="text-muted">No especificada</em>'; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Rol:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-primary"><?php echo htmlspecialchars(ucfirst($currentUser['rol'])); ?></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Último acceso:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php 
                        if (!empty($currentUser['ultimo_acceso'])) {
                            $lastAccess = new DateTime($currentUser['ultimo_acceso']);
                            echo $lastAccess->format('d/m/Y H:i');
                        } else {
                            echo '<em class="text-muted">Nunca</em>';
                        }
                        ?>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?php echo $baseUrl; ?>perfil/editar" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>