<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-download"></i> Exportar
            </button>
        </div>
        <button type="button" class="btn btn-sm btn-primary">
            <i class="bi bi-plus"></i> Nuevo
        </button>
    </div>
</div>

<!-- Estadísticas principales -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Clientes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['total_clientes']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Ventas del Mes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            $<?php echo number_format($stats['ventas_mes'], 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-currency-dollar text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-info">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Oportunidades
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $stats['oportunidades_abiertas']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-target text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Stock Bajo
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $stats['productos_stock_bajo']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficas y contenido adicional -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ventas por Mes</h5>
            </div>
            <div class="card-body">
                <canvas id="ventasChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribución de Clientes</h5>
            </div>
            <div class="card-body">
                <canvas id="clientesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actividades Recientes</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-person-plus text-success"></i>
                            Nuevo cliente registrado: Dr. Juan Pérez
                        </div>
                        <small class="text-muted">Hace 2 horas</small>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-cart-check text-primary"></i>
                            Pedido #1234 completado
                        </div>
                        <small class="text-muted">Hace 4 horas</small>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-exclamation-triangle text-warning"></i>
                            Stock bajo en producto ABC-123
                        </div>
                        <small class="text-muted">Hace 6 horas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Próximos Vencimientos</h5>
            </div>
            <div class="card-body">
                <?php if ($stats['productos_por_vencer'] > 0): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    Tienes <?php echo $stats['productos_por_vencer']; ?> productos que vencen en los próximos 30 días.
                    <a href="<?php echo $baseUrl; ?>inventarios?filter=vencimiento" class="alert-link">Ver detalles</a>
                </div>
                <?php else: ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    No hay productos próximos a vencer en los próximos 30 días.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de ventas
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ventasCtx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Ventas',
                data: [12000, 19000, 15000, 25000, 22000, 30000],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    // Gráfica de clientes
    const clientesCtx = document.getElementById('clientesChart').getContext('2d');
    new Chart(clientesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Doctores', 'Farmacias', 'Hospitales'],
            datasets: [{
                data: [45, 35, 20],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 99, 132)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>