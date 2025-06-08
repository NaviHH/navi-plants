// Funcionalidades principales de la aplicación
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Inicializar modal de fotos
    initPhotoModal();
    
    // Inicializar formularios
    initForms();
    
    // Inicializar preview de archivos
    initFilePreview();
    
    // Inicializar tooltips
    initTooltips();
    
    // Inicializar animaciones
    initAnimations();
    
    // Inicializar autenticación
    initAuthentication();
}

// Sistema de autenticación
function initAuthentication() {
    // Cerrar modal al presionar Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideLoginModal();
        }
    });
    
    // Cerrar modal de login al hacer click fuera
    const loginModal = document.getElementById('loginModal');
    if (loginModal) {
        loginModal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideLoginModal();
            }
        });
    }
}

// Funciones de autenticación
function showLoginModal() {
    const modal = document.getElementById('loginModal');
    if (modal) {
        modal.style.display = 'flex';
        const input = modal.querySelector('.login-input');
        if (input) {
            setTimeout(() => input.focus(), 100);
        }
    }
}

function hideLoginModal() {
    const modal = document.getElementById('loginModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Función para mostrar/ocultar formulario de nueva planta
function toggleNewPlantForm() {
    const form = document.getElementById('newPlantForm');
    const fab = document.querySelector('.add-plant-fab');
    
    if (form.style.display === 'none' || form.style.display === '') {
        // Mostrar formulario con magia
        form.style.display = 'block';
        fab.style.transform = 'scale(0.8)';
        fab.style.opacity = '0.7';
        
        // Efecto mágico de aparición
        createMagicSparkles(form);
        
        // Scroll al formulario y focus en primer input
        setTimeout(() => {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const firstInput = form.querySelector('input[name="nombre"]');
            if (firstInput) firstInput.focus();
        }, 100);
    } else {
        // Ocultar formulario
        form.style.display = 'none';
        fab.style.transform = 'scale(1)';
        fab.style.opacity = '1';
    }
}

// Crear efecto de sparkles mágicos
function createMagicSparkles(element) {
    const sparkles = ['✨', '💫', '⭐', '🌟'];
    const rect = element.getBoundingClientRect();
    
    for (let i = 0; i < 6; i++) {
        setTimeout(() => {
            const sparkle = document.createElement('div');
            sparkle.textContent = sparkles[Math.floor(Math.random() * sparkles.length)];
            sparkle.style.position = 'fixed';
            sparkle.style.left = (rect.left + Math.random() * rect.width) + 'px';
            sparkle.style.top = (rect.top + Math.random() * rect.height) + 'px';
            sparkle.style.fontSize = '1.2rem';
            sparkle.style.pointerEvents = 'none';
            sparkle.style.zIndex = '9999';
            sparkle.style.animation = 'magicSparkle 1.5s ease-out forwards';
            
            document.body.appendChild(sparkle);
            
            setTimeout(() => sparkle.remove(), 1500);
        }, i * 100);
    }
}

// CSS para la animación de sparkles (se agregará dinámicamente)
if (!document.querySelector('#magic-styles')) {
    const style = document.createElement('style');
    style.id = 'magic-styles';
    style.textContent = `
        @keyframes magicSparkle {
            0% {
                opacity: 0;
                transform: scale(0) translateY(0);
            }
            50% {
                opacity: 1;
                transform: scale(1) translateY(-20px);
            }
            100% {
                opacity: 0;
                transform: scale(0) translateY(-40px);
            }
        }
    `;
    document.head.appendChild(style);
}

// Modal para ver fotos en grande
function initPhotoModal() {
    const modal = document.getElementById('photoModal');
    const modalImg = document.getElementById('modalImage');
    const span = document.getElementsByClassName('close')[0];
    
    // Agregar event listener a todas las fotos
    document.querySelectorAll('.foto-item img').forEach(img => {
        img.addEventListener('click', function() {
            modal.style.display = 'block';
            modalImg.src = this.src;
            modalImg.alt = this.alt;
        });
    });
    
    // Cerrar modal al hacer clic en X
    if (span) {
        span.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }
    
    // Cerrar modal al hacer clic fuera de la imagen
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    
    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            modal.style.display = 'none';
        }
    });
}

// Validación y manejo de formularios
function initForms() {
    // Formulario nueva planta
    const formPlanta = document.getElementById('formNuevaPlanta');
    if (formPlanta) {
        formPlanta.addEventListener('submit', function(e) {
            if (!validatePlantForm(this)) {
                e.preventDefault();
            }
        });
    }
    
    // Formularios de nuevas fotos
    document.querySelectorAll('.nueva-foto-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validatePhotoForm(this)) {
                e.preventDefault();
            }
        });
    });
}

// Validar formulario nueva planta
function validatePlantForm(form) {
    const nombre = form.querySelector('input[name="nombre"]');
    const fecha = form.querySelector('input[name="fecha_plantacion"]');
    
    // Validar nombre
    if (!nombre.value.trim()) {
        showError(nombre, 'El nombre de la planta es obligatorio');
        return false;
    }
    
    // Validar fecha
    if (!fecha.value) {
        showError(fecha, 'La fecha de plantación es obligatoria');
        return false;
    }
    
    // Validar que la fecha no sea futura
    const fechaPlantacion = new Date(fecha.value);
    const hoy = new Date();
    hoy.setHours(23, 59, 59, 999); // Final del día
    
    if (fechaPlantacion > hoy) {
        showError(fecha, 'La fecha de plantación no puede ser futura');
        return false;
    }
    
    return true;
}

