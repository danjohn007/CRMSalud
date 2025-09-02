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
                <form method="POST" action="<?php echo $baseUrl; ?>perfil/actualizar">
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
                        <input type="password" class="form-control" id="password_nueva" name="password_nueva">
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