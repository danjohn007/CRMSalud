<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-boxes"></i> <?php echo $title; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-filter"></i> Filtrar
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo $baseUrl; ?>inventarios">Todos los inventarios</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php echo $baseUrl; ?>inventarios?filter=stock_bajo">Stock Bajo</a></li>
                <li><a class="dropdown-item" href="<?php echo $baseUrl; ?>inventarios?filter=vencimiento">Próximos a Vencer</a></li>
            </ul>
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

<!-- Tabla de Inventarios -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($inventarios)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Fecha Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventarios as $inventario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inventario['producto_nombre'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($inventario['sku'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo ($inventario['stock_actual'] <= ($inventario['stock_minimo'] ?? 0)) ? 'danger' : 'success'; ?>">
                                        <?php echo $inventario['stock_actual'] ?? 0; ?>
                                    </span>
                                </td>
                                <td><?php echo $inventario['stock_minimo'] ?? 0; ?></td>
                                <td>
                                    <?php if (!empty($inventario['fecha_vencimiento'])): ?>
                                        <?php 
                                        $fechaVencimiento = new DateTime($inventario['fecha_vencimiento']);
                                        $hoy = new DateTime();
                                        $diferencia = $hoy->diff($fechaVencimiento);
                                        $diasRestantes = $diferencia->days;
                                        $vencido = $fechaVencimiento < $hoy;
                                        ?>
                                        <span class="badge bg-<?php echo $vencido ? 'danger' : ($diasRestantes <= 30 ? 'warning' : 'info'); ?>">
                                            <?php echo $fechaVencimiento->format('d/m/Y'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo $baseUrl; ?>inventarios/show/<?php echo $inventario['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-boxes display-1 text-muted"></i>
                <h4 class="mt-3">No hay registros de inventario</h4>
                <p class="text-muted">No se encontraron registros para mostrar.</p>
            </div>
        <?php endif; ?>
    </div>
</div>