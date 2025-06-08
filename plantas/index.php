<?php
require_once 'config.php';

// Iniciar sesión
session_start();

// Configuración de admin
define('ADMIN_PASSWORD', 'CAMBIAR_ESTA_CONTRASEÑA'); // ⚠️ IMPORTANTE: Cambiar por una contraseña segura

$error = '';
$success = '';

// Función para verificar si es admin
function esAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

// Procesar login
if (isset($_POST['admin_login'])) {
    $password = $_POST['admin_password'] ?? '';
    if ($password === ADMIN_PASSWORD) {
        $_SESSION['admin'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = 'Contraseña incorrecta';
    }
}

// Procesar logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Procesar formularios (solo si es admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nueva_planta'])) {
        if (!esAdmin()) {
            $error = 'No tienes permisos para realizar esta acción';
        } else {
            $resultado = procesarNuevaPlanta($_POST, $_FILES);
            if ($resultado['success']) {
                header('Location: index.php?msg=planta_creada');
                exit;
            } else {
                $error = $resultado['error'];
            }
        }
    }
    
    if (isset($_POST['agregar_foto'])) {
        if (!esAdmin()) {
            $error = 'No tienes permisos para realizar esta acción';
        } else {
            $resultado = procesarNuevaFoto($_POST, $_FILES);
            if ($resultado['success']) {
                header('Location: index.php?msg=foto_agregada');
                exit;
            } else {
                $error = $resultado['error'];
            }
        }
    }
    
    if (isset($_POST['agregar_cuidado'])) {
        if (!esAdmin()) {
            $error = 'No tienes permisos para realizar esta acción';
        } else {
            $resultado = procesarNuevoCuidado($_POST);
            if ($resultado['success']) {
                header('Location: index.php?msg=cuidado_agregado');
                exit;
            } else {
                $error = $resultado['error'];
            }
        }
    }
    
    if (isset($_POST['eliminar_planta'])) {
        if (!esAdmin()) {
            $error = 'No tienes permisos para realizar esta acción';
        } else {
            $resultado = eliminarPlanta($_POST['planta_id']);
            if ($resultado['success']) {
                header('Location: index.php?msg=planta_eliminada');
                exit;
            } else {
                $error = $resultado['error'];
            }
        }
    }
}

// Obtener estadísticas
$stats = obtenerEstadisticas();

// Obtener todas las plantas
$plantas = obtenerPlantas();

// Función para procesar nueva planta
function procesarNuevaPlanta($post, $files) {
    global $conn;
    
    $nombre = limpiar_entrada($post['nombre']);
    $nombre_cientifico = limpiar_entrada($post['nombre_cientifico'] ?? '');
    $tipo = limpiar_entrada($post['tipo'] ?? '');
    $fecha_plantacion = limpiar_entrada($post['fecha_plantacion']);
    $ubicacion = limpiar_entrada($post['ubicacion'] ?? '');
    $descripcion = limpiar_entrada($post['descripcion'] ?? '');
    
    // Validaciones
    if (empty($nombre) || empty($fecha_plantacion)) {
        return ['success' => false, 'error' => 'Nombre y fecha de plantación son obligatorios'];
    }
    
    // Validar fecha
    $fecha = DateTime::createFromFormat('Y-m-d', $fecha_plantacion);
    if (!$fecha || $fecha > new DateTime()) {
        return ['success' => false, 'error' => 'Fecha de plantación inválida'];
    }
    
    // Procesar foto inicial si existe
    $foto_archivo = '';
    if (isset($files['foto_inicial']) && $files['foto_inicial']['size'] > 0) {
        $resultado_foto = subirFoto($files['foto_inicial']);
        if (!$resultado_foto['success']) {
            return $resultado_foto;
        }
        $foto_archivo = $resultado_foto['archivo'];
    }
    
    // Insertar planta
    $sql = "INSERT INTO plantas (nombre, nombre_cientifico, tipo, fecha_plantacion, ubicacion, descripcion, foto_actual) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nombre, $nombre_cientifico, $tipo, $fecha_plantacion, $ubicacion, $descripcion, $foto_archivo);
    
    if ($stmt->execute()) {
        $planta_id = $conn->insert_id;
        
        // Si hay foto inicial, agregarla a la tabla de fotos
        if ($foto_archivo) {
            $sql_foto = "INSERT INTO fotos (planta_id, archivo, comentario, tipo_foto) VALUES (?, ?, 'Foto inicial', 'inicial')";
            $stmt_foto = $conn->prepare($sql_foto);
            $stmt_foto->bind_param("is", $planta_id, $foto_archivo);
            $stmt_foto->execute();
        }
        
        return ['success' => true, 'planta_id' => $planta_id];
    } else {
        return ['success' => false, 'error' => 'Error al crear la planta'];
    }
}

