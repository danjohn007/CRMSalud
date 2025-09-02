<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="bi bi-tools"></i> Test del Sistema CRM Salud
                </h3>
            </div>
            <div class="card-body">
                <p class="mb-4">
                    Esta página verifica que el sistema esté configurado correctamente y todos los componentes funcionen adecuadamente.
                </p>
                
                <?php foreach ($tests as $test): ?>
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo htmlspecialchars($test['name']); ?></h5>
                        <span class="badge bg-<?php echo $test['status'] === 'success' ? 'success' : ($test['status'] === 'warning' ? 'warning' : 'danger'); ?>">
                            <?php 
                            $icon = $test['status'] === 'success' ? 'check-circle' : ($test['status'] === 'warning' ? 'exclamation-triangle' : 'x-circle');
                            $text = $test['status'] === 'success' ? 'OK' : ($test['status'] === 'warning' ? 'Advertencia' : 'Error');
                            ?>
                            <i class="bi bi-<?php echo $icon; ?>"></i> <?php echo $text; ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="mb-3"><?php echo htmlspecialchars($test['message']); ?></p>
                        
                        <?php if (!empty($test['details'])): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <tbody>
                                    <?php foreach ($test['details'] as $key => $value): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                        <td>
                                            <?php if (is_array($value)): ?>
                                                <?php echo implode(', ', array_map('htmlspecialchars', $value)); ?>
                                            <?php else: ?>
                                                <?php echo htmlspecialchars($value); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle"></i> Información del Sistema</h6>
                    <ul class="mb-0">
                        <li><strong>Versión de PHP:</strong> <?php echo PHP_VERSION; ?></li>
                        <li><strong>Versión del Sistema:</strong> <?php echo APP_VERSION; ?></li>
                        <li><strong>Zona Horaria:</strong> <?php echo date_default_timezone_get(); ?></li>
                        <li><strong>Fecha/Hora Actual:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
                        <li><strong>URL Base:</strong> <?php echo BASE_URL; ?></li>
                    </ul>
                </div>
                
                <div class="text-center">
                    <a href="<?php echo $baseUrl; ?>auth/login" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Ir al Sistema
                    </a>
                    <button onclick="location.reload()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Ejecutar Tests Nuevamente
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-database"></i> Creación de Base de Datos
                </h5>
            </div>
            <div class="card-body">
                <p>Si la conexión a la base de datos falló, es probable que necesites crear la base de datos y las tablas. Ejecuta los siguientes comandos SQL:</p>
                
                <div class="accordion" id="sqlAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="createDbHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createDbCollapse">
                                1. Crear Base de Datos
                            </button>
                        </h2>
                        <div id="createDbCollapse" class="accordion-collapse collapse" data-bs-parent="#sqlAccordion">
                            <div class="accordion-body">
                                <pre><code>CREATE DATABASE IF NOT EXISTS crm_salud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="createTablesHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createTablesCollapse">
                                2. Crear Tablas
                            </button>
                        </h2>
                        <div id="createTablesCollapse" class="accordion-collapse collapse" data-bs-parent="#sqlAccordion">
                            <div class="accordion-body">
                                <p>Las tablas se crearán automáticamente cuando ejecutes el archivo <code>install.sql</code> que está incluido en el sistema.</p>
                                <a href="<?php echo $baseUrl; ?>install.sql" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="bi bi-download"></i> Descargar install.sql
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>