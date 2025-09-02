<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-pencil"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $baseUrl; ?>reportes/view/<?php echo $id; ?>" class="btn btn-sm btn-outline-secondary">
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
                <h5 class="mb-0">Editar Reporte</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl; ?>reportes/update/<?php echo $id; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Reporte *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="Reporte de Ventas - <?php echo date('F Y'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Reporte *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="ventas" selected>Reporte de Ventas</option>
                                    <option value="inventario">Reporte de Inventario</option>
                                    <option value="clientes">Reporte de Clientes</option>
                                    <option value="marketing">Reporte de Marketing</option>
                                    <option value="financiero">Reporte Financiero</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-01'); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-t'); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="formato" class="form-label">Formato de Salida</label>
                                <select class="form-select" id="formato" name="formato">
                                    <option value="pdf" selected>PDF</option>
                                    <option value="excel">Excel</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3">Reporte mensual de ventas con análisis detallado de productos y clientes.</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Filtros Adicionales</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="incluir_graficos" name="incluir_graficos" checked>
                                    <label class="form-check-label" for="incluir_graficos">
                                        Incluir gráficos
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agrupar_por_mes" name="agrupar_por_mes">
                                    <label class="form-check-label" for="agrupar_por_mes">
                                        Agrupar por mes
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Actualizar Reporte
                        </button>
                        <a href="<?php echo $baseUrl; ?>reportes/view/<?php echo $id; ?>" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>