// Función para procesar nueva foto
function procesarNuevaFoto($post, $files) {
    global $conn;
    
    $planta_id = intval($post['planta_id']);
    $comentario = limpiar_entrada($post['comentario'] ?? '');
    $tipo_foto = limpiar_entrada($post['tipo_foto'] ?? 'evolucion');
    
    if (!isset($files['nueva_foto']) || $files['nueva_foto']['size'] === 0) {
        return ['success' => false, 'error' => 'Debes seleccionar una foto'];
    }
    
    $resultado_foto = subirFoto($files['nueva_foto']);
    if (!$resultado_foto['success']) {
        return $resultado_foto;
    }
    
    // Insertar foto
    $sql = "INSERT INTO fotos (planta_id, archivo, comentario, tipo_foto) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $planta_id, $resultado_foto['archivo'], $comentario, $tipo_foto);
    
    if ($stmt->execute()) {
        // Actualizar foto actual de la planta
        $sql_update = "UPDATE plantas SET foto_actual = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $resultado_foto['archivo'], $planta_id);
        $stmt_update->execute();
        
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => 'Error al agregar la foto'];
    }
}

// Función para subir foto
function subirFoto($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Error al subir el archivo'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'El archivo es demasiado grande (máximo 5MB)'];
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_TYPES)) {
        return ['success' => false, 'error' => 'Tipo de archivo no permitido'];
    }
    
    // Crear directorio si no existe
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    // Generar nombre único
    $nombre_archivo = time() . '_' . uniqid() . '.' . $ext;
    $ruta_completa = UPLOAD_DIR . $nombre_archivo;
    
    if (move_uploaded_file($file['tmp_name'], $ruta_completa)) {
        return ['success' => true, 'archivo' => $nombre_archivo];
    } else {
        return ['success' => false, 'error' => 'Error al mover el archivo'];
    }
}

// Función para obtener estadísticas
function obtenerEstadisticas() {
    global $conn;
    
    $sql = "SELECT * FROM vista_estadisticas";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Función para obtener plantas
function obtenerPlantas() {
    global $conn;
    
    $sql = "SELECT * FROM plantas ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $plantas = [];
    
    while ($planta = $result->fetch_assoc()) {
        $planta['fotos'] = obtenerFotosPlanta($planta['id']);
        $planta['cuidados'] = obtenerCuidadosPlanta($planta['id']);
        $plantas[] = $planta;
    }
    
    return $plantas;
}

// Función para obtener fotos de una planta
function obtenerFotosPlanta($planta_id) {
    global $conn;
    
    $sql = "SELECT * FROM fotos WHERE planta_id = ? ORDER BY fecha ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $planta_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $fotos = [];
    while ($foto = $result->fetch_assoc()) {
        $fotos[] = $foto;
    }
    
    return $fotos;
}

// Función para obtener cuidados de una planta
function obtenerCuidadosPlanta($planta_id) {
    global $conn;
    
    $sql = "SELECT * FROM cuidados WHERE planta_id = ? ORDER BY fecha DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $planta_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cuidados = [];
    while ($cuidado = $result->fetch_assoc()) {
        $cuidados[] = $cuidado;
    }
    
    return $cuidados;
}

// Función para procesar nuevo cuidado
function procesarNuevoCuidado($post) {
    global $conn;
    
    $planta_id = intval($post['planta_id']);
    $tipo_cuidado = limpiar_entrada($post['tipo_cuidado']);
    $descripcion = limpiar_entrada($post['descripcion']);
    
    $sql = "INSERT INTO cuidados (planta_id, tipo_cuidado, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $planta_id, $tipo_cuidado, $descripcion);
    
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => 'Error al agregar el cuidado'];
    }
}

