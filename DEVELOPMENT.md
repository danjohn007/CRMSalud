# CRM Salud - Guía de Desarrollo

## Estructura del Proyecto

El proyecto sigue una arquitectura MVC (Model-View-Controller) pura en PHP sin frameworks externos.

### Directorios Principales

```
CRMSalud/
├── assets/                 # Archivos estáticos (CSS, JS, imágenes)
├── config/                 # Configuración del sistema
├── controllers/            # Controladores MVC
├── core/                   # Clases base del framework
├── models/                 # Modelos de datos
├── views/                  # Plantillas HTML
├── uploads/                # Archivos subidos por usuarios
├── .htaccess              # Configuración Apache
├── index.php              # Punto de entrada
├── install.sql            # Script de instalación de BD
└── README.md              # Documentación principal
```

## Convenciones de Código

### Nomenclatura
- **Clases**: PascalCase (ej: `UserController`, `ClienteModel`)
- **Métodos**: camelCase (ej: `getUserById`, `createClient`)
- **Variables**: camelCase (ej: `$userName`, `$clienteData`)
- **Constantes**: UPPER_SNAKE_CASE (ej: `DB_HOST`, `BASE_URL`)
- **Archivos**: PascalCase para clases, lowercase para otros

### Estructura de Controladores

```php
<?php
class MiController extends BaseController {
    
    public function __construct() {
        parent::__construct();
        // Inicialización específica
    }
    
    public function index() {
        // Listar elementos
    }
    
    public function create() {
        // Mostrar formulario / Crear elemento
    }
    
    public function view($id) {
        // Mostrar detalle
    }
    
    public function edit($id) {
        // Editar elemento
    }
    
    public function delete($id) {
        // Eliminar elemento
    }
}
?>
```

### Estructura de Modelos

```php
<?php
class MiModel extends BaseModel {
    protected $table = 'mi_tabla';
    
    public function metodosEspecificos() {
        // Métodos específicos del modelo
    }
}
?>
```

## Sistema de Rutas

Las rutas siguen el patrón: `controlador/accion/parametros`

Ejemplos:
- `/` → `HomeController::index()`
- `/clientes` → `ClientesController::index()`
- `/clientes/create` → `ClientesController::create()`
- `/clientes/view/123` → `ClientesController::view(123)`

## Base de Datos

### Convenciones
- Nombres de tablas en plural y minúsculas
- Campos `id` como clave primaria autoincremental
- Campos `created_at` y `updated_at` para auditoría
- Campo `activo` para soft deletes
- Relaciones usando `_id` (ej: `cliente_id`, `producto_id`)

### Migraciones
Para modificar la estructura de la base de datos:
1. Crear script SQL en directorio `migrations/`
2. Ejecutar manualmente en el servidor
3. Documentar cambios en changelog

## Sistema de Autenticación

### Roles del Sistema
- **admin**: Acceso completo
- **vendedor**: Clientes, ventas, oportunidades
- **marketing**: Campañas, comunicación
- **inventarios**: Productos, stock

### Verificación de Permisos
```php
// En controladores
if (!$this->hasPermission('admin')) {
    $this->flash('error', 'Sin permisos');
    $this->redirect('dashboard');
}
```

## Vistas y Plantillas

### Estructura de Vistas
```php
// En controlador
$this->view('carpeta/archivo', [
    'title' => 'Título de la página',
    'data' => $datos,
    'flashMessages' => $this->getFlashMessages()
]);
```

### Variables Disponibles en Vistas
- `$currentUser`: Usuario actual
- `$baseUrl`: URL base del sistema
- `$assetsUrl`: URL de assets
- Variables pasadas desde el controlador

## JavaScript y Frontend

### Objeto Principal
```javascript
CRMSalud.init(); // Inicializar sistema

// Utilities
CRMSalud.utils.formatCurrency(1000); // $1,000.00
CRMSalud.utils.showToast('Mensaje', 'success');

// API calls
CRMSalud.api.get('endpoint').then(data => {
    // Manejar respuesta
});
```

