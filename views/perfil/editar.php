<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-pencil"></i> Editar Perfil</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>perfil" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>perfil/actualizar" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo htmlspecialchars($currentUser['nombre']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($currentUser['email']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono (opcional)</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" 
                               value="<?php echo htmlspecialchars($currentUser['telefono'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección (opcional)</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($currentUser['direccion'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagen_perfil" class="form-label">Imagen de Perfil (opcional)</label>
                        <input type="file" class="form-control" id="imagen_perfil" name="imagen_perfil" 
                               accept="image/jpeg,image/png,image/gif"
                               data-max-size="<?php echo MAX_UPLOAD_SIZE; ?>"
                               data-allowed-types="image/jpeg,image/png,image/gif">
                        <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: <?php echo MAX_UPLOAD_SIZE / 1024 / 1024; ?>MB</div>
                        <?php if (!empty($currentUser['imagen_perfil'])): ?>
                            <div class="mt-2">
                                <small class="text-muted">Imagen actual:</small><br>
                                <img src="<?php echo BASE_URL; ?>uploads/profiles/<?php echo htmlspecialchars($currentUser['imagen_perfil']); ?>" 
                                     alt="Imagen de perfil actual" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <input type="text" class="form-control" id="rol" name="rol" 
                               value="<?php echo htmlspecialchars(ucfirst($currentUser['rol'])); ?>" 
                               readonly disabled>
                        <div class="form-text">El rol no puede ser modificado. Contacta al administrador.</div>
                    </div>
                    
                    <hr>
                    
                    <h6>Cambiar Contraseña (opcional)</h6>
                    <div class="mb-3">
                        <label for="password_actual" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="password_actual" name="password_actual">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password_nueva" name="password_nueva" minlength="6">
                        <div class="form-text">Mínimo 6 caracteres</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmar" name="password_confirmar">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo $baseUrl; ?>perfil" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>