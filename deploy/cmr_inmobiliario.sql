-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: cmr_inmobiliario
-- ------------------------------------------------------
-- Server version	5.7.18-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('inmobiliaria-cache-929411c0b4eb416c5d63f55efb896525','i:1;',1787347182),('inmobiliaria-cache-929411c0b4eb416c5d63f55efb896525:timer','i:1787347182;',1787347182);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caracteristica_propiedad`
--

DROP TABLE IF EXISTS `caracteristica_propiedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caracteristica_propiedad` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `propiedad_id` bigint(20) unsigned NOT NULL,
  `caracteristica_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `caracteristica_propiedad_propiedad_id_caracteristica_id_unique` (`propiedad_id`,`caracteristica_id`),
  KEY `caracteristica_propiedad_caracteristica_id_foreign` (`caracteristica_id`),
  CONSTRAINT `caracteristica_propiedad_caracteristica_id_foreign` FOREIGN KEY (`caracteristica_id`) REFERENCES `caracteristicas` (`id`),
  CONSTRAINT `caracteristica_propiedad_propiedad_id_foreign` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caracteristica_propiedad`
--

LOCK TABLES `caracteristica_propiedad` WRITE;
/*!40000 ALTER TABLE `caracteristica_propiedad` DISABLE KEYS */;
INSERT INTO `caracteristica_propiedad` VALUES (9,3,30,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(10,3,45,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(11,3,52,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(12,4,54,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(24,4,59,'2026-06-24 19:23:08','2026-06-24 19:23:08'),(25,4,60,'2026-06-24 19:23:08','2026-06-24 19:23:08'),(26,5,1,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(27,5,2,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(28,5,3,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(29,5,5,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(30,5,6,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(31,5,10,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(32,5,12,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(33,5,13,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(34,5,18,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(35,5,19,'2026-07-30 13:03:08','2026-07-30 13:03:08'),(36,5,25,'2026-07-30 13:03:08','2026-07-30 13:03:08');
/*!40000 ALTER TABLE `caracteristica_propiedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caracteristicas`
--

DROP TABLE IF EXISTS `caracteristicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caracteristicas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `caracteristicas_nombre_categoria_unique` (`nombre`,`categoria`),
  KEY `caracteristicas_categoria_index` (`categoria`),
  KEY `caracteristicas_activa_index` (`activa`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caracteristicas`
--

LOCK TABLES `caracteristicas` WRITE;
/*!40000 ALTER TABLE `caracteristicas` DISABLE KEYS */;
INSERT INTO `caracteristicas` VALUES (1,'Agua Corriente','servicio',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(2,'Cloaca','servicio',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(3,'Gas Natural','servicio',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(4,'Internet','servicio',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(5,'Electricidad','servicio',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(6,'Pavimento','servicio',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(7,'Teléfono','servicio',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(8,'Cable','servicio',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(9,'Altillo','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(10,'Balcón','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(11,'Baulera','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(12,'Cocina','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(13,'Comedor diario','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(14,'Dependencia','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(15,'Oficina','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(16,'Hall','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(17,'Jardín','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(18,'Lavadero','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(19,'Living comedor','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(20,'Patio','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(21,'Sótano','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(22,'Terraza','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(23,'Toilette','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(24,'Vestidor','ambiente',1,'2026-06-24 19:03:21','2026-06-24 19:03:21'),(25,'Tiene cartel','cartel',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(26,'Sin cartel','cartel',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(27,'Oportunidad','observacion',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(28,'Acepta Lote','observacion',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(29,'Acepta Permuta','observacion',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(30,'Apto Credito','observacion',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(31,'Venta Con Renta','observacion',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(32,'Al Rio','preferencia_lote',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(33,'Interno','preferencia_lote',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(34,'Al Golf','preferencia_lote',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(35,'Al lago','preferencia_lote',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(36,'Perimetral','preferencia_lote',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(37,'Lindero Interno','preferencia_lote',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(38,'Aire Acondicionado individual','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(39,'Alarma','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(40,'Amoblado','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(41,'Calefacción','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(42,'Centro de deportes','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(43,'Gimnasio','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(44,'Hidromasaje','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(45,'Parrilla','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(46,'Quincho','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(47,'Sala de juegos','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(48,'Sauna','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(49,'Solarium','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(50,'SUM','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(51,'Cancha de Paddle','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(52,'Pileta','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(53,'Riego automático','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(54,'Seguridad Privada','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(55,'Luminoso','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(56,'Amarra','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(57,'Laundry','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(58,'Seguridad 24hs','amenity',1,'2026-06-24 19:14:09','2026-06-24 19:14:09'),(59,'Propiedad destacada','observacion',1,'2026-06-24 19:23:08','2026-06-24 19:23:08'),(60,'Apto profesional','observacion',1,'2026-06-24 19:23:08','2026-06-24 19:23:08'),(61,'Acepta mascotas','observacion',1,'2026-06-24 19:23:08','2026-06-24 19:23:08');
/*!40000 ALTER TABLE `caracteristicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consultas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `propiedad_id` bigint(20) unsigned DEFAULT NULL,
  `operacion_propiedad_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_seguimiento` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nueva',
  `responsable_id` bigint(20) unsigned DEFAULT NULL,
  `prioridad` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'media',
  `proxima_tarea` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proxima_tarea_en` datetime DEFAULT NULL,
  `motivo_cierre` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cerrada_en` timestamp NULL DEFAULT NULL,
  `notas_internas` text COLLATE utf8mb4_unicode_ci,
  `leida_en` timestamp NULL DEFAULT NULL,
  `atendida_en` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultas_propiedad_id_foreign` (`propiedad_id`),
  KEY `consultas_operacion_propiedad_id_foreign` (`operacion_propiedad_id`),
  KEY `consultas_created_at_index` (`created_at`),
  KEY `consultas_nombre_index` (`nombre`),
  KEY `consultas_email_index` (`email`),
  KEY `consultas_telefono_index` (`telefono`),
  KEY `consultas_estado_seguimiento_index` (`estado_seguimiento`),
  KEY `consultas_responsable_id_foreign` (`responsable_id`),
  KEY `consultas_prioridad_index` (`prioridad`),
  KEY `consultas_proxima_tarea_en_index` (`proxima_tarea_en`),
  CONSTRAINT `consultas_operacion_propiedad_id_foreign` FOREIGN KEY (`operacion_propiedad_id`) REFERENCES `operaciones_propiedad` (`id`),
  CONSTRAINT `consultas_propiedad_id_foreign` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`),
  CONSTRAINT `consultas_responsable_id_foreign` FOREIGN KEY (`responsable_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (1,3,4,'Mariana Lopez','mariana.lopez@example.com','+54 9 11 5020-1144','Hola, me interesa coordinar una visita esta semana. Quisiera saber si la propiedad sigue disponible.','visita_coordinada',1,'media','llamar para coordinar visita','2026-07-29 12:19:00',NULL,NULL,'','2026-07-29 13:34:42','2026-07-29 13:35:43','2026-07-14 15:54:13','2026-07-29 19:48:02'),(2,4,5,'Federico Martin','federico.martin@example.com','+54 9 11 6314-9021','Estoy buscando una propiedad para alquilar con cochera. Puedo visitar por la tarde.','en_seguimiento',NULL,'media',NULL,NULL,NULL,NULL,'Responder con opciones similares si esta unidad no está disponible.','2026-07-12 15:54:13',NULL,'2026-07-14 15:54:13','2026-07-14 15:54:13'),(3,NULL,NULL,'Valeria Gomez','valeria.gomez@example.com','+54 9 11 3848-7710','Necesito más información sobre gastos, expensas y medios de pago.','contactada',NULL,'media',NULL,NULL,NULL,NULL,'Se envió información por WhatsApp. Esperar confirmación de visita.','2026-07-10 15:54:13','2026-07-11 15:54:13','2026-07-14 15:54:13','2026-07-14 15:54:13'),(4,NULL,NULL,'Santiago Perez','santiago.perez@example.com',NULL,'Consulta general: busco casa en zona norte, mínimo 3 dormitorios.','cerrada',NULL,'media',NULL,NULL,NULL,NULL,'Se cerró porque compró por otra inmobiliaria.','2026-07-07 15:54:13','2026-07-08 15:54:13','2026-07-14 15:54:13','2026-07-14 15:54:13');
/*!40000 ALTER TABLE `consultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_comercial` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zona_horaria` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'America/Argentina/Buenos_Aires',
  `logo_ruta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'Rosario Costantini',NULL,'inmobiliaria@gmail.com','+541149397070','+541149397070','Avenida Perón 1630','America/Argentina/Buenos_Aires','empresa/tp49NnAnMTfQF7GAnwvCMfrh2PicMOQEKPwmU1Yc.png','2026-08-12 21:47:07','2026-08-12 21:49:13');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_oportunidades`
--

DROP TABLE IF EXISTS `historial_oportunidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historial_oportunidades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `oportunidad_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `oportunidad_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned DEFAULT NULL,
  `evento` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cambios` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historial_oportunidades_oportunidad_type_oportunidad_id_index` (`oportunidad_type`,`oportunidad_id`),
  KEY `historial_oportunidades_usuario_id_foreign` (`usuario_id`),
  KEY `historial_oportunidades_evento_index` (`evento`),
  CONSTRAINT `historial_oportunidades_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_oportunidades`
--

LOCK TABLES `historial_oportunidades` WRITE;
/*!40000 ALTER TABLE `historial_oportunidades` DISABLE KEYS */;
INSERT INTO `historial_oportunidades` VALUES (1,'App\\Models\\Consulta',1,1,'seguimiento_actualizado','Se actualizaron la tarea o las notas de la oportunidad.',NULL,'2026-07-29 19:19:35','2026-07-29 19:19:35'),(2,'App\\Models\\Consulta',1,1,'seguimiento_actualizado','Se actualizó la oportunidad comercial.','{\"responsable\": [\"Sin asignar\", \"Administrador\"]}','2026-07-29 19:48:02','2026-07-29 19:48:02');
/*!40000 ALTER TABLE `historial_oportunidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_visitas`
--

DROP TABLE IF EXISTS `historial_visitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historial_visitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visita_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned DEFAULT NULL,
  `evento` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historial_visitas_visita_id_foreign` (`visita_id`),
  KEY `historial_visitas_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `historial_visitas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `historial_visitas_visita_id_foreign` FOREIGN KEY (`visita_id`) REFERENCES `visitas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_visitas`
--

LOCK TABLES `historial_visitas` WRITE;
/*!40000 ALTER TABLE `historial_visitas` DISABLE KEYS */;
INSERT INTO `historial_visitas` VALUES (1,1,1,'creada','Se coordinó la visita.',NULL,'2026-07-29 19:47:06','2026-07-29 19:47:06');
/*!40000 ALTER TABLE `historial_visitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes_propiedad`
--

DROP TABLE IF EXISTS `imagenes_propiedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagenes_propiedad` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `propiedad_id` bigint(20) unsigned NOT NULL,
  `ruta` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_original` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `portada` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imagenes_propiedad_propiedad_id_orden_index` (`propiedad_id`,`orden`),
  KEY `imagenes_propiedad_propiedad_id_portada_index` (`propiedad_id`,`portada`),
  CONSTRAINT `imagenes_propiedad_propiedad_id_foreign` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad`
--

LOCK TABLES `imagenes_propiedad` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad` DISABLE KEYS */;
INSERT INTO `imagenes_propiedad` VALUES (4,4,'propiedades/4/1087270e-36c5-4d81-a9af-508dd7ec84b7.jpg','bahia.jpg',1,1,'2026-06-24 18:10:50','2026-06-24 18:10:50'),(5,3,'propiedades/3/c9ed1502-73d4-4ea2-99c3-f8559762242f.png','Imagen generada 1 (1).png',1,1,'2026-06-24 18:18:01','2026-06-24 18:37:15'),(6,3,'propiedades/3/18202001-703e-4123-b1a8-7d20be5bedbd.png','Imagen generada 1.png',2,0,'2026-06-24 18:18:01','2026-06-24 18:18:01'),(7,3,'propiedades/3/42d52fc6-3657-4121-af69-b9dd32fea52a.png','Etios.png',3,0,'2026-06-24 18:18:01','2026-06-24 18:37:15'),(8,5,'propiedades/5/7a0394ff-1b29-49ae-8635-05d4f90179c8.png','img-house.png',1,1,'2026-07-30 13:04:23','2026-07-30 13:04:23');
/*!40000 ALTER TABLE `imagenes_propiedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2026_06_24_000000_create_usuarios_table',1),(4,'2026_06_24_000100_create_tipos_propiedad_table',1),(5,'2026_06_24_000200_create_ubicaciones_table',1),(6,'2026_06_24_000300_create_propiedades_table',1),(7,'2026_06_24_000400_create_operaciones_propiedad_table',1),(8,'2026_06_24_000500_create_imagenes_propiedad_table',1),(9,'2026_06_24_000600_create_consultas_table',1),(10,'2026_06_24_000700_create_tasaciones_table',1),(11,'2026_06_24_000201_add_unique_index_to_ubicaciones_table',2),(12,'2026_06_24_000250_create_caracteristicas_table',3),(13,'2026_06_24_000350_create_caracteristica_propiedad_table',3),(14,'2026_06_24_000360_seed_nuevas_caracteristicas_y_migrar_booleanos',4),(15,'2026_06_24_000370_drop_legacy_characteristic_columns_from_propiedades',5),(16,'2026_06_24_000380_migrate_observation_flags_to_caracteristicas',6),(17,'2026_06_24_000800_add_geocodificacion_to_propiedades_table',7),(18,'2026_06_24_000900_add_expensas_moneda_to_propiedades_table',8),(19,'2026_06_24_001000_create_videos_propiedad_table',9),(20,'2026_07_29_000000_create_sessions_table',10),(21,'2026_07_29_010000_add_crm_to_contactos',11),(22,'2026_07_29_020000_create_visitas_tables',12),(23,'2026_07_29_030000_create_secuencias_table',13),(24,'2026_08_12_000000_create_empresas_table',14),(25,'2026_08_12_010000_add_datos_personales_to_usuarios_table',15),(26,'2026_08_12_020000_ensure_catalogo_tipos_propiedad',16),(27,'2026_08_21_000000_add_rol_to_usuarios_table',17);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operaciones_propiedad`
--

DROP TABLE IF EXISTS `operaciones_propiedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operaciones_propiedad` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `propiedad_id` bigint(20) unsigned NOT NULL,
  `tipo_operacion` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moneda` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio` decimal(15,2) unsigned DEFAULT NULL,
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pausada',
  `publicada_en` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `operaciones_propiedad_propiedad_id_tipo_operacion_unique` (`propiedad_id`,`tipo_operacion`),
  KEY `operaciones_propiedad_tipo_operacion_estado_index` (`tipo_operacion`,`estado`),
  KEY `operaciones_propiedad_precio_index` (`precio`),
  KEY `operaciones_propiedad_estado_index` (`estado`),
  KEY `operaciones_propiedad_publicada_en_index` (`publicada_en`),
  CONSTRAINT `operaciones_propiedad_propiedad_id_foreign` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operaciones_propiedad`
--

LOCK TABLES `operaciones_propiedad` WRITE;
/*!40000 ALTER TABLE `operaciones_propiedad` DISABLE KEYS */;
INSERT INTO `operaciones_propiedad` VALUES (4,3,'venta','USD',850000.00,'publicada','2026-06-24 18:00:25','2026-06-24 18:00:25','2026-06-24 18:00:25'),(5,4,'venta','USD',200000.00,'publicada','2026-06-24 18:04:11','2026-06-24 18:04:11','2026-06-24 18:04:11'),(6,5,'alquiler','ARS',1000000.00,'publicada','2026-07-30 13:03:08','2026-07-30 13:03:08','2026-07-30 13:03:08');
/*!40000 ALTER TABLE `operaciones_propiedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `propiedades`
--

DROP TABLE IF EXISTS `propiedades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `propiedades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_propiedad_id` bigint(20) unsigned NOT NULL,
  `ubicacion_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_interno` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expensas` decimal(15,2) unsigned DEFAULT NULL,
  `expensas_moneda` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion_corta` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_normalizada` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mostrar_direccion` tinyint(1) NOT NULL DEFAULT '0',
  `ambientes` smallint(5) unsigned DEFAULT NULL,
  `dormitorios` smallint(5) unsigned DEFAULT NULL,
  `banios` smallint(5) unsigned DEFAULT NULL,
  `cocheras` smallint(5) unsigned DEFAULT NULL,
  `superficie_total` decimal(12,2) unsigned DEFAULT NULL,
  `superficie_cubierta` decimal(12,2) unsigned DEFAULT NULL,
  `superficie_descubierta` decimal(12,2) unsigned DEFAULT NULL,
  `superficie_terreno` decimal(12,2) unsigned DEFAULT NULL,
  `antiguedad` smallint(5) unsigned DEFAULT NULL,
  `orientacion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `proveedor_geocodificacion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ubicacion_confirmada` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `propiedades_slug_unique` (`slug`),
  UNIQUE KEY `propiedades_codigo_interno_unique` (`codigo_interno`),
  KEY `propiedades_tipo_propiedad_id_foreign` (`tipo_propiedad_id`),
  KEY `propiedades_ubicacion_id_foreign` (`ubicacion_id`),
  KEY `propiedades_titulo_index` (`titulo`),
  KEY `propiedades_ambientes_index` (`ambientes`),
  KEY `propiedades_dormitorios_index` (`dormitorios`),
  CONSTRAINT `propiedades_tipo_propiedad_id_foreign` FOREIGN KEY (`tipo_propiedad_id`) REFERENCES `tipos_propiedad` (`id`),
  CONSTRAINT `propiedades_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `propiedades`
--

LOCK TABLES `propiedades` WRITE;
/*!40000 ALTER TABLE `propiedades` DISABLE KEYS */;
INSERT INTO `propiedades` VALUES (3,3,1,'Casa - Nordelta - El Yacht','casa-nordelta-el-yacht','K1789',NULL,NULL,'Hermosa casa 5 ambientes, super luminosa',NULL,'las palmeras 3305',NULL,0,5,4,2,NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,NULL,0,'2026-06-24 18:00:25','2026-06-24 18:07:39',NULL),(4,2,1,'Departamento - Nordelta - El Yacht','departamento-nordelta-el-yacht','K1790',1200.00,'ARS','Depatamento apto profesional',NULL,'Bolívar 318',NULL,0,3,2,2,NULL,NULL,NULL,NULL,NULL,10,'norte',NULL,NULL,NULL,NULL,0,'2026-06-24 18:04:11','2026-06-24 18:43:07',NULL),(5,3,3,'Casa 4 ambientes - San Fernando','casa-4-ambientes-san-fernando','Ca0384',NULL,NULL,'Hermosa casa 4 ambientes en San Fernando','OPORTUNIDAD !! Alquiler en San Fernando centro','general Lavalle 1070, San fernando','1070, General Lavalle, San Fernando, Partido de San Fernando, Buenos Aires, 1646, Argentina',0,4,3,2,1,120.00,100.00,20.00,NULL,65,NULL,-34.4421937,-58.5582351,'openstreetmap',NULL,1,'2026-07-30 13:03:08','2026-07-30 13:03:08',NULL);
/*!40000 ALTER TABLE `propiedades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secuencias`
--

DROP TABLE IF EXISTS `secuencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secuencias` (
  `clave` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ultimo_numero` bigint(20) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secuencias`
--

LOCK TABLES `secuencias` WRITE;
/*!40000 ALTER TABLE `secuencias` DISABLE KEYS */;
INSERT INTO `secuencias` VALUES ('codigo_propiedad',0,'2026-07-30 13:49:27','2026-07-30 13:49:27');
/*!40000 ALTER TABLE `secuencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`),
  CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasaciones`
--

DROP TABLE IF EXISTS `tasaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_propiedad_id` bigint(20) unsigned DEFAULT NULL,
  `ubicacion_texto` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci,
  `estado_seguimiento` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nueva',
  `responsable_id` bigint(20) unsigned DEFAULT NULL,
  `prioridad` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'media',
  `proxima_tarea` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proxima_tarea_en` datetime DEFAULT NULL,
  `motivo_cierre` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cerrada_en` timestamp NULL DEFAULT NULL,
  `notas_internas` text COLLATE utf8mb4_unicode_ci,
  `leida_en` timestamp NULL DEFAULT NULL,
  `atendida_en` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasaciones_tipo_propiedad_id_foreign` (`tipo_propiedad_id`),
  KEY `tasaciones_created_at_index` (`created_at`),
  KEY `tasaciones_nombre_index` (`nombre`),
  KEY `tasaciones_email_index` (`email`),
  KEY `tasaciones_telefono_index` (`telefono`),
  KEY `tasaciones_estado_seguimiento_index` (`estado_seguimiento`),
  KEY `tasaciones_responsable_id_foreign` (`responsable_id`),
  KEY `tasaciones_prioridad_index` (`prioridad`),
  KEY `tasaciones_proxima_tarea_en_index` (`proxima_tarea_en`),
  CONSTRAINT `tasaciones_responsable_id_foreign` FOREIGN KEY (`responsable_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasaciones_tipo_propiedad_id_foreign` FOREIGN KEY (`tipo_propiedad_id`) REFERENCES `tipos_propiedad` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasaciones`
--

LOCK TABLES `tasaciones` WRITE;
/*!40000 ALTER TABLE `tasaciones` DISABLE KEYS */;
INSERT INTO `tasaciones` VALUES (1,'Carolina Suarez','carolina.suarez@example.com','+54 9 11 5122-3001',3,'Nordelta, Tigre','Barrio Los Lagos, lote interno','Quiero tasar una casa de 4 ambientes con pileta para posible venta.','nueva',NULL,'media',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-14 15:54:13','2026-07-14 15:54:13'),(2,'Diego Fernandez','diego.fernandez@example.com','+54 9 11 6420-8842',2,'Puerto Madero, CABA','Juana Manso 1200','Necesito una valuación para alquiler anual.','en_seguimiento',NULL,'media',NULL,NULL,NULL,NULL,'Pedir fotos y datos de amenities.','2026-07-13 15:54:13',NULL,'2026-07-14 15:54:13','2026-07-14 15:54:13'),(3,'Lucia Alvarez','lucia.alvarez@example.com','+54 9 11 2398-1204',1,'San Matias, Escobar',NULL,'Terreno en barrio cerrado. Quiero saber valor de mercado actual.','contactada',NULL,'media',NULL,NULL,NULL,NULL,'Se solicitó documentación del lote.','2026-07-09 15:54:13','2026-07-10 15:54:13','2026-07-14 15:54:13','2026-07-14 15:54:13');
/*!40000 ALTER TABLE `tasaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_propiedad`
--

DROP TABLE IF EXISTS `tipos_propiedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_propiedad` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipos_propiedad_nombre_unique` (`nombre`),
  KEY `tipos_propiedad_activo_index` (`activo`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_propiedad`
--

LOCK TABLES `tipos_propiedad` WRITE;
/*!40000 ALTER TABLE `tipos_propiedad` DISABLE KEYS */;
INSERT INTO `tipos_propiedad` VALUES (1,'Terreno',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(2,'Departamento',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(3,'Casa',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(4,'Quinta',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(5,'Oficina',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(6,'Amarra',0,'2026-06-24 15:18:54','2026-08-12 22:18:23'),(7,'Local',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(8,'Edificio Comercial',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(9,'Campo',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(10,'Cochera',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(11,'Hotel',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(12,'Nave Industrial',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(13,'PH',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(14,'Deposito',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(15,'Fondo de Comercio',0,'2026-06-24 15:18:54','2026-08-12 22:18:45'),(16,'Baulera',0,'2026-06-24 15:18:54','2026-08-12 22:18:27'),(17,'Bodega',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(18,'Finca',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(19,'Chacra',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(20,'Cama nautica',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(21,'Isla',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(22,'Terraza',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(23,'Galpon',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(24,'Villa',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(25,'Terreno comercial',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(26,'Terreno industrial',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(27,'Hacienda',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(28,'Haras',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(29,'Consultorio',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(30,'Monoambiente',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(31,'Terreno en condominio',1,'2026-06-24 15:18:54','2026-06-24 15:18:54');
/*!40000 ALTER TABLE `tipos_propiedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ubicaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pais` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zona` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localidad` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria_barrio` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barrio_principal` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barrio` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_completo` varchar(700) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ubicaciones_nombre_completo_unique` (`nombre_completo`),
  KEY `ubicaciones_pais_index` (`pais`),
  KEY `ubicaciones_zona_index` (`zona`),
  KEY `ubicaciones_localidad_index` (`localidad`),
  KEY `ubicaciones_barrio_principal_index` (`barrio_principal`),
  KEY `ubicaciones_barrio_index` (`barrio`),
  KEY `ubicaciones_activa_index` (`activa`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicaciones`
--

LOCK TABLES `ubicaciones` WRITE;
/*!40000 ALTER TABLE `ubicaciones` DISABLE KEYS */;
INSERT INTO `ubicaciones` VALUES (1,'Argentina','G.B.A. Zona Norte','Tigre','Countries/B.Cerrado (Tigre)','Nordelta','El Yacht','Argentina | G.B.A. Zona Norte | Tigre | Countries/B.Cerrado (Tigre) | Nordelta | El Yacht',1,'2026-06-24 15:18:54','2026-06-24 15:18:54'),(2,'Argentina','G. B. A. Zona Norte','Tigre',NULL,NULL,NULL,'Argentina | G. B. A. Zona Norte | Tigre',1,'2026-06-26 18:30:16','2026-06-26 18:30:16'),(3,'Argentina','G. B. A. Zona Norte','San Fernando',NULL,NULL,NULL,'Argentina | G. B. A. Zona Norte | San Fernando',1,'2026-06-26 19:07:34','2026-06-26 19:07:34'),(4,'Argentina','Buenos Aires','San Fernando',NULL,NULL,NULL,'Argentina | Buenos Aires | San Fernando',0,'2026-06-26 19:15:29','2026-07-30 12:55:37'),(5,'Argentina','G. B. A. Zona Norte','Vicente Lopez','Olivos',NULL,NULL,'Argentina | G. B. A. Zona Norte | Vicente Lopez | Olivos',1,'2026-07-30 14:04:23','2026-07-30 14:04:23');
/*!40000 ALTER TABLE `ubicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `celular` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dni` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `contrasenia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'administrador',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `ultimo_acceso_en` timestamp NULL DEFAULT NULL,
  `recordar_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`),
  UNIQUE KEY `usuarios_dni_unique` (`dni`),
  KEY `usuarios_activo_index` (`activo`),
  KEY `usuarios_rol_index` (`rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador',NULL,'admin@gmail.com','01167814237',NULL,'Avenida Perón 163 - Dpto 3',NULL,NULL,'$2y$12$tNZnKmmI2Ve.NldLhXKzYeKQlkDmfvB1tx9jIKC/z7/GYABOH1wnS','administrador',1,'2026-08-21 21:18:43','NtwGUBt527KB6wWFXydJ21uyooKQeRjjMZpk6Oe1NI5AeIIqD1jgIWFJGvfE','2026-06-24 15:21:25','2026-08-21 21:18:43'),(2,'Victoria','Kowalk','victoriakowalk@gmail.com','01167814237','1167814237','Gral. Lavalle 1070','33058211','1987-05-12','$2y$12$TSp5Up0eV4xAPCyiECrzKu6QfzZHdwgoOEnyfpQt/92y8Wtv8LriO','administrador',1,NULL,NULL,'2026-08-12 21:34:01','2026-08-12 21:45:59'),(3,'Juan','Perez','juanperez@mail.com',NULL,'1100000000','Calle Falsa 123','11111111','1990-05-12','$2y$12$uJWRLN6Q/IrWswFSTglbduL4Lpe0SzKl37S.OBGkh7P4OASQ7Mlwy','asesor',1,NULL,NULL,'2026-08-21 21:41:21','2026-08-21 21:41:21');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos_propiedad`
--

DROP TABLE IF EXISTS `videos_propiedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos_propiedad` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `propiedad_id` bigint(20) unsigned NOT NULL,
  `tipo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruta` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `videos_propiedad_propiedad_id_orden_index` (`propiedad_id`,`orden`),
  KEY `videos_propiedad_propiedad_id_tipo_index` (`propiedad_id`,`tipo`),
  CONSTRAINT `videos_propiedad_propiedad_id_foreign` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos_propiedad`
--

LOCK TABLES `videos_propiedad` WRITE;
/*!40000 ALTER TABLE `videos_propiedad` DISABLE KEYS */;
INSERT INTO `videos_propiedad` VALUES (1,4,'youtube','casa',NULL,'https://www.youtube.com/watch?v=CkUId-O04Zc','CkUId-O04Zc',1,'2026-06-26 19:49:40','2026-06-26 19:49:40'),(2,5,'youtube','Casa 4 ambientes - San Fernando',NULL,'https://www.youtube.com/watch?v=7XBS-dlk_-8&list=RD7XBS-dlk_-8&start_radio=1','7XBS-dlk_-8',1,'2026-07-30 13:05:48','2026-07-30 13:05:48');
/*!40000 ALTER TABLE `videos_propiedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitas`
--

DROP TABLE IF EXISTS `visitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `oportunidad_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oportunidad_id` bigint(20) unsigned DEFAULT NULL,
  `propiedad_id` bigint(20) unsigned NOT NULL,
  `asesor_id` bigint(20) unsigned NOT NULL,
  `interesado_nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interesado_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interesado_telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `motivo_cancelacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resultado` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comentarios_resultado` text COLLATE utf8mb4_unicode_ci,
  `proxima_accion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proxima_accion_en` datetime DEFAULT NULL,
  `recordatorio_cliente_enviado_en` timestamp NULL DEFAULT NULL,
  `recordatorio_asesor_enviado_en` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitas_oportunidad_type_oportunidad_id_index` (`oportunidad_type`,`oportunidad_id`),
  KEY `visitas_propiedad_id_foreign` (`propiedad_id`),
  KEY `visitas_asesor_id_foreign` (`asesor_id`),
  KEY `visitas_inicio_index` (`inicio`),
  KEY `visitas_fin_index` (`fin`),
  KEY `visitas_estado_index` (`estado`),
  CONSTRAINT `visitas_asesor_id_foreign` FOREIGN KEY (`asesor_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `visitas_propiedad_id_foreign` FOREIGN KEY (`propiedad_id`) REFERENCES `propiedades` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitas`
--

LOCK TABLES `visitas` WRITE;
/*!40000 ALTER TABLE `visitas` DISABLE KEYS */;
INSERT INTO `visitas` VALUES (1,'App\\Models\\Consulta',1,3,1,'Mariana Lopez','mariana.lopez@example.com','+54 9 11 5020-1144','2026-07-30 14:45:00','2026-07-30 16:47:00','confirmada',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-29 19:47:06','2026-07-29 19:47:06');
/*!40000 ALTER TABLE `visitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'cmr_inmobiliario'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-28 14:49:33
