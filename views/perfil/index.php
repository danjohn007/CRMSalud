<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-circle"></i> Mi Perfil</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($currentUser['profile_image']) && file_exists($currentUser['profile_image'])): ?>
                <div class="text-center mb-4">
                    <img src="<?php echo $baseUrl . $currentUser['profile_image']; ?>" 
                         alt="Imagen de perfil" class="rounded-circle" 
                         style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <?php endif; ?>
                
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
                <?php if (!empty($currentUser['telefono'])): ?>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Teléfono:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($currentUser['telefono']); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($currentUser['direccion'])): ?>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Dirección:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php echo nl2br(htmlspecialchars($currentUser['direccion'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Rol:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-primary"><?php echo htmlspecialchars(ucfirst($currentUser['rol'])); ?></span>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?php echo $baseUrl; ?>perfil/editar" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Configuración</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo $baseUrl; ?>perfil/editar" class="btn btn-outline-primary">
                        <i class="bi bi-gear"></i> Cambiar Datos
                    </a>
                    <a href="#" class="btn btn-outline-secondary">
                        <i class="bi bi-key"></i> Cambiar Contraseña
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>