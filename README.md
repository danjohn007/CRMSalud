# CRM Salud - Sistema CRM Especializado en el Sector Salud

Un sistema CRM completo diseñado específicamente para empresas del sector salud, permitiendo gestionar relaciones comerciales con doctores, farmacias y hospitales de manera eficiente.

## 🚀 Características Principales

### Gestión de Clientes
- **Doctores**: Registro con especialidad, cédula profesional, y datos específicos
- **Farmacias**: Control de ubicación, volumen de compra y términos comerciales
- **Hospitales**: Gestión de contratos y licitaciones especializadas
- **Segmentación**: Por tipo, ubicación, especialidad y volumen de compra

### Catálogo de Productos
- Control de SKU, lotes y fechas de vencimiento
- Múltiples listas de precios por segmento de cliente
- Gestión de productos controlados y que requieren receta
- Información detallada: principio activo, presentación, marca

### Inventarios Inteligentes
- Control de stock por lote y fecha de vencimiento
- Alertas automáticas por stock mínimo
- Notificaciones de productos próximos a vencer
- Trazabilidad completa de movimientos

### Pipeline de Ventas
- Gestión de oportunidades con estados personalizables
- Conversión automática a cotizaciones y pedidos
- Flujo de aprobaciones para descuentos
- Seguimiento de probabilidades de cierre

### Marketing Segmentado
- Creación de segmentos dinámicos de clientes
- Campañas multicanal (Email, WhatsApp, SMS)
- Métricas de apertura, clics y conversión
- A/B testing básico

### Comunicación Multicanal
- Registro de todas las interacciones
- Plantillas reutilizables para comunicaciones
- Programación de seguimientos
- Historial completo por cliente

### Reportes y Analytics
- Dashboard con KPIs en tiempo real
- Gráficas interactivas con Chart.js
- Reportes de ventas, inventario y marketing
- Exportación en múltiples formatos

### Calendario Integrado
- Agenda de visitas y actividades
- Integración con FullCalendar.js
- Recordatorios automáticos
- Sincronización con oportunidades

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7+ (sin frameworks)
- **Base de Datos**: MySQL 5.7+
- **Arquitectura**: MVC (Model-View-Controller)
- **Frontend**: HTML5 + CSS3 + JavaScript
- **CSS Framework**: Bootstrap 5
- **Gráficas**: Chart.js
- **Calendario**: FullCalendar.js
- **Autenticación**: Sesiones PHP + password_hash()
- **Servidor Web**: Apache 2.4+ con mod_rewrite

## 📋 Requisitos del Sistema

### Servidor
- **PHP**: 7.4 o superior
- **MySQL**: 5.7 o superior
- **Apache**: 2.4+ con mod_rewrite habilitado
- **Extensiones PHP requeridas**:
  - PDO y PDO_MySQL
  - Session
  - JSON
  - MBString
  - GD (para manejo de imágenes)

### Navegador
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## 📦 Instalación

### 1. Descargar el Sistema
```bash
git clone https://github.com/danjohn007/CRMSalud.git
cd CRMSalud
```

### 2. Configurar el Servidor Web

#### Apache Virtual Host (Recomendado)
```apache
<VirtualHost *:80>
    ServerName crmsalud.local
    DocumentRoot /path/to/CRMSalud
    
    <Directory /path/to/CRMSalud>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### O simplemente copiar en el directorio web
```bash
# Para XAMPP/WAMP
cp -r CRMSalud /xampp/htdocs/

# Para LAMP
cp -r CRMSalud /var/www/html/
```

### 3. Configurar la Base de Datos

#### 3.1 Crear la Base de Datos
```sql
CREATE DATABASE crm_salud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 3.2 Importar la Estructura y Datos
```bash
mysql -u username -p crm_salud < install.sql
```

#### 3.3 Configurar Credenciales
Editar `config/config.php` y actualizar las credenciales de la base de datos:

```php
// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'crm_salud');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Configurar Permisos
```bash
# Dar permisos de escritura al directorio uploads
chmod 755 uploads/
chown www-data:www-data uploads/ # En sistemas Linux
```

### 5. Verificar la Instalación

Visitar: `http://tu-dominio/test` para ejecutar las pruebas del sistema.

## 🔐 Acceso al Sistema

### Usuarios por Defecto

| Rol | Email | Contraseña | Permisos |
|-----|-------|------------|----------|
| **Administrador** | admin@crmsalud.com | password | Acceso completo |
| **Vendedor** | vendedor@crmsalud.com | password | Clientes, ventas, oportunidades |
| **Marketing** | marketing@crmsalud.com | password | Campañas, segmentos, comunicación |
| **Inventarios** | inventarios@crmsalud.com | password | Productos, inventarios, reportes |

> ⚠️ **Importante**: Cambiar las contraseñas por defecto inmediatamente después de la instalación.

## 📁 Estructura del Proyecto

```
CRMSalud/
├── assets/                 # Archivos estáticos
│   ├── css/               # Hojas de estilo
│   ├── js/                # JavaScript
│   └── images/            # Imágenes
├── config/                # Configuración
│   ├── config.php         # Configuración principal
│   └── database.php       # Conexión a BD
├── controllers/           # Controladores MVC
├── core/                  # Clases principales
│   ├── BaseController.php # Controlador base
│   ├── BaseModel.php      # Modelo base
│   └── Router.php         # Sistema de rutas
├── models/                # Modelos de datos
├── views/                 # Plantillas HTML
│   ├── layout/            # Layout principal
│   ├── auth/              # Autenticación
│   ├── dashboard/         # Dashboard
│   └── errors/            # Páginas de error
├── uploads/               # Archivos subidos
├── .htaccess             # Configuración Apache
├── index.php             # Punto de entrada
├── install.sql           # Script de instalación
└── README.md             # Este archivo
```

