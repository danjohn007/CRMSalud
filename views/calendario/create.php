<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-plus-circle"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>calendario" class="btn btn-sm btn-outline-secondary">
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
                <h5 class="mb-0">Crear Nuevo Evento</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>calendario/store">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título del Evento *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Evento *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="cita">Cita Médica</option>
                                    <option value="reunion">Reunión</option>
                                    <option value="llamada">Llamada</option>
                                    <option value="tarea">Tarea</option>
                                    <option value="recordatorio">Recordatorio</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha *</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="hora_inicio" class="form-label">Hora de Inicio *</label>
                                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="hora_fin" class="form-label">Hora de Fin</label>
                                <input type="time" class="form-control" id="hora_fin" name="hora_fin">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente Relacionado</label>
                                <select class="form-select" id="cliente_id" name="cliente_id">
                                    <option value="">Seleccionar cliente...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-select" id="prioridad" name="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="todo_el_dia" name="todo_el_dia">
                                <label class="form-check-label" for="todo_el_dia">
                                    Evento de todo el día
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recordatorio" name="recordatorio" checked>
                                <label class="form-check-label" for="recordatorio">
                                    Enviar recordatorio
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Crear Evento
                        </button>
                        <a href="<?php echo $baseUrl; ?>calendario" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>