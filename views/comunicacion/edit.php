<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-pencil"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>comunicacion/show/<?php echo $id; ?>" class="btn btn-sm btn-outline-secondary">
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
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editar Comunicación</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>comunicacion/update/<?php echo $id; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Comunicación *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="email" selected>Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="llamada">Llamada</option>
                                    <option value="sistema">Notificación Sistema</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="destinatario_id" class="form-label">Destinatario *</label>
                                <select class="form-select" id="destinatario_id" name="destinatario_id" required>
                                    <option value="1" selected>Cliente de ejemplo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto *</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" value="Información sobre productos de salud" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje *</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="6" required>Estimado cliente,

Esperamos que se encuentre bien. Le escribimos para informarle sobre nuestros nuevos productos de salud que pueden ser de su interés.

Quedamos a su disposición para cualquier consulta.

Saludos cordiales,
Equipo CRM Salud</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-select" id="prioridad" name="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="programada" class="form-label">Programar Envío</label>
                                <input type="datetime-local" class="form-control" id="programada" name="programada">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Actualizar Comunicación
                        </button>
                        <a href="<?php echo $baseUrl; ?>comunicacion/show/<?php echo $id; ?>" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>