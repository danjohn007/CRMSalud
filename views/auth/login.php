<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bi bi-hospital" style="font-size: 3rem; color: var(--bs-primary);"></i>
                    <h3 class="mt-2">CRM Salud</h3>
                    <p class="text-muted">Sistema especializado en el sector salud</p>
                </div>
                
                <form method="POST" id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">
                            Recordarme
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </button>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <small class="text-muted">
                        ¿Olvidaste tu contraseña? 
                        <a href="#" class="text-decoration-none">Recuperar</a>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                © <?php echo date('Y'); ?> CRM Salud. Todos los derechos reservados.
            </small>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    // Validación en tiempo real
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    emailInput.addEventListener('blur', function() {
        if (this.value && !validateEmail(this.value)) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        }
    });
    
    passwordInput.addEventListener('blur', function() {
        if (this.value && this.value.length < 6) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        }
    });
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (!emailInput.value || !validateEmail(emailInput.value)) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!passwordInput.value || passwordInput.value.length < 6) {
            passwordInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
});
</script>