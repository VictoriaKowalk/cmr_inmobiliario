-- CMR Inmobiliario - Base inicial completa
-- Motor: MySQL 8+ / charset UTF-8
-- Este archivo crea la estructura, relaciones y catálogos iniciales.
-- No crea usuarios con contraseña ni propiedades de ejemplo.
-- Para cargar provincias, departamentos, municipios y localidades oficiales,
-- ejecutar luego en el proyecto: php artisan ubicaciones:importar-georef

CREATE DATABASE IF NOT EXISTS `cmr_inmobiliario`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cmr_inmobiliario`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `historial_visitas`;
DROP TABLE IF EXISTS `visitas`;
DROP TABLE IF EXISTS `historial_oportunidades`;
DROP TABLE IF EXISTS `videos_propiedad`;
DROP TABLE IF EXISTS `imagenes_propiedad`;
DROP TABLE IF EXISTS `operaciones_propiedad`;
DROP TABLE IF EXISTS `caracteristica_propiedad`;
DROP TABLE IF EXISTS `consultas`;
DROP TABLE IF EXISTS `tasaciones`;
DROP TABLE IF EXISTS `propiedades`;
DROP TABLE IF EXISTS `caracteristicas`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `ubicaciones`;
DROP TABLE IF EXISTS `reglas_tipos_ubicacion`;
DROP TABLE IF EXISTS `tipos_ubicacion`;
DROP TABLE IF EXISTS `tipos_propiedad`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `empresas`;
DROP TABLE IF EXISTS `secuencias`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `empresas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_comercial` VARCHAR(150) NOT NULL,
  `razon_social` VARCHAR(180) NULL,
  `email` VARCHAR(255) NULL,
  `telefono` VARCHAR(50) NULL,
  `whatsapp` VARCHAR(50) NULL,
  `direccion` VARCHAR(255) NULL,
  `zona_horaria` VARCHAR(80) NOT NULL DEFAULT 'America/Argentina/Buenos_Aires',
  `logo_ruta` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `usuarios` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NULL,
  `email` VARCHAR(255) NOT NULL,
  `telefono` VARCHAR(50) NULL,
  `celular` VARCHAR(50) NULL,
  `direccion` VARCHAR(255) NULL,
  `dni` VARCHAR(20) NULL,
  `fecha_nacimiento` DATE NULL,
  `contrasenia` VARCHAR(255) NOT NULL,
  `rol` VARCHAR(30) NOT NULL DEFAULT 'administrador',
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `ultimo_acceso_en` TIMESTAMP NULL,
  `recordar_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unico` (`email`),
  UNIQUE KEY `usuarios_dni_unico` (`dni`),
  KEY `usuarios_rol_indice` (`rol`),
  KEY `usuarios_activo_indice` (`activo`)
) ENGINE=InnoDB;

CREATE TABLE `tipos_propiedad` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipos_propiedad_nombre_unico` (`nombre`),
  KEY `tipos_propiedad_activo_indice` (`activo`)
) ENGINE=InnoDB;

CREATE TABLE `tipos_ubicacion` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(50) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `orden` TINYINT UNSIGNED NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipos_ubicacion_codigo_unico` (`codigo`),
  KEY `tipos_ubicacion_activo_indice` (`activo`)
) ENGINE=InnoDB;

CREATE TABLE `reglas_tipos_ubicacion` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_ubicacion_padre_id` BIGINT UNSIGNED NOT NULL,
  `tipo_ubicacion_hijo_id` BIGINT UNSIGNED NOT NULL,
  `activa` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `regla_ubicacion_padre_hijo_unica` (`tipo_ubicacion_padre_id`, `tipo_ubicacion_hijo_id`),
  KEY `regla_ubicacion_activa_indice` (`activa`),
  CONSTRAINT `regla_ubicacion_padre_fk` FOREIGN KEY (`tipo_ubicacion_padre_id`) REFERENCES `tipos_ubicacion` (`id`) ON DELETE CASCADE,
  CONSTRAINT `regla_ubicacion_hijo_fk` FOREIGN KEY (`tipo_ubicacion_hijo_id`) REFERENCES `tipos_ubicacion` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `ubicaciones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ubicacion_padre_id` BIGINT UNSIGNED NULL,
  `tipo_ubicacion_id` BIGINT UNSIGNED NULL,
  `nombre` VARCHAR(150) NULL,
  `nombre_normalizado` VARCHAR(150) NULL,
  `codigo_georef` VARCHAR(100) NULL,
  `origen` VARCHAR(30) NOT NULL DEFAULT 'personalizado',
  `pais` VARCHAR(100) NOT NULL,
  `zona` VARCHAR(150) NULL,
  `localidad` VARCHAR(150) NULL,
  `categoria_barrio` VARCHAR(150) NULL,
  `barrio_principal` VARCHAR(150) NULL,
  `barrio` VARCHAR(150) NULL,
  `nombre_completo` VARCHAR(700) NOT NULL,
  `activa` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ubicacion_padre_tipo_nombre_unica` (`ubicacion_padre_id`, `tipo_ubicacion_id`, `nombre_normalizado`),
  UNIQUE KEY `ubicacion_tipo_georef_unica` (`tipo_ubicacion_id`, `codigo_georef`),
  KEY `ubicacion_padre_tipo_indice` (`ubicacion_padre_id`, `tipo_ubicacion_id`),
  KEY `ubicacion_origen_indice` (`origen`),
  KEY `ubicacion_activa_indice` (`activa`),
  KEY `ubicacion_pais_indice` (`pais`),
  KEY `ubicacion_zona_indice` (`zona`),
  KEY `ubicacion_localidad_indice` (`localidad`),
  KEY `ubicacion_barrio_principal_indice` (`barrio_principal`),
  KEY `ubicacion_barrio_indice` (`barrio`),
  CONSTRAINT `ubicacion_padre_fk` FOREIGN KEY (`ubicacion_padre_id`) REFERENCES `ubicaciones` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `ubicacion_tipo_fk` FOREIGN KEY (`tipo_ubicacion_id`) REFERENCES `tipos_ubicacion` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `caracteristicas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `categoria` VARCHAR(30) NOT NULL,
  `activa` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `caracteristica_nombre_categoria_unica` (`nombre`, `categoria`),
  KEY `caracteristica_categoria_indice` (`categoria`),
  KEY `caracteristica_activa_indice` (`activa`)
) ENGINE=InnoDB;

