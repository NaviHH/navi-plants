# 🌱 Plants Evolution

<div align="center">

![Plants Evolution](https://img.shields.io/badge/Plants-Evolution-green?style=for-the-badge&logo=leaf)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

Una aplicación web moderna y elegante para documentar y seguir la evolución de tus plantas, cactus y flores con un diseño responsive y características avanzadas.

[🚀 Demo Live](#) • [📖 Documentación](SETUP.md) • [🐛 Reportar Bug](issues) • [✨ Solicitar Feature](issues)

</div>

## ✨ Características

### 🌟 Funcionalidades Principales
- **📷 Timeline de Evolución** - Documenta el crecimiento con fotos y comentarios
- **🌱 Gestión de Plantas** - Agrega, edita y organiza tu colección
- **📊 Dashboard de Estadísticas** - Visualiza el progreso de tu jardín
- **🔐 Sistema de Autenticación** - Modo visitante y administrador
- **📱 Diseño Responsive** - Funciona perfectamente en móvil y desktop
- **🌐 Acceso Remoto** - Accede desde cualquier lugar del mundo

### 🎨 Diseño y UX
- **🌿 Tema Natural** - Colores verdes inspirados en la naturaleza
- **✨ Animaciones Suaves** - Efectos visuales elegantes y no invasivos
- **🎯 FAB Intuitivo** - Botón flotante para agregar plantas rápidamente
- **🖼️ Galería Moderna** - Visualización de fotos con zoom y overlays
- **🌙 Preparado para Modo Oscuro** - Variables CSS listas para implementar

### 🔧 Características Técnicas
- **🚀 XAMPP Ready** - Configuración automática incluida
- **🔒 Seguridad Básica** - Validación de archivos y protección SQL
- **📁 Subida de Archivos** - Soporte para múltiples formatos de imagen
- **⚡ Performance** - Optimizado para carga rápida
- **🌐 DuckDNS Integration** - Scripts automáticos para acceso externo

## 📸 Screenshots

<details>
<summary>🖼️ Ver Capturas de Pantalla</summary>

### Dashboard Principal
![Dashboard](docs/images/dashboard.png)

### Vista de Planta Individual
![Plant Detail](docs/images/plant-detail.png)

### Formulario de Agregar Planta
![Add Plant](docs/images/add-plant.png)

### Timeline de Evolución
![Evolution Timeline](docs/images/evolution.png)

</details>

## 🚀 Instalación Rápida

### Prerrequisitos
- Windows 10/11
- XAMPP (Apache + MySQL + PHP)

### Instalación en 5 Minutos

```bash
# 1. Clona el repositorio
git clone https://github.com/tu-usuario/plants-evolution.git
cd plants-evolution

# 2. Copia a XAMPP
copy plantas C:\xampp\htdocs\plantas

# 3. Configura la base de datos
# Ve a http://localhost/phpmyadmin
# Crea base de datos 'plantas'
# Importa plantas/plantas.sql

# 4. Configura la aplicación
copy plantas\config.example.php plantas\config.php
# Edita config.php con tus datos de MySQL
```

### Configuración de Archivos

1. **Configuración de Base de Datos:**
   ```bash
   cp plantas/config.example.php plantas/config.php
   # Edita config.php con tu contraseña de MySQL
   ```

2. **Configuración de DuckDNS (Opcional):**
   ```bash
   cp plantas/scripts/update-ip.example.bat plantas/scripts/update-ip.bat
   # Edita update-ip.bat con tu dominio y token de DuckDNS
   ```

## 📖 Documentación Completa

Para una guía paso a paso completa, consulta [SETUP.md](SETUP.md) que incluye:

- ✅ Instalación detallada de XAMPP
- ✅ Configuración de base de datos
- ✅ Configuración de acceso externo
- ✅ Configuración de DuckDNS
- ✅ Port forwarding del router
- ✅ Solución de problemas comunes
- ✅ Configuración de seguridad

## 🎯 Uso

### Acceso a la Aplicación

- **Local:** `http://localhost/plantas/`
- **Externo:** `http://tu-dominio.duckdns.org/plantas/`
- **Administración:** `http://localhost/phpmyadmin`

### Credenciales por Defecto

- **Contraseña Admin:** Configurar en `index.php` (ver SETUP.md para detalles)
- **MySQL User:** `root`
- **MySQL Password:** (configurable en `config.php`)

### Primeros Pasos

1. **🌱 Agrega tu primera planta** usando el botón FAB flotante
2. **📷 Sube fotos de evolución** con comentarios
3. **📊 Observa las estadísticas** actualizarse automáticamente
4. **🔐 Usa modo visitante** para mostrar tu colección a otros

## 🏗️ Estructura del Proyecto

```
plants-evolution/
├── 📁 plantas/                 # Aplicación principal
│   ├── 📄 index.php           # Página principal
│   ├── 📄 config.example.php  # Configuración de ejemplo
│   ├── 📄 plantas.sql         # Estructura de base de datos
│   ├── 📁 css/               # Estilos CSS
│   ├── 📁 js/                # JavaScript
│   ├── 📁 uploads/           # Fotos subidas (git ignored)
│   └── 📁 scripts/           # Scripts de automatización
├── 📄 README.md              # Este archivo
├── 📄 SETUP.md               # Guía de instalación completa
├── 📄 .gitignore             # Archivos ignorados por Git
└── 📄 LICENSE                # Licencia del proyecto
```

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 8.x con MySQL
- **Frontend:** HTML5, CSS3 (Variables CSS), JavaScript ES6+
- **Estilos:** CSS Grid, Flexbox, Animaciones CSS
- **Tipografía:** Inter (Google Fonts)
- **Iconos:** Emojis nativos para mejor compatibilidad
- **Base de Datos:** MySQL con relaciones CASCADE
- **Servidor:** Apache (XAMPP)

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Por favor:

1. **Fork** el proyecto
2. **Crea** una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. **Push** a la rama (`git push origin feature/AmazingFeature`)
5. **Abre** un Pull Request

### 🐛 Reportar Bugs

Si encuentras un bug, por favor abre un [issue](issues) con:
- Descripción del problema
- Pasos para reproducir
- Capturas de pantalla si es posible
- Información del sistema (OS, navegador, versión PHP)

### 💡 Solicitar Features

Para nuevas características, abre un [issue](issues) describiendo:
- Funcionalidad deseada
- Justificación/caso de uso
- Posible implementación

## 📝 Roadmap

### Versión 1.1
- [ ] 🌙 Modo oscuro completo
- [ ] 📧 Notificaciones de riego
- [ ] 📱 PWA (Progressive Web App)
- [ ] 🔍 Búsqueda y filtros avanzados

### Versión 1.2
- [ ] 👥 Sistema multiusuario
- [ ] 🏷️ Sistema de etiquetas
- [ ] 📈 Gráficos de crecimiento
- [ ] 🌡️ Registro de condiciones ambientales

### Versión 2.0
- [ ] 🤖 Reconocimiento de plantas con IA
- [ ] ☁️ Sincronización en la nube
- [ ] 📱 App móvil nativa
- [ ] 🌍 Comunidad y compartir plantas

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## 👨‍💻 Autor

**Desarrollado con ❤️ por [Tu Nombre]**

- 🌐 Website: [tu-website.com]
- 📧 Email: [tu-email@example.com]
- 🐦 Twitter: [@tu-twitter]
- 💼 LinkedIn: [tu-linkedin]

## 🙏 Agradecimientos

- 🎨 Diseño inspirado en tendencias de UI/UX modernas
- 🌿 Iconografía natural usando emojis Unicode
- 📚 Documentación inspirada en mejores prácticas de GitHub
- 🚀 XAMPP por facilitar el desarrollo local

---

<div align="center">

**⭐ Si este proyecto te resultó útil, dale una estrella ⭐**

[![Stargazers repo roster for @tu-usuario/plants-evolution](https://reporoster.com/stars/tu-usuario/plants-evolution)](https://github.com/tu-usuario/plants-evolution/stargazers)

</div> 