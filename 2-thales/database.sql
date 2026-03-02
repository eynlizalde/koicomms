-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: koicomms_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `activity_images`
--

DROP TABLE IF EXISTS `activity_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_key` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_images`
--

LOCK TABLES `activity_images` WRITE;
/*!40000 ALTER TABLE `activity_images` DISABLE KEYS */;
INSERT INTO `activity_images` VALUES (8,'fieldtrip','assets/fieldtrip1.jpg',0),(9,'fieldtrip','assets/fieldtrip2.jpg',1),(10,'fieldtrip','assets/fieldtrip3.jpg',2),(11,'gpsoa','assets/foundationday1.jpg',0),(12,'gpsoa','assets/foundationday2.jpg',1),(13,'gpsoa','assets/foundationday3.jpg',2),(14,'gpsoa','assets/foundationday4.jpg',3),(15,'gpsoa','assets/foundationday5.jpg',4),(16,'gpsoa','assets/foundationday6.jpg',5),(17,'recollection','assets/gospelrecoll1.jpg',0),(18,'womens_month','assets/womensceleb1.jpg',0);
/*!40000 ALTER TABLE `activity_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL,
  `section_id` varchar(50) NOT NULL,
  `content_text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_name` (`page_name`,`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (1,'activities','page_intro',''),(2,'activities','fieldtrip_title','Field Trip'),(3,'activities','fieldtrip_desc','After a busy month, students enjoyed a relaxing day out, promoting both physical and mental well-being. The trip also included a visit to a museum, offering valuable educational insights.'),(4,'activities','gpsoa_title','GPSOA and Foundation Celebration'),(5,'activities','gpsoa_desc','One of the most anticipated school events, GPSOA features exciting tournaments in sports like chess, volleyball, basketball, and majorette. Winners are recognized during the Foundation Celebration, which also includes a lively dance intermission.'),(6,'activities','recollection_title','Recollection'),(7,'activities','recollection_desc','Strengthening our connection with the Lord is a key mission of our school. Every year, students participate in a meaningful recollection at the chapel beside the school.'),(8,'activities','womens_month_title','WomenΓÇÖs Month Celebration'),(9,'activities','womens_month_desc','Honoring the achievements, strength, and contributions of women, a program is arranged to inspire students to appreciate and empower the women in their lives through meaningful reflections.'),(24,'fees','payment_schedule_title','Schedule of Payment'),(25,'fees','payment_note','<strong>Note:</strong> Books, P.E. Uniforms, Regular Uniforms, Miscellaneous and other fees are paid upon enrollment.'),(26,'fees','tuition_fees_title','A. Tuition Fees may be paid on the following basis:'),(27,'fees','tuition_fees_list','<li><strong>CASH</strong> - All fees are paid in full during enrollment.</li>\n                    <li><strong>SEMI-ANNUAL</strong>\n                        <ul>\n                            <li>First payment is paid on 15th day of August.</li>\n                            <li>Second payment is paid on 15th day of December.</li>\n                        </ul>\n                    </li>\n                    <li><strong>QUARTERLY</strong>\n                        <ul>\n                            <li>First Payment: 15th day of August</li>\n                            <li>Second Payment: 15th October</li>\n                            <li>Third Payment: 15th December</li>\n                            <li>Fourth Payment: 15th February</li>\n                        </ul>\n                    </li>\n                    <li><strong>MONTHLY</strong> - Payment is due every 15th of the month.</li>'),(28,'fees','payment_notification','Parents are requested to notify the Office of the Finance for the mode of payment they prefer to avail themselves of.'),(29,'fees','refunds_title','B. Refunds of tuition fees to students who decide to withdraw will be made as follows:'),(30,'fees','refunds_list','<li>First week after registration - 80%</li>\n                    <li>Second, Third and Fourth Week after Registration - 50%</li>\n                    <li>30 days after registration - No Refund</li>'),(31,'fees','refunds_note','This is applicable regardless of whether or not the student had actually attended classes.'),(32,'fees','admission_req_title','Admission Requirements'),(33,'fees','admission_privilege','Admission to ARMY\'S ANGELS INTEGRATED SCHOOL is not a right, rather it is a privilege. Only those students who passed our screening tests and whom we feel showed potentials of benefiting for our school program will be admitted.'),(34,'fees','general_req_title','A. GENERAL REQUIREMENTS'),(35,'fees','general_req_list','<li>Form 138/SF 9 (Original Copy)</li>\n                    <li>PSA Birth Certificate (Original and 2 Photocopies)</li>\n                    <li>2pcs 2x2 picture with White Background</li>\n                    <li>Good Moral Certificate</li>\n                    <li>Certificate of Completion</li>\n                    <li>For ALS:\n                        <ul>\n                            <li>a. Original Portfolio</li>\n                            <li>b. Certificate of ALS Graduate (Photocopy)</li>\n                        </ul>\n                    </li>\n                    <li>Form 137 (Original)</li>\n                    <li>Medical Certificate (Optional)</li>'),(36,'fees','preschool_req_title','B. PRE SCHOOL'),(37,'fees','preschool_req_list','<li><strong>Age Requirement</strong>\n                        <ul>\n                            <li>KINDER: 4-5 years old</li>\n                            <li>PREPARATORY: 5-6 years old</li>\n                        </ul>\n                    </li>\n                    <li>Admission of the students in AAIS is considered to be an expression of willingness consequently of his parent and guardians to abide by all rules and regulations of the school thus the students and his parents and guardians are committed to comply strictly with the regulations of the school.</li>\n                    <li>All applicants shall undergo the following Admission Procedure:\n                        <ul>\n                            <li>a. Applicants must submit all requirements together with the student application form to the School Admin Office for initial screening.</li>\n                            <li>b. AAIS reserves the right to admit, re-admit, refuse or dismiss a student on the basis of his academic and/or conduct performance.</li>\n                            <li>c. Currently enrolled students with good standing are given priority in the registration in the next year. Priority is lost when he/she fails to enroll at the scheduled date of enrollment prescribed by the school. The school operates on the \"first come, first served\" basis.</li>\n                        </ul>\n                    </li>'),(38,'fees','fees_adjustment_title','Adjustment of Fees'),(39,'fees','scholarship_title','A. Scholarship'),(40,'fees','scholarship_desc','A student who garnered First Honors Overall by grade level shall enjoy free tuition fee excluding miscellaneous and registration fees. However, 2nd and 3rd placers will get 50% and 30% discount on tuition fees respectively in both elementary and high school levels only. Students must be enrolled previously in AAIS to be eligible in this scholarship.'),(41,'fees','discount_title','B. Discount'),(42,'fees','discount_list','<li>When 3 children in a family enrolled simultaneously, the youngest shall be entitled to a 20% discount on the tuition fee.</li>\n                    <li>When more than three children in the family are enrolled simultaneously only the youngest shall be entitled to a 50% discount on tuition fee.</li>\n                    <li>An employee shall be entitled to a fifty percent discount on the tuition fee, of his/her immediate dependent after having established one school year of residence in AAIS.</li>'),(43,'fees','transferees_title','C. Transferees-Honor Pupils'),(44,'fees','transferees_desc','Honor pupils who transferred in can avail of the discount on tuition fees based on the following criteria:'),(45,'fees','transferees_list','<li><strong>With Highest Honors Certificate</strong> is awarded to students with an average grade of 98-100 with no grade below 88 in any subject and at least AO in conduct.</li>\n                    <li><strong>With Highest Honors in Class of:</strong>\n                        <ul>\n                            <li>30 pupils - 30%</li>\n                            <li>Less than 30 pupils - 20%</li>\n                            <li>More than 30 pupils - 40%</li>\n                        </ul>\n                    </li>\n                    <li><strong>With High Honors in Class of:</strong>\n                        <ul>\n                            <li>30 pupils - 25%</li>\n                            <li>Less than 30 pupils - 15%</li>\n                            <li>More than 30 pupils - 30%</li>\n                        </ul>\n                    </li>'),(46,'fees','transferees_req','<strong>Requirement:</strong> Certification from previous school of the class standing/ranking the honor pupil duly signed by the principal.'),(47,'fees','other_info_title','Other Information'),(48,'fees','other_info_list','<li>All payments except for the tuition fees are non-refundable.</li>\n                <li>Parents who pay in checks should inform the Treasurer in person. Bad checks will incur charges of P500.00.</li>\n                <li>Parents are advised to pay their obligations on time so that their child/children can take the examinations without tension and anxiety. In case of not meeting such obligations, parents themselves and not the children or helper should see the Finance Officer to make arrangements two days before the examination dates, NOT DURING EXAMINATION DAYS.</li>'),(49,'fees','enrollment_policies_title','Enrollment Policies'),(50,'fees','disqualification_title','DISQUALIFICATION FOR ENROLLMENT'),(51,'fees','disqualification_desc','The school reserves the right to refuse student when his/her public and private behavior is at variance with the school principles.'),(52,'fees','dropping_title','DROPPING OR WITHDRAWAL FROM THE COURSE'),(53,'fees','dropping_desc_1','Miscellaneous fees are made in full and not refundable. Tuition fees are refundable in full if the withdrawal is done by the parent/guardian before the opening of classes. Tuition fee is refunded if withdrawal is done in accordance of the following schedule:'),(54,'fees','dropping_list','<li>A. Within one week from the start of classes - 80% of the assessed tuition fee</li>\n                <li>B. Within two weeks from the start of classes - 50% of the assessed tuition fee</li>\n                <li>C. Within one month from the start of classes - No refund</li>'),(55,'fees','dropping_note','<strong>NOTE:</strong> Students who withdraw and drop the course after one month from the start of classes shall be liable for the whole tuition fee and miscellaneous fees.'),(85,'activities','foundation_celebration_desc','-	2nd week of February of every year<br><br>Activities: 	Field Demonstration, Games, Quiz Bee &amp; Spelling Contest, Ball Games, Cheer dance<br>Medals and Certificates are to be given to the best Performers during the Recognition/Graduation Day');
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('enrollment_url','https://docs.google.com/forms/d/e/1FAIpQLSfgqKHwYmDm2FPWLBCyHL0awb6zPHps4rwwPDKNpnRU3maDSA/viewform');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `enrollment_id` varchar(255) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reset_token` (`reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Admin','aais@gmail.com','$2y$10$ChmyZ1OOYg1YGipuehvt7uqQJou9JjCF5vNOZUMK8JHUwUR6UTM06','2026-01-25 18:51:58',NULL,'admin',NULL,NULL),(6,'Admin','armysangelsw@gmail.com','$2y$10$mLKSC5g8DKNNYDQnt6lU/eqciM53K2Xy92Fne7JcefhqjQZw.Egxa','2026-01-31 22:00:15',NULL,'admin',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-03  4:21:38
