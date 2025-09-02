<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-lines-fill"></i> <?php echo htmlspecialchars($cliente['nombre']); ?>
        <span class="badge <?php echo $cliente['activo'] ? 'bg-success' : 'bg-danger'; ?> ms-2">
            <?php echo $cliente['activo'] ? 'Activo' : 'Inactivo'; ?>
        </span>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo $baseUrl; ?>clientes/edit/<?php echo $cliente['id']; ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <form method="POST" action="<?php echo $baseUrl; ?>clientes/cambiarEstatus/<?php echo $cliente['id']; ?>" style="display: inline;">
                <button type="submit" class="btn btn-outline-<?php echo $cliente['activo'] ? 'warning' : 'success'; ?> btn-sm"
                        onclick="return confirm('¿Estás seguro de <?php echo $cliente['activo'] ? 'desactivar' : 'activar'; ?> este cliente?')">
                    <i class="bi bi-<?php echo $cliente['activo'] ? 'pause' : 'play'; ?>"></i> 
                    <?php echo $cliente['activo'] ? 'Desactivar' : 'Activar'; ?>
                </button>
            </form>
        </div>
        <a href="<?php echo $baseUrl; ?>clientes" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Información principal -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Tipo de Cliente:</strong>
                            <span class="badge bg-info ms-2"><?php echo ucfirst($cliente['tipo']); ?></span>
                        </div>
                        <div class="mb-3">
                            <strong>Nombre:</strong><br>
                            <?php echo htmlspecialchars($cliente['nombre']); ?>
                        </div>
                        <?php if (!empty($cliente['email'])): ?>
                        <div class="mb-3">
                            <strong>Email:</strong><br>
                            <a href="mailto:<?php echo htmlspecialchars($cliente['email']); ?>">
                                <?php echo htmlspecialchars($cliente['email']); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($cliente['telefono'])): ?>
                        <div class="mb-3">
                            <strong>Teléfono:</strong><br>
                            <a href="tel:<?php echo htmlspecialchars($cliente['telefono']); ?>">
                                <?php echo htmlspecialchars($cliente['telefono']); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <?php if (!empty($cliente['direccion'])): ?>
                        <div class="mb-3">
                            <strong>Dirección:</strong><br>
                            <?php echo nl2br(htmlspecialchars($cliente['direccion'])); ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($cliente['ciudad']) || !empty($cliente['estado'])): ?>
                        <div class="mb-3">
                            <strong>Ubicación:</strong><br>
                            <?php 
                            $ubicacion = [];
                            if (!empty($cliente['ciudad'])) $ubicacion[] = $cliente['ciudad'];
                            if (!empty($cliente['estado'])) $ubicacion[] = $cliente['estado'];
                            if (!empty($cliente['codigo_postal'])) $ubicacion[] = 'CP ' . $cliente['codigo_postal'];
                            echo htmlspecialchars(implode(', ', $ubicacion));
                            ?>
                        </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <strong>Registrado:</strong><br>
                            <?php echo date('d/m/Y H:i', strtotime($cliente['created_at'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($cliente['tipo'] === 'doctor'): ?>
        <!-- Información profesional para doctores -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Profesional</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php if (!empty($cliente['especialidad'])): ?>
                        <div class="mb-3">
                            <strong>Especialidad:</strong><br>
                            <?php echo htmlspecialchars($cliente['especialidad']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <?php if (!empty($cliente['cedula_profesional'])): ?>
                        <div class="mb-3">
                            <strong>Cédula Profesional:</strong><br>
                            <?php echo htmlspecialchars($cliente['cedula_profesional']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Información comercial -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Comercial</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Volumen de Compra:</strong><br>
                            <span class="badge 
                                <?php echo $cliente['volumen_compra'] === 'alto' ? 'bg-success' : 
                                    ($cliente['volumen_compra'] === 'medio' ? 'bg-warning' : 'bg-secondary'); ?>">
                                <?php echo ucfirst($cliente['volumen_compra']); ?>
                            </span>
                        </div>
                        <?php if (!empty($cliente['rfc'])): ?>
                        <div class="mb-3">
                            <strong>RFC:</strong><br>
                            <?php echo htmlspecialchars($cliente['rfc']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <?php if ($cliente['descuento_autorizado'] > 0): ?>
                        <div class="mb-3">
                            <strong>Descuento Autorizado:</strong><br>
                            <?php echo number_format($cliente['descuento_autorizado'], 2); ?>%
                        </div>
                        <?php endif; ?>
                        <?php if ($cliente['limite_credito'] > 0): ?>
                        <div class="mb-3">
                            <strong>Límite de Crédito:</strong><br>
                            $<?php echo number_format($cliente['limite_credito'], 2); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($cliente['dias_credito'] > 0): ?>
                        <div class="mb-3">
                            <strong>Días de Crédito:</strong><br>
                            <?php echo $cliente['dias_credito']; ?> días
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($cliente['notas'])): ?>
        <!-- Notas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Notas</h5>
            </div>
            <div class="card-body">
                <?php echo nl2br(htmlspecialchars($cliente['notas'])); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Panel lateral -->
    <div class="col-lg-4">
        <!-- Acciones rápidas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary btn-sm disabled">
                        <i class="bi bi-envelope"></i> Enviar Email
                    </a>
                    <a href="#" class="btn btn-outline-success btn-sm disabled">
                        <i class="bi bi-whatsapp"></i> Enviar WhatsApp
                    </a>
                    <a href="#" class="btn btn-outline-info btn-sm disabled">
                        <i class="bi bi-file-earmark-text"></i> Nueva Cotización
                    </a>
                    <a href="#" class="btn btn-outline-warning btn-sm disabled">
                        <i class="bi bi-calendar-event"></i> Agendar Cita
                    </a>
                </div>
                <small class="text-muted mt-2 d-block">Funciones disponibles en próximas versiones</small>
            </div>
        </div>
        
        <!-- Resumen de actividad -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Resumen de Actividad</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-primary mb-0">0</h5>
                            <small class="text-muted">Cotizaciones</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success mb-0">0</h5>
                        <small class="text-muted">Pedidos</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-info mb-0">0</h5>
                            <small class="text-muted">Comunicaciones</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-warning mb-0">0</h5>
                        <small class="text-muted">Oportunidades</small>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Datos disponibles cuando se implementen los módulos correspondientes</small>
            </div>
        </div>
    </div>
</div>