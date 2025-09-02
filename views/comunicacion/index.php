<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-chat-dots"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>comunicacion/mensajes" class="btn btn-sm btn-primary">
            <i class="bi bi-envelope"></i> Mensajes
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

<!-- Contenido de Comunicación -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Centro de Comunicación</h5>
            </div>
            <div class="card-body">
                <div class="text-center p-4">
                    <i class="bi bi-chat-dots" style="font-size: 3rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">Módulo de Comunicación</h4>
                    <p class="text-muted">
                        Este módulo está en construcción. Próximamente podrás gestionar mensajes, notificaciones y comunicaciones.
                    </p>
                    <a href="<?php echo $baseUrl; ?>dashboard" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>