<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-gear"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>configuracion/general" class="btn btn-sm btn-outline-primary">General</a>
            <a href="<?php echo $baseUrl; ?>configuracion/sistema" class="btn btn-sm btn-outline-primary">Sistema</a>
        </div>
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

<!-- Contenido de Configuración -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Configuración del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="text-center p-4">
                    <i class="bi bi-gear" style="font-size: 3rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">Módulo de Configuración</h4>
                    <p class="text-muted">
                        Este módulo está en construcción. Próximamente podrás configurar todos los parámetros del sistema.
                    </p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Solo los administradores pueden acceder a esta sección.
                    </div>
                    <a href="<?php echo $baseUrl; ?>dashboard" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>