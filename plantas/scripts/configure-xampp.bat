@echo off
REM Script para configurar XAMPP para acceso externo
REM EJECUTAR COMO ADMINISTRADOR

echo ==========================================
echo   CONFIGURADOR XAMPP PARA ACCESO EXTERNO
echo   Plants Evolution
echo ==========================================
echo.

REM Verificar si se ejecuta como administrador
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Este script debe ejecutarse como ADMINISTRADOR
    echo.
    echo Haz clic derecho en el archivo y selecciona "Ejecutar como administrador"
    pause
    exit /b 1
)

echo ✅ Ejecutándose como administrador
echo.

REM Verificar si XAMPP está instalado
set XAMPP_PATH=C:\xampp
if not exist "%XAMPP_PATH%" (
    echo ❌ ERROR: XAMPP no está instalado en %XAMPP_PATH%
    echo.
    echo Instala XAMPP primero desde: https://www.apachefriends.org/
    pause
    exit /b 1
)

echo ✅ XAMPP encontrado en %XAMPP_PATH%
echo.

REM Hacer backup de archivos de configuración
echo Creando backup de configuraciones...
if not exist "%XAMPP_PATH%\backup" mkdir "%XAMPP_PATH%\backup"

copy "%XAMPP_PATH%\apache\conf\httpd.conf" "%XAMPP_PATH%\backup\httpd.conf.bak" >nul 2>&1
copy "%XAMPP_PATH%\apache\conf\extra\httpd-xampp.conf" "%XAMPP_PATH%\backup\httpd-xampp.conf.bak" >nul 2>&1

echo ✅ Backup creado en %XAMPP_PATH%\backup
echo.

REM Configurar Apache para acceso externo
echo Configurando Apache para acceso externo...

REM Crear archivo de configuración temporal
echo ^<Directory "C:/xampp/htdocs"^> > "%TEMP%\xampp_config.tmp"
echo     AllowOverride All >> "%TEMP%\xampp_config.tmp"
echo     Require all granted >> "%TEMP%\xampp_config.tmp"
echo ^</Directory^> >> "%TEMP%\xampp_config.tmp"

REM Modificar httpd.conf para escuchar en todas las interfaces
powershell -Command "(Get-Content '%XAMPP_PATH%\apache\conf\httpd.conf') -replace 'Listen 80', 'Listen 0.0.0.0:80' | Set-Content '%XAMPP_PATH%\apache\conf\httpd.conf'"

REM Agregar configuración de directorio si no existe
findstr /C:"Require all granted" "%XAMPP_PATH%\apache\conf\httpd.conf" >nul
if %errorlevel% neq 0 (
    echo. >> "%XAMPP_PATH%\apache\conf\httpd.conf"
    echo # Configuración para acceso externo - Plants Evolution >> "%XAMPP_PATH%\apache\conf\httpd.conf"
    type "%TEMP%\xampp_config.tmp" >> "%XAMPP_PATH%\apache\conf\httpd.conf"
)

del "%TEMP%\xampp_config.tmp"

echo ✅ Apache configurado para acceso externo
echo.

REM Configurar MySQL para acceso externo (opcional)
echo ¿Quieres permitir acceso externo a MySQL también? (s/n)
echo (Recomendado: NO, a menos que sepas lo que haces)
set /p mysql_respuesta=

if /i "%mysql_respuesta%"=="s" (
    echo Configurando MySQL para acceso externo...
    REM Descomentar bind-address en my.ini
    powershell -Command "(Get-Content '%XAMPP_PATH%\mysql\bin\my.ini') -replace '#bind-address', 'bind-address' | Set-Content '%XAMPP_PATH%\mysql\bin\my.ini'"
    echo ⚠️  MySQL configurado para acceso externo
    echo ⚠️  IMPORTANTE: Configura una contraseña fuerte para root
) else (
    echo ✅ MySQL mantenido solo para acceso local (recomendado)
)

echo.

REM Configurar Firewall de Windows
echo Configurando Firewall de Windows...

REM Agregar regla para Apache (puerto 80)
netsh advfirewall firewall add rule name="XAMPP Apache (Plants Evolution)" dir=in action=allow protocol=TCP localport=80 >nul 2>&1

if /i "%mysql_respuesta%"=="s" (
    REM Agregar regla para MySQL (puerto 3306) solo si se habilitó acceso externo
    netsh advfirewall firewall add rule name="XAMPP MySQL (Plants Evolution)" dir=in action=allow protocol=TCP localport=3306 >nul 2>&1
    echo ✅ Firewall configurado para Apache (80) y MySQL (3306)
) else (
    echo ✅ Firewall configurado para Apache (puerto 80)
)

echo.

REM Mostrar información de red
echo INFORMACIÓN DE RED:
echo.
for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr /c:"IPv4"') do (
    set "ip=%%i"
    setlocal enabledelayedexpansion
    echo IP Local: !ip!
    endlocal
)

echo.
echo PRÓXIMOS PASOS:
echo.
echo 1. ✅ Copia la carpeta 'plantas' a C:\xampp\htdocs\
echo 2. ✅ Inicia Apache y MySQL desde XAMPP Control Panel
echo 3. ✅ Ve a http://localhost/phpmyadmin
echo 4. ✅ Crea base de datos 'plantas' e importa plantas.sql
echo 5. ✅ Prueba localmente: http://localhost/plantas/
echo 6. ✅ Configura tu router para port forwarding (puerto 80)
echo 7. ✅ Configura DuckDNS con scripts/update-ip.bat
echo.

echo CONFIGURACIÓN DE ROUTER:
echo - Accede a tu router (usualmente 192.168.1.1)
echo - Ve a Port Forwarding / Virtual Servers
echo - Agrega regla: Puerto 80 → IP de este PC
echo.

echo ¿Quieres que abramos XAMPP Control Panel ahora? (s/n)
set /p xampp_respuesta=

if /i "%xampp_respuesta%"=="s" (
    echo Abriendo XAMPP Control Panel...
    start "" "%XAMPP_PATH%\xampp-control.exe"
)

echo.
echo ✅ Configuración completada
echo.
echo ⚠️  IMPORTANTE PARA SEGURIDAD:
echo - Configura contraseñas para MySQL
echo - Mantén XAMPP actualizado
echo - Considera usar SSL/HTTPS para producción
echo.

pause 