CREATE TABLE `propiedades` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_propiedad_id` BIGINT UNSIGNED NOT NULL,
  `ubicacion_id` BIGINT UNSIGNED NOT NULL,
  `titulo` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `codigo_interno` VARCHAR(50) NOT NULL,
  `expensas` DECIMAL(15,2) UNSIGNED NULL,
  `expensas_moneda` VARCHAR(10) NULL,
  `descripcion_corta` VARCHAR(500) NULL,
  `descripcion` TEXT NULL,
  `direccion` VARCHAR(255) NULL,
  `direccion_normalizada` VARCHAR(255) NULL,
  `mostrar_direccion` TINYINT(1) NOT NULL DEFAULT 0,
  `ambientes` SMALLINT UNSIGNED NULL,
  `dormitorios` SMALLINT UNSIGNED NULL,
  `banios` SMALLINT UNSIGNED NULL,
  `cocheras` SMALLINT UNSIGNED NULL,
  `superficie_total` DECIMAL(12,2) UNSIGNED NULL,
  `superficie_cubierta` DECIMAL(12,2) UNSIGNED NULL,
  `superficie_descubierta` DECIMAL(12,2) UNSIGNED NULL,
  `superficie_terreno` DECIMAL(12,2) UNSIGNED NULL,
  `antiguedad` SMALLINT UNSIGNED NULL,
  `orientacion` VARCHAR(50) NULL,
  `latitud` DECIMAL(10,7) NULL,
  `longitud` DECIMAL(10,7) NULL,
  `proveedor_geocodificacion` VARCHAR(50) NULL,
  `place_id` VARCHAR(255) NULL,
  `ubicacion_confirmada` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `propiedad_slug_unica` (`slug`),
  UNIQUE KEY `propiedad_codigo_interno_unico` (`codigo_interno`),
  KEY `propiedad_tipo_fk_indice` (`tipo_propiedad_id`),
  KEY `propiedad_ubicacion_fk_indice` (`ubicacion_id`),
  KEY `propiedad_titulo_indice` (`titulo`),
  KEY `propiedad_ambientes_indice` (`ambientes`),
  KEY `propiedad_dormitorios_indice` (`dormitorios`),
  CONSTRAINT `propiedad_tipo_fk` FOREIGN KEY (`tipo_propiedad_id`) REFERENCES `tipos_propiedad` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `propiedad_ubicacion_fk` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `caracteristica_propiedad` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `propiedad_id` BIGINT UNSIGNED NOT NULL,
  `caracteristica_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `propiedad_caracteristica_unica` (`propiedad_id`, `caracteristica_id`),
  CONSTRAINT `caracteristica_propiedad_propiedad_fk` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE,
  CONSTRAINT `caracteristica_propiedad_caracteristica_fk` FOREIGN KEY (`caracteristica_id`) REFERENCES `caracteristicas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `operaciones_propiedad` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `propiedad_id` BIGINT UNSIGNED NOT NULL,
  `tipo_operacion` VARCHAR(30) NOT NULL,
  `moneda` VARCHAR(10) NULL,
  `precio` DECIMAL(15,2) UNSIGNED NULL,
  `estado` VARCHAR(30) NOT NULL DEFAULT 'pausada',
  `publicada_en` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `propiedad_operacion_unica` (`propiedad_id`, `tipo_operacion`),
  KEY `operacion_precio_indice` (`precio`),
  KEY `operacion_estado_indice` (`estado`),
  KEY `operacion_tipo_estado_indice` (`tipo_operacion`, `estado`),
  KEY `operacion_publicada_indice` (`publicada_en`),
  CONSTRAINT `operacion_propiedad_fk` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `imagenes_propiedad` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `propiedad_id` BIGINT UNSIGNED NOT NULL,
  `ruta` VARCHAR(500) NOT NULL,
  `nombre_original` VARCHAR(255) NULL,
  `orden` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `portada` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `imagen_propiedad_orden_indice` (`propiedad_id`, `orden`),
  KEY `imagen_propiedad_portada_indice` (`propiedad_id`, `portada`),
  CONSTRAINT `imagen_propiedad_fk` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `videos_propiedad` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `propiedad_id` BIGINT UNSIGNED NOT NULL,
  `tipo` VARCHAR(30) NOT NULL,
  `titulo` VARCHAR(255) NULL,
  `ruta` VARCHAR(500) NULL,
  `url` VARCHAR(500) NULL,
  `youtube_id` VARCHAR(50) NULL,
  `orden` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `video_propiedad_orden_indice` (`propiedad_id`, `orden`),
  KEY `video_propiedad_tipo_indice` (`propiedad_id`, `tipo`),
  CONSTRAINT `video_propiedad_fk` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `consultas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `propiedad_id` BIGINT UNSIGNED NULL,
  `operacion_propiedad_id` BIGINT UNSIGNED NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `email` VARCHAR(255) NULL,
  `telefono` VARCHAR(50) NULL,
  `mensaje` TEXT NOT NULL,
  `estado_seguimiento` VARCHAR(30) NOT NULL DEFAULT 'nueva',
  `responsable_id` BIGINT UNSIGNED NULL,
  `prioridad` VARCHAR(20) NOT NULL DEFAULT 'media',
  `proxima_tarea` VARCHAR(255) NULL,
  `proxima_tarea_en` DATETIME NULL,
  `motivo_cierre` VARCHAR(500) NULL,
  `cerrada_en` TIMESTAMP NULL,
  `notas_internas` TEXT NULL,
  `leida_en` TIMESTAMP NULL,
  `atendida_en` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `consulta_propiedad_indice` (`propiedad_id`),
  KEY `consulta_operacion_indice` (`operacion_propiedad_id`),
  KEY `consulta_responsable_indice` (`responsable_id`),
  KEY `consulta_nombre_indice` (`nombre`),
  KEY `consulta_email_indice` (`email`),
  KEY `consulta_telefono_indice` (`telefono`),
  KEY `consulta_estado_indice` (`estado_seguimiento`),
  KEY `consulta_prioridad_indice` (`prioridad`),
  KEY `consulta_proxima_tarea_indice` (`proxima_tarea_en`),
  KEY `consulta_creada_indice` (`created_at`),
  CONSTRAINT `consulta_propiedad_fk` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `consulta_operacion_fk` FOREIGN KEY (`operacion_propiedad_id`) REFERENCES `operaciones_propiedad` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `consulta_responsable_fk` FOREIGN KEY (`responsable_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `tasaciones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `email` VARCHAR(255) NULL,
  `telefono` VARCHAR(50) NULL,
  `tipo_propiedad_id` BIGINT UNSIGNED NULL,
  `ubicacion_texto` VARCHAR(500) NOT NULL,
  `direccion` VARCHAR(255) NULL,
  `mensaje` TEXT NULL,
  `estado_seguimiento` VARCHAR(30) NOT NULL DEFAULT 'nueva',
  `responsable_id` BIGINT UNSIGNED NULL,
  `prioridad` VARCHAR(20) NOT NULL DEFAULT 'media',
  `proxima_tarea` VARCHAR(255) NULL,
  `proxima_tarea_en` DATETIME NULL,
  `motivo_cierre` VARCHAR(500) NULL,
  `cerrada_en` TIMESTAMP NULL,
  `notas_internas` TEXT NULL,
  `leida_en` TIMESTAMP NULL,
  `atendida_en` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `tasacion_tipo_indice` (`tipo_propiedad_id`),
  KEY `tasacion_responsable_indice` (`responsable_id`),
  KEY `tasacion_nombre_indice` (`nombre`),
  KEY `tasacion_email_indice` (`email`),
  KEY `tasacion_telefono_indice` (`telefono`),
  KEY `tasacion_estado_indice` (`estado_seguimiento`),
  KEY `tasacion_prioridad_indice` (`prioridad`),
  KEY `tasacion_proxima_tarea_indice` (`proxima_tarea_en`),
  KEY `tasacion_creada_indice` (`created_at`),
  CONSTRAINT `tasacion_tipo_fk` FOREIGN KEY (`tipo_propiedad_id`) REFERENCES `tipos_propiedad` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `tasacion_responsable_fk` FOREIGN KEY (`responsable_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `historial_oportunidades` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `oportunidad_type` VARCHAR(255) NOT NULL,
  `oportunidad_id` BIGINT UNSIGNED NOT NULL,
  `usuario_id` BIGINT UNSIGNED NULL,
  `evento` VARCHAR(50) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `cambios` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `historial_oportunidad_indice` (`oportunidad_type`, `oportunidad_id`),
  KEY `historial_usuario_indice` (`usuario_id`),
  KEY `historial_evento_indice` (`evento`),
  CONSTRAINT `historial_oportunidad_usuario_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `visitas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `oportunidad_type` VARCHAR(255) NULL,
  `oportunidad_id` BIGINT UNSIGNED NULL,
  `propiedad_id` BIGINT UNSIGNED NOT NULL,
  `asesor_id` BIGINT UNSIGNED NOT NULL,
  `interesado_nombre` VARCHAR(150) NOT NULL,
  `interesado_email` VARCHAR(255) NULL,
  `interesado_telefono` VARCHAR(50) NULL,
  `inicio` DATETIME NOT NULL,
  `fin` DATETIME NOT NULL,
  `estado` VARCHAR(30) NOT NULL DEFAULT 'pendiente',
  `lugar` VARCHAR(255) NULL,
  `observaciones` TEXT NULL,
  `motivo_cancelacion` VARCHAR(500) NULL,
  `resultado` VARCHAR(30) NULL,
  `comentarios_resultado` TEXT NULL,
  `proxima_accion` VARCHAR(255) NULL,
  `proxima_accion_en` DATETIME NULL,
  `recordatorio_cliente_enviado_en` TIMESTAMP NULL,
  `recordatorio_asesor_enviado_en` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `visita_oportunidad_indice` (`oportunidad_type`, `oportunidad_id`),
  KEY `visita_propiedad_indice` (`propiedad_id`),
  KEY `visita_asesor_indice` (`asesor_id`),
  KEY `visita_inicio_indice` (`inicio`),
  KEY `visita_fin_indice` (`fin`),
  KEY `visita_estado_indice` (`estado`),
  CONSTRAINT `visita_propiedad_fk` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `visita_asesor_fk` FOREIGN KEY (`asesor_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `historial_visitas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `visita_id` BIGINT UNSIGNED NOT NULL,
  `usuario_id` BIGINT UNSIGNED NULL,
  `evento` VARCHAR(50) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `datos` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `historial_visita_usuario_indice` (`usuario_id`),
  CONSTRAINT `historial_visita_fk` FOREIGN KEY (`visita_id`) REFERENCES `visitas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historial_visita_usuario_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `secuencias` (
  `clave` VARCHAR(255) NOT NULL,
  `ultimo_numero` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB;

CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_usuario_indice` (`user_id`),
  KEY `sessions_actividad_indice` (`last_activity`),
  CONSTRAINT `sessions_usuario_fk` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiracion_indice` (`expiration`)
) ENGINE=InnoDB;

CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiracion_indice` (`expiration`)
) ENGINE=InnoDB;

CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_indice` (`queue`)
) ENGINE=InnoDB;

CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unico` (`uuid`)
) ENGINE=InnoDB;

-- Catálogos: tipos de propiedad, características y estructura de ubicaciones.
INSERT INTO `tipos_propiedad` (`nombre`, `activo`, `created_at`, `updated_at`) VALUES
('Terreno',1,NOW(),NOW()),('Departamento',1,NOW(),NOW()),('Casa',1,NOW(),NOW()),
('Quinta',1,NOW(),NOW()),('Oficina',1,NOW(),NOW()),('Amarra',1,NOW(),NOW()),
('Local',1,NOW(),NOW()),('Edificio Comercial',1,NOW(),NOW()),('Campo',1,NOW(),NOW()),
('Cochera',1,NOW(),NOW()),('Hotel',1,NOW(),NOW()),('Nave Industrial',1,NOW(),NOW()),
('PH',1,NOW(),NOW()),('Depósito',1,NOW(),NOW()),('Fondo de Comercio',1,NOW(),NOW()),
('Baulera',1,NOW(),NOW()),('Bodega',1,NOW(),NOW()),('Finca',1,NOW(),NOW()),
('Chacra',1,NOW(),NOW()),('Cama náutica',1,NOW(),NOW()),('Isla',1,NOW(),NOW()),
('Terraza',1,NOW(),NOW()),('Galpón',1,NOW(),NOW()),('Villa',1,NOW(),NOW()),
('Terreno comercial',1,NOW(),NOW()),('Terreno industrial',1,NOW(),NOW()),
('Hacienda',1,NOW(),NOW()),('Haras',1,NOW(),NOW()),('Consultorio',1,NOW(),NOW()),
('Monoambiente',1,NOW(),NOW()),('Terreno en condominio',1,NOW(),NOW());

INSERT INTO `tipos_ubicacion` (`codigo`,`nombre`,`orden`,`activo`,`created_at`,`updated_at`) VALUES
('pais','País',1,1,NOW(),NOW()),('provincia','Provincia',2,1,NOW(),NOW()),
('zona_comercial','Zona comercial',3,1,NOW(),NOW()),('partido','Partido',4,1,NOW(),NOW()),
('departamento','Departamento',4,1,NOW(),NOW()),('comuna','Comuna',4,1,NOW(),NOW()),
('municipio','Municipio',5,1,NOW(),NOW()),('localidad','Localidad',6,1,NOW(),NOW()),
('barrio','Barrio',7,1,NOW(),NOW()),('subbarrio','Subbarrio',8,1,NOW(),NOW());

INSERT INTO `reglas_tipos_ubicacion` (`tipo_ubicacion_padre_id`,`tipo_ubicacion_hijo_id`,`activa`,`created_at`,`updated_at`)
SELECT padre.id, hijo.id, 1, NOW(), NOW()
FROM `tipos_ubicacion` padre JOIN `tipos_ubicacion` hijo
WHERE (padre.codigo,hijo.codigo) IN (
 ('pais','provincia'),('provincia','zona_comercial'),('provincia','partido'),
 ('provincia','departamento'),('provincia','comuna'),('provincia','municipio'),('provincia','localidad'),
 ('zona_comercial','partido'),('zona_comercial','departamento'),('zona_comercial','comuna'),
 ('zona_comercial','municipio'),('zona_comercial','localidad'),('partido','municipio'),
 ('partido','localidad'),('partido','barrio'),('departamento','municipio'),
 ('departamento','localidad'),('departamento','barrio'),('comuna','localidad'),
 ('comuna','barrio'),('municipio','localidad'),('municipio','barrio'),
 ('localidad','barrio'),('localidad','subbarrio'),('barrio','subbarrio')
);

INSERT INTO `caracteristicas` (`nombre`,`categoria`,`activa`,`created_at`,`updated_at`) VALUES
('Agua Corriente','servicio',1,NOW(),NOW()),('Cloaca','servicio',1,NOW(),NOW()),('Gas Natural','servicio',1,NOW(),NOW()),('Internet','servicio',1,NOW(),NOW()),('Electricidad','servicio',1,NOW(),NOW()),('Pavimento','servicio',1,NOW(),NOW()),('Teléfono','servicio',1,NOW(),NOW()),('Cable','servicio',1,NOW(),NOW()),
('Altillo','ambiente',1,NOW(),NOW()),('Balcón','ambiente',1,NOW(),NOW()),('Baulera','ambiente',1,NOW(),NOW()),('Cocina','ambiente',1,NOW(),NOW()),('Comedor diario','ambiente',1,NOW(),NOW()),('Dependencia','ambiente',1,NOW(),NOW()),('Oficina','ambiente',1,NOW(),NOW()),('Hall','ambiente',1,NOW(),NOW()),('Jardín','ambiente',1,NOW(),NOW()),('Lavadero','ambiente',1,NOW(),NOW()),('Living comedor','ambiente',1,NOW(),NOW()),('Patio','ambiente',1,NOW(),NOW()),('Sótano','ambiente',1,NOW(),NOW()),('Terraza','ambiente',1,NOW(),NOW()),('Toilette','ambiente',1,NOW(),NOW()),('Vestidor','ambiente',1,NOW(),NOW()),
('Tiene cartel','cartel',1,NOW(),NOW()),('Sin cartel','cartel',1,NOW(),NOW()),
('Oportunidad','observacion',1,NOW(),NOW()),('Acepta Lote','observacion',1,NOW(),NOW()),('Acepta Permuta','observacion',1,NOW(),NOW()),('Apto Crédito','observacion',1,NOW(),NOW()),('Venta con renta','observacion',1,NOW(),NOW()),('Acepta mascotas','observacion',1,NOW(),NOW()),('Apto profesional','observacion',1,NOW(),NOW()),('Propiedad destacada','observacion',1,NOW(),NOW()),
('Al río','preferencia_lote',1,NOW(),NOW()),('Interno','preferencia_lote',1,NOW(),NOW()),('Al golf','preferencia_lote',1,NOW(),NOW()),('Al lago','preferencia_lote',1,NOW(),NOW()),('Perimetral','preferencia_lote',1,NOW(),NOW()),('Lindero interno','preferencia_lote',1,NOW(),NOW()),
('Aire acondicionado individual','amenity',1,NOW(),NOW()),('Alarma','amenity',1,NOW(),NOW()),('Amoblado','amenity',1,NOW(),NOW()),('Calefacción','amenity',1,NOW(),NOW()),('Centro de deportes','amenity',1,NOW(),NOW()),('Gimnasio','amenity',1,NOW(),NOW()),('Hidromasaje','amenity',1,NOW(),NOW()),('Parrilla','amenity',1,NOW(),NOW()),('Quincho','amenity',1,NOW(),NOW()),('Sala de juegos','amenity',1,NOW(),NOW()),('Sauna','amenity',1,NOW(),NOW()),('Solarium','amenity',1,NOW(),NOW()),('SUM','amenity',1,NOW(),NOW()),('Cancha de paddle','amenity',1,NOW(),NOW()),('Pileta','amenity',1,NOW(),NOW()),('Riego automático','amenity',1,NOW(),NOW()),('Seguridad privada','amenity',1,NOW(),NOW()),('Luminoso','amenity',1,NOW(),NOW()),('Amarra','amenity',1,NOW(),NOW()),('Laundry','amenity',1,NOW(),NOW()),('Seguridad 24hs','amenity',1,NOW(),NOW());

 INSERT INTO `ubicaciones` (`tipo_ubicacion_id`,`nombre`,`nombre_normalizado`,`origen`,`pais`,`nombre_completo`,`activa`,`created_at`,`updated_at`)
SELECT id, 'Argentina', 'argentina', 'semilla', 'Argentina', 'Argentina', 1, NOW(), NOW()
FROM `tipos_ubicacion` WHERE `codigo` = 'pais';


+
-- Ubicaciones comerciales de Nordelta obtenidas de Tokko (22/09/2026).
-- Se incluyen 43 barrios y 38 subbarrios; no se importan áreas ni condominios.
INSERT INTO ubicaciones (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,pais,nombre_completo,activa,created_at,updated_at)
SELECT argentina.id, tipo.id, 'Buenos Aires', 'buenos aires', 'semilla', 'Argentina', 'Argentina | Buenos Aires', 1, NOW(), NOW()
FROM ubicaciones argentina JOIN tipos_ubicacion tipo
WHERE argentina.nombre_completo = 'Argentina' AND tipo.codigo = 'provincia';

INSERT INTO ubicaciones (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,pais,nombre_completo,activa,created_at,updated_at)
SELECT provincia.id, tipo.id, 'G.B.A. Zona Norte', 'g.b.a. zona norte', 'semilla', 'Argentina', 'Argentina | Buenos Aires | G.B.A. Zona Norte', 1, NOW(), NOW()
FROM ubicaciones provincia JOIN tipos_ubicacion tipo
WHERE provincia.nombre_completo = 'Argentina | Buenos Aires' AND tipo.codigo = 'zona_comercial';

INSERT INTO ubicaciones (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,pais,nombre_completo,activa,created_at,updated_at)
SELECT zona.id, tipo.id, 'Partido de Tigre', 'partido de tigre', 'semilla', 'Argentina', 'Argentina | Buenos Aires | G.B.A. Zona Norte | Partido de Tigre', 1, NOW(), NOW()
FROM ubicaciones zona JOIN tipos_ubicacion tipo
WHERE zona.nombre_completo = 'Argentina | Buenos Aires | G.B.A. Zona Norte' AND tipo.codigo = 'partido';

INSERT INTO ubicaciones (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,pais,nombre_completo,activa,created_at,updated_at)
SELECT partido.id, tipo.id, 'Municipio de Tigre', 'municipio de tigre', 'semilla', 'Argentina', 'Argentina | Buenos Aires | G.B.A. Zona Norte | Partido de Tigre | Municipio de Tigre', 1, NOW(), NOW()
FROM ubicaciones partido JOIN tipos_ubicacion tipo
WHERE partido.nombre_completo = 'Argentina | Buenos Aires | G.B.A. Zona Norte | Partido de Tigre' AND tipo.codigo = 'municipio';

INSERT INTO ubicaciones (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,pais,nombre_completo,activa,created_at,updated_at)
SELECT municipio.id, tipo.id, 'Nordelta', 'nordelta', 'tokko', 'Argentina', 'Argentina | Buenos Aires | G.B.A. Zona Norte | Partido de Tigre | Municipio de Tigre | Nordelta', 1, NOW(), NOW()
FROM ubicaciones municipio JOIN tipos_ubicacion tipo
WHERE municipio.nombre_completo = 'Argentina | Buenos Aires | G.B.A. Zona Norte | Partido de Tigre | Municipio de Tigre' AND tipo.codigo = 'localidad';

INSERT INTO ubicaciones (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,pais,nombre_completo,activa,created_at,updated_at)
SELECT nordelta.id, tipo.id, datos.nombre, datos.nombre_normalizado, 'tokko', 'Argentina', CONCAT(nordelta.nombre_completo, ' | ', datos.nombre), 1, NOW(), NOW()
FROM (
SELECT 'Aqua Golf' AS nombre, 'aqua golf' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre, 'bahia grande' AS nombre_normalizado
UNION ALL
SELECT 'Barrancas del Lago' AS nombre, 'barrancas del lago' AS nombre_normalizado
UNION ALL
SELECT 'Cabos del Lago' AS nombre, 'cabos del lago' AS nombre_normalizado
UNION ALL
SELECT 'Carpinchos' AS nombre, 'carpinchos' AS nombre_normalizado
UNION ALL
SELECT 'Casuarinas' AS nombre, 'casuarinas' AS nombre_normalizado
UNION ALL
SELECT 'El Golf' AS nombre, 'el golf' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre, 'el palmar' AS nombre_normalizado
UNION ALL
SELECT 'El Portal' AS nombre, 'el portal' AS nombre_normalizado
UNION ALL
SELECT 'El Yacht' AS nombre, 'el yacht' AS nombre_normalizado
UNION ALL
SELECT 'Gaviotas' AS nombre, 'gaviotas' AS nombre_normalizado
UNION ALL
SELECT 'Islas del Canal' AS nombre, 'islas del canal' AS nombre_normalizado
UNION ALL
SELECT 'Islas del Golf' AS nombre, 'islas del golf' AS nombre_normalizado
UNION ALL
SELECT 'La Alameda' AS nombre, 'la alameda' AS nombre_normalizado
UNION ALL
SELECT 'La Isla' AS nombre, 'la isla' AS nombre_normalizado
UNION ALL
SELECT 'Lago Escondido' AS nombre, 'lago escondido' AS nombre_normalizado
UNION ALL
SELECT 'Lagos del Golf' AS nombre, 'lagos del golf' AS nombre_normalizado
UNION ALL
SELECT 'Las Caletas' AS nombre, 'las caletas' AS nombre_normalizado
UNION ALL
SELECT 'Las Glorietas' AS nombre, 'las glorietas' AS nombre_normalizado
UNION ALL
SELECT 'Las Tipas' AS nombre, 'las tipas' AS nombre_normalizado
UNION ALL
SELECT 'Loft del Sendero' AS nombre, 'loft del sendero' AS nombre_normalizado
UNION ALL
SELECT 'Los Alisos' AS nombre, 'los alisos' AS nombre_normalizado
UNION ALL
SELECT 'Los Castaños' AS nombre, 'los castanos' AS nombre_normalizado
UNION ALL
SELECT 'Los Castores' AS nombre, 'los castores' AS nombre_normalizado
UNION ALL
SELECT 'Los Lagos' AS nombre, 'los lagos' AS nombre_normalizado
UNION ALL
SELECT 'Los Puentes' AS nombre, 'los puentes' AS nombre_normalizado
UNION ALL
SELECT 'Los Sauces' AS nombre, 'los sauces' AS nombre_normalizado
UNION ALL
SELECT 'Marinas del Este' AS nombre, 'marinas del este' AS nombre_normalizado
UNION ALL
SELECT 'Nordelta Paseo de la Bahia' AS nombre, 'nordelta paseo de la bahia' AS nombre_normalizado
UNION ALL
SELECT 'Oceana Nordelta' AS nombre, 'oceana nordelta' AS nombre_normalizado
UNION ALL
SELECT 'Plaza del Sendero' AS nombre, 'plaza del sendero' AS nombre_normalizado
UNION ALL
SELECT 'Portezuelo' AS nombre, 'portezuelo' AS nombre_normalizado
UNION ALL
SELECT 'Portobello' AS nombre, 'portobello' AS nombre_normalizado
UNION ALL
SELECT 'Puerto Escondido' AS nombre, 'puerto escondido' AS nombre_normalizado
UNION ALL
SELECT 'Qbay Yacht' AS nombre, 'qbay yacht' AS nombre_normalizado
UNION ALL
SELECT 'Quartier' AS nombre, 'quartier' AS nombre_normalizado
UNION ALL
SELECT 'Quarzo' AS nombre, 'quarzo' AS nombre_normalizado
UNION ALL
SELECT 'Rivera' AS nombre, 'rivera' AS nombre_normalizado
UNION ALL
SELECT 'Sendero' AS nombre, 'sendero' AS nombre_normalizado
UNION ALL
SELECT 'Silvestre' AS nombre, 'silvestre' AS nombre_normalizado
UNION ALL
SELECT 'Solares del Portezuelo' AS nombre, 'solares del portezuelo' AS nombre_normalizado
UNION ALL
SELECT 'Virazon' AS nombre, 'virazon' AS nombre_normalizado
UNION ALL
SELECT 'Yoo Nordelta' AS nombre, 'yoo nordelta' AS nombre_normalizado
) datos
JOIN ubicaciones nordelta ON nordelta.nombre_completo = 'Argentina | Buenos Aires | G.B.A. Zona Norte | Partido de Tigre | Municipio de Tigre | Nordelta'
JOIN tipos_ubicacion tipo ON tipo.codigo = 'barrio';

INSERT INTO ubicaciones (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,pais,nombre_completo,activa,created_at,updated_at)
SELECT barrio.id, tipo.id, datos.nombre, datos.nombre_normalizado, 'tokko', 'Argentina', CONCAT(barrio.nombre_completo, ' | ', datos.nombre), 1, NOW(), NOW()
FROM (
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'Bahia Grande Amarras' AS nombre, 'bahia grande amarras' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'El Reflejo' AS nombre, 'el reflejo' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'Lofts de la Bahía' AS nombre, 'lofts de la bahia' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'Marinas del Canal' AS nombre, 'marinas del canal' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'Miradores de la Bahia' AS nombre, 'miradores de la bahia' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'Ribera' AS nombre, 'ribera' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'Terrazas de la Bahia I' AS nombre, 'terrazas de la bahia i' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'Terrazas de la Bahia II' AS nombre, 'terrazas de la bahia ii' AS nombre_normalizado
UNION ALL
SELECT 'Bahia Grande' AS nombre_padre, 'bahia grande' AS padre_normalizado, 'Vista Bahia' AS nombre, 'vista bahia' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Chateau del Palmar' AS nombre, 'chateau del palmar' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Del Lago Condominium' AS nombre, 'del lago condominium' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Homes II' AS nombre, 'homes ii' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Homes III' AS nombre, 'homes iii' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Infinity Residences' AS nombre, 'infinity residences' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Insignia Nordelta' AS nombre, 'insignia nordelta' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Jardines del Palmar' AS nombre, 'jardines del palmar' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Posadas Nordelta' AS nombre, 'posadas nordelta' AS nombre_normalizado
UNION ALL
SELECT 'El Palmar' AS nombre_padre, 'el palmar' AS padre_normalizado, 'Zerena' AS nombre, 'zerena' AS nombre_normalizado
UNION ALL
SELECT 'El Portal' AS nombre_padre, 'el portal' AS padre_normalizado, 'Antares' AS nombre, 'antares' AS nombre_normalizado
UNION ALL
SELECT 'El Portal' AS nombre_padre, 'el portal' AS padre_normalizado, 'Chateau Del Portal' AS nombre, 'chateau del portal' AS nombre_normalizado
UNION ALL
SELECT 'El Portal' AS nombre_padre, 'el portal' AS padre_normalizado, 'North Coral Plaza' AS nombre, 'north coral plaza' AS nombre_normalizado
UNION ALL
SELECT 'El Portal' AS nombre_padre, 'el portal' AS padre_normalizado, 'Puerta Norte' AS nombre, 'puerta norte' AS nombre_normalizado
UNION ALL
SELECT 'El Portal' AS nombre_padre, 'el portal' AS padre_normalizado, 'Quartier Nordelta' AS nombre, 'quartier nordelta' AS nombre_normalizado
UNION ALL
SELECT 'El Portal' AS nombre_padre, 'el portal' AS padre_normalizado, 'Vientos del Delta' AS nombre, 'vientos del delta' AS nombre_normalizado
UNION ALL
SELECT 'Islas del Canal' AS nombre_padre, 'islas del canal' AS padre_normalizado, 'Acqua Rio' AS nombre, 'acqua rio' AS nombre_normalizado
UNION ALL
SELECT 'Islas del Canal' AS nombre_padre, 'islas del canal' AS padre_normalizado, 'Ribera' AS nombre, 'ribera' AS nombre_normalizado
UNION ALL
SELECT 'Islas del Golf' AS nombre_padre, 'islas del golf' AS padre_normalizado, 'Qbay Golf' AS nombre, 'qbay golf' AS nombre_normalizado
UNION ALL
SELECT 'Los Castaños' AS nombre_padre, 'los castanos' AS padre_normalizado, 'La Balconada' AS nombre, 'la balconada' AS nombre_normalizado
UNION ALL
SELECT 'Los Castaños' AS nombre_padre, 'los castanos' AS padre_normalizado, 'Las Piedras' AS nombre, 'las piedras' AS nombre_normalizado
UNION ALL
SELECT 'Nordelta Paseo de la Bahia' AS nombre_padre, 'nordelta paseo de la bahia' AS padre_normalizado, 'Paseo de la Bahia - Studios I' AS nombre, 'paseo de la bahia - studios i' AS nombre_normalizado
UNION ALL
SELECT 'Nordelta Paseo de la Bahia' AS nombre_padre, 'nordelta paseo de la bahia' AS padre_normalizado, 'Paseo de la Bahia - Studios II' AS nombre, 'paseo de la bahia - studios ii' AS nombre_normalizado
UNION ALL
SELECT 'Puerto Escondido' AS nombre_padre, 'puerto escondido' AS padre_normalizado, 'Espigon 12' AS nombre, 'espigon 12' AS nombre_normalizado
UNION ALL
SELECT 'Puerto Escondido' AS nombre_padre, 'puerto escondido' AS padre_normalizado, 'Hanami park' AS nombre, 'hanami park' AS nombre_normalizado
UNION ALL
SELECT 'Puerto Escondido' AS nombre_padre, 'puerto escondido' AS padre_normalizado, 'Qbay Rio' AS nombre, 'qbay rio' AS nombre_normalizado
UNION ALL
SELECT 'Puerto Escondido' AS nombre_padre, 'puerto escondido' AS padre_normalizado, 'The Kiri' AS nombre, 'the kiri' AS nombre_normalizado
UNION ALL
SELECT 'Puerto Escondido' AS nombre_padre, 'puerto escondido' AS padre_normalizado, 'Vilago' AS nombre, 'vilago' AS nombre_normalizado
UNION ALL
SELECT 'Sendero' AS nombre_padre, 'sendero' AS padre_normalizado, 'Casas del Sendero' AS nombre, 'casas del sendero' AS nombre_normalizado
UNION ALL
SELECT 'Sendero' AS nombre_padre, 'sendero' AS padre_normalizado, 'Lago del Sendero' AS nombre, 'lago del sendero' AS nombre_normalizado
) datos
JOIN ubicaciones nordelta ON nordelta.nombre_completo = 'Argentina | Buenos Aires | G.B.A. Zona Norte | Partido de Tigre | Municipio de Tigre | Nordelta'
JOIN ubicaciones barrio ON barrio.ubicacion_padre_id = nordelta.id AND barrio.nombre_normalizado = datos.padre_normalizado
JOIN tipos_ubicacion tipo ON tipo.codigo = 'subbarrio';
INSERT INTO `secuencias` (`clave`,`ultimo_numero`,`created_at`,`updated_at`)
VALUES ('codigo_propiedad',0,NOW(),NOW());

-- El esquema ya está completo: Laravel no volverá a ejecutar sus migraciones.
INSERT INTO `migrations` (`migration`,`batch`) VALUES
('0001_01_01_000001_create_cache_table',1),
('0001_01_01_000002_create_jobs_table',1),
('2026_06_24_000000_create_usuarios_table',1),
('2026_06_24_000100_create_tipos_propiedad_table',1),
('2026_06_24_000200_create_ubicaciones_table',1),
('2026_06_24_000201_add_unique_index_to_ubicaciones_table',1),
('2026_06_24_000250_create_caracteristicas_table',1),
('2026_06_24_000300_create_propiedades_table',1),
('2026_06_24_000350_create_caracteristica_propiedad_table',1),
('2026_06_24_000360_seed_nuevas_caracteristicas_y_migrar_booleanos',1),
('2026_06_24_000370_drop_legacy_characteristic_columns_from_propiedades',1),
('2026_06_24_000380_migrate_observation_flags_to_caracteristicas',1),
('2026_06_24_000400_create_operaciones_propiedad_table',1),
('2026_06_24_000500_create_imagenes_propiedad_table',1),
('2026_06_24_000600_create_consultas_table',1),
('2026_06_24_000700_create_tasaciones_table',1),
('2026_06_24_000800_add_geocodificacion_to_propiedades_table',1),
('2026_06_24_000900_add_expensas_moneda_to_propiedades_table',1),
('2026_06_24_001000_create_videos_propiedad_table',1),
('2026_07_29_000000_create_sessions_table',1),
('2026_07_29_010000_add_crm_to_contactos',1),
('2026_07_29_020000_create_visitas_tables',1),
('2026_07_29_030000_create_secuencias_table',1),
('2026_08_12_000000_create_empresas_table',1),
('2026_08_12_010000_add_datos_personales_to_usuarios_table',1),
('2026_08_12_020000_ensure_catalogo_tipos_propiedad',1),
('2026_08_21_000000_add_rol_to_usuarios_table',1),
('2026_09_18_000000_normalizar_jerarquia_de_ubicaciones',1),
('2026_09_18_000001_agregar_unicidad_georef_a_ubicaciones',1),
('2026_09_18_000002_quitar_codigos_tecnicos_de_rutas_ubicaciones',1),
('2026_09_22_000000_corregir_nordelta_como_localidad_de_tigre',1);

-- Plantilla para agregar una ubicación personalizada debajo de otra existente.
-- Reemplazar 0 por el ID de la ubicación padre y usar el tipo permitido.
-- INSERT INTO ubicaciones
-- (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,pais,nombre_completo,activa,created_at,updated_at)
-- VALUES
-- (0,0,'Nombre','nombre','personalizado','Argentina','Argentina | ... | Nombre',1,NOW(),NOW());

SET FOREIGN_KEY_CHECKS = 1;
