@echo off
REM Script para configurar tarea programada de actualización DuckDNS
REM EJECUTAR COMO ADMINISTRADOR

echo ==========================================
echo   CONFIGURADOR DE TAREA PROGRAMADA
echo   Plants Evolution - DuckDNS Update
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

REM Obtener la ruta actual
set SCRIPT_PATH=%~dp0update-ip.bat

REM Verificar que existe el script de actualización
if not exist "%SCRIPT_PATH%" (
    echo ❌ ERROR: No se encuentra el archivo update-ip.bat
    echo Debe estar en la misma carpeta que este script
    pause
    exit /b 1
)

echo ✅ Script update-ip.bat encontrado
echo.

REM Eliminar tarea existente si existe
echo Eliminando tarea existente (si existe)...
schtasks /delete /tn "PlantsEvolution-DuckDNS" /f >nul 2>&1

REM Crear nueva tarea programada
echo Creando nueva tarea programada...
schtasks /create ^
    /tn "PlantsEvolution-DuckDNS" ^
    /tr "\"%SCRIPT_PATH%\" silent" ^
    /sc minute ^
    /mo 5 ^
    /ru "SYSTEM" ^
    /rl highest ^
    /f

if %errorlevel%==0 (
    echo ✅ Tarea programada creada exitosamente
    echo.
    echo CONFIGURACIÓN:
    echo - Nombre: PlantsEvolution-DuckDNS
    echo - Frecuencia: Cada 5 minutos
    echo - Script: %SCRIPT_PATH%
    echo - Usuario: SYSTEM
    echo.
    echo ⚠️  IMPORTANTE:
    echo Ahora debes editar el archivo update-ip.bat y configurar:
    echo 1. Tu dominio DuckDNS
    echo 2. Tu token de DuckDNS
    echo.
    echo Para ver/editar la tarea usa: taskschd.msc
) else (
    echo ❌ ERROR: No se pudo crear la tarea programada
    echo Código de error: %errorlevel%
)

echo.
echo ¿Quieres probar la actualización de DuckDNS ahora? (s/n)
set /p respuesta=

if /i "%respuesta%"=="s" (
    echo.
    echo Ejecutando actualización de prueba...
    call "%SCRIPT_PATH%" silent
)

echo.
echo Proceso completado. Presiona cualquier tecla para salir...
pause >nul 