# 🚀 Guía de Instalación Completa - Plants Evolution

Esta guía te llevará paso a paso desde cero hasta tener tu aplicación funcionando y accesible desde internet.

## ⏱️ Tiempo Estimado: 2-3 horas

---

## 📋 Paso 1: Preparar el Sistema (15 minutos)

### 1.1 Descargar XAMPP
1. Ve a https://www.apachefriends.org/
2. Descarga **XAMPP para Windows** (versión más reciente)
3. Ejecuta el instalador **como Administrador**
4. Instala en la ruta por defecto: `C:\xampp`
5. Selecciona: Apache, MySQL, PHP, phpMyAdmin

### 1.2 Verificar Instalación
1. Abre **XAMPP Control Panel** como Administrador
2. Haz clic en **Start** junto a Apache y MySQL
3. Deberían mostrar fondo verde y "Running"
4. Prueba: http://localhost (debería aparecer página de XAMPP)

---

## 📁 Paso 2: Instalar Plants Evolution (30 minutos)

### 2.1 Copiar Archivos
1. Copia toda la carpeta `plantas` a `C:\xampp\htdocs\`
2. La estructura debe quedar así:
   ```
   C:\xampp\htdocs\plantas\
   ├── index.php
   ├── config.php
   ├── plantas.sql
   ├── css/
   ├── js/
   ├── uploads/
   └── scripts/
   ```

### 2.2 Crear Base de Datos
1. Ve a http://localhost/phpmyadmin
2. Haz clic en **"Nueva"** (crear base de datos)
3. Nombre: `plantas`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Haz clic en **"Crear"**

### 2.3 Importar Estructura
1. Selecciona la base de datos `plantas`
2. Ve a la pestaña **"Importar"**
3. Haz clic en **"Seleccionar archivo"**
4. Busca y selecciona `C:\xampp\htdocs\plantas\plantas.sql`
5. Haz clic en **"Continuar"**
6. Deberías ver: "Importación finalizada correctamente"

### 2.4 Prueba Local
1. Ve a http://localhost/plantas/
2. Deberías ver la aplicación funcionando
3. Prueba agregar una planta nueva
4. Prueba subir una foto

---

## 🌐 Paso 3: Configurar Acceso Externo (45 minutos)

### 3.1 Configurar XAMPP para Acceso Externo
1. Ve a `C:\xampp\htdocs\plantas\scripts\`
2. **Clic derecho** en `configure-xampp.bat`
3. Selecciona **"Ejecutar como administrador"**
4. Sigue las instrucciones del script
5. Di **"No"** cuando pregunte sobre MySQL externo
6. El script configurará automáticamente Apache y Firewall

### 3.2 Configurar Router (Port Forwarding)
1. Abre tu navegador y ve a **192.168.1.1** (o la IP de tu router)
2. Inicia sesión (usuario/contraseña suelen estar en el router)
3. Busca: **"Port Forwarding"**, **"Virtual Servers"** o **"NAT"**
4. Crear nueva regla:
   - **Puerto externo:** 80
   - **Puerto interno:** 80
   - **IP interna:** [IP de tu PC] (la mostró el script)
   - **Protocolo:** TCP
   - **Estado:** Habilitado
5. Guarda la configuración

### 3.3 Configurar DuckDNS (Dominio Gratuito)
1. Ve a https://www.duckdns.org/
2. Inicia sesión con **Google** o **GitHub**
3. En "domains", escribe tu nombre: `plantas-evolution` (o el que prefieras)
4. Haz clic en **"add domain"**
5. Copia el **token** que aparece arriba
6. Anota tu dominio: `plantas-evolution.duckdns.org`

### 3.4 Configurar Actualización Automática de IP
1. Ve a `C:\xampp\htdocs\plantas\scripts\`
2. **Clic derecho** en `update-ip.bat` → **"Editar"**
3. Cambia estas líneas:
   ```batch
   set DOMAIN=plantas-evolution    (tu dominio sin .duckdns.org)
   set TOKEN=AQUI-VA-TU-TOKEN     (el token que copiaste)
   ```
4. Guarda el archivo
5. **Doble clic** en `update-ip.bat` para probar
6. Debería mostrar "OK" o similar

### 3.5 Automatizar Actualización de IP
1. **Clic derecho** en `setup-scheduled-task.bat`
2. Selecciona **"Ejecutar como administrador"**
3. El script creará una tarea que actualiza tu IP cada 5 minutos
4. Prueba la actualización cuando te lo pregunte

---

## 🧪 Paso 4: Probar Acceso Externo (15 minutos)

### 4.1 Probar Localmente
1. Ve a http://localhost/plantas/
2. Agrega una planta de prueba
3. Sube una foto
4. Verifica que todo funciona

### 4.2 Probar desde Internet
1. Desde tu móvil (usando datos, no WiFi) ve a:
   `http://plantas-evolution.duckdns.org/plantas/`