### Validaciones
- Validación en tiempo real con JavaScript
- Validación servidor en controladores
- Mensajes de error consistentes

## Desarrollo de Nuevos Módulos

### 1. Crear Modelo
```php
// models/NuevoModelo.php
class NuevoModelo extends BaseModel {
    protected $table = 'nueva_tabla';
}
```

### 2. Crear Controlador
```php
// controllers/NuevoController.php  
class NuevoController extends BaseController {
    // Implementar métodos CRUD
}
```

### 3. Crear Vistas
```
views/nuevo/
├── index.php
├── create.php
├── view.php
└── edit.php
```

### 4. Agregar Navegación
Modificar `views/layout/header.php` para incluir enlace en sidebar.

### 5. Configurar Permisos
Actualizar `BaseController::checkAuth()` si es necesario.

## Testing

### Test de Sistema
Visitar `/test` para verificar:
- Conexión a base de datos
- Configuración de URLs
- Permisos de directorios
- Módulos PHP requeridos

### Test Manual
1. Crear usuarios de prueba
2. Verificar flujos principales
3. Probar validaciones
4. Verificar responsive design

## Despliegue

### Ambiente de Desarrollo
```bash
php -S localhost:8000
```

### Ambiente de Producción
1. Subir archivos al servidor
2. Configurar virtual host Apache
3. Importar base de datos
4. Ajustar configuración en `config/config.php`
5. Configurar permisos de directorios
6. Habilitar HTTPS
7. Configurar backups

### Lista de Verificación
- [ ] Base de datos configurada
- [ ] Archivos subidos
- [ ] Permisos configurados
- [ ] .htaccess funcionando
- [ ] URL base correcta
- [ ] Email configurado (si aplica)
- [ ] Backups programados
- [ ] Monitoreo configurado

## Mantenimiento

### Logs
- Logs de PHP en `/tmp/` o configuración del servidor
- Logs de errores SQL en `error_log()`
- Logs de aplicación usando `error_log()`

### Backups
```sql
-- Backup de base de datos
mysqldump -u usuario -p crm_salud > backup_crm_salud_$(date +%Y%m%d).sql

-- Restaurar backup
mysql -u usuario -p crm_salud < backup_crm_salud_20240101.sql
```

### Actualizaciones
1. Backup completo
2. Subir nuevos archivos
3. Ejecutar migraciones de BD
4. Verificar funcionamiento
5. Documentar cambios

## Mejores Prácticas

### Seguridad
- Nunca hardcodear credenciales
- Validar todas las entradas
- Usar prepared statements
- Sanitizar salidas HTML
- Implementar rate limiting
- Configurar headers de seguridad

### Performance
- Usar índices en BD apropiadamente
- Implementar paginación
- Optimizar consultas SQL
- Comprimir assets
- Usar caché cuando sea apropiado

### Código
- Seguir convenciones de nomenclatura
- Comentar código complejo
- Usar try-catch para errores
- Implementar logging apropiado
- Escribir código autodocumentado

## Troubleshooting Común

### Error 404 en URLs
- Verificar mod_rewrite habilitado
- Verificar .htaccess presente
- Verificar permisos de archivo

### Error de Conexión BD
- Verificar credenciales en config.php
- Verificar servidor MySQL activo
- Verificar permisos de usuario BD

### Errores de Permisos
```bash
chmod 755 directorio/
chmod 644 archivo.php
chown www-data:www-data directorio/
```

### Session Warnings
- Verificar session_start() solo una vez
- Configurar sesiones antes de iniciar

## Contacto y Soporte

Para dudas sobre desarrollo:
- Revisar esta documentación
- Consultar código existente como ejemplo
- Verificar logs de errores
- Contactar al equipo de desarrollo