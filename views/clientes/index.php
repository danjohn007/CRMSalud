<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-people"></i> Gestión de Clientes
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-download"></i> Exportar
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-filter"></i> Filtrar
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo $baseUrl; ?>clientes">Todos los clientes</a></li>
                <li><hr class="dropdown-divider"></li>
                <?php foreach ($tiposCliente as $key => $label): ?>
                <li><a class="dropdown-item" href="<?php echo $baseUrl; ?>clientes?tipo=<?php echo $key; ?>">
                    <?php echo $label; ?>s
                </a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <a href="<?php echo $baseUrl; ?>clientes/create" class="btn btn-sm btn-primary">
            <i class="bi bi-plus"></i> Nuevo Cliente
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Clientes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['total']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-info">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Doctores
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['doctor'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-badge text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Farmacias
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['farmacia'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-building text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Hospitales
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['hospital'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-hospital text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar cliente</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Nombre, email o teléfono..." 
                           value="<?php echo htmlspecialchars($currentFilters['search']); ?>">
                </div>
            </div>
            
            <div class="col-md-3">
                <label for="tipo" class="form-label">Tipo de cliente</label>
                <select class="form-select" id="tipo" name="tipo">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($tiposCliente as $key => $label): ?>
                    <option value="<?php echo $key; ?>" <?php echo $currentFilters['tipo'] === $key ? 'selected' : ''; ?>>
                        <?php echo $label; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="<?php echo $baseUrl; ?>clientes" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de clientes -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            Lista de Clientes 
            <span class="badge bg-secondary"><?php echo number_format($clientes['total']); ?></span>
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($clientes['items'])): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <th>Volumen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes['items'] as $cliente): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-2">
                                    <?php 
                                    $icon = $cliente['tipo'] === 'doctor' ? 'person-badge' : 
                                           ($cliente['tipo'] === 'farmacia' ? 'building' : 'hospital');
                                    ?>
                                    <i class="bi bi-<?php echo $icon; ?> text-primary"></i>
                                </div>
                                <div>
                                    <strong><?php echo htmlspecialchars($cliente['nombre']); ?></strong>
                                    <?php if ($cliente['especialidad']): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($cliente['especialidad']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $cliente['tipo'] === 'doctor' ? 'primary' : ($cliente['tipo'] === 'farmacia' ? 'success' : 'warning'); ?>">
                                <?php echo $tiposCliente[$cliente['tipo']]; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($cliente['email']): ?>
                            <a href="mailto:<?php echo htmlspecialchars($cliente['email']); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($cliente['email']); ?>
                            </a>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($cliente['telefono']): ?>
                            <a href="tel:<?php echo htmlspecialchars($cliente['telefono']); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($cliente['telefono']); ?>
                            </a>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($cliente['ciudad'] ?? '-'); ?></td>
                        <td>
                            <?php 
                            $volumenColors = ['bajo' => 'secondary', 'medio' => 'warning', 'alto' => 'success'];
                            $volumenColor = $volumenColors[$cliente['volumen_compra']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $volumenColor; ?>">
                                <?php echo ucfirst($cliente['volumen_compra']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo $baseUrl; ?>clientes/show/<?php echo $cliente['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo $baseUrl; ?>clientes/edit/<?php echo $cliente['id']; ?>" 
                                   class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?php echo $baseUrl; ?>comunicacion/create?cliente_id=<?php echo $cliente['id']; ?>" 
                                   class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Contactar">
                                    <i class="bi bi-chat-dots"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <?php if ($clientes['totalPages'] > 1): ?>
        <nav aria-label="Paginación de clientes" class="mt-3">
            <ul class="pagination justify-content-center">
                <?php if ($clientes['page'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $clientes['page'] - 1; ?>&<?php echo http_build_query($currentFilters); ?>">
                        Anterior
                    </a>
                </li>
                <?php endif; ?>
                
                <?php for ($i = max(1, $clientes['page'] - 2); $i <= min($clientes['totalPages'], $clientes['page'] + 2); $i++): ?>
                <li class="page-item <?php echo $i == $clientes['page'] ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&<?php echo http_build_query($currentFilters); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>
                
                <?php if ($clientes['page'] < $clientes['totalPages']): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $clientes['page'] + 1; ?>&<?php echo http_build_query($currentFilters); ?>">
                        Siguiente
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-people" style="font-size: 4rem; color: #dee2e6;"></i>
            <h4 class="mt-3 text-muted">No se encontraron clientes</h4>
            <p class="text-muted">
                <?php if (!empty($currentFilters['search']) || !empty($currentFilters['tipo'])): ?>
                    No hay clientes que coincidan con los filtros aplicados.
                <?php else: ?>
                    Aún no tienes clientes registrados en el sistema.
                <?php endif; ?>
            </p>
            <a href="<?php echo $baseUrl; ?>clientes/create" class="btn btn-primary">
                <i class="bi bi-plus"></i> Registrar Primer Cliente
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>