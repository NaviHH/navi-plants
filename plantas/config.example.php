<?php
// Configuración de base de datos - ARCHIVO DE EJEMPLO
// Copia este archivo como config.php y personaliza los valores

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Cambia por tu contraseña de MySQL
define('DB_NAME', 'plantas');

// Crear conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// Configurar charset UTF-8
$conn->set_charset("utf8");

// Configuraciones generales
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Función para limpiar datos de entrada
function limpiar_entrada($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

// Función para formatear fechas
function formatear_fecha($fecha) {
    return date('d/m/Y H:i', strtotime($fecha));
}

// Función para calcular días desde plantación
function dias_desde_plantacion($fecha_plantacion) {
    $fecha1 = new DateTime($fecha_plantacion);
    $fecha2 = new DateTime();
    return $fecha1->diff($fecha2)->days;
}
?> 