// Función para eliminar planta
function eliminarPlanta($planta_id) {
    global $conn;
    
    $planta_id = intval($planta_id);
    
    // Obtener archivos de fotos para eliminar
    $sql_fotos = "SELECT archivo FROM fotos WHERE planta_id = ?";
    $stmt_fotos = $conn->prepare($sql_fotos);
    $stmt_fotos->bind_param("i", $planta_id);
    $stmt_fotos->execute();
    $result_fotos = $stmt_fotos->get_result();
    
    // Eliminar archivos físicos
    while ($foto = $result_fotos->fetch_assoc()) {
        $archivo_path = UPLOAD_DIR . $foto['archivo'];
        if (file_exists($archivo_path)) {
            unlink($archivo_path);
        }
    }
    
    // Eliminar planta (las fotos y cuidados se eliminan automáticamente por CASCADE)
    $sql = "DELETE FROM plantas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $planta_id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => 'Error al eliminar la planta'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌱 Plants Evolution - Mi Jardín Virtual</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌱</text></svg>">
</head>
<body>
    <!-- Elementos decorativos flotantes -->
    <div class="floating-leaves">
        <div class="leaf">🌿</div>
        <div class="leaf">🍃</div>
        <div class="leaf">🌱</div>
        <div class="leaf">🌿</div>
        <div class="leaf">🍃</div>
        <div class="sparkle">✨</div>
        <div class="sparkle">💫</div>
        <div class="sparkle">⭐</div>
    </div>
    
    <div class="container">
        <!-- Login/Logout Button -->
        <div class="auth-corner">
            <?php if (esAdmin()): ?>
                <div class="admin-info">
                    <span class="admin-badge">👤 Admin</span>
                    <a href="?logout=1" class="logout-btn">Cerrar sesión</a>
                </div>
            <?php else: ?>
                <button class="login-btn" onclick="showLoginModal()">🔐 Login</button>
            <?php endif; ?>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>🌱 Las Plantas de Navi</h1>
        </div>

        <!-- Modal de Login -->
        <div id="loginModal" class="login-modal" style="display: none;">
            <div class="login-modal-content">
                <div class="login-header">
                    <h3>🔐 Acceso de Administrador</h3>
                    <button class="close-modal" onclick="hideLoginModal()">×</button>
                </div>
                <form method="POST" class="login-form">
                    <input type="password" name="admin_password" placeholder="Contraseña" required class="login-input">
                    <button type="submit" name="admin_login" class="login-submit">Ingresar</button>
                </form>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="message success">
                <?php
                switch($_GET['msg']) {
                    case 'planta_creada':
                        echo '✅ ¡Planta creada exitosamente!';
                        break;
                    case 'foto_agregada':
                        echo '📸 ¡Foto agregada exitosamente!';
                        break;
                    case 'cuidado_agregado':
                        echo '✅ ¡Cuidado registrado exitosamente!';
                        break;
                    case 'planta_eliminada':
                        echo '🗑️ Planta eliminada correctamente';
                        break;
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="message error">
                ❌ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <?php if ($stats): ?>
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['total_plantas'] ?></div>
                <div class="stat-label">Total Plantas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['plantas_activas'] ?></div>
                <div class="stat-label">Plantas Activas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['tipos_diferentes'] ?></div>
                <div class="stat-label">Tipos Diferentes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= round($stats['promedio_dias_cuidado'] ?? 0) ?></div>
                <div class="stat-label">Días Promedio</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Botón flotante para agregar planta - Solo para Admin -->
        <?php if (esAdmin()): ?>
        <div class="add-plant-fab" onclick="toggleNewPlantForm()">
            <span class="fab-icon">+</span>
            <span class="fab-tooltip">Agregar Nueva Planta</span>
        </div>
        
        <!-- Formulario Nueva Planta - Desplegable -->
        <div class="form-container-minimal" id="newPlantForm" style="display: none;">
            <div class="form-header">
                <h3>🌱 Nueva Planta</h3>
                <button type="button" class="close-form-btn" onclick="toggleNewPlantForm()">×</button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="formNuevaPlanta" class="form-minimal">
                <div class="form-row">
                    <input type="text" name="nombre" required placeholder="Nombre de la planta" class="input-main">
                    <select name="tipo" class="input-secondary">
                        <option value="">Tipo</option>
                        <option value="cactus">Cactus</option>
                        <option value="suculenta">Suculenta</option>
                        <option value="flor">Flor</option>
                        <option value="arbol">Árbol</option>
                        <option value="arbusto">Arbusto</option>
                        <option value="hierba">Hierba</option>
                        <option value="planta de interior">Planta de interior</option>
                    </select>
                    <input type="date" name="fecha_plantacion" required class="input-secondary">
                </div>
                <div class="form-row">
                    <input type="file" name="foto_inicial" accept="image/*" class="input-file">
                    <button type="submit" name="nueva_planta" class="btn-minimal">Agregar</button>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="visitor-info">
            <div class="visitor-card">
                <h3>👁️ Modo Visitante</h3>
                <p>Estás viendo la colección de plantas en modo solo lectura. Solo el administrador puede agregar o modificar plantas.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Lista de Plantas -->
        <div class="plantas-grid">
            <?php foreach ($plantas as $planta): ?>
                <div class="planta-card estado-<?= $planta['estado'] ?>">
                    <!-- Header minimalista -->
                    <div class="planta-header-minimal">
                        <div class="planta-info">
                            <h3 class="planta-title-minimal">
                                <?= htmlspecialchars($planta['nombre']) ?>
                                <?php if ($planta['tipo']): ?>
                                    <span class="planta-type-minimal"><?= htmlspecialchars($planta['tipo']) ?></span>
                                <?php endif; ?>
                            </h3>
                            <div class="planta-meta-minimal">
                                <span>📅 <?= dias_desde_plantacion($planta['fecha_plantacion']) ?> días</span>
                                <span>📸 <?= count($planta['fotos']) ?> fotos</span>
                            </div>
                        </div>
                        <?php if (esAdmin()): ?>
                        <div class="planta-actions">
                            <button class="btn-icon" onclick="toggleAddPhoto(<?= $planta['id'] ?>)" title="Agregar foto">📸</button>
                            <button class="btn-icon btn-danger" onclick="eliminarPlanta(<?= $planta['id'] ?>, '<?= htmlspecialchars($planta['nombre']) ?>')" title="Eliminar planta">🗑️</button>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Timeline de Evolución -->
                    <div class="evolution-timeline">
                        <?php if (!empty($planta['fotos'])): ?>
                            <div class="evolution-header">
                                <h4>🌱 Evolución</h4>
                                <span class="evolution-count"><?= count($planta['fotos']) ?> momentos</span>
                            </div>
                            <div class="evolution-gallery">
                                <?php foreach ($planta['fotos'] as $index => $foto): ?>
                                    <div class="evolution-item" data-index="<?= $index ?>">
                                        <div class="evolution-image">
                                            <img src="<?= UPLOAD_DIR . htmlspecialchars($foto['archivo']) ?>" 
                                                 alt="<?= htmlspecialchars($foto['comentario']) ?>">
                                            <div class="evolution-overlay">
                                                <div class="evolution-date"><?= date('d/m/Y', strtotime($foto['fecha'])) ?></div>
                                                <div class="evolution-type"><?= ucfirst($foto['tipo_foto']) ?></div>
                                            </div>
                                        </div>
                                        <?php if ($foto['comentario']): ?>
                                            <div class="evolution-comment"><?= htmlspecialchars($foto['comentario']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-evolution">
                                <div class="no-evolution-icon">📷</div>
                                <p>
                                    <?php if (esAdmin()): ?>
                                        ¡Comienza la historia de esta planta!
                                    <?php else: ?>
                                        Esta planta aún no tiene fotos registradas
                                    <?php endif; ?>
                                </p>
                                <?php if (esAdmin()): ?>
                                    <button class="btn-minimal" onclick="toggleAddPhoto(<?= $planta['id'] ?>)">Agregar primera foto</button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario Nueva Foto - Solo para Admin -->
                        <?php if (esAdmin()): ?>
                        <div class="add-photo-form" id="addPhotoForm_<?= $planta['id'] ?>" style="display: none;">
                            <form method="POST" enctype="multipart/form-data" class="photo-form">
                                <input type="hidden" name="planta_id" value="<?= $planta['id'] ?>">
                                <div class="photo-form-row">
                                    <input type="file" name="nueva_foto" accept="image/*" required class="input-file">
                                    <select name="tipo_foto" class="input-secondary">
                                        <option value="evolucion">🌱 Evolución</option>
                                        <option value="floracion">🌸 Floración</option>
                                        <option value="fruto">🍎 Fruto</option>
                                        <option value="problema">⚠️ Problema</option>
                                    </select>
                                </div>
                                <div class="photo-form-row">
                                    <input type="text" name="comentario" placeholder="¿Qué observas?" class="input-main">
                                    <button type="submit" name="agregar_foto" class="btn-minimal">Agregar</button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>

                        <!-- Cuidados (oculto por defecto) -->
                        <div class="cuidados-section" id="cuidadosSection_<?= $planta['id'] ?>" style="display: none;">
                            <?php if (!empty($planta['cuidados'])): ?>
                                <div class="cuidados-lista">
                                    <h5>🌿 Últimos Cuidados</h5>
                                    <?php foreach (array_slice($planta['cuidados'], 0, 3) as $cuidado): ?>
                                        <div class="cuidado-item">
                                            <strong><?= ucfirst($cuidado['tipo_cuidado']) ?></strong>
                                            <span><?= date('d/m', strtotime($cuidado['fecha'])) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="add-cuidado-form">
                                <form method="POST" class="cuidado-form">
                                    <input type="hidden" name="planta_id" value="<?= $planta['id'] ?>">
                                    <div class="cuidado-form-row">
                                        <select name="tipo_cuidado" required class="input-secondary">
                                            <option value="">Tipo</option>
                                            <option value="riego">💧 Riego</option>
                                            <option value="fertilizante">🌱 Fertilizante</option>
                                            <option value="poda">✂️ Poda</option>
                                            <option value="nota">📝 Nota</option>
                                        </select>
                                        <input type="text" name="descripcion" placeholder="Descripción" class="input-main">
                                        <button type="submit" name="agregar_cuidado" class="btn-minimal">+</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($plantas)): ?>
            <div style="text-align: center; padding: 50px; color: #666;">
                <h3>🌱 ¡Bienvenido a Plants Evolution!</h3>
                <p>No tienes plantas registradas aún.</p>
                <p>¡Agrega tu primera planta usando el formulario de arriba!</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal para ver fotos grandes -->
    <div id="photoModal" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
        </div>
    </div>

    <!-- Formulario oculto para eliminar plantas -->
    <form id="eliminarPlantaForm" method="POST" style="display: none;">
        <input type="hidden" name="planta_id" id="eliminarPlantaId">
        <input type="hidden" name="eliminar_planta" value="1">
    </form>

    <script src="js/main.js"></script>
    
    <script>
    // Función para mostrar/ocultar formulario de agregar foto
    function toggleAddPhoto(plantaId) {
        const form = document.getElementById('addPhotoForm_' + plantaId);
        const isVisible = form.style.display !== 'none';
        
        // Ocultar todos los formularios abiertos
        document.querySelectorAll('.add-photo-form').forEach(f => f.style.display = 'none');
        
        // Mostrar u ocultar el formulario actual
        form.style.display = isVisible ? 'none' : 'block';
        
        if (!isVisible) {
            form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
    
    // Función para eliminar planta
    function eliminarPlanta(plantaId, nombrePlanta) {
        if (confirm(`¿Estás seguro de que quieres eliminar "${nombrePlanta}"?\n\nEsta acción eliminará la planta y todas sus fotos permanentemente.`)) {
            document.getElementById('eliminarPlantaId').value = plantaId;
            document.getElementById('eliminarPlantaForm').submit();
        }
    }
    
    // Función para mostrar/ocultar sección de cuidados
    function toggleCuidados(plantaId) {
        const section = document.getElementById('cuidadosSection_' + plantaId);
        const isVisible = section.style.display !== 'none';
        section.style.display = isVisible ? 'none' : 'block';
    }
    
    // Mejorar la galería de evolución
    document.addEventListener('DOMContentLoaded', function() {
        // Hacer las imágenes clickeables para zoom
        document.querySelectorAll('.evolution-image img').forEach(img => {
            img.addEventListener('click', function() {
                // Crear modal simple para zoom
                const modal = document.createElement('div');
                modal.className = 'image-zoom-modal';
                modal.innerHTML = `
                    <div class="zoom-modal-content">
                        <img src="${this.src}" alt="${this.alt}">
                        <button class="zoom-close">×</button>
                    </div>
                `;
                document.body.appendChild(modal);
                
                modal.addEventListener('click', () => modal.remove());
                modal.querySelector('.zoom-close').addEventListener('click', () => modal.remove());
            });
        });
    });
    </script>
    
    <style>
    .message {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .message.success {
        background: #e8f5e8;
        color: #2e7d32;
        border: 1px solid #4CAF50;
    }
    
    .message.error {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #f44336;
    }
    </style>
</body>
</html> 