## 🔧 Configuración Avanzada

### URLs Amigables
El sistema detecta automáticamente la URL base. Para configuración manual:

```php
// En config/config.php
define('BASE_URL', 'http://tu-dominio.com/crmsalud/');
```

### Configuración de Email
```php
// Configuración SMTP (agregar en config.php)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'tu-email@gmail.com');
define('SMTP_PASS', 'tu-contraseña');
```

### Configuración de Uploads
```php
// Tamaño máximo de archivos (en config.php)
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB

// Extensiones permitidas
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);
```

## 🔒 Seguridad

### Características de Seguridad Implementadas
- Contraseñas hasheadas con `password_hash()`
- Validación de entrada y sanitización
- Protección contra SQL Injection (PDO con prepared statements)
- Protección de directorios sensibles via .htaccess
- Validación de tipos de archivo en uploads
- Control de acceso basado en roles
- Sesiones seguras con configuración personalizada

### Recomendaciones Adicionales
1. **Cambiar contraseñas por defecto**
2. **Configurar HTTPS en producción**
3. **Actualizar PHP y MySQL regularmente**
4. **Configurar backups automáticos**
5. **Monitorear logs de acceso**

## 📊 Módulos del Sistema

### 1. Gestión de Usuarios
- CRUD de usuarios con roles específicos
- Control de permisos granular
- Histórico de accesos

### 2. Gestión de Clientes
- Registro especializado por tipo de cliente
- Segmentación automática
- Histórico de interacciones

### 3. Catálogo de Productos
- Gestión completa de productos farmacéuticos
- Control de precios por segmento
- Información regulatoria

### 4. Control de Inventarios
- Gestión por lotes y vencimientos
- Alertas inteligentes
- Trazabilidad completa

### 5. Pipeline de Ventas
- Gestión de oportunidades
- Cotizaciones profesionales
- Control de pedidos

### 6. Marketing Digital
- Segmentación dinámica
- Campañas multicanal
- Métricas avanzadas

### 7. Comunicación
- Registro de interacciones
- Plantillas personalizables
- Seguimientos automáticos

### 8. Reportes y Analytics
- Dashboard ejecutivo
- Reportes especializados
- Exportación flexible

### 9. Calendario
- Agenda integrada
- Recordatorios automáticos
- Sincronización de actividades

## 🚀 Uso del Sistema

### Primer Acceso
1. Visitar `http://tu-dominio/test` para verificar la instalación
2. Acceder con las credenciales por defecto
3. Cambiar contraseñas inmediatamente
4. Configurar datos de la empresa
5. Importar clientes y productos

### Flujo de Trabajo Típico
1. **Registrar clientes** con sus datos específicos
2. **Cargar catálogo** de productos
3. **Configurar inventarios** con lotes y vencimientos
4. **Crear oportunidades** de venta
5. **Generar cotizaciones** personalizadas
6. **Convertir a pedidos** y hacer seguimiento
7. **Ejecutar campañas** de marketing
8. **Analizar resultados** en reportes

## 🆘 Solución de Problemas

### Error de Conexión a Base de Datos
1. Verificar credenciales en `config/config.php`
2. Confirmar que el servidor MySQL esté activo
3. Verificar que la base de datos exista

### Error 404 en URLs
1. Verificar que mod_rewrite esté habilitado
2. Confirmar que el archivo `.htaccess` esté presente
3. Verificar permisos del directorio

### Problemas de Permisos
```bash
# Ajustar permisos (Linux)
sudo chown -R www-data:www-data /path/to/CRMSalud
sudo chmod -R 755 /path/to/CRMSalud
sudo chmod 755 uploads/
```

### Error en Uploads
1. Verificar permisos del directorio `uploads/`
2. Revisar configuración de `upload_max_filesize` en PHP
3. Confirmar que las extensiones estén permitidas

## 📞 Soporte

### Documentación
- Manual de usuario: `/docs/manual-usuario.pdf`
- API Documentation: `/docs/api.md`
- Video tutoriales: Disponibles en el dashboard

### Contacto
- **Email**: soporte@crmsalud.com
- **Teléfono**: +52 (55) 1234-5678
- **Sitio Web**: https://crmsalud.com

## 🔄 Actualizaciones

### Verificar Versión Actual
Visitar: `http://tu-dominio/test` para ver la versión instalada.

### Proceso de Actualización
1. Hacer backup de la base de datos
2. Hacer backup de archivos personalizados
3. Descargar nueva versión
4. Ejecutar scripts de migración si es necesario
5. Verificar funcionamiento

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## 📋 Roadmap

### Versión 1.1 (Próximamente)
- [ ] Integración con APIs de WhatsApp Business
- [ ] Módulo de facturación electrónica
- [ ] App móvil para vendedores
- [ ] Integración con ERP externos

### Versión 1.2 (Futuro)
- [ ] Inteligencia artificial para recomendaciones
- [ ] Módulo de e-commerce integrado
- [ ] API REST completa
- [ ] Multi-empresa y multi-moneda

---

## ⚡ Inicio Rápido

### Instalación Express (5 minutos)

```bash
# 1. Clonar repositorio
git clone https://github.com/danjohn007/CRMSalud.git

# 2. Crear base de datos
mysql -u root -p -e "CREATE DATABASE crm_salud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Importar datos
mysql -u root -p crm_salud < install.sql

# 4. Configurar (editar config/config.php)
nano config/config.php

# 5. ¡Listo! Acceder a http://localhost/CRMSalud
```

**Usuario por defecto**: admin@crmsalud.com / password

---

*CRM Salud - Transformando la gestión comercial en el sector salud* 🏥💊
