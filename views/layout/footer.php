    <?php if (isset($currentUser) && $currentUser): ?>
            </main>
        </div>
    </div>
    <?php else: ?>
        </div>
    <?php endif; ?>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript personalizado -->
    <script src="<?php echo $assetsUrl; ?>js/app.js"></script>
    
    <script>
        // Activar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    var alertInstance = new bootstrap.Alert(alert);
                    alertInstance.close();
                }
            });
        }, 5000);
        
        // Confirmar eliminaciones
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-delete') || e.target.closest('.btn-delete')) {
                if (!confirm('¿Está seguro de que desea eliminar este elemento?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
    
    <!-- Scripts adicionales específicos de la página -->
    <?php if (isset($additionalScripts)): ?>
        <?php echo $additionalScripts; ?>
    <?php endif; ?>
    
</body>
</html>