-- MySQL dump 10.13  Distrib 5.5.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: lego_collection
-- ------------------------------------------------------
-- Server version	5.5.54-0ubuntu0.14.04.1

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
-- Table structure for table `Lego`
--

DROP TABLE IF EXISTS `Lego`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Lego` (
  `id` int(5) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text,
  `image_path` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Lego`
--

LOCK TABLES `Lego` WRITE;
/*!40000 ALTER TABLE `Lego` DISABLE KEYS */;
INSERT INTO `Lego` VALUES (75156,'Krennic\'s Imperial Shuttle','<p>When a tough transport ship is needed, Krennic\'s Imperial Shuttle is the perfect choice. Put him into the pilot seat, open out the thick-armor plating and seat the Death Troopers. Lower the ramp and check the blasters are secured, then arm the spring-loaded shooters and seal the hull for takeoff. Raise the landing skids, lower the wings for flight mode and set off on another dangerous mission!</p>','75156-krennic-shuttle.png'),(75169,'Duel on Naboo','<p>Master Qui-Gon has cornered Darth Maul at the power generator on Naboo and needs your help to defeat him! Duel it out with the Lightsabers and if you fall in the generator core, activate the catapult to jump back out. If Maul is too strong, push the lever to open the laser doors so Obi-Wan can join the battle! Who will win this epic duel? That\'s for you to decide...</p>','75169-duel-on-naboo.png'),(75170,'The Phantom','<p>Stay one step ahead of Admiral Thrawn with the Rebels’ cool starship, The Phantom. Load Kanan and his droid Chopper into place, raise the landing gear and launch! Open the rear hatch to access the detonator, and if you get into trouble, fire the shooters to keep the Imperials off your tail or detach the cockpit for a quick getaway!</p>','75170-phantom.png'),(75171,'Battle on Skarif','<p>Go on a daring mission to find the top-secret Death Star plans at the beach bunker. Uncover the hidden weapon stash, dodge the exploding floor panels and then tip the tower to unlock the bunker doors. Will you be able to grab the plans and escape planet Scarif before they catch you? That\'s for you to decide.</p>','75171-battle-on-scarif.png'),(75172,'Y-Wing Starfighter','<p>When the Rebels need a tough starfighter, call in the Y-wing! Drive the weapons loader into position and lift the ammo into place. Then seat the Y-Wing Pilot, lift the landing gear and take to the skies. When you reach your target, fire the shooters and turn the gearwheel to open the hatch and drop the bombs!</p>','75172-y-wing.png'),(75173,'Luke\'s Landspeeder','<p>Help Luke and C-3PO track down Jedi legend Ben Kenobi in his fast landspeeder. Open the trunk, grab the binoculars and scan the hori zon—but watch out for the dangerous Tusken Raider and hungry womp rat!</p>','75173-lukes-landspeeder.png'),(75174,'Desert Skiff Escape','<p>Help Han Solo rescue his furry friend Chewbacca before he walks the plank! Can he defeat the skiff guard and bring Boba Fett crashing down before Chewbacca falls from the skiff and becomes another tasty meal for the mighty Sarlacc? Only you can decide!</p>','75174-desert-skiff.png'),(75175,'A-Wing Starfighter','<p>The Empire is approaching so it’s time to scramble the A-Wing Starfighter! Use the service cart tools to check it over, load the shooters, detach the ladder and climb aboard. Then power up the engines and take off on another exciting LEGO® Star Wars mission!</p>','75175-a-wing.png');
/*!40000 ALTER TABLE `Lego` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Type`
--

DROP TABLE IF EXISTS `Type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Type` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Type`
--

LOCK TABLES `Type` WRITE;
/*!40000 ALTER TABLE `Type` DISABLE KEYS */;
INSERT INTO `Type` VALUES (1,'Wishlist'),(2,'Own'),(3,'Sold'),(4,'Lost');
/*!40000 ALTER TABLE `Type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'Phil','Schanely'),(2,'Ethan','Schanely');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Lego`
--

DROP TABLE IF EXISTS `User_Lego`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User_Lego` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user` int(2) NOT NULL,
  `lego` int(5) NOT NULL,
  `type` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Lego`
--

LOCK TABLES `User_Lego` WRITE;
/*!40000 ALTER TABLE `User_Lego` DISABLE KEYS */;
INSERT INTO `User_Lego` VALUES (1,1,75171,2),(2,1,75173,3),(3,1,75156,1),(4,1,75172,1);
/*!40000 ALTER TABLE `User_Lego` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `User_Lego_View`
--

DROP TABLE IF EXISTS `User_Lego_View`;
/*!50001 DROP VIEW IF EXISTS `User_Lego_View`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `User_Lego_View` (
  `id` tinyint NOT NULL,
  `user` tinyint NOT NULL,
  `lego__id` tinyint NOT NULL,
  `lego__name` tinyint NOT NULL,
  `lego__description` tinyint NOT NULL,
  `lego__image_path` tinyint NOT NULL,
  `type__id` tinyint NOT NULL,
  `type__name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `User_Lego_View`
--

/*!50001 DROP TABLE IF EXISTS `User_Lego_View`*/;
/*!50001 DROP VIEW IF EXISTS `User_Lego_View`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `User_Lego_View` AS select `User_Lego`.`id` AS `id`,`User_Lego`.`user` AS `user`,`Lego`.`id` AS `lego__id`,`Lego`.`name` AS `lego__name`,`Lego`.`description` AS `lego__description`,`Lego`.`image_path` AS `lego__image_path`,`Type`.`id` AS `type__id`,`Type`.`name` AS `type__name` from ((`User_Lego` join `Lego` on((`User_Lego`.`lego` = `Lego`.`id`))) join `Type` on((`User_Lego`.`type` = `Type`.`id`))) */;
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

-- Dump completed on 2017-11-11 16:01:10