// Validar formulario nueva foto
function validatePhotoForm(form) {
    const fileInput = form.querySelector('input[type="file"]');
    
    if (!fileInput.files.length) {
        showError(fileInput, 'Debes seleccionar una foto');
        return false;
    }
    
    const file = fileInput.files[0];
    
    // Validar tipo de archivo
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        showError(fileInput, 'Solo se permiten archivos de imagen (JPG, PNG, GIF, WebP)');
        return false;
    }
    
    // Validar tamaño (5MB máximo)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        showError(fileInput, 'La imagen no puede ser mayor a 5MB');
        return false;
    }
    
    return true;
}

// Mostrar errores de validación
function showError(input, message) {
    // Remover error anterior si existe
    const existingError = input.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Crear elemento de error
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = '#f44336';
    errorDiv.style.fontSize = '12px';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;
    
    // Agregar después del input
    input.parentNode.appendChild(errorDiv);
    
    // Focus en el campo con error
    input.focus();
    
    // Remover error después de 5 segundos
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 5000);
}

// Preview de archivos antes de subir
function initFilePreview() {
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                createImagePreview(this, file);
            }
        });
    });
}

// Crear preview de imagen
function createImagePreview(input, file) {
    // Remover preview anterior si existe
    const existingPreview = input.parentNode.querySelector('.image-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    // Crear elemento de preview
    const previewDiv = document.createElement('div');
    previewDiv.className = 'image-preview';
    previewDiv.style.marginTop = '10px';
    
    const img = document.createElement('img');
    img.style.maxWidth = '200px';
    img.style.maxHeight = '200px';
    img.style.borderRadius = '10px';
    img.style.boxShadow = '0 4px 10px rgba(0,0,0,0.1)';
    
    // Leer archivo y mostrar preview
    const reader = new FileReader();
    reader.onload = function(e) {
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
    
    previewDiv.appendChild(img);
    
    // Agregar información del archivo
    const fileInfo = document.createElement('div');
    fileInfo.style.fontSize = '12px';
    fileInfo.style.color = '#666';
    fileInfo.style.marginTop = '5px';
    fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;
    
    previewDiv.appendChild(fileInfo);
    
    // Insertar preview después del input
    input.parentNode.appendChild(previewDiv);
}

// Formatear tamaño de archivo
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Inicializar tooltips
function initTooltips() {
    // Agregar tooltips a elementos con atributo data-tooltip
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

// Mostrar tooltip
function showTooltip(e) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = e.target.getAttribute('data-tooltip');
    tooltip.style.position = 'absolute';
    tooltip.style.background = '#333';
    tooltip.style.color = 'white';
    tooltip.style.padding = '8px 12px';
    tooltip.style.borderRadius = '6px';
    tooltip.style.fontSize = '12px';
    tooltip.style.zIndex = '1000';
    tooltip.style.whiteSpace = 'nowrap';
    tooltip.style.pointerEvents = 'none';
    
    document.body.appendChild(tooltip);
    
    // Posicionar tooltip
    const rect = e.target.getBoundingClientRect();
    tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = (rect.top - tooltip.offsetHeight - 8) + 'px';
    
    e.target.tooltipElement = tooltip;
}

// Ocultar tooltip
function hideTooltip(e) {
    if (e.target.tooltipElement) {
        e.target.tooltipElement.remove();
        delete e.target.tooltipElement;
    }
}

// Inicializar animaciones
function initAnimations() {
    // Animación de aparición para las cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });
    
    document.querySelectorAll('.planta-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
}

// Funciones de utilidad
function showNotification(message, type = 'success') {
    // Crear notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.padding = '15px 20px';
    notification.style.borderRadius = '10px';
    notification.style.color = 'white';
    notification.style.fontWeight = '600';
    notification.style.zIndex = '1000';
    notification.style.transform = 'translateX(100%)';
    notification.style.transition = 'transform 0.3s ease';
    
    // Colores según tipo
    switch(type) {
        case 'success':
            notification.style.background = '#4CAF50';
            break;
        case 'error':
            notification.style.background = '#f44336';
            break;
        case 'warning':
            notification.style.background = '#FF9800';
            break;
        default:
            notification.style.background = '#2196F3';
    }
    
    document.body.appendChild(notification);
    
    // Mostrar notificación
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Ocultar notificación después de 3 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// Confirmar eliminación
function confirmarEliminacion(mensaje = '¿Estás seguro de que quieres eliminar este elemento?') {
    return confirm(mensaje);
}

// Formatear fechas
function formatearFecha(fecha) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(fecha).toLocaleDateString('es-ES', options);
}

// Smooth scroll para navegación
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Lazy loading para imágenes
function initLazyLoading() {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Auto-save para formularios largos
function initAutoSave() {
    const forms = document.querySelectorAll('form[data-autosave]');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('input', debounce(() => {
                saveFormData(form);
            }, 1000));
        });
        
        // Restaurar datos guardados
        restoreFormData(form);
    });
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Guardar datos del formulario
function saveFormData(form) {
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    localStorage.setItem(`form_${form.id}`, JSON.stringify(data));
}

// Restaurar datos del formulario
function restoreFormData(form) {
    const savedData = localStorage.getItem(`form_${form.id}`);
    
    if (savedData) {
        const data = JSON.parse(savedData);
        
        Object.keys(data).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input && input.type !== 'file') {
                input.value = data[key];
            }
        });
    }
}

// Limpiar datos guardados del formulario
function clearSavedFormData(formId) {
    localStorage.removeItem(`form_${formId}`);
} 