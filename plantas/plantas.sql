-- Base de datos: plantas
CREATE DATABASE IF NOT EXISTS plantas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE plantas;

-- Tabla de plantas
CREATE TABLE IF NOT EXISTS plantas (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    nombre_cientifico VARCHAR(150) DEFAULT NULL,
    tipo VARCHAR(50) DEFAULT NULL,
    fecha_plantacion DATE NOT NULL,
    ubicacion VARCHAR(100) DEFAULT NULL,
    descripcion TEXT,
    foto_actual VARCHAR(255) DEFAULT NULL,
    estado ENUM('activa', 'inactiva', 'muerta') DEFAULT 'activa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de fotos/evolución
CREATE TABLE IF NOT EXISTS fotos (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    planta_id INT(11) NOT NULL,
    archivo VARCHAR(255) NOT NULL,
    comentario TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tipo_foto ENUM('inicial', 'evolucion', 'problema', 'floracion', 'fruto') DEFAULT 'evolucion',
    FOREIGN KEY (planta_id) REFERENCES plantas(id) ON DELETE CASCADE,
    KEY idx_planta_fecha (planta_id, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de cuidados/notas
CREATE TABLE IF NOT EXISTS cuidados (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    planta_id INT(11) NOT NULL,
    tipo_cuidado ENUM('riego', 'fertilizante', 'poda', 'trasplante', 'tratamiento', 'nota') NOT NULL,
    descripcion TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (planta_id) REFERENCES plantas(id) ON DELETE CASCADE,
    KEY idx_planta_cuidado (planta_id, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos de ejemplo
INSERT INTO plantas (nombre, tipo, fecha_plantacion, descripcion) VALUES
('Cactus Barrilla', 'Cactus', '2024-01-15', 'Mi primer cactus, muy resistente'),
('Rosa del Desierto', 'Suculenta', '2024-02-01', 'Bonita suculenta que cambió de color'),
('Ficus Benjamina', 'Árbol', '2024-01-20', 'Pequeño árbol para interior');

-- Crear índices para mejor rendimiento
CREATE INDEX idx_plantas_tipo ON plantas(tipo);
CREATE INDEX idx_plantas_estado ON plantas(estado);
CREATE INDEX idx_plantas_fecha ON plantas(fecha_plantacion);

-- Crear vista para estadísticas
CREATE VIEW vista_estadisticas AS
SELECT 
    COUNT(*) as total_plantas,
    COUNT(CASE WHEN estado = 'activa' THEN 1 END) as plantas_activas,
    COUNT(CASE WHEN estado = 'muerta' THEN 1 END) as plantas_muertas,
    COUNT(DISTINCT tipo) as tipos_diferentes,
    AVG(DATEDIFF(NOW(), fecha_plantacion)) as promedio_dias_cuidado
FROM plantas; 