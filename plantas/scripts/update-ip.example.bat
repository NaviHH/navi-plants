@echo off
REM Script para actualizar DuckDNS con la IP actual
REM Copia este archivo como update-ip.bat y edita las variables

REM CONFIGURACIÓN - EDITA ESTAS LÍNEAS
set DOMAIN=tu-dominio-aqui
set TOKEN=tu-token-duckdns-aqui

REM ========================================
REM NO EDITAR DEBAJO DE ESTA LÍNEA
REM ========================================

echo [%date% %time%] Actualizando DuckDNS...

REM Intentar con curl primero
where curl >nul 2>nul
if %errorlevel%==0 (
    echo Usando curl...
    curl -k "https://www.duckdns.org/update?domains=%DOMAIN%&token=%TOKEN%&ip="
    echo.
    echo DuckDNS actualizado con curl
    goto :end
)

REM Si no hay curl, usar PowerShell
echo Usando PowerShell...
powershell -Command "try { $response = Invoke-WebRequest -Uri 'https://www.duckdns.org/update?domains=%DOMAIN%&token=%TOKEN%&ip=' -UseBasicParsing; Write-Host 'Respuesta:' $response.Content } catch { Write-Host 'Error:' $_.Exception.Message }"

:end
echo [%date% %time%] Proceso completado
echo.

REM Solo pausar si se ejecuta directamente (no desde tarea programada)
if "%1"=="" pause 