2. O pide a un amigo que pruebe desde su casa
3. Deberías ver la misma aplicación

### 4.3 Solución de Problemas
Si no funciona desde internet:

**Problema: "Esta página no está disponible"**
- ✅ Verifica que Apache esté ejecutándose (XAMPP Control Panel)
- ✅ Verifica port forwarding en router
- ✅ Verifica que el firewall permite el puerto 80

**Problema: "IP no actualizada"**
- ✅ Revisa el archivo `update-ip.bat` (dominio y token correctos)
- ✅ Ejecuta manualmente el script y verifica errores

**Problema: "Conexión rechazada"**
- ✅ Verifica que tu ISP no bloquee el puerto 80
- ✅ Algunos ISP bloquean servidores domésticos

---

## 🔒 Paso 5: Seguridad Básica (30 minutos)

### 5.1 Configurar Contraseña de MySQL
1. Ve a http://localhost/phpmyadmin
2. Haz clic en **"Cuentas de usuario"**
3. Edita usuario **"root"** con host **"localhost"**
4. Cambia contraseña por una segura
5. Edita `C:\xampp\htdocs\plantas\config.php`:
   ```php
   define('DB_PASS', 'tu-nueva-contraseña');
   ```

### 5.2 Configurar Acceso Básico
1. Crea archivo `C:\xampp\htdocs\plantas\.htaccess`:
   ```apache
   # Protección básica
   Options -Indexes
   
   # Denegar acceso a archivos sensibles
   <Files "config.php">
       Deny from all
   </Files>
   
   <Files "plantas.sql">
       Deny from all
   </Files>
   ```

### 5.3 Backup Automático (Opcional)
1. Crea carpeta `C:\xampp\htdocs\plantas\backups\`
2. El sistema ya incluye funciones de backup
3. Considera hacer backup manual regular de:
   - Base de datos (desde phpMyAdmin)
   - Carpeta `uploads/` (fotos)

---

## 🎉 ¡Listo! Tu aplicación está funcionando

### URLs de Acceso:
- **Local:** http://localhost/plantas/
- **Internet:** http://tu-dominio.duckdns.org/plantas/
- **Administración BD:** http://localhost/phpmyadmin

### Características Disponibles:
- ✅ Agregar plantas nuevas
- ✅ Subir fotos de evolución
- ✅ Ver timeline de cada planta
- ✅ Comentarios en fotos
- ✅ Registro de cuidados
- ✅ Estadísticas básicas
- ✅ Acceso desde móvil
- ✅ Acceso desde cualquier parte del mundo

---

## 🔧 Mantenimiento y Mejoras

### Mantenimiento Regular:
- Mantén XAMPP actualizado
- Haz backup de fotos y base de datos
- Verifica que la IP se actualice correctamente

### Posibles Mejoras Futuras:
- Instalar SSL/HTTPS (Let's Encrypt)
- Agregar autenticación de usuarios
- Implementar notificaciones de riego
- Crear app móvil nativa
- Migrar a servidor VPS para mejor rendimiento

---

## 🆘 Soporte

Si tienes problemas:

1. **Revisar logs de XAMPP:**
   - `C:\xampp\apache\logs\error.log`
   - `C:\xampp\mysql\data\mysql_error.log`

2. **Verificar conectividad:**
   - Prueba http://localhost/plantas/ (local)
   - Prueba desde móvil con datos (externo)

3. **Comandos útiles:**
   ```cmd
   # Ver IP actual
   ipconfig
   
   # Probar conectividad
   ping tu-dominio.duckdns.org
   
   # Ver puertos abiertos
   netstat -an | findstr :80
   ```

4. **Recursos:**
   - Documentación XAMPP: https://www.apachefriends.org/docs/
   - DuckDNS Help: https://www.duckdns.org/help.jsp

---

**¡Disfruta registrando la evolución de tus plantas! 🌱📸** 