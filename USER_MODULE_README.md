# Módulo de Usuarios - CRM Salud

## Funcionalidades Implementadas

### 🔧 Corrección de Errores
- **Error SQL corregido**: Se solucionó el error `SQLSTATE[HY093]: Invalid parameter number` en el buscador de clientes
- **Parámetros únicos**: Cada parámetro en las consultas SQL ahora tiene un nombre único

### 👤 Gestión de Usuarios (Administradores)
- **Lista de usuarios**: Ver todos los usuarios del sistema con información completa
- **Crear usuario**: Formulario completo para crear nuevos usuarios
- **Editar usuario**: Modificar información de usuarios existentes
- **Ver detalles**: Vista detallada de información de usuario
- **Activar/Desactivar**: Gestión del estado de usuarios

### 🎭 Perfil de Usuario (Todos los usuarios)
- **Ver perfil**: Página de perfil personal con toda la información
- **Editar perfil**: Formulario para actualizar información personal
- **Cambio de contraseña**: Función segura para cambiar contraseña
- **Imagen de perfil**: Subida y gestión de foto de perfil

### 📱 Nuevos Campos Implementados
- **Teléfono**: Campo opcional para número telefónico
- **Dirección**: Campo opcional para dirección física
- **Imagen de perfil**: Subida de imagen con validaciones

## Instalación

### 1. Migración de Base de Datos
Ejecutar el script de migración para agregar los nuevos campos:

```sql
-- Ejecutar en MySQL
mysql -u usuario -p nombre_bd < migrations/001_add_user_profile_fields.sql
```

### 2. Configuración de Directorios
El directorio `uploads/profiles/` debe tener permisos de escritura:

```bash
chmod 755 uploads/
chmod 755 uploads/profiles/
```

### 3. Configuración de Permisos
Asegurar que el servidor web puede escribir en el directorio de uploads:

```bash
chown -R www-data:www-data uploads/
```

## Uso

### Para Administradores

#### Gestionar Usuarios
1. Ir a **Usuarios** en el menú principal
2. Ver lista de todos los usuarios
3. Crear nuevos usuarios con el botón "Nuevo Usuario"
4. Editar usuarios existentes
5. Activar/desactivar usuarios según sea necesario

### Para Todos los Usuarios

#### Actualizar Perfil
1. Ir a **Mi Perfil** en el menú
2. Hacer clic en "Editar Perfil"
3. Actualizar información personal:
   - Nombre
   - Email
   - Teléfono (opcional)
   - Dirección (opcional)
   - Imagen de perfil (opcional)

#### Cambiar Contraseña
1. En la página "Editar Perfil"
2. Llenar los campos de contraseña:
   - Contraseña actual
   - Nueva contraseña (mínimo 6 caracteres)
   - Confirmar nueva contraseña
3. Guardar cambios

## Validaciones y Seguridad

### Imágenes de Perfil
- **Formatos permitidos**: JPG, PNG, GIF
- **Tamaño máximo**: 5MB (configurable en `MAX_UPLOAD_SIZE`)
- **Nombres únicos**: Prevención de conflictos con nombres únicos
- **Eliminación automática**: Las imágenes anteriores se eliminan al subir una nueva

### Contraseñas
- **Mínimo 6 caracteres**: Validación de longitud
- **Hash seguro**: Uso de `password_hash()` con `PASSWORD_DEFAULT`
- **Verificación actual**: Requerida para cambios de contraseña
- **Confirmación**: Doble verificación en formularios

### Validación de Datos
- **Email válido**: Verificación con `filter_var()`
- **Campos requeridos**: Validación de campos obligatorios
- **Sanitización**: Limpieza de datos de entrada
- **Prevención de duplicados**: Verificación de emails únicos

## Compatibilidad

### Base de Datos
- ✅ **MySQL 5.7+**: Completamente compatible
- ✅ **MySQL 8.0+**: Completamente compatible
- ❌ **SQLite**: No compatible (según requerimientos)

### PHP
- ✅ **PHP 7.4+**: Compatible
- ✅ **PHP 8.0+**: Compatible
- ✅ **PHP 8.3**: Testado y funcionando

## Archivos Nuevos/Modificados

### Controladores
- `controllers/UsuariosController.php` - Gestión completa de usuarios
- `controllers/PerfilController.php` - Gestión de perfil personal

### Modelos
- `models/User.php` - Funcionalidades extendidas de usuario
- `models/Cliente.php` - Corrección de error SQL

### Vistas
- `views/usuarios/index.php` - Lista de usuarios
- `views/usuarios/create.php` - Crear usuario
- `views/usuarios/view.php` - Ver usuario
- `views/usuarios/edit.php` - Editar usuario
- `views/perfil/index.php` - Ver perfil personal
- `views/perfil/editar.php` - Editar perfil personal

### Base de Datos
- `migrations/001_add_user_profile_fields.sql` - Migración para nuevos campos

### Configuración
- `.gitignore` - Exclusión de archivos innecesarios
- `uploads/profiles/.gitkeep` - Preservación de estructura

## Solución de Problemas

### Error "No such file or directory"
Si aparece error de conexión a base de datos, verificar:
1. Configuración en `config/config.php`
2. Servidor MySQL ejecutándose
3. Credenciales correctas

### Error de permisos en uploads
Si no se pueden subir imágenes:
```bash
chmod 755 uploads/profiles/
chown www-data:www-data uploads/profiles/
```

### Error de migración
Si falla la migración:
1. Verificar que la tabla `usuarios` existe
2. Hacer backup antes de ejecutar
3. Ejecutar manualmente campo por campo si es necesario

## Mantenimiento

### Limpieza de Imágenes
Crear un script de limpieza periódica para eliminar imágenes huérfanas:

```sql
-- Buscar imágenes sin usuario asociado
SELECT imagen_perfil FROM usuarios WHERE imagen_perfil IS NOT NULL;
```

### Logs de Acceso
Monitorear la tabla `usuarios.ultimo_acceso` para detectar cuentas inactivas.

### Respaldos
Incluir el directorio `uploads/profiles/` en los respaldos regulares del sistema.