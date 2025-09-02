/**
 * CRM Salud - JavaScript Application
 * Funcionalidades generales del sistema
 */

// Objeto principal de la aplicación
const CRMSalud = {
    
    // Configuración
    config: {
        baseUrl: window.location.origin + window.location.pathname.replace('/index.php', '').replace(/\/$/, '') + '/',
        apiUrl: window.location.origin + window.location.pathname.replace('/index.php', '').replace(/\/$/, '') + '/api/',
        timeout: 30000
    },
    
    // Inicialización de la aplicación
    init: function() {
        this.initTooltips();
        this.initConfirmDialogs();
        this.initFormValidation();
        this.initDataTables();
        this.initDatePickers();
        this.initSelectSearch();
        this.initFileUploads();
        this.initSidebar();
        this.initNotifications();
        
        console.log('CRM Salud inicializado correctamente');
    },
    
    // Inicializar tooltips de Bootstrap
    initTooltips: function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    
    // Inicializar diálogos de confirmación
    initConfirmDialogs: function() {
        document.addEventListener('click', function(e) {
            const target = e.target.closest('[data-confirm]');
            if (target) {
                e.preventDefault();
                const message = target.getAttribute('data-confirm') || '¿Está seguro de realizar esta acción?';
                
                if (confirm(message)) {
                    if (target.href) {
                        window.location.href = target.href;
                    } else if (target.tagName === 'BUTTON' && target.form) {
                        target.form.submit();
                    }
                }
            }
        });
    },
    
    // Validación de formularios
    initFormValidation: function() {
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
        
        // Validación en tiempo real para campos específicos
        this.setupRealtimeValidation();
    },
    
    // Configurar validación en tiempo real
    setupRealtimeValidation: function() {
        // Validación de email
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value && !this.checkValidity()) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else if (this.value) {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
            });
        });
        
        // Validación de teléfonos
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Solo permitir números, espacios, guiones y paréntesis
                this.value = this.value.replace(/[^0-9\s\-\(\)]/g, '');
            });
        });
        
        // Validación de números
        const numberInputs = document.querySelectorAll('input[type="number"]');
        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.min && parseFloat(this.value) < parseFloat(this.min)) {
                    this.setCustomValidity(`El valor mínimo es ${this.min}`);
                } else if (this.max && parseFloat(this.value) > parseFloat(this.max)) {
                    this.setCustomValidity(`El valor máximo es ${this.max}`);
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    },
    
    // Inicializar DataTables
    initDataTables: function() {
        if (typeof $.fn.DataTable !== 'undefined') {
            $('.data-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']],
                columnDefs: [
                    {
                        targets: 'no-sort',
                        orderable: false
                    }
                ]
            });
        }
    },
    
    // Inicializar selectores de fecha
    initDatePickers: function() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            if (!input.value && input.hasAttribute('data-default-today')) {
                input.value = new Date().toISOString().split('T')[0];
            }
        });
    },
    
    // Inicializar búsqueda en selects
    initSelectSearch: function() {
        if (typeof Choices !== 'undefined') {
            const selectElements = document.querySelectorAll('.select-search');
            selectElements.forEach(select => {
                new Choices(select, {
                    searchEnabled: true,
                    itemSelectText: '',
                    noResultsText: 'No se encontraron resultados',
                    noChoicesText: 'No hay opciones disponibles'
                });
            });
        }
    },
    
    // Inicializar carga de archivos
    initFileUploads: function() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Validar tamaño
                    const maxSize = this.getAttribute('data-max-size') || 5242880; // 5MB por defecto
                    if (file.size > maxSize) {
                        alert('El archivo es demasiado grande. Tamaño máximo: ' + (maxSize / 1024 / 1024) + 'MB');
                        this.value = '';
                        return;
                    }
                    
                    // Validar tipo
                    const allowedTypes = this.getAttribute('data-allowed-types');
                    if (allowedTypes) {
                        const types = allowedTypes.split(',');
                        const fileType = file.type;
                        const fileExtension = file.name.split('.').pop().toLowerCase();
                        
                        if (!types.includes(fileType) && !types.includes('.' + fileExtension)) {
                            alert('Tipo de archivo no permitido');
                            this.value = '';
                            return;
                        }
                    }
                    
                    // Mostrar preview para imágenes
                    if (file.type.startsWith('image/')) {
                        this.showImagePreview(file);
                    }
                }
            });
        });
    },
    
    // Mostrar preview de imagen
    showImagePreview: function(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.querySelector('.image-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.className = 'image-preview mt-2';
                preview.style.maxWidth = '200px';
                preview.style.maxHeight = '200px';
                preview.style.borderRadius = '8px';
                this.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        }.bind(this);
        reader.readAsDataURL(file);
    },
    
    // Inicializar sidebar responsive
    initSidebar: function() {
        const sidebarToggle = document.querySelector('[data-bs-toggle="sidebar"]');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
            
            // Cerrar sidebar al hacer clic fuera en móviles
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        }
    },
    
    // Sistema de notificaciones
    initNotifications: function() {
        // Auto-ocultar alertas después de 5 segundos
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            setTimeout(() => {
                const alertInstance = new bootstrap.Alert(alert);
                alertInstance.close();
            }, 5000);
        });
    },
    
    // Utilidades
    utils: {
        
        // Formatear números como moneda
        formatCurrency: function(amount, currency = 'MXN') {
            return new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: currency
            }).format(amount);
        },
        
        // Formatear fechas
        formatDate: function(date, options = {}) {
            const defaultOptions = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const formatOptions = { ...defaultOptions, ...options };
            
            return new Intl.DateTimeFormat('es-MX', formatOptions).format(new Date(date));
        },
        
        // Formatear números
        formatNumber: function(number, decimals = 0) {
            return new Intl.NumberFormat('es-MX', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(number);
        },
        
        // Debounce function
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        // Mostrar loading en botón
        showButtonLoading: function(button, text = 'Cargando...') {
            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${text}`;
        },
        
        // Restaurar botón
        restoreButton: function(button, originalText) {
            button.disabled = false;
            button.innerHTML = originalText;
        },
        
        // Copiar al portapapeles
        copyToClipboard: function(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.showToast('Copiado al portapapeles', 'success');
            }).catch(() => {
                // Fallback para navegadores más antiguos
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                this.showToast('Copiado al portapapeles', 'success');
            });
        },
        
        // Mostrar toast notification
        showToast: function(message, type = 'info') {
            // Crear container si no existe
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }
            
            // Crear toast
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Mostrar toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remover del DOM después de ocultarse
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }
    },
    
    // API wrapper
    api: {
        
        // Realizar petición GET
        get: function(endpoint, params = {}) {
            const url = new URL(CRMSalud.config.apiUrl + endpoint);
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
            
            return fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json());
        },
        
        // Realizar petición POST
        post: function(endpoint, data = {}) {
            return fetch(CRMSalud.config.apiUrl + endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            }).then(response => response.json());
        },
        
        // Realizar petición PUT
        put: function(endpoint, data = {}) {
            return fetch(CRMSalud.config.apiUrl + endpoint, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            }).then(response => response.json());
        },
        
        // Realizar petición DELETE
        delete: function(endpoint) {
            return fetch(CRMSalud.config.apiUrl + endpoint, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json());
        }
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    CRMSalud.init();
});

// Exponer CRMSalud globalmente
window.CRMSalud = CRMSalud;