<div class="text-center">
    <div class="error-code">404</div>
    <h1 class="error-title">Página no encontrada</h1>
    <p class="error-description">
        Lo sentimos, la página que buscas no existe o ha sido movida.
    </p>
    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
        <i class="bi bi-house"></i> Volver al inicio
    </a>
</div>

<style>
.error-code {
    font-size: 8rem;
    font-weight: bold;
    color: var(--bs-primary);
    opacity: 0.8;
}

.error-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.error-description {
    font-size: 1.1rem;
    color: var(--bs-secondary);
    margin-bottom: 2rem;
}
</style>