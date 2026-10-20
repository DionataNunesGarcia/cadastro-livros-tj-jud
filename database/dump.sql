-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: tjjud_dump
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Assunto`
--

DROP TABLE IF EXISTS `Assunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Assunto` (
  `codAs` int NOT NULL AUTO_INCREMENT,
  `Descricao` varchar(20) NOT NULL,
  PRIMARY KEY (`codAs`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Assunto`
--

LOCK TABLES `Assunto` WRITE;
/*!40000 ALTER TABLE `Assunto` DISABLE KEYS */;
INSERT INTO `Assunto` VALUES (1,'Romance'),(2,'Naturalismo'),(3,'Realismo'),(4,'Literatura BR'),(5,'Literatura infantil'),(6,'Ficção científica');
/*!40000 ALTER TABLE `Assunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Autor`
--

DROP TABLE IF EXISTS `Autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Autor` (
  `CodAu` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(40) NOT NULL,
  PRIMARY KEY (`CodAu`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Autor`
--

LOCK TABLES `Autor` WRITE;
/*!40000 ALTER TABLE `Autor` DISABLE KEYS */;
INSERT INTO `Autor` VALUES (1,'Machado de Assis'),(2,'Clarice Lispector'),(3,'Monteiro Lobato'),(4,'Carlos Drummond de Andrade'),(5,'Jorge Amado'),(6,'Conceição Evaristo'),(7,'Paulo Coelho'),(8,'Luis Fernando Verissimo'),(9,'Manuel Bandeira'),(10,'Cecília Meireles'),(11,'Aluísio Azevedo'),(12,'Guimarães Rosa');
/*!40000 ALTER TABLE `Autor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Livro`
--

DROP TABLE IF EXISTS `Livro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Livro` (
  `Codl` int NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(40) NOT NULL,
  `Editora` varchar(40) NOT NULL,
  `Edicao` int NOT NULL,
  `AnoPublicacao` varchar(4) NOT NULL,
  `Valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`Codl`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Livro`
--

LOCK TABLES `Livro` WRITE;
/*!40000 ALTER TABLE `Livro` DISABLE KEYS */;
INSERT INTO `Livro` VALUES (1,'Dom Casmurro','Garnier',1,'1899',49.90),(2,'O Cortiço','Garnier',2,'1890',39.90),(3,'Memórias Póstumas','Garnier',3,'1881',59.90),(4,'Grande Sertão: Veredas','José Olympio',1,'1956',89.90);
/*!40000 ALTER TABLE `Livro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Livro_Assunto`
--

DROP TABLE IF EXISTS `Livro_Assunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Livro_Assunto` (
  `Livro_Codl` int NOT NULL,
  `Assunto_codAs` int NOT NULL,
  PRIMARY KEY (`Livro_Codl`,`Assunto_codAs`),
  KEY `IDX_2F01B7434A5AFC39` (`Livro_Codl`),
  KEY `IDX_2F01B743A5E1B302` (`Assunto_codAs`),
  CONSTRAINT `FK_2F01B7434A5AFC39` FOREIGN KEY (`Livro_Codl`) REFERENCES `Livro` (`Codl`),
  CONSTRAINT `FK_2F01B743A5E1B302` FOREIGN KEY (`Assunto_codAs`) REFERENCES `Assunto` (`codAs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Livro_Assunto`
--

LOCK TABLES `Livro_Assunto` WRITE;
/*!40000 ALTER TABLE `Livro_Assunto` DISABLE KEYS */;
INSERT INTO `Livro_Assunto` VALUES (1,1),(2,1),(2,2),(3,1),(3,3),(4,1);
/*!40000 ALTER TABLE `Livro_Assunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Livro_Autor`
--

DROP TABLE IF EXISTS `Livro_Autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Livro_Autor` (
  `Livro_Codl` int NOT NULL,
  `Autor_CodAu` int NOT NULL,
  PRIMARY KEY (`Livro_Codl`,`Autor_CodAu`),
  KEY `IDX_412939414A5AFC39` (`Livro_Codl`),
  KEY `IDX_41293941B44F3F36` (`Autor_CodAu`),
  CONSTRAINT `FK_412939414A5AFC39` FOREIGN KEY (`Livro_Codl`) REFERENCES `Livro` (`Codl`),
  CONSTRAINT `FK_41293941B44F3F36` FOREIGN KEY (`Autor_CodAu`) REFERENCES `Autor` (`CodAu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Livro_Autor`
--

LOCK TABLES `Livro_Autor` WRITE;
/*!40000 ALTER TABLE `Livro_Autor` DISABLE KEYS */;
INSERT INTO `Livro_Autor` VALUES (1,1),(2,11),(3,1),(4,12);
/*!40000 ALTER TABLE `Livro_Autor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20261017014401','2026-10-20 07:19:30',237),('DoctrineMigrations\\Version20261019072254','2026-10-20 07:19:31',7);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750` (`queue_name`,`available_at`,`delivered_at`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_livros_por_autor`
--

DROP TABLE IF EXISTS `vw_livros_por_autor`;
/*!50001 DROP VIEW IF EXISTS `vw_livros_por_autor`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_livros_por_autor` AS SELECT 
 1 AS `codigo_autor`,
 1 AS `autor`,
 1 AS `codigo_livro`,
 1 AS `titulo`,
 1 AS `editora`,
 1 AS `edicao`,
 1 AS `ano_publicacao`,
 1 AS `valor`,
 1 AS `assuntos`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'tjjud_dump'
--

--
-- Final view structure for view `vw_livros_por_autor`
--

/*!50001 DROP VIEW IF EXISTS `vw_livros_por_autor`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `vw_livros_por_autor` AS select `au`.`CodAu` AS `codigo_autor`,`au`.`Nome` AS `autor`,`l`.`Codl` AS `codigo_livro`,`l`.`Titulo` AS `titulo`,`l`.`Editora` AS `editora`,`l`.`Edicao` AS `edicao`,`l`.`AnoPublicacao` AS `ano_publicacao`,`l`.`Valor` AS `valor`,group_concat(distinct `a`.`Descricao` order by `a`.`Descricao` ASC separator ', ') AS `assuntos` from ((((`Autor` `au` join `Livro_Autor` `lau` on((`lau`.`Autor_CodAu` = `au`.`CodAu`))) join `Livro` `l` on((`l`.`Codl` = `lau`.`Livro_Codl`))) left join `Livro_Assunto` `la` on((`la`.`Livro_Codl` = `l`.`Codl`))) left join `Assunto` `a` on((`a`.`codAs` = `la`.`Assunto_codAs`))) group by `au`.`CodAu`,`au`.`Nome`,`l`.`Codl`,`l`.`Titulo`,`l`.`Editora`,`l`.`Edicao`,`l`.`AnoPublicacao`,`l`.`Valor` order by `au`.`Nome`,`l`.`Titulo` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-20  7:19:31
