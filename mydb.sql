CREATE DATABASE  IF NOT EXISTS `unagauch_db` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `unagauch_db`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: unagauch_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.22-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `calificacion`
--

DROP TABLE IF EXISTS `calificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calificacion` (
  `idcalificacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calificacion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcalificacion`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calificacion`
--

LOCK TABLES `calificacion` WRITE;
/*!40000 ALTER TABLE `calificacion` DISABLE KEYS */;
INSERT INTO `calificacion` VALUES (0,'creado'),(1,'pendiente'),(2,'finalizado'),(3,'Mal'),(4,'Neutral'),(5,'Bien'),(6,'eliminada');
/*!40000 ALTER TABLE `calificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `idCategoria` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Categoria` varchar(45) DEFAULT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Aire Libre','2017-05-20 06:37:09','2017-05-20 06:37:09'),(2,'Espera','2017-05-20 06:37:09','2017-05-20 06:37:09'),(3,'Compras','2017-05-20 06:37:09','2017-05-20 06:37:09'),(4,'Viajes','2017-06-11 20:13:51','2017-06-11 20:13:51');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentario`
--

DROP TABLE IF EXISTS `comentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comentario` (
  `idComentario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuarioid` int(10) unsigned NOT NULL,
  `comentario` text NOT NULL,
  `gauchadaid` int(10) unsigned NOT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idComentario`),
  KEY `fk_C_U_idx` (`usuarioid`),
  KEY `fk_C_G_idx` (`gauchadaid`),
  CONSTRAINT `fk_C_G` FOREIGN KEY (`gauchadaid`) REFERENCES `gauchada` (`idGauchada`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_C_U` FOREIGN KEY (`usuarioid`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario`
--

LOCK TABLES `comentario` WRITE;
/*!40000 ALTER TABLE `comentario` DISABLE KEYS */;
INSERT INTO `comentario` VALUES (32,37,'Voy a Noruega en 15 min. Para cuando lo queres?',122,'2017-06-11 20:17:05','2017-06-11 20:17:05'),(33,40,'Eeh',121,'2017-06-13 02:26:45','2017-06-13 02:26:45'),(34,37,'Are they taking the hobbits to Isengard???',126,'2017-06-13 03:59:20','2017-06-13 03:59:20'),(35,42,'dsadsa',122,'2017-06-26 01:59:43','2017-06-26 01:59:43');
/*!40000 ALTER TABLE `comentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compracredito`
--

DROP TABLE IF EXISTS `compracredito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compracredito` (
  `idCompraCredito` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuarioid` int(10) unsigned NOT NULL,
  `preciocreditoid` int(10) unsigned NOT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idCompraCredito`),
  KEY `fk_CC_U_idx` (`usuarioid`),
  KEY `fk_CC_PC_idx` (`preciocreditoid`),
  CONSTRAINT `fk_CC_PC` FOREIGN KEY (`preciocreditoid`) REFERENCES `preciocredito` (`idPrecioCredito`),
  CONSTRAINT `fk_CC_U` FOREIGN KEY (`usuarioid`) REFERENCES `usuario` (`idUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compracredito`
--

LOCK TABLES `compracredito` WRITE;
/*!40000 ALTER TABLE `compracredito` DISABLE KEYS */;
INSERT INTO `compracredito` VALUES (63,37,62,'2017-06-11 18:58:27','2017-06-11 18:58:27'),(64,38,63,'2017-06-11 19:58:22','2017-06-11 19:58:22'),(65,38,64,'2017-06-11 19:58:22','2017-06-11 19:58:22'),(66,40,65,'2017-06-21 16:40:52','2017-06-21 16:40:52'),(67,40,0,'2017-06-21 16:54:03','2017-06-21 16:54:03'),(68,40,0,'2017-06-21 16:55:14','2017-06-21 16:55:14'),(69,40,0,'2017-06-21 17:35:24','2017-06-21 17:35:24');
/*!40000 ALTER TABLE `compracredito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gauchada`
--

DROP TABLE IF EXISTS `gauchada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gauchada` (
  `idGauchada` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `autor_usuarioid` int(10) unsigned NOT NULL,
  `descripcion` text NOT NULL,
  `titulo` varchar(45) DEFAULT NULL,
  `foto` varchar(254) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `calificacionid` int(10) unsigned NOT NULL,
  `comentarioCal` text,
  `cantPost` int(11) DEFAULT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idGauchada`),
  KEY `fk_G_U_idx` (`autor_usuarioid`),
  KEY `fk_G_Cf_idx` (`calificacionid`),
  CONSTRAINT `fk_G_Cf` FOREIGN KEY (`calificacionid`) REFERENCES `calificacion` (`idcalificacion`) ON UPDATE CASCADE,
  CONSTRAINT `fk_G_U` FOREIGN KEY (`autor_usuarioid`) REFERENCES `usuario` (`idUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gauchada`
--

LOCK TABLES `gauchada` WRITE;
/*!40000 ALTER TABLE `gauchada` DISABLE KEYS */;
INSERT INTO `gauchada` VALUES (121,37,'Quiero que me compren 5 paquetes de galletas y me los dejen en casa.','Comprar Galletas','uploads/1497207042cookiejar.png','2016-06-02',2,'',1,'2017-06-18 21:27:13','2017-06-11 18:50:41'),(122,38,'Necesito cafÃ© de Noruega, cualquier marca, para llevar. ','CafÃ© noruego','uploads/1497210962starpetsmacchiatowithcream.png','2017-07-05',0,NULL,3,'2017-06-26 01:59:34','2017-06-11 19:56:01'),(123,38,'Necesito ayuda para plantar 20 cipreses de origen mongolÃ©s','Plantar cipreses de Mongolia','uploads/1497211993pineconewalldecor.png','2017-08-20',0,NULL,1,'2017-06-13 03:55:56','2017-06-11 20:13:12'),(124,37,'Busco a alguien que estÃ© por viajar a Inglaterra y pueda traerme un desayuno inglÃ©s.','Desayuno inglÃ©s','uploads/1497217924regalpastriestraydecor.png','2017-07-24',0,NULL,1,'2017-06-13 03:55:56','2017-06-11 21:52:03'),(125,39,'fdghjyklÃ±','ewfrgyuk','uploads/1497218815afterglowcherry.png','2018-02-02',6,NULL,0,'2017-06-13 03:54:58','2017-06-11 22:06:55'),(126,40,'Necesito ayuda para destruir un anillo, mi sobrino no quiere hacerse cargo, el asunto es de carÃ¡cter urgente.','Destruir anillo Ãšnico','uploads/1498061089oldwesttreasuremap.png','2018-11-07',0,NULL,1,'2017-06-21 19:36:30','2017-06-13 02:12:23'),(127,40,'ewewq','ewqew',NULL,'2017-12-31',6,NULL,0,'2017-06-21 16:55:14','2017-06-21 16:42:05');
/*!40000 ALTER TABLE `gauchada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gauchadacat`
--

DROP TABLE IF EXISTS `gauchadacat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gauchadacat` (
  `idgauchadaCat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catId` int(10) unsigned NOT NULL,
  `gauchadaId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idgauchadaCat`),
  KEY `fk_GID_G_idx` (`gauchadaId`),
  KEY `fk_CID_C_idx` (`catId`),
  CONSTRAINT `fk_CID_C` FOREIGN KEY (`catId`) REFERENCES `categoria` (`idCategoria`) ON UPDATE CASCADE,
  CONSTRAINT `fk_GID_G` FOREIGN KEY (`gauchadaId`) REFERENCES `gauchada` (`idGauchada`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gauchadacat`
--

LOCK TABLES `gauchadacat` WRITE;
/*!40000 ALTER TABLE `gauchadacat` DISABLE KEYS */;
INSERT INTO `gauchadacat` VALUES (164,3,121),(165,2,122),(166,3,122),(167,1,123),(168,4,122),(169,3,124),(170,4,124),(171,1,125),(172,2,125),(173,3,125),(174,4,125),(207,1,126),(208,2,126),(209,4,126),(210,1,127),(211,2,127);
/*!40000 ALTER TABLE `gauchadacat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gauchadazona`
--

DROP TABLE IF EXISTS `gauchadazona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gauchadazona` (
  `idgauchadazona` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zonaId` int(10) unsigned NOT NULL,
  `gauchadaId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idgauchadazona`),
  KEY `fk_GZID_G_idx` (`gauchadaId`),
  KEY `fk_GZID_Z_idx` (`zonaId`),
  CONSTRAINT `fk_GZID_G` FOREIGN KEY (`gauchadaId`) REFERENCES `gauchada` (`idGauchada`) ON UPDATE CASCADE,
  CONSTRAINT `fk_GZID_Z` FOREIGN KEY (`zonaId`) REFERENCES `zona` (`idZona`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gauchadazona`
--

LOCK TABLES `gauchadazona` WRITE;
/*!40000 ALTER TABLE `gauchadazona` DISABLE KEYS */;
INSERT INTO `gauchadazona` VALUES (147,1,121),(148,2,122),(149,1,123),(150,2,124),(151,1,125),(163,1,126),(164,1,127);
/*!40000 ALTER TABLE `gauchadazona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postulante`
--

DROP TABLE IF EXISTS `postulante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postulante` (
  `idPostulante` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuarioid` int(10) unsigned NOT NULL,
  `gauchadaid` int(10) unsigned NOT NULL,
  `comentario` text NOT NULL,
  `estado` int(11) DEFAULT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idPostulante`),
  KEY `fk_P_U_idx` (`usuarioid`),
  KEY `fk_P_G_idx` (`gauchadaid`),
  CONSTRAINT `fk_P_G` FOREIGN KEY (`gauchadaid`) REFERENCES `gauchada` (`idGauchada`) ON UPDATE CASCADE,
  CONSTRAINT `fk_P_U` FOREIGN KEY (`usuarioid`) REFERENCES `usuario` (`idUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postulante`
--

LOCK TABLES `postulante` WRITE;
/*!40000 ALTER TABLE `postulante` DISABLE KEYS */;
INSERT INTO `postulante` VALUES (54,37,122,'Salgo para Noruega en 15 min. Vuelvo maÃ±ana a la maÃ±ana con tu cafÃ©, te va?',0,'2017-06-21 19:37:39','2017-06-11 20:16:37'),(55,37,123,'Me encanta plantar cipreses! He planta 58 en total. Me gustaria poder llegar a los 100 para fin de mes.',0,'2017-06-11 21:54:20','2017-06-11 21:54:20'),(56,39,124,'Viajo a Japon maÃ±ana, sirve igual? ',0,'2017-06-13 03:15:40','2017-06-11 21:58:22'),(57,39,121,'Mi tÃ­o trabaja en un fÃ¡brica de galletas te puedo conseguir descuento.',1,'2017-06-13 04:03:30','2017-06-11 21:58:46'),(58,39,122,'Voy a JapÃ³n, me han dicho que el cafÃ© japones es mas rico que el noruego.',0,'2017-06-21 19:37:39','2017-06-11 21:59:08'),(62,37,126,'YO LLEVARE EL ANILLO! YO LLEVARE EL ANILLO A MORDOR!',0,'2017-06-21 19:36:59','2017-06-13 03:59:40'),(63,42,122,'sdsadsa',0,'2017-06-26 01:59:34','2017-06-26 01:59:34');
/*!40000 ALTER TABLE `postulante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preciocredito`
--

DROP TABLE IF EXISTS `preciocredito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preciocredito` (
  `idPrecioCredito` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creditos` varchar(45) DEFAULT NULL,
  `precio` decimal(6,2) DEFAULT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idPrecioCredito`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preciocredito`
--

LOCK TABLES `preciocredito` WRITE;
/*!40000 ALTER TABLE `preciocredito` DISABLE KEYS */;
INSERT INTO `preciocredito` VALUES (0,'1',0.00,'2017-06-13 12:43:07','2017-06-13 12:42:56',NULL),(62,'2',50.00,'2017-06-11 18:58:26','2017-06-11 18:58:26',NULL),(63,'1',50.00,'2017-06-11 19:58:21','2017-06-11 19:58:21',NULL),(64,'1',50.00,'2017-06-11 19:58:22','2017-06-11 19:58:22',NULL),(65,'1',50.00,'2017-06-21 16:40:52','2017-06-21 16:40:52',NULL);
/*!40000 ALTER TABLE `preciocredito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reputacion`
--

DROP TABLE IF EXISTS `reputacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reputacion` (
  `idReputacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reputacion` varchar(45) DEFAULT NULL,
  `hasta` int(11) DEFAULT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idReputacion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reputacion`
--

LOCK TABLES `reputacion` WRITE;
/*!40000 ALTER TABLE `reputacion` DISABLE KEYS */;
INSERT INTO `reputacion` VALUES (1,'Buen Tipo',5,'2017-05-24 14:53:20','2017-05-24 14:21:33'),(2,'Dios',10,'2017-06-11 18:45:38','2017-06-10 17:33:38'),(3,'Irresponsable',0,'2017-06-13 13:14:25','2017-06-11 02:09:40'),(4,'Responsable',8,'2017-06-11 18:45:38','2017-06-11 02:09:40');
/*!40000 ALTER TABLE `reputacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respuesta`
--

DROP TABLE IF EXISTS `respuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `respuesta` (
  `idrespuesta` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuarioId` int(10) unsigned NOT NULL,
  `comentarioid` int(10) unsigned NOT NULL,
  `respuesta` text NOT NULL,
  `creado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idrespuesta`),
  KEY `fk_R_U_idx` (`usuarioId`),
  KEY `fk_R_C_idx` (`comentarioid`),
  CONSTRAINT `fk_R_C` FOREIGN KEY (`comentarioid`) REFERENCES `comentario` (`idComentario`) ON UPDATE CASCADE,
  CONSTRAINT `fk_R_U` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`idUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respuesta`
--

LOCK TABLES `respuesta` WRITE;
/*!40000 ALTER TABLE `respuesta` DISABLE KEYS */;
INSERT INTO `respuesta` VALUES (1,37,33,'Bolson!? Claro que conozco un Bolson!','2017-06-13 04:02:13'),(2,38,32,'ADSADA','2017-06-21 18:57:12');
/*!40000 ALTER TABLE `respuesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `idUsuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellido` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `mail` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `foto` varchar(254) COLLATE utf8_spanish_ci DEFAULT NULL,
  `clave` varchar(64) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nacimiento` date DEFAULT NULL,
  `puntaje` int(11) DEFAULT NULL,
  `nivelUsuario` tinyint(1) NOT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (37,'Sol','Fagot','sol.fagot@gmail.com','uploads/1497206634averagehomegrownhappymoodyghost.png','123','221498436','1992-10-07',1,0,'2017-06-11 18:43:54','2017-06-11 18:43:54'),(38,'Pepe','Honguito','pepe@honguito','uploads/1498075324maroonmushy.png','123','123','1993-09-05',1,0,'2017-06-21 20:02:04','2017-06-11 19:24:05'),(39,'Usuario','Usuario 1','usuario@gmail.com','uploads/1497218256babychickdoll.png','123','2214578469','1995-06-11',-1,0,'2017-06-18 21:25:06','2017-06-11 21:57:36'),(40,'Bilbo','Bolson','bilbao@gmail.com','uploads/1498054515cheesetray.png','123','21321','1986-09-22',1,0,'2017-06-21 14:15:15','2017-06-13 02:06:42'),(41,'Admin','Admin','admin@admin.com','uploads/1498077556starsticker.png','123','123','1980-06-12',1,1,'2017-06-21 20:39:34','2017-06-21 20:39:16'),(42,'dsd','dsad','min@fjkds','uploads/1498442437ancientbooksdecor.png','123','123','2007-03-31',1,0,'2017-06-26 02:00:37','2017-06-26 01:58:33');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zona`
--

DROP TABLE IF EXISTS `zona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zona` (
  `idZona` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Zona` varchar(45) DEFAULT NULL,
  `editado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idZona`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona`
--

LOCK TABLES `zona` WRITE;
/*!40000 ALTER TABLE `zona` DISABLE KEYS */;
INSERT INTO `zona` VALUES (1,'La Plata','2017-05-20 06:39:11','2017-05-20 06:39:11'),(2,'City Bell','2017-05-20 06:39:11','2017-05-20 06:39:11'),(3,'Tolosa','2017-05-20 06:39:11','2017-05-20 06:39:11');
/*!40000 ALTER TABLE `zona` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-25 23:12:13
