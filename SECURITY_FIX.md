# 🚨 CORRECCIÓN URGENTE DE SEGURIDAD

## ⚡ ACCIONES INMEDIATAS

### 1. Eliminar Archivos Sensibles del Repositorio

```bash
# Eliminar archivos con información sensible
git rm docs/navi-plants/config.php
git rm docs/navi-plants/scripts/update-ip.bat
git rm -r docs/navi-plants/uploads/

# Confirmar cambios
git commit -m "🔒 Remove sensitive files and credentials"
git push origin main
```

### 2. Actualizar .gitignore (YA ESTÁ BIEN CONFIGURADO)

El .gitignore actual ya protege estos archivos correctamente.

### 3. Cambiar Contraseñas Inmediatamente

#### En index.php:
```php
// ANTES (INSEGURO):
define('ADMIN_PASSWORD', 'navi2024');

// DESPUÉS (SEGURO):
define('ADMIN_PASSWORD', 'TU_NUEVA_CONTRASEÑA_FUERTE_2024!');
```

#### En config.php (local):
```php
// Cambiar por contraseña real y fuerte
define('DB_PASS', 'tu_contraseña_mysql_fuerte');
```

### 4. Actualizar Documentación

#### README.md - Cambiar:
```markdown
# ANTES:
- **Contraseña Admin:** `navi2024` (cambiar en `index.php`)

# DESPUÉS:
- **Contraseña Admin:** Configurar en `index.php` (ver SETUP.md)
```

#### install.md - Cambiar:
```markdown
# ANTES:
- **Login Admin:** Contraseña `navi2024`

# DESPUÉS:
- **Login Admin:** Contraseña configurada por el usuario
```

### 5. Crear Variables de Entorno (Recomendado)

```php
// En config.php
define('ADMIN_PASSWORD', $_ENV['PLANTS_ADMIN_PASSWORD'] ?? 'default_temp_password');
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');
```

## 🔐 MEJORES PRÁCTICAS IMPLEMENTADAS

### ✅ Lo que YA está bien configurado:
1. **.gitignore** - Excluye archivos sensibles correctamente
2. **config.example.php** - Archivo de ejemplo sin credenciales
3. **update-ip.example.bat** - Script de ejemplo sin tokens
4. **Estructura de proyecto** - Separación correcta

### ⚠️ Lo que necesitas corregir:
1. **Eliminar docs/navi-plants/** - Es una duplicación con datos reales
2. **Cambiar contraseñas hardcodeadas**
3. **Actualizar documentación**
4. **Revisar historial de Git** - Considera limpiar historial si es necesario

## 🚀 Comandos de Limpieza

```bash
# 1. Eliminar carpeta docs/navi-plants (contiene datos reales)
git rm -r docs/navi-plants/
git commit -m "🔒 Remove folder with real credentials"

# 2. Actualizar archivos de documentación
# (Editar README.md, install.md, SETUP.md manualmente)

# 3. Push cambios
git push origin main

# 4. (OPCIONAL) Limpiar historial si es crítico
# SOLO SI ES ABSOLUTAMENTE NECESARIO:
# git filter-branch --force --index-filter 'git rm --cached --ignore-unmatch docs/navi-plants/config.php' --prune-empty --tag-name-filter cat -- --all
```

## 📋 Lista de Verificación

- [ ] Eliminar `docs/navi-plants/` del repositorio
- [ ] Cambiar contraseña admin en código local
- [ ] Actualizar documentación (README, install.md, SETUP.md)
- [ ] Verificar que .gitignore funciona correctamente
- [ ] Cambiar contraseñas de MySQL localmente
- [ ] Revisar si alguien más ha visto el repositorio
- [ ] Cambiar tokens de DuckDNS si estaban expuestos

---

**🔒 IMPORTANTE:** Estos cambios son críticos para la seguridad de tu aplicación. Hazlos inmediatamente antes de que alguien más pueda acceder a esta información. 