# 📝 Changelog - Plants Evolution

Todos los cambios importantes del proyecto se documentan en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere al [Versionado Semántico](https://semver.org/lang/es/).

## [Unreleased] - Próximas Mejoras

### Planeado
- 🌙 Modo oscuro completo
- 📧 Notificaciones de riego
- 📱 PWA (Progressive Web App)
- 🔍 Búsqueda y filtros avanzados
- 👥 Sistema multiusuario
- 🏷️ Sistema de etiquetas
- 📈 Gráficos de crecimiento

## [1.0.0] - 2024-01-XX - Versión Inicial

### ✨ Agregado
- **🌱 Gestión completa de plantas**
  - Formulario de agregar plantas con validación
  - Campos: nombre, tipo, fecha de plantación, foto inicial
  - Eliminación segura con confirmación
  - Estados de plantas (activa, inactiva, muerta)

- **📷 Sistema de fotos y evolución**
  - Subida de múltiples fotos por planta
  - Timeline visual de evolución
  - Tipos de fotos: inicial, evolución, floración, fruto, problema
  - Galería responsiva con zoom modal
  - Comentarios en cada foto

- **🔐 Sistema de autenticación**
  - Modo visitante (solo lectura)
  - Modo administrador con contraseña
  - Protección de operaciones CRUD
  - Interfaz de login elegante

- **📊 Dashboard y estadísticas**
  - Contador de plantas totales
  - Plantas activas vs inactivas
  - Tipos diferentes de plantas
  - Promedio de días de cuidado

- **🎨 Diseño moderno y responsive**
  - Tema natural con colores verdes
  - Animaciones suaves y micro-interacciones
  - FAB (Floating Action Button) para agregar plantas
  - Elementos decorativos (hojas flotantes, sparkles)
  - Diseño mobile-first

- **🌐 Configuración para acceso externo**
  - Scripts de configuración automática XAMPP
  - Integración con DuckDNS
  - Port forwarding automático
  - Tarea programada para actualización de IP

- **🔧 Características técnicas**
  - PHP 8.x con MySQL
  - Validación de archivos y seguridad básica
  - Base de datos optimizada con índices
  - Subida de archivos con límites configurables
  - CSS moderno con variables personalizadas

### 🛠️ Infraestructura
- **📁 Estructura del proyecto organizada**
  - Separación clara de responsabilidades
  - CSS y JavaScript modulares
  - Scripts de automatización incluidos
  - Documentación completa

- **🔒 Seguridad básica implementada**
  - Sanitización de entradas
  - Protección SQL injection
  - Validación de tipos de archivo
  - Límites de tamaño de subida

- **📱 Responsive design completo**
  - Breakpoints: 1200px, 768px, 480px
  - Navegación optimizada para móvil
  - Formularios adaptables
  - Galería de fotos responsive

## [0.9.0] - 2024-01-XX - Beta Release

### ✨ Agregado
- Funcionalidad básica de plantas
- Subida de fotos simple
- Interfaz básica
- Base de datos inicial

### 🔧 Corregido
- Errores de validación
- Problemas de responsive
- Bugs en subida de archivos

---

## 🏷️ Tipos de Cambios

- **✨ Agregado** - Para nuevas funcionalidades
- **🔧 Cambiado** - Para cambios en funcionalidades existentes
- **❌ Deprecado** - Para funcionalidades que serán eliminadas
- **🗑️ Eliminado** - Para funcionalidades eliminadas
- **🔧 Corregido** - Para corrección de bugs
- **🔒 Seguridad** - Para mejoras de seguridad

---

## 📅 Versionado

- **Mayor** - Cambios incompatibles en la API
- **Menor** - Nuevas funcionalidades compatibles
- **Parche** - Correcciones de bugs compatibles

Ejemplo: `1.2.3` = Mayor.Menor.Parche 