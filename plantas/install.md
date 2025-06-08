# 🚀 Instalación Rápida - Plants Evolution

Esta guía te ayudará a configurar Plants Evolution en tu entorno local desde el repositorio de GitHub.

## ⚡ Instalación en 10 Minutos

### 1. Prerrequisitos
- **XAMPP** instalado en `C:\xampp` ([Descargar](https://www.apachefriends.org/))
- **Git** para clonar el repositorio

### 2. Clonar y Configurar

```bash
# Clonar el repositorio
git clone https://github.com/tu-usuario/plants-evolution.git
cd plants-evolution

# Copiar archivos a XAMPP
copy plantas C:\xampp\htdocs\plantas

# Configurar base de datos
copy plantas\config.example.php plantas\config.php
```

### 3. Configurar Base de Datos

1. **Iniciar XAMPP:**
   - Abre XAMPP Control Panel como Administrador
   - Start: Apache y MySQL

2. **Crear Base de Datos:**
   - Ve a: http://localhost/phpmyadmin
   - Clic en "Nueva"
   - Nombre: `plantas`
   - Cotejamiento: `utf8mb4_unicode_ci`
   - Clic en "Crear"

3. **Importar Estructura:**
   - Selecciona la base de datos `plantas`
   - Pestaña "Importar"
   - Seleccionar archivo: `C:\xampp\htdocs\plantas\plantas.sql`
   - Clic en "Continuar"

### 4. Configurar Archivos

**Editar `config.php`:**
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Tu contraseña de MySQL (vacía por defecto)
define('DB_NAME', 'plantas');
```

**Para acceso externo (opcional):**
```bash
# Copiar script de DuckDNS
copy plantas\scripts\update-ip.example.bat plantas\scripts\update-ip.bat
# Editar update-ip.bat con tu dominio y token
```

### 5. Probar Instalación

- **Local:** http://localhost/plantas/
- **Login Admin:** Configurar contraseña en `index.php` (línea 8)

## 🔧 Configuración Adicional

### Permisos de Carpetas (Windows)
```cmd
# Dar permisos de escritura a uploads
icacls "C:\xampp\htdocs\plantas\uploads" /grant Everyone:(OI)(CI)F
```

### Variables de Configuración

**Contraseña de Admin** (cambiar en `index.php`):
```php
define('ADMIN_PASSWORD', 'tu-nueva-contraseña');
```

**Límites de Archivos** (en `config.php`):
```php
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
```

## 🌐 Acceso Externo (Opcional)

### Configurar DuckDNS
1. Registrarse en https://www.duckdns.org/
2. Crear dominio: `mi-plantas.duckdns.org`
3. Copiar token
4. Editar `plantas/scripts/update-ip.bat`:
   ```batch
   set DOMAIN=mi-plantas
   set TOKEN=tu-token-aqui
   ```

### Configurar Router
- **Puerto:** 80
- **IP Destino:** IP de tu PC
- **Protocolo:** TCP

### Scripts Automáticos
```cmd
# Configurar XAMPP para acceso externo
# Ejecutar como Administrador:
plantas\scripts\configure-xampp.bat

# Configurar actualización automática de IP
# Ejecutar como Administrador:
plantas\scripts\setup-scheduled-task.bat
```

## 🚨 Solución de Problemas

### Error: "No se puede conectar a la base de datos"
```bash
# Verificar que MySQL esté ejecutándose
# En XAMPP Control Panel: MySQL debe mostrar "Running"

# Verificar credenciales en config.php
# Usuario: root
# Contraseña: (vacía por defecto o la que configuraste)
```

### Error: "No se pueden subir archivos"
```cmd
# Verificar permisos de uploads/
icacls "C:\xampp\htdocs\plantas\uploads" /grant Everyone:(OI)(CI)F

# Verificar que la carpeta existe
mkdir "C:\xampp\htdocs\plantas\uploads"
```

### Error: "Acceso denegado desde internet"
```bash
# Verificar configuración de Apache
# El script configure-xampp.bat debe haberse ejecutado correctamente

# Verificar firewall de Windows
# Puerto 80 debe estar abierto para entrada

# Verificar router
# Port forwarding debe estar configurado
```

## 📁 Estructura del Proyecto

```
C:\xampp\htdocs\plantas\
├── index.php              # Aplicación principal
├── config.php             # Configuración (crear desde .example)
├── plantas.sql            # Estructura de base de datos
├── css/styles.css         # Estilos
├── js/main.js             # JavaScript
├── uploads/               # Fotos (crear si no existe)
└── scripts/               # Scripts de automatización
    ├── configure-xampp.bat
    ├── setup-scheduled-task.bat
    └── update-ip.bat       # (crear desde .example)
```

## ✅ Verificación Final

**Lista de verificación:**
- [ ] XAMPP ejecutándose (Apache + MySQL)
- [ ] Base de datos `plantas` creada e importada
- [ ] `config.php` configurado correctamente
- [ ] Carpeta `uploads/` con permisos de escritura
- [ ] http://localhost/plantas/ funciona
- [ ] Puedes agregar una planta de prueba
- [ ] Puedes subir una foto

## 🆘 Obtener Ayuda

Si tienes problemas:
1. Revisa [SETUP.md](../SETUP.md) para guía completa
2. Abre un [issue](https://github.com/tu-usuario/plants-evolution/issues)
3. Consulta la sección de solución de problemas

---

**¡Disfruta documentando la evolución de tus plantas! 🌱📸** 