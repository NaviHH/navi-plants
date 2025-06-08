# 🤝 Guía de Contribución - Plants Evolution

¡Gracias por tu interés en contribuir a Plants Evolution! Este documento te guiará sobre cómo contribuir efectivamente al proyecto.

## 📋 Índice

- [Código de Conducta](#código-de-conducta)
- [Tipos de Contribuciones](#tipos-de-contribuciones)
- [Proceso de Desarrollo](#proceso-de-desarrollo)
- [Configuración del Entorno](#configuración-del-entorno)
- [Estándares de Código](#estándares-de-código)
- [Proceso de Pull Request](#proceso-de-pull-request)
- [Reportar Bugs](#reportar-bugs)
- [Solicitar Features](#solicitar-features)

## 🌟 Código de Conducta

Este proyecto se adhiere a un código de conducta que esperamos que todos los participantes respeten:

- **Se respetuoso** con otros colaboradores
- **Se constructivo** en el feedback
- **Se paciente** con nuevos contribuidores
- **Se inclusivo** y welcoming

## 🎯 Tipos de Contribuciones

### 🐛 Reportar Bugs
- Usa la plantilla de issue para bugs
- Incluye pasos para reproducir
- Proporciona información del sistema
- Adjunta capturas de pantalla si es relevante

### ✨ Nuevas Características
- Abre un issue antes de implementar
- Discute la funcionalidad propuesta
- Considera el impacto en la UX
- Mantén compatibilidad con versiones anteriores

### 📖 Documentación
- Mejora el README.md
- Actualiza SETUP.md
- Comenta código complejo
- Traduce documentación

### 🎨 Mejoras de UI/UX
- Sigue el tema de colores verdes
- Mantén consistencia visual
- Asegura responsividad
- Prueba en diferentes navegadores

### 🔧 Refactoring
- Mejora rendimiento
- Simplifica código
- Optimiza consultas SQL
- Reduce duplicación

## 🛠️ Configuración del Entorno

### Prerrequisitos
```bash
# Instalar XAMPP
# Descargar desde: https://www.apachefriends.org/

# Clonar el repositorio
git clone https://github.com/tu-usuario/plants-evolution.git
cd plants-evolution
```

### Configuración Local
```bash
# 1. Copiar a XAMPP
copy plantas C:\xampp\htdocs\plantas

# 2. Configurar base de datos
cp plantas/config.example.php plantas/config.php
# Editar config.php con tus credenciales

# 3. Importar base de datos
# Ve a http://localhost/phpmyadmin
# Crea base de datos 'plantas'
# Importa plantas/plantas.sql

# 4. Probar instalación
# Ve a http://localhost/plantas/
```

## 📏 Estándares de Código

### PHP
```php
<?php
// Usar PSR-12 como base
// Siempre abrir con <?php
// Usar espacios, no tabs (4 espacios)
// Nombres de variables en snake_case
// Nombres de funciones en camelCase
// Comentarios en español

function calcularDiasPlantacion($fecha_inicio) {
    // Lógica aquí
    return $resultado;
}
?>
```

### CSS
```css
/* Usar nomenclatura BEM cuando sea apropiado */
/* Variables CSS para colores y medidas */
/* Comentarios descriptivos */
/* Mobile-first design */

.planta-card {
    /* Propiedades ordenadas alfabéticamente */
    background: var(--surface);
    border-radius: var(--radius-lg);
    padding: 24px;
}

.planta-card__header {
    /* Modificadores con doble guión */
}

.planta-card--featured {
    /* Estados con doble guión */
}
```

### JavaScript
```javascript
// ES6+ features
// Funciones arrow cuando sea apropiado
// Async/await para promesas
// Comentarios JSDoc para funciones complejas

/**
 * Procesa la subida de una nueva foto
 * @param {File} file - Archivo de imagen
 * @param {number} plantaId - ID de la planta
 * @returns {Promise<Object>} Resultado de la operación
 */
async function procesarNuevaFoto(file, plantaId) {
    // Implementación
}
```

### SQL
```sql
-- Usar mayúsculas para palabras clave SQL
-- Nombres de tablas en plural
-- Nombres de columnas en snake_case
-- Comentarios descriptivos

CREATE TABLE IF NOT EXISTS plantas (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    fecha_plantacion DATE NOT NULL,
    -- Más columnas...
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 🔄 Proceso de Development

### Branching Strategy
```bash
# Crear rama para nueva feature
git checkout -b feature/nueva-funcionalidad

# Crear rama para bugfix
git checkout -b bugfix/corregir-error

# Crear rama para hotfix
git checkout -b hotfix/arreglo-critico
```

### Commit Messages
```bash
# Formato: tipo(scope): descripción

git commit -m "feat(plants): agregar filtro por tipo de planta"
git commit -m "fix(photos): corregir subida de imágenes grandes"
git commit -m "docs(readme): actualizar instrucciones de instalación"
git commit -m "style(css): mejorar responsividad en móviles"
git commit -m "refactor(db): optimizar consultas de estadísticas"
```

### Tipos de Commit
- `feat`: Nueva funcionalidad
- `fix`: Corrección de bug
- `docs`: Cambios en documentación
- `style`: Cambios de formato/estilo
- `refactor`: Refactoring de código
- `test`: Agregar/modificar tests
- `chore`: Tareas de mantenimiento

## 📝 Proceso de Pull Request

### Antes de Crear PR
- [ ] Probar cambios localmente
- [ ] Verificar que no hay errores PHP
- [ ] Comprobar responsividad
- [ ] Revisar compatibilidad con navegadores
- [ ] Actualizar documentación si es necesario

### Creando el PR
1. **Título descriptivo**: `feat: agregar modo oscuro a la interfaz`
2. **Descripción detallada**:
   ```markdown
   ## 📋 Descripción
   Implementa modo oscuro completo para mejorar la experiencia de usuario en condiciones de poca luz.
   
   ## 🔧 Cambios Realizados
   - Agregado toggle de modo oscuro en header
   - Implementadas variables CSS para tema oscuro
   - Actualizado localStorage para persistencia
   - Mejorada accesibilidad con contrastes apropiados
   
   ## 🧪 Testing
   - [ ] Probado en Chrome, Firefox, Safari
   - [ ] Verificado en móvil y desktop
   - [ ] Comprobada persistencia entre sesiones
   
   ## 📸 Screenshots
   [Agregar capturas antes/después]
   ```

### Durante Review
- Responde a comentarios constructivamente
- Haz cambios solicitados en la misma rama
- Mantén conversación enfocada en mejoras técnicas
- Agradece el feedback recibido

## 🐛 Reportar Bugs

### Información Necesaria
```markdown
**🐛 Descripción del Bug**
Descripción clara y concisa del problema.

**🔄 Pasos para Reproducir**
1. Ve a '...'
2. Haz clic en '...'
3. Desplázate hacia '...'
4. Ver error

**✅ Comportamiento Esperado**
Descripción de lo que esperabas que pasara.

**📱 Información del Sistema**
- OS: [ej. Windows 10]
- Navegador: [ej. Chrome 122]
- Versión PHP: [ej. 8.2]
- Versión MySQL: [ej. 8.0]

**📸 Capturas de Pantalla**
Si es aplicable, agrega capturas para explicar el problema.

**📋 Contexto Adicional**
Cualquier otra información relevante.
```

## ✨ Solicitar Features

### Template para Feature Request
```markdown
**🚀 Feature Request**

**📝 Descripción**
Descripción clara de la funcionalidad que te gustaría ver.

**💡 Motivación**
¿Por qué sería útil esta funcionalidad? ¿Qué problema resuelve?

**🎯 Solución Propuesta**
Describe cómo te imaginas que funcionaría.

**🔄 Alternativas Consideradas**
¿Has considerado otras formas de lograr esto?

**📋 Contexto Adicional**
Cualquier otra información, capturas, mockups, etc.
```

## 🎨 Guías de Diseño

### Paleta de Colores
```css
:root {
    --primary-color: #10b981;    /* Verde principal */
    --primary-light: #34d399;    /* Verde claro */
    --primary-dark: #059669;     /* Verde oscuro */
    --secondary-color: #6366f1;  /* Púrpura */
    --accent-color: #f59e0b;     /* Ámbar */
}
```

### Principios de Diseño
- **Natural**: Inspirado en plantas y naturaleza
- **Moderno**: UI contemporáneo y limpio
- **Accesible**: Contrastes apropiados y navegación clara
- **Responsive**: Funciona bien en todos los dispositivos
- **Performante**: Carga rápida y animaciones suaves

## 🏆 Reconocimientos

Los contribuidores serán reconocidos en:
- Lista de contributors en GitHub
- Sección de agradecimientos en README
- Commits firmados en el historial
- Posibles mentions en release notes

## 📞 Contacto

Si tienes preguntas sobre contribuir:
- Abre un issue con etiqueta `question`
- Contacta mantenedores directamente
- Únete a discusiones en GitHub Discussions

---

**¡Gracias por contribuir a Plants Evolution! 🌱** 