/*

SQLyog Ultimate v8.6 Beta2
MySQL - 5.5.5-10.1.21-MariaDB : Database - bmt

*********************************************************************

*/



/*!40101 SET NAMES utf8 */;



/*!40101 SET SQL_MODE=''*/;



/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*CREATE DATABASE /*!32312 IF NOT EXISTS `bmt` /*!40100 DEFAULT CHARACTER SET latin1 */;



/*USE `bmt`;*/



/*Table structure for table `bmt` */



DROP TABLE IF EXISTS `bmt`;



CREATE TABLE `bmt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_bmt` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rekening` int(10) unsigned NOT NULL,
  `nama` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bmt_id_bmt_unique` (`id_bmt`),
  KEY `bmt_id_rekening_foreign` (`id_rekening`),
  CONSTRAINT `bmt_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=402 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `bmt` */



LOCK TABLES `bmt` WRITE;



insert  into `bmt`(`id`,`id_bmt`,`id_rekening`,`nama`,`saldo`,`detail`,`created_at`,`updated_at`) values (262,'1',1,'AKTIVA','','','2018-06-24 16:51:31','2018-07-25 05:14:26'),(263,'2',2,'HUTANG','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(264,'2.1',3,'SIMPANAN SYARIAH','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(265,'2.1.1',4,'SIMPANAN MUDHAROBAH UMUM','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(266,'2.1.1.1',5,'SIMPANAN MUDHAROBAH UMUM','7620000','','2018-06-24 16:51:31','2018-08-05 16:57:45'),(267,'2.1.2',6,'SIMPANAN MUDHAROBAH BERJANGKA','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(268,'2.1.2.1',7,'SIMPANAN TARBIYAH / PENDIDIKAN','74999.99999999907','','2018-06-24 16:51:31','2018-07-30 15:30:44'),(269,'2.1.2.2',8,'SIMPANAN IDUL FITRI','0','','2018-06-24 16:51:31','2018-07-30 12:33:17'),(270,'2.1.2.3',9,'SIMPANAN IDUL ADHA','0','','2018-06-24 16:51:31','2018-07-30 15:30:44'),(271,'2.1.2.4',10,'SIMPANAN WALIMAH','0','','2018-06-24 16:51:31','2018-07-30 12:33:18'),(272,'1.1',11,'KAS','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(273,'1.1.1',12,'KAS','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(274,'1.1.1.1',13,'KAS TELLER 1','51394000','','2018-06-24 16:51:31','2018-08-14 14:04:55'),(275,'1.1.1.2',14,'KAS TELLER 2','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(276,'2.1.3',44,'SIMPANAN WADIAH','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(277,'2.1.3.1',45,'SIMPANAN WADIAH','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(278,'2.2',46,'MUDHARABAH SYARIAH','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(279,'2.2.1',47,'MUDHARABAH BERJANGKA','','','2018-06-24 16:51:31','2018-06-24 16:51:31'),(280,'2.2.1.1',48,'MUDHARABAH 1 BULAN','0','','2018-06-24 16:51:32','2018-07-02 19:33:27'),(281,'2.2.1.2',49,'MUDHARABAH  3 BULAN','6000000','','2018-06-24 16:51:32','2018-07-15 06:30:25'),(282,'2.3',50,'ANTAR KOPERASI PASIVA','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(283,'2.4',54,'PINJAMAN DARI BANK DAN NON BANK','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(284,'2.4.1',55,'PINJAMAN BANK','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(285,'2.4.2',56,'PINJAMAN NON BANK','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(286,'2.4.1.1',57,'PINJAMAN BANK MANDIRI SYARIAH JEMUR','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(287,'2.4.1.2',58,'PINJAMAN BPR SYARIAH MOJOKERTO','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(288,'2.4.2.1',59,'JAMSOSTEK KARIMUNJAWA SURABAYA','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(289,'2.4.2.2',60,'JAMSOSTEK DARMO SURABAYA','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(290,'2.4.2.3',61,'JAMSOSTEK PERAK SURABAYA','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(291,'1.2',62,'BANK','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(292,'1.3',63,'ANTAR KOPERASI AKTIVA','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(293,'1.4',64,'INVESTASI','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(294,'1.5',65,'PEMBIAYAAN','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(295,'1.6',66,'PEMBIAYAAN LAIN-LAIN','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(296,'1.7',67,'PENYISIHAN PIUTANG','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(297,'1.8',68,'BIAYA DIBAYAR DIMUKA','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(298,'1.9',69,'PENYERTAAN PADA ENTITAS LAIN','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(299,'1.10',70,'TANAH DAN BANGUNGAN','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(300,'1.11',74,'GEDUNG KANTOR','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(301,'1.12',77,'AKUMULASI PENYUST. GEDUNG KANTOR','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(302,'1.13',78,'KENDARAAN','','','2018-06-24 16:51:32','2018-06-24 16:51:32'),(303,'1.14',79,'AKUMULASI PENYUST. KENDARAAN','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(304,'1.15',80,'INVENTARIS KANTOR','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(305,'1.16',81,'BIAYA PRA OPERASIONAL','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(306,'1.2.1',82,'BANK SYARIAH','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(307,'1.2.2',84,'BANK KONVENSIONAL','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(308,'1.2.1.1',86,'BANK KRIAN','26110000','','2018-06-24 16:51:33','2018-08-05 17:10:06'),(309,'1.2.1.2',87,'BANK JEMUR SARI SURABAYA','5450000','','2018-06-24 16:51:33','2018-08-05 17:09:24'),(310,'1.2.2.1',88,'MANDIRI CABANG UNAIR','','','2018-06-24 16:51:33','2018-06-25 12:44:31'),(311,'1.3.1',89,'A.K.A. KOPERASI','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(312,'1.3.1.1',90,'A.K.A. PUSAT KJKS JATIM MICROFIN','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(313,'1.3.1.2',91,'INKOPSYAH BMT','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(314,'1.3.1.3',92,'PUSKOPSYAH JATIM AL AKBAR','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(315,'1.4.1',93,'INVESTASI LAINNYA','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(316,'1.4.1.1',94,'INVESTASI CABANG BUNGA GRESIK','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(317,'1.5.1',95,'PEMBIAYAAN MDA','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(318,'1.5.2',96,'PEMBIAYAAN MRB','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(319,'1.5.3',97,'PEMBIAYAAN QORD','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(320,'1.6.1',98,'PEMBIAYAAN LAIN EKSTERNAL','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(321,'1.5.1.1',99,'PEMBIAYAAN MDA','1900000','','2018-06-24 16:51:33','2018-08-14 14:04:55'),(322,'1.5.2.1',100,'PEMBIAYAAN MRB','28916000','','2018-06-24 16:51:33','2018-08-06 16:56:29'),(323,'1.5.2.2',101,'PIUTANG MRB YANG DITANGGUHKAN','-1400000','','2018-06-24 16:51:33','2018-08-06 16:56:29'),(324,'1.5.3.1',102,'PEMBIAYAAN QORD','','','2018-06-24 16:51:33','2018-06-24 16:51:33'),(325,'1.6.1.1',103,'PEMBY. MDA LAIN-LAIN','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(326,'1.7.1',104,'PENYISIHAN PIUTANG UMUM','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(327,'2.1.2.5',105,'SIMPANAN ZIAROH/WISATA','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(328,'2.1.2.6',106,'SIMPANAN UNIT LAIN','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(329,'2.3.1',107,'A.K.P KOPERASI','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(330,'2.3.1.3',108,'A.K.P PUSAT KJKS JATIM MICROFIN','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(331,'2.3.1.1',109,'INKOPSYAH','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(332,'2.3.1.2',110,'BMT MBS','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(333,'2.5',111,'DANA PENDIDIKAN','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(334,'2.6',112,'ZAKAT','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(335,'2.7',113,'DANA SOSIAL','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(336,'2.8',114,'RUPA-RUPA PASIVA','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(337,'3.1',115,'MODAL','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(338,'3.2',116,'KEKAYAAN & SHU','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(339,'3.2.1',117,'SIMPANAN POKOK ANGGOTA','225000','','2018-06-24 16:51:34','2018-07-26 07:21:56'),(340,'3.2.2',118,'WAQAF UANG','5100000','','2018-06-24 16:51:34','2018-08-05 17:09:24'),(341,'3.2.3',119,'SIMPANAN WAJIB ANGGOTA','200000','','2018-06-24 16:51:34','2018-07-26 14:06:07'),(342,'3.2.4',120,'SIMPANAN SUKA RELA ANGGOTA','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(343,'3.2.5',121,'DANA CADANGAN UMUM','0','','2018-06-24 16:51:34','2018-07-30 15:30:43'),(344,'3.2.6',122,'SHU BERJALAN','49600000','','2018-06-24 16:51:34','2018-08-14 14:04:55'),(345,'3',123,'MODAL','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(346,'4',125,'PENDAPATAN','','','2018-06-24 16:51:34','2018-06-24 16:51:34'),(347,'5',126,'BIAYA','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(348,'4.1',127,'PENDAPATAN OPERASIONAL','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(349,'4.1.1',128,'PENDAPATAN PEMBIAYAAN','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(350,'4.1.1.1',129,'PENDAPATAN BH PEMBY MDA','6500000','','2018-06-24 16:51:35','2018-08-14 14:04:55'),(351,'4.1.1.2',130,'PENDAPATAN BH PEMBY MRB','43100000','','2018-06-24 16:51:35','2018-07-25 05:14:26'),(352,'4.1.1.3',131,'PENDAPATAN BH PEMBY QORD','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(353,'4.1.2',132,'PENDAPATAN OPERASIONAL LAINNYA','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(354,'4.1.2.1',133,'PENDAPATAN BH LAINNYA','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(355,'5.1',134,'BEBAN LANGSUNG TABUNGAN','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(356,'5.2',135,'BEBAN LANGSUNG ANTAR KOPERASI PASIVA','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(357,'5.3',136,'BEBAN LANGSUNG PINJAM BANK DAN NON BANK','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(358,'5.4',137,'BEBAN OPERASIONAL DAN ADMINISTRASI','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(359,'5.1.1',138,'BEBAN BH TABUNGAN MDA UMUM','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(360,'5.1.1.1',139,'BEBAN BH TABUNGAN MDA UMUM','0','','2018-06-24 16:51:35','2018-07-30 12:53:41'),(361,'5.1.2',140,'BEBAN BH TABUNGAN MDA BERJANGKA','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(362,'5.1.2.1',141,'BEBAN TAB. TARBIYAH / PENDIDIKAN','0','','2018-06-24 16:51:35','2018-07-25 08:46:24'),(363,'5.1.2.2',142,'BEBAN TAB. IDUL FITRI','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(364,'5.1.2.3',143,'BEBAN TAB. IDUL ADHA','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(365,'5.1.2.4',144,'BEBAN TAB. WALIMAH','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(366,'5.1.2.5',145,'BEBAN TAB. ZIARAH / WISATA','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(367,'5.1.2.6',146,'BEBAN TAB. UNIT LAIN','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(368,'5.2.1',147,'BEBAN BH ANTAR KOP. SYARIAH','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(369,'5.2.1.1',148,'BEBAN BH INKOPSYAH','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(370,'5.2.1.2',149,'BEBAN BH  MICROFIN','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(371,'5.3.1',150,'BEBAN BH PINJAMAN BANK','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(372,'5.3.1.1',151,'BEBAN BH PINJAMAN MANDIRI SYARIAH JEMUR','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(373,'5.3.1.2',152,'BEBAN BH PINJAMAN BPR SYARIAH MOJOKERTO','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(374,'5.4.1',153,'BIAYA KARYAWAN','','','2018-06-24 16:51:35','2018-06-24 16:51:35'),(375,'5.4.1.1',154,'BEBAN BISYAROH KARYAWAN','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(376,'5.4.2',155,'BIAYA KANTOR','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(377,'5.4.2.1',156,'BIAYA PERLENGKAPAN KANTOR','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(378,'5.4.2.2',157,'BIAYA LISTRIK, PDAM, DAN TELEPON','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(379,'5.4.2.3',158,'BIAYA TRANSPORTASI','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(380,'5.4.2.4',159,'BIAYA ORGANISASI','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(381,'5.4.2.5',160,'BIAYA PROMOSI','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(382,'5.5',161,'BEBAN DEPOSITO','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(383,'5.5.1',162,'BEBAN MUDHARABAH SYARIAH','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(384,'5.5.1.1',163,'BEBAN MUDHARABAH BERJANGKA','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(385,'5.5.1.1.1',164,'BEBAN MUDHARABAH 1 BULAN','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(386,'5.5.1.1.2',165,'BEBAN MUDHARABAH 3 BULAN','0','','2018-06-24 16:51:36','2018-07-30 12:53:41'),(387,'5.5.1.1.3',166,'BEBAN MUDHARABAH 6 BULAN','0','','2018-06-24 16:51:36','2018-07-30 12:53:41'),(388,'5.5.1.1.4',167,'BEBAN MUDHARABAH 9 BULAN','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(389,'5.5.1.1.5',168,'BEBAN MUDHARABAH 12 BULAN','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(390,'2.2.1.3',169,'MUDHARABAH 6 BULAN','7000000','','2018-06-24 16:51:36','2018-07-14 11:26:52'),(391,'2.2.1.4',170,'MUDHARABAH 9 BULAN','','','2018-06-24 16:51:36','2018-06-25 12:38:14'),(392,'2.2.1.5',171,'MUDHARABAH 12 BULAN','11000000','','2018-06-24 16:51:36','2018-07-02 20:27:20'),(393,'4.1.3',172,'PENDAPATAN ADMINISTRASI','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(394,'4.1.3.1',173,'PENDAPATAN ADMINISTRASI PEMBIAYAAN','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(395,'2.9',174,'PEMINDAHAN BUKUAN','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(396,'2.9.1',175,'PEMINDAHAN BUKUAN','','','2018-06-24 16:51:36','2018-06-24 16:51:36'),(397,'3.2.7',176,'SHU YANG HARUS DIBAGIKAN','25000000','','2018-07-14 10:53:54','2018-07-30 15:30:45'),(399,'4.1.3.2',178,'PENDAPATAN ADMINISTRASI TABUNGAN','','','2018-07-24 19:39:48','2018-07-24 19:39:48'),(400,'2.1.2.7',179,'SIMPANAN MAAL','1050000','','2018-08-02 07:45:36','2018-08-05 17:10:06'),(401,'2.10',180,'PAJAK YANG DITANGGUHKAN','','','2018-08-14 14:05:33','2018-08-14 14:05:33');



UNLOCK TABLES;



/*Table structure for table `deposito` */



DROP TABLE IF EXISTS `deposito`;



CREATE TABLE `deposito` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_deposito` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rekening` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_pengajuan` int(10) unsigned NOT NULL,
  `jenis_deposito` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempo` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `deposito_id_deposito_unique` (`id_deposito`),
  KEY `deposito_id_rekening_foreign` (`id_rekening`),
  KEY `deposito_id_user_foreign` (`id_user`),
  KEY `deposito_id_pengajuan_foreign` (`id_pengajuan`),
  CONSTRAINT `deposito_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `deposito_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  CONSTRAINT `deposito_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `deposito` */



LOCK TABLES `deposito` WRITE;



insert  into `deposito`(`id`,`id_deposito`,`id_rekening`,`id_user`,`id_pengajuan`,`jenis_deposito`,`detail`,`tempo`,`created_at`,`updated_at`,`status`) values (55,'2.1',48,2,463,'MUDHARABAH 1 BULAN','{\"saldo\":0,\"id_pengajuan\":463,\"id_pencairan\":\"115\"}','2018-07-02','2018-07-02 19:24:06','2018-07-02 19:25:11','not active'),(56,'2.2',48,2,465,'MUDHARABAH 1 BULAN','{\"saldo\":0,\"id_pengajuan\":465,\"id_pencairan\":\"115\"}','2018-07-02','2018-07-02 19:29:41','2018-07-02 19:33:26','not active'),(57,'2.3',171,2,469,'MUDHARABAH 12 BULAN','{\"saldo\":\"1000000\",\"id_pengajuan\":529,\"id_pencairan\":\"115\"}','2018-07-01','2018-07-01 20:26:48','2018-07-12 14:18:33','blocked'),(60,'2.4',169,2,534,'MUDHARABAH 6 BULAN','{\"saldo\":1000000,\"id_pengajuan\":534,\"id_pencairan\":\"127\"}','2018-07-12','2018-07-12 13:57:59','2018-07-12 13:57:59','active'),(62,'14.1',169,14,698,'MUDHARABAH 6 BULAN','{\"saldo\":5000000,\"id_pengajuan\":698,\"id_pencairan\":\"130\"}','2018-07-14','2018-07-14 11:26:52','2018-07-14 11:26:52','active'),(63,'2.6',49,2,703,'MUDHARABAH  3 BULAN','{\"saldo\":5000000,\"id_pengajuan\":703,\"id_pencairan\":\"139\"}','2018-07-15','2018-07-15 06:30:25','2018-07-15 06:30:25','active');



UNLOCK TABLES;



/*Table structure for table `jaminan` */



DROP TABLE IF EXISTS `jaminan`;



CREATE TABLE `jaminan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_jaminan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `jaminan` */



LOCK TABLES `jaminan` WRITE;



insert  into `jaminan`(`id`,`nama_jaminan`,`status`,`detail`,`created_at`,`updated_at`) values (1,'Mobil','active','[\"Merk\",\"Tahun Beli\",\"Harga Jual\",\"Nomor Polisi\"]','2018-08-04 05:25:37','2018-08-04 05:25:37'),(2,'Motor','active','[\"Merk\",\"Tahun Beli\",\"Harga Jual\"]','2018-08-04 05:26:08','2018-08-04 05:31:32');



UNLOCK TABLES;



/*Table structure for table `maal` */



DROP TABLE IF EXISTS `maal`;



CREATE TABLE `maal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_maal` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rekening` int(10) unsigned NOT NULL,
  `nama_kegiatan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pelaksaaan` date NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maal_id_maal_unique` (`id_maal`),
  KEY `maal_id_rekening_foreign` (`id_rekening`),
  CONSTRAINT `maal_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `maal` */



LOCK TABLES `maal` WRITE;



insert  into `maal`(`id`,`id_maal`,`id_rekening`,`nama_kegiatan`,`tanggal_pelaksaaan`,`status`,`detail`,`created_at`,`updated_at`) values (1,'1',179,'Baru','2018-05-30','active','{\"detail\":\"Baksos Panti asuhan\",\"dana\":5100000,\"terkumpul\":1050000,\"path_poster\":\"\"}',NULL,'2018-08-05 17:10:06'),(6,'2',179,'Baksos','2018-06-30','active','{\"detail\":\"Baksos Panti Asuhan BMT MUDA\",\"dana\":\"2600000\",\"terkumpul\":0,\"path_poster\":\"\\/WEu93yBnnUUDKBT8rMRifPRwoHFHvDX9idemztta.jpeg\"}','2018-06-08 21:58:26','2018-08-02 15:52:13');



UNLOCK TABLES;



/*Table structure for table `migrations` */



DROP TABLE IF EXISTS `migrations`;



CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `migrations` */



LOCK TABLES `migrations` WRITE;



insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2018_04_25_225926_create_datamaster_table',1),(4,'2018_04_25_232548_create_datatransaksi_table',1),(5,'2018_05_26_064044_add_status_table',2),(6,'2018_05_28_055737_add_status_angsur',3),(10,'2018_06_24_162719_add_penyimpanan_rekening',4),(11,'2018_07_15_063403_add_wajib_pokok',4),(12,'2018_07_15_063519_add_penyimpanan_wajib_pokok',4),(13,'2018_07_15_064459_add_role',5),(14,'2018_07_25_110259_create_shu_table',6),(15,'2018_07_26_204217_create_jaminan_table',7),(16,'2018_07_30_132240_create_penyimpanan_user',7),(17,'2018_08_03_143836_create_penyimpanan_jaminan',8);



UNLOCK TABLES;



/*Table structure for table `password_resets` */



DROP TABLE IF EXISTS `password_resets`;



CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `password_resets` */



LOCK TABLES `password_resets` WRITE;



UNLOCK TABLES;



/*Table structure for table `pembiayaan` */



DROP TABLE IF EXISTS `pembiayaan`;



CREATE TABLE `pembiayaan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pembiayaan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rekening` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_pengajuan` int(10) unsigned NOT NULL,
  `jenis_pembiayaan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempo` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_angsuran` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `angsuran_ke` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pembiayaan_id_pembiayaan_unique` (`id_pembiayaan`),
  KEY `pembiayaan_id_rekening_foreign` (`id_rekening`),
  KEY `pembiayaan_id_user_foreign` (`id_user`),
  KEY `pembiayaan_id_pengajuan_foreign` (`id_pengajuan`),
  CONSTRAINT `pembiayaan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembiayaan_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembiayaan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `pembiayaan` */



LOCK TABLES `pembiayaan` WRITE;



insert  into `pembiayaan`(`id`,`id_pembiayaan`,`id_rekening`,`id_user`,`id_pengajuan`,`jenis_pembiayaan`,`detail`,`tempo`,`created_at`,`updated_at`,`status`,`status_angsuran`,`angsuran_ke`) values (78,'2.1',100,2,523,'PEMBIAYAAN MRB','{\"pinjaman\":10000000,\"margin\":24000000,\"nisbah\":0.1,\"total_pinjaman\":34000000,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"angsuran_pokok\":1416666.6666666667,\"lama_angsuran\":24,\"angsuran_ke\":24,\"tagihan_bulanan\":0,\"id_pengajuan\":523}','2018-07-11','2018-07-11 13:29:52','2018-07-12 21:32:52','not active','2','24'),(80,'2.2',100,2,567,'PEMBIAYAAN MRB','{\"pinjaman\":1000000,\"margin\":200000,\"nisbah\":0.1,\"total_pinjaman\":1200000,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"angsuran_pokok\":600000,\"lama_angsuran\":\"2\",\"angsuran_ke\":2,\"tagihan_bulanan\":0,\"id_pengajuan\":567}','2018-07-12','2018-07-12 20:35:22','2018-07-12 20:35:43','not active','2','2'),(81,'2.3',100,2,570,'PEMBIAYAAN MRB','{\"pinjaman\":1000000,\"margin\":200000,\"nisbah\":0.1,\"total_pinjaman\":1200000,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"angsuran_pokok\":600000,\"lama_angsuran\":\"2\",\"angsuran_ke\":2,\"tagihan_bulanan\":0,\"id_pengajuan\":570}','2018-07-12','2018-07-12 20:39:03','2018-07-12 20:39:25','not active','2','2'),(97,'2.6',100,2,726,'PEMBIAYAAN MRB','{\"pinjaman\":1000000,\"margin\":200000,\"nisbah\":0.1,\"total_pinjaman\":1200000,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"angsuran_pokok\":600000,\"lama_angsuran\":\"2\",\"angsuran_ke\":2,\"tagihan_bulanan\":600000,\"sisa_ang_bln\":0,\"sisa_mar_bln\":0,\"id_pengajuan\":726}','2018-07-18','2018-07-18 06:48:43','2018-07-19 23:00:09','not active','0','2'),(100,'2.7',99,2,751,'PEMBIAYAAN MDA','{\"pinjaman\":1000000,\"margin\":200000,\"nisbah\":0.1,\"total_pinjaman\":1200000,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"angsuran_pokok\":600000,\"lama_angsuran\":\"2\",\"angsuran_ke\":2,\"tagihan_bulanan\":600000,\"sisa_ang_bln\":0,\"sisa_mar_bln\":0,\"id_pengajuan\":751}','2018-07-19','2018-07-19 10:55:31','2018-07-20 00:01:48','not active','0','2'),(101,'2.8',100,2,778,'PEMBIAYAAN MRB','{\"pinjaman\":1000000,\"margin\":200000,\"nisbah\":0.1,\"total_pinjaman\":1200000,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"angsuran_pokok\":600000,\"lama_angsuran\":\"2\",\"angsuran_ke\":2,\"tagihan_bulanan\":600000,\"sisa_ang_bln\":0,\"sisa_mar_bln\":0,\"id_pengajuan\":778}','2018-07-20','2018-07-20 00:04:19','2018-07-25 05:14:26','not active','0','2'),(102,'2.9',99,2,783,'PEMBIAYAAN MDA','{\"pinjaman\":1000000,\"margin\":200000,\"nisbah\":0.1,\"total_pinjaman\":1200000,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"angsuran_pokok\":600000,\"lama_angsuran\":\"2\",\"angsuran_ke\":2,\"tagihan_bulanan\":600000,\"sisa_ang_bln\":0,\"sisa_mar_bln\":0,\"id_pengajuan\":783}','2018-07-25','2018-07-25 08:56:41','2018-08-14 14:04:31','not active','0','2'),(103,'2.10',99,2,794,'PEMBIAYAAN MDA','{\"pinjaman\":1000000,\"margin\":500000,\"nisbah\":0.1,\"total_pinjaman\":1500000,\"sisa_angsuran\":1000000,\"sisa_margin\":500000,\"sisa_pinjaman\":1500000,\"angsuran_pokok\":300000,\"lama_angsuran\":\"5\",\"angsuran_ke\":0,\"tagihan_bulanan\":300000,\"sisa_ang_bln\":200000,\"sisa_mar_bln\":100000,\"id_pengajuan\":794}','2018-08-06','2018-08-06 16:01:45','2018-08-06 16:01:45','active','0','0'),(104,'2.11',99,2,794,'PEMBIAYAAN MDA','{\"pinjaman\":1000000,\"margin\":500000,\"nisbah\":0.1,\"total_pinjaman\":1500000,\"sisa_angsuran\":800000,\"sisa_margin\":400000,\"sisa_pinjaman\":1200000,\"angsuran_pokok\":300000,\"lama_angsuran\":\"5\",\"angsuran_ke\":1,\"tagihan_bulanan\":300000,\"sisa_ang_bln\":\"200000\",\"sisa_mar_bln\":100000,\"id_pengajuan\":794}','2018-08-06','2018-08-06 16:01:58','2018-08-14 14:04:55','active','0','1'),(110,'2.12',100,2,797,'PEMBIAYAAN MRB','{\"pinjaman\":10000000,\"margin\":1000000,\"nisbah\":0.1,\"total_pinjaman\":11000000,\"sisa_angsuran\":10000000,\"sisa_margin\":1000000,\"sisa_pinjaman\":11000000,\"angsuran_pokok\":11000000,\"lama_angsuran\":1,\"angsuran_ke\":0,\"tagihan_bulanan\":11000000,\"sisa_ang_bln\":10000000,\"sisa_mar_bln\":1000000,\"id_pengajuan\":797}','2018-08-06','2018-08-06 16:56:29','2018-08-06 16:56:29','active','0','0');



UNLOCK TABLES;



/*Table structure for table `pengajuan` */



DROP TABLE IF EXISTS `pengajuan`;



CREATE TABLE `pengajuan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_rekening` int(10) unsigned NOT NULL,
  `jenis_pengajuan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengajuan_id_user_foreign` (`id_user`),
  KEY `pengajuan_id_rekening_foreign` (`id_rekening`),
  CONSTRAINT `pengajuan_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengajuan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=801 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `pengajuan` */



LOCK TABLES `pengajuan` WRITE;



insert  into `pengajuan`(`id`,`id_user`,`id_rekening`,`jenis_pengajuan`,`status`,`kategori`,`detail`,`created_at`,`updated_at`) values (448,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:32:22','2018-07-02 16:32:22'),(450,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:34:41','2018-07-02 16:34:41'),(451,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:35:07','2018-07-02 16:35:08'),(454,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:40:28','2018-07-02 16:40:28'),(455,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:51:38','2018-07-02 16:51:39'),(456,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:52:12','2018-07-02 16:52:12'),(457,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:53:49','2018-07-02 16:53:50'),(458,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:54:21','2018-07-02 16:54:21'),(459,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:54:37','2018-07-02 16:54:38'),(460,1,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 16:55:22','2018-07-02 16:55:22'),(461,1,5,'Kredit Tabungan [Tunai]','Sudah Dikonfirmasi','Kredit Tabungan','{\"id\":2,\"id_tabungan\":\"115\",\"nama\":\"Ghani Ramadhan\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"50000\",\"kredit\":\"Tunai\",\"path_bukti\":null,\"id_rekening\":5,\"nama_tabungan\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 19:20:57','2018-07-02 19:20:58'),(462,1,5,'Debit Tabungan [Tunai]','Sudah Dikonfirmasi','Debit Tabungan','{\"id\":2,\"id_tabungan\":\"2.1\",\"nama\":\"Ghani Ramadhan\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"20000\",\"debit\":\"Tunai\",\"id_rekening\":5,\"nama_tabungan\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 19:22:03','2018-07-02 19:22:03'),(463,1,48,'Buka Deposito MUDHARABAH 1 BULAN','Sudah Dikonfirmasi','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"jumlah\":\"300000\",\"deposito\":\"48\",\"nama_rekening\":\"MUDHARABAH 1 BULAN\"}','2018-07-02 19:24:06','2018-07-02 19:24:07'),(464,2,48,'Pencairan Deposito [Tunai]','Sudah Dikonfirmasi','Pencairan Deposito','{\"pencairan\":\"Tunai\",\"id_deposito\":\"2.1\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank\":null,\"no_bank\":null,\"atasnama\":null,\"keterangan\":null,\"jumlah\":\"300000\"}','2018-07-02 19:25:11','2018-07-02 19:25:11'),(465,1,48,'Buka Deposito MUDHARABAH 1 BULAN','Sudah Dikonfirmasi','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"jumlah\":\"300000\",\"deposito\":\"48\",\"nama_rekening\":\"MUDHARABAH 1 BULAN\"}','2018-07-02 19:29:41','2018-07-02 19:29:41'),(466,2,48,'Pencairan Deposito [Tunai]','Sudah Dikonfirmasi','Pencairan Deposito','{\"pencairan\":\"Tunai\",\"id_deposito\":\"2.2\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank\":null,\"no_bank\":null,\"atasnama\":null,\"keterangan\":null,\"jumlah\":\"300000\"}','2018-07-02 19:33:26','2018-07-02 19:33:27'),(467,1,5,'Kredit Tabungan [Transfer]','Sudah Dikonfirmasi','Kredit Tabungan','{\"id\":2,\"id_tabungan\":\"115\",\"nama\":\"Ghani Ramadhan\",\"bank\":\"87\",\"no_bank\":\"12\",\"daribank\":\"bgx\",\"atasnama\":\"g\",\"jumlah\":\"500000\",\"kredit\":\"Transfer\",\"path_bukti\":\"yFXzbfDSi7n81ORrSY5CeEaDtxDegtWz67GaBtMN.jpeg\",\"id_rekening\":5,\"nama_tabungan\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 19:52:48','2018-07-02 19:52:48'),(468,1,5,'Debit Tabungan [Transfer]','Sudah Dikonfirmasi','Debit Tabungan','{\"id\":2,\"id_tabungan\":\"2.1\",\"nama\":\"Ghani Ramadhan\",\"bank\":\"f\",\"no_bank\":\"321\",\"daribank\":\"87\",\"atasnama\":\"gd\",\"jumlah\":\"50000\",\"debit\":\"Transfer\",\"id_rekening\":5,\"nama_tabungan\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-02 19:54:27','2018-07-02 19:54:27'),(469,1,171,'Buka Deposito MUDHARABAH 12 BULAN','Sudah Dikonfirmasi','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"jumlah\":\"1000000\",\"deposito\":\"171\",\"nama_rekening\":\"MUDHARABAH 12 BULAN\"}','2018-07-02 20:26:47','2018-07-02 20:26:48'),(470,1,171,'Buka Deposito MUDHARABAH 12 BULAN','Sudah Dikonfirmasi','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"jumlah\":\"10000000\",\"deposito\":\"171\",\"nama_rekening\":\"MUDHARABAH 12 BULAN\"}','2018-07-02 20:27:20','2018-07-02 20:27:20'),(471,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"1 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Pertanian\",\"usaha\":\"1\",\"jaminan\":\"1\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/koYvCUBXKsyvk6fqUFZATOMFcriL4D1NGwv44AJi.jpeg\"}','2018-07-02 20:28:25','2018-07-02 20:28:25'),(472,2,100,'Pengajuan Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Industri\",\"usaha\":\"Bisnis\",\"keterangan\":\"1 Bulan\",\"jaminan\":\"sertifikat\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/6pcskQUMrgGU9pwc1a7hQOAhPwvMx6EassBH5nlu.jpeg\"}','2018-07-02 20:40:14','2018-07-02 20:40:36'),(473,2,100,'Pengajuan Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"keterangan\":\"1 Bulan\",\"jaminan\":\"sertifikat\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/ZhaxAcdJD87FFePqZr3ol2df4srtv1zIF6IDoDP4.jpeg\"}','2018-07-02 20:46:09','2018-07-02 20:46:34'),(476,2,100,'Pengajuan Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"keterangan\":\"1 Bulan\",\"jaminan\":\"sertifikat\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/mGehzERc6O5qBsn2Vdr6I7JsjPUlC5vbC82hSEYc.jpeg\"}','2018-07-03 06:00:58','2018-07-03 06:01:36'),(482,2,100,'Pengajuan Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Industri\",\"usaha\":\"dagang\",\"keterangan\":\"1 Bulan\",\"jaminan\":\"1\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/OsIkqnqQVP8JNTzS0JpLJDghVvenAxY6ojGLc9lg.jpeg\"}','2018-07-03 07:18:12','2018-07-03 07:19:35'),(523,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Tahun\",\"pembiayaan\":\"100\",\"jumlah\":\"10000000\",\"jenis_Usaha\":\"Pertanian\",\"usaha\":\"usaha\",\"jaminan\":\"sertifikat\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/N318669W9nxuhMszhFEHaOmtEI2RpWsBVgmeoOwI.jpeg\"}','2018-07-11 13:29:51','2018-07-11 13:29:52'),(525,3,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Disetujui','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghulam\",\"id\":3,\"akad\":\"5\",\"tabungan\":\"5\",\"keterangan\":\"investasi\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-12 10:29:20','2018-07-12 10:29:33'),(526,3,5,'Kredit Tabungan [Tunai]','Sudah Dikonfirmasi','Kredit Tabungan','{\"kredit\":\"Tunai\",\"id_tabungan\":\"128\",\"id\":3,\"nama\":\"Ghulam\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"1000000\",\"path_bukti\":null,\"id_rekening\":5,\"nama_tabungan\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-12 10:29:46','2018-07-12 10:29:53'),(527,3,5,'Kredit Tabungan [Tunai]','Sudah Dikonfirmasi','Kredit Tabungan','{\"kredit\":\"Tunai\",\"id_tabungan\":\"128\",\"id\":3,\"nama\":\"Ghulam\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"100000\",\"path_bukti\":null,\"id_rekening\":5,\"nama_tabungan\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-12 10:30:58','2018-07-12 10:31:08'),(529,3,7,'Buka Tabungan SIMPANAN TARBIYAH / PENDIDIKAN','Disetujui','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghulam\",\"id\":3,\"akad\":\"7\",\"tabungan\":\"7\",\"keterangan\":null,\"nama_rekening\":\"SIMPANAN TARBIYAH \\/ PENDIDIKAN\"}','2018-07-12 10:46:03','2018-07-12 10:46:20'),(530,3,100,'Pengajuan Pembiayaan PEMBIAYAAN MRB','Menunggu Konfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghulam\",\"id\":3,\"pembiayaan\":\"100\",\"jumlah\":\"10000000\",\"jenis_Usaha\":\"Pertanian\",\"usaha\":\"dagang\",\"keterangan\":\"1 Tahun\",\"jaminan\":\"ijazah\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/BECNg9QGO6HCITapH9iRoQLk8jdD3876LF4um9gN.jpeg\"}','2018-07-12 11:00:23','2018-07-12 11:00:23'),(534,1,169,'Buka Deposito MUDHARABAH 6 BULAN','Sudah Dikonfirmasi','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":null,\"jumlah\":\"1000000\",\"deposito\":\"169\",\"id_pencairan\":\"127\",\"nama_rekening\":\"MUDHARABAH 6 BULAN\"}','2018-07-12 13:57:59','2018-07-12 13:57:59'),(562,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"kepercayaan\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/qsKp5Mh8IV6xZYrgqAkGI1zz24eFyhIMrtqQweyZ.jpeg\"}','2018-07-12 20:28:23','2018-07-12 20:28:23'),(563,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"sda\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/HR5zqqmJe9y7wzM7cKBjFwWA61lDI6dQ8QL21uRg.jpeg\"}','2018-07-12 20:33:18','2018-07-12 20:33:18'),(564,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"sda\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/awrWdzHz9hzlKBCVDgeHroC5alIcmZJCt5iK61Wi.jpeg\"}','2018-07-12 20:33:42','2018-07-12 20:33:42'),(565,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"sda\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/VqnOy9EzbYkzEFiu95p7FhYJ6pg5Wq9qTdruJJQj.jpeg\"}','2018-07-12 20:34:35','2018-07-12 20:34:35'),(566,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"sda\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/ywxH3GAvuVEGJnzylclSPJb56cKjuciCd5kc0PlJ.jpeg\"}','2018-07-12 20:35:12','2018-07-12 20:35:12'),(567,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"sda\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/gG1FaYKONzNpa2GaAt9C9Tdx68zxE9bPl3VJeqn7.jpeg\"}','2018-07-12 20:35:22','2018-07-12 20:35:22'),(570,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/jtk8bkSbwvR6K1ZOkQYD7FjQrrSdBX8wkrEvF9yk.jpeg\"}','2018-07-12 20:39:03','2018-07-12 20:39:03'),(602,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghulam\",\"id\":3,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"10000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"safd\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/dnB6igVipdxLdmlkoRVfuD7nsT9W2Uvt9a7wS1al.jpeg\"}','2018-07-12 21:35:18','2018-07-12 21:35:18'),(603,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"10000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"sd\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/Qzdsk1xQbGCS9Ox8PM3mc7tB3HrSVsAixVYU6oXz.jpeg\"}','2018-07-12 21:36:47','2018-07-12 21:36:47'),(604,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"10000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"2\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/Z06NDRSrlNIWRQZPYixzn3rt0xg8FxZvd6fj5Nda.jpeg\"}','2018-07-12 21:39:10','2018-07-12 21:39:10'),(605,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"10000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"2\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/pkyAxVKG9fkpWiOwvrkM3lOzBDxzUAdiYzB35igr.jpeg\"}','2018-07-12 21:39:56','2018-07-12 21:39:56'),(606,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/3KkyD0vyagoYowcE64LhhCqciaYQIisdwqyIQm3A.jpeg\"}','2018-07-13 05:47:24','2018-07-13 05:47:24'),(607,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/N4Cwh71Ma0a6680AEBl6AWFpaQOYp7Q4cdyc1qq6.jpeg\"}','2018-07-13 05:48:28','2018-07-13 05:48:28'),(608,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/3zyNwyhW3EMi8CN3A7P37Wo7GgqYFLG2CYcsU8eQ.jpeg\"}','2018-07-13 05:49:34','2018-07-13 05:49:34'),(609,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/p5osN7r2ScFP7YXnIOx3wrRrW9iBhO8KoFv1omSu.jpeg\"}','2018-07-13 05:50:40','2018-07-13 05:50:40'),(610,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/znZmh3B27Zdrc9mosrLIHN0GGTMaXpSAPGY4hlqi.jpeg\"}','2018-07-13 05:50:58','2018-07-13 05:50:58'),(611,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/dKRf0kHcSNQMOvpsmybmTiWPHe2TuFReJSrJDnqu.jpeg\"}','2018-07-13 05:53:42','2018-07-13 05:53:42'),(612,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"2\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/2w4Dmw1mjoQAFlvh24YiIle3c9JeshNpuQ9kNKgV.jpeg\"}','2018-07-13 05:54:21','2018-07-13 05:54:21'),(613,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"2\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/TrTjt3MUeR3Ech1ShH2deQAV53HwCGhUaCehmp07.jpeg\"}','2018-07-13 05:54:41','2018-07-13 05:54:41'),(614,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"2\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/xUsh8bWAvxQJTGxuYRTv2DCH77tmFZVfQGDrDXBK.jpeg\"}','2018-07-13 05:55:12','2018-07-13 05:55:12'),(615,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/nVA7WCnPqJ8SIgoaS5D5ZxgNPZMqBZRedKP5IOAC.jpeg\"}','2018-07-13 05:57:24','2018-07-13 05:57:25'),(618,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"10000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/mUJ5MaNSiytmC0og6E3dOrdxUhADz5IoQMrKTOHm.jpeg\"}','2018-07-13 07:48:33','2018-07-13 07:48:33'),(620,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"2\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/xLk5m3ZlEN4uMHmIpyNHKeVuIrMuxqcNBVTWQSCd.jpeg\"}','2018-07-13 07:51:09','2018-07-13 07:51:09'),(696,14,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Disetujui','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Dadang\",\"id\":14,\"akad\":\"5\",\"tabungan\":\"5\",\"keterangan\":\"-\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-14 11:16:00','2018-07-14 11:16:48'),(697,14,5,'Kredit Tabungan [Tunai]','Sudah Dikonfirmasi','Kredit Tabungan','{\"kredit\":\"Tunai\",\"id_tabungan\":\"130\",\"id\":14,\"nama\":\"Dadang\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"1000000\",\"path_bukti\":null,\"id_rekening\":5,\"nama_tabungan\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-14 11:21:01','2018-07-14 11:21:30'),(698,14,169,'Buka Deposito MUDHARABAH 6 BULAN','Disetujui','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Dadang\",\"id\":14,\"jumlah\":\"5000000\",\"deposito\":\"169\",\"keterangan\":\"o\",\"id_pencairan\":\"130\",\"nama_rekening\":\"MUDHARABAH 6 BULAN\"}','2018-07-14 11:24:37','2018-07-14 11:26:53'),(700,1,169,'Buka Deposito MUDHARABAH 6 BULAN','Disetujui','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghulam\",\"id\":3,\"keterangan\":\"anggota\",\"jumlah\":\"10000000\",\"deposito\":\"169 25\",\"id_pencairan\":\"129\",\"nama_rekening\":\"MUDHARABAH 6 BULAN\"}','2018-07-15 05:55:15','2018-07-15 05:55:15'),(701,1,169,'Buka Deposito MUDHARABAH 6 BULAN','Disetujui','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghulam\",\"id\":3,\"keterangan\":\"anggota\",\"jumlah\":\"10000000\",\"deposito\":\"169 25\",\"id_pencairan\":\"129\",\"nama_rekening\":\"MUDHARABAH 6 BULAN\"}','2018-07-15 05:55:29','2018-07-15 05:55:29'),(703,2,49,'Buka Deposito MUDHARABAH  3 BULAN','Disetujui','Deposito','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"jumlah\":\"5000000\",\"deposito\":\"49\",\"keterangan\":null,\"id_pencairan\":\"125\",\"nama_rekening\":\"MUDHARABAH  3 BULAN\"}','2018-07-15 06:28:50','2018-07-15 06:30:26'),(708,15,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Disetujui','Tabungan Awal','{\"atasnama\":\"Pribadi\",\"nama\":\"Demsy\",\"id\":15,\"keterangan\":\"Tabungan Awal\",\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-15 12:33:26','2018-07-15 13:26:49'),(709,16,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Disetujui','Tabungan Awal','{\"atasnama\":\"Pribadi\",\"nama\":\"Iman\",\"id\":16,\"keterangan\":\"Tabungan Awal\",\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-15 13:34:30','2018-07-15 13:34:55'),(710,16,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Disetujui','Tabungan Awal','{\"atasnama\":\"Pribadi\",\"nama\":\"Iman\",\"id\":16,\"keterangan\":\"Tabungan Awal\",\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-17 09:24:17','2018-07-17 09:32:28'),(715,3,119,'Simpanan Wajib [Transfer]','Sudah Dikonfirmasi','Simpanan Wajib','{\"id\":\"3\",\"jenis\":\"1\",\"nama\":\"Ghulam\",\"bank\":\"86\",\"no_bank\":\"13\",\"daribank\":\"a\",\"atasnama\":\"da\",\"jumlah\":\"10000\",\"path_bukti\":\"transfer\\/x4o4cjyPxZuZfjOk224IjWB2ePvVSgY2Nzf28G7O.jpeg\"}','2018-07-17 14:12:19','2018-07-17 14:12:20'),(717,3,119,'Simpanan Wajib [Tunai]','Sudah Dikonfirmasi','Simpanan Wajib','{\"id\":\"3\",\"jenis\":\"0\",\"nama\":\"Ghulam\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"10000\",\"path_bukti\":null}','2018-07-17 14:13:43','2018-07-17 14:13:44'),(718,3,119,'Simpanan Wajib [Tunai]','Sudah Dikonfirmasi','Simpanan Wajib','{\"id\":\"3\",\"jenis\":\"0\",\"nama\":\"Ghulam\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"10000\",\"path_bukti\":null}','2018-07-17 14:15:32','2018-07-17 14:15:32'),(719,9,119,'Simpanan Wajib [Tunai]','Sudah Dikonfirmasi','Simpanan Wajib','{\"id\":\"9\",\"jenis\":\"0\",\"nama\":\"Ghulam Fajri\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"10000\",\"path_bukti\":null}','2018-07-17 14:20:02','2018-07-17 14:20:03'),(720,3,119,'Simpanan Wajib [Tunai]','Sudah Dikonfirmasi','Simpanan Wajib','{\"id\":\"3\",\"jenis\":\"0\",\"nama\":\"Ghulam\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"10000\",\"path_bukti\":null}','2018-07-17 14:31:35','2018-07-17 14:31:36'),(721,3,119,'Simpanan Wajib [Tunai]','Sudah Dikonfirmasi','Simpanan Wajib','{\"id\":\"3\",\"jenis\":\"0\",\"nama\":\"Ghulam\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"1\",\"path_bukti\":null}','2018-07-17 19:25:30','2018-07-17 19:25:31'),(722,3,119,'Simpanan Wajib [Tunai]','Sudah Dikonfirmasi','Simpanan Wajib','{\"id\":\"3\",\"jenis\":\"0\",\"nama\":\"Ghulam\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"9999\",\"path_bukti\":null}','2018-07-17 19:31:14','2018-07-17 19:31:15'),(723,15,5,'Buka Tabungan SIMPANAN MUDHAROBAH UMUM','Disetujui','Tabungan Awal','{\"atasnama\":\"Pribadi\",\"nama\":\"Demsy\",\"id\":15,\"keterangan\":\"Tabungan Awal\",\"akad\":\"5\",\"tabungan\":\"5\",\"nama_rekening\":\"SIMPANAN MUDHAROBAH UMUM\"}','2018-07-17 19:55:07','2018-07-17 19:55:28'),(724,2,99,'Pengajuan Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"keterangan\":\"2 Bulan\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/0w8s73r7qbIO9ZuG8c9SrvZQ87BZF4JatcV8Y5W2.jpeg\"}','2018-07-18 06:23:42','2018-07-18 06:25:24'),(725,2,99,'Pengajuan Pembiayaan PEMBIAYAAN MDA','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"keterangan\":\"2 Bulan\",\"jaminan\":\"s\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/jHvmxwVOFKCw1rDSW4vsom5rQVwzJVv02IUEduPY.jpeg\"}','2018-07-18 06:35:10','2018-07-18 06:36:18'),(726,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"ijazah\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/HeB5lrHJfks0o7m5YeYjIzCnrwr39flz7lL7qU55.jpeg\"}','2018-07-18 06:48:43','2018-07-18 06:48:43'),(737,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"sertifikat\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/pvED1fio6hmOqom9ZDbRNjeqcO52xlXrWm5rlNuN.jpeg\"}','2018-07-18 11:27:43','2018-07-18 11:27:43'),(751,1,99,'Buka Pembiayaan PEMBIAYAAN MDA','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"2\",\"jaminan\":\"1\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/stogK3wqhH2TmPL4JmCjxXBl8ifLZdW7YbMHOVHg.jpeg\"}','2018-07-19 10:55:31','2018-07-19 10:55:31'),(755,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.7\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":500000,\"bayar_mar\":200000,\"jumlah\":700000,\"nisbah\":\"250000\",\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-07-19 13:00:19','2018-07-19 21:55:47'),(756,2,100,'Angsuran Pembiayaan [Tunai]','Menunggu Konfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":500000,\"bayar_mar\":100000,\"jumlah\":600000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 13:25:40','2018-07-19 13:25:40'),(757,2,100,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":400000,\"sisa_mar\":500000,\"bayar_ang\":400000,\"bayar_mar\":50000,\"jumlah\":450000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:05:49','2018-07-19 22:50:11'),(758,2,100,'Angsuran Pembiayaan [Tunai]','Menunggu Konfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":500000,\"bayar_ang\":500000,\"bayar_mar\":100000,\"jumlah\":600000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:23:26','2018-07-19 22:23:26'),(760,2,100,'Angsuran Pembiayaan [Tunai]','Menunggu Konfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":0,\"sisa_mar\":500000,\"bayar_ang\":0,\"bayar_mar\":100000,\"jumlah\":100000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:25:09','2018-07-19 22:25:09'),(761,2,100,'Angsuran Pembiayaan [Tunai]','Menunggu Konfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":0,\"sisa_mar\":500000,\"bayar_ang\":0,\"bayar_mar\":50000,\"jumlah\":50000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:25:43','2018-07-19 22:25:43'),(762,2,100,'Angsuran Pembiayaan [Tunai]','Menunggu Konfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":500000,\"bayar_ang\":500000,\"bayar_mar\":0,\"jumlah\":500000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:25:56','2018-07-19 22:25:56'),(763,2,100,'Angsuran Pembiayaan [Tunai]','Menunggu Konfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":400000,\"sisa_mar\":500000,\"bayar_ang\":400000,\"bayar_mar\":0,\"jumlah\":400000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:26:11','2018-07-19 22:26:11'),(764,2,100,'Angsuran Pembiayaan [Tunai]','Menunggu Konfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":0,\"sisa_mar\":500000,\"bayar_ang\":0,\"bayar_mar\":100000,\"jumlah\":100000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:35:01','2018-07-19 22:35:01'),(765,2,100,'Angsuran Pembiayaan [Tunai]','Menunggu Konfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":0,\"bayar_mar\":100000,\"jumlah\":100000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:48:11','2018-07-19 22:48:11'),(766,2,100,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":100000,\"sisa_mar\":50000,\"bayar_ang\":0,\"bayar_mar\":50000,\"jumlah\":50000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:52:54','2018-07-19 22:53:03'),(767,2,100,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":100000,\"sisa_mar\":0,\"bayar_ang\":50000,\"bayar_mar\":0,\"jumlah\":50000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:53:52','2018-07-19 22:54:12'),(768,2,100,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":50000,\"sisa_mar\":0,\"bayar_ang\":50000,\"bayar_mar\":0,\"jumlah\":50000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:55:29','2018-07-19 22:58:05'),(769,2,100,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.6\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":500000,\"bayar_mar\":100000,\"jumlah\":600000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-19 22:59:17','2018-07-19 23:00:09'),(775,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.7\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":400000,\"bayar_mar\":0,\"jumlah\":400000,\"nisbah\":\"250000\",\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-07-19 23:03:04','2018-07-19 23:41:21'),(776,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.7\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":100000,\"sisa_mar\":250000,\"bayar_ang\":50000,\"bayar_mar\":400000,\"jumlah\":450000,\"nisbah\":null,\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-07-19 23:42:44','2018-07-19 23:43:39'),(777,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.7\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":50000,\"sisa_mar\":0,\"bayar_ang\":50000,\"bayar_mar\":0,\"jumlah\":50000,\"nisbah\":null,\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-07-19 23:44:34','2018-07-20 00:01:48'),(778,1,100,'Buka Pembiayaan PEMBIAYAAN MRB','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"100\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"2\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/5j0SU5qo0yYersudVNsa7IEgS8ZcAX2TUAlwCVr6.jpeg\"}','2018-07-20 00:04:18','2018-07-20 00:04:19'),(780,2,100,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.8\",\"id\":1,\"nama\":\"admin\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":500000,\"bayar_mar\":100000,\"jumlah\":600000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-20 00:19:04','2018-07-20 00:19:04'),(781,2,7,'Buka Tabungan SIMPANAN TARBIYAH / PENDIDIKAN','Disetujui','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"akad\":\"7\",\"tabungan\":\"7\",\"keterangan\":null,\"nama_rekening\":\"SIMPANAN TARBIYAH \\/ PENDIDIKAN\"}','2018-07-24 16:03:27','2018-07-24 16:24:36'),(782,2,100,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.8\",\"id\":1,\"nama\":\"admin\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":500000,\"bayar_mar\":100000,\"jumlah\":600000,\"nisbah\":null,\"jenis\":\"1\",\"path_bukti\":null,\"id_rekening\":100,\"nama_pembiayaan\":\"PEMBIAYAAN MRB\"}','2018-07-25 05:14:26','2018-07-25 05:14:26'),(783,2,99,'Buka Pembiayaan PEMBIAYAAN MDA','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"keterangan\":\"2 Bulan\",\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"dagang\",\"jaminan\":\"sertifikat\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/Kuuwp03iPtfxoruT5YKT4BhLMbEPniyITyKQjQ7p.jpeg\"}','2018-07-25 08:56:41','2018-07-25 08:56:41'),(784,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.9\",\"id\":1,\"nama\":\"admin\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":300000,\"bayar_mar\":200000,\"jumlah\":500000,\"nisbah\":\"300000\",\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-07-25 08:57:06','2018-07-25 08:57:06'),(785,9,9,'Buka Tabungan SIMPANAN IDUL ADHA','Sudah Dikonfirmasi','Tabungan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghulam Fajri\",\"id\":9,\"keterangan\":null,\"akad\":\"9\",\"tabungan\":\"9\",\"nama_rekening\":\"SIMPANAN IDUL ADHA\"}','2018-07-26 07:13:28','2018-07-26 07:13:29'),(786,9,9,'Buka Tabungan SIMPANAN IDUL ADHA','Disetujui','Tabungan Awal','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghulam Fajri\",\"id\":9,\"keterangan\":\"Tabungan Awal\",\"akad\":\"9\",\"tabungan\":\"9\",\"nama_rekening\":\"SIMPANAN IDUL ADHA\"}','2018-07-26 07:16:26','2018-07-26 07:18:46'),(787,10,7,'Buka Tabungan SIMPANAN TARBIYAH / PENDIDIKAN','Disetujui','Tabungan Awal','{\"atasnama\":\"Pribadi\",\"nama\":\"dadang\",\"id\":10,\"keterangan\":\"Tabungan Awal\",\"akad\":\"7\",\"tabungan\":\"7\",\"nama_rekening\":\"SIMPANAN TARBIYAH \\/ PENDIDIKAN\"}','2018-07-26 07:21:14','2018-07-26 07:21:57'),(788,10,119,'Simpanan Wajib [Tunai]','Sudah Dikonfirmasi','Simpanan Wajib','{\"id\":\"10\",\"jenis\":\"0\",\"nama\":\"dadang\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"10000\",\"path_bukti\":null}','2018-07-26 14:06:07','2018-07-26 14:06:07'),(791,2,179,'Donasi Maal [Transfer]','Sudah Dikonfirmasi','Donasi Maal','{\"donasi\":\"Transfer\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"dari\":\"86\",\"kegiatan\":\"1\",\"no_bank\":\"1\",\"daribank\":\"MANDIRI\",\"atasnama\":\"1\",\"jumlah\":\"1000000\",\"path_bukti\":\"9cPXV2oHX3RmfIYaWNwxycYUOjTOcu7wXqW9JORX.jpeg\"}','2018-08-05 16:59:13','2018-08-05 17:10:06'),(792,2,118,'Donasi Waqaf [Transfer]','Sudah Dikonfirmasi','Donasi Waqaf','{\"donasi\":\"Transfer\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"dari\":\"87\",\"kegiatan\":null,\"no_bank\":\"1\",\"daribank\":\"MANDIRI\",\"atasnama\":\"ghulam fajri\",\"jumlah\":\"5000000\",\"path_bukti\":\"ealUiTmYjspWJIow4yVIth3lKYBqcCQOZlZDyo2Q.jpeg\"}','2018-08-05 16:59:32','2018-08-05 17:09:24'),(793,2,99,'Pengajuan Pembiayaan PEMBIAYAAN MDA','Menunggu Konfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"99\",\"jumlah\":\"5000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"Bisnis\",\"keterangan\":\"3 Bulan\",\"jaminan\":\"Motor\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/gx7ZhoB5IegVMuGev9VXk6Eg7z1T6tVgYuWGzpJp.jpeg\"}','2018-08-05 18:27:59','2018-08-05 18:27:59'),(794,2,99,'Pengajuan Pembiayaan PEMBIAYAAN MDA','Sudah Dikonfirmasi','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"99\",\"jumlah\":\"1000000\",\"jenis_Usaha\":\"Dagang\",\"usaha\":\"Bisnis\",\"keterangan\":\"5 Bulan\",\"jaminan\":\"Motor\",\"nama_rekening\":\"PEMBIAYAAN MDA\",\"path_jaminan\":\"jaminan\\/F9r8wxRUmrzR9vHNuR0fOdZjwlXvluIzHcb9VkZe.jpeg\"}','2018-08-05 18:29:40','2018-08-06 16:01:58'),(795,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.9\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":200000,\"sisa_mar\":0,\"bayar_ang\":200,\"bayar_mar\":0,\"jumlah\":200,\"nisbah\":null,\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-08-05 20:34:01','2018-08-05 20:34:01'),(796,2,7,'Kredit Tabungan [Tunai]','Menunggu Konfirmasi','Kredit Tabungan','{\"kredit\":\"Tunai\",\"id_tabungan\":\"139\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank\":null,\"no_bank\":null,\"daribank\":null,\"atasnama\":null,\"jumlah\":\"100000\",\"path_bukti\":null,\"id_rekening\":7,\"nama_tabungan\":\"SIMPANAN TARBIYAH \\/ PENDIDIKAN\"}','2018-08-05 20:55:58','2018-08-05 20:55:58'),(797,2,100,'Pengajuan Pembiayaan PEMBIAYAAN MRB','Disetujui','Pembiayaan','{\"atasnama\":\"Pribadi\",\"nama\":\"Ghani Ramadhan\",\"id\":2,\"pembiayaan\":\"100\",\"jumlah\":\"10000000\",\"jenis_Usaha\":\"Industri\",\"usaha\":\"2\",\"keterangan\":\"2 Pekan\",\"jaminan\":\"Mobil\",\"nama_rekening\":\"PEMBIAYAAN MRB\",\"path_jaminan\":\"jaminan\\/apc2zRtDpxt52tt2dLxsLiIqHjH60IXqlrN80a93.jpeg\"}','2018-08-05 21:09:19','2018-08-06 16:56:30'),(798,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.9\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":199800,\"sisa_mar\":0,\"bayar_ang\":199800,\"bayar_mar\":0,\"jumlah\":199800,\"nisbah\":null,\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-08-05 21:09:47','2018-08-05 21:10:24'),(799,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.9\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"500000\",\"tipe_pembayaran\":null,\"sisa_ang\":500000,\"sisa_mar\":100000,\"bayar_ang\":500000,\"bayar_mar\":400000,\"jumlah\":900000,\"nisbah\":\"500000\",\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-08-14 14:04:31','2018-08-14 14:04:31'),(800,2,99,'Angsuran Pembiayaan [Tunai]','Sudah Dikonfirmasi','Angsuran Pembiayaan','{\"angsuran\":\"Tunai\",\"id_pembiayaan\":\"2.11\",\"id\":2,\"nama\":\"Ghani Ramadhan\",\"bank_user\":null,\"no_bank\":null,\"atasnama\":\"Ghani Ramadhan\",\"bank\":null,\"pokok\":\"200000\",\"tipe_pembayaran\":null,\"sisa_ang\":200000,\"sisa_mar\":100000,\"bayar_ang\":200000,\"bayar_mar\":2000000,\"jumlah\":2200000,\"nisbah\":\"200000\",\"jenis\":\"2\",\"path_bukti\":null,\"id_rekening\":99,\"nama_pembiayaan\":\"PEMBIAYAAN MDA\"}','2018-08-14 14:04:55','2018-08-14 14:04:55');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_bmt` */



DROP TABLE IF EXISTS `penyimpanan_bmt`;



CREATE TABLE `penyimpanan_bmt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_bmt` int(10) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_bmt_id_user_foreign` (`id_user`),
  KEY `penyimpanan_bmt_id_bmt_foreign` (`id_bmt`),
  CONSTRAINT `penyimpanan_bmt_id_bmt_foreign` FOREIGN KEY (`id_bmt`) REFERENCES `bmt` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyimpanan_bmt_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2057 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_bmt` */



LOCK TABLES `penyimpanan_bmt` WRITE;



insert  into `penyimpanan_bmt`(`id`,`id_user`,`id_bmt`,`status`,`transaksi`,`created_at`,`updated_at`) values (1003,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":448}','2018-07-02 16:32:22','2018-07-02 16:32:22'),(1004,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":448}','2018-07-02 16:32:22','2018-07-02 16:32:22'),(1005,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":449}','2018-07-02 16:32:42','2018-07-02 16:32:42'),(1006,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":449}','2018-07-02 16:32:42','2018-07-02 16:32:42'),(1007,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":450}','2018-07-02 16:34:41','2018-07-02 16:34:41'),(1008,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":450}','2018-07-02 16:34:41','2018-07-02 16:34:41'),(1009,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":451}','2018-07-02 16:35:07','2018-07-02 16:35:07'),(1010,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":451}','2018-07-02 16:35:07','2018-07-02 16:35:07'),(1011,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":452}','2018-07-02 16:35:18','2018-07-02 16:35:18'),(1012,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":452}','2018-07-02 16:35:18','2018-07-02 16:35:18'),(1013,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":453}','2018-07-02 16:36:18','2018-07-02 16:36:18'),(1014,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":453}','2018-07-02 16:36:18','2018-07-02 16:36:18'),(1015,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":454}','2018-07-02 16:40:28','2018-07-02 16:40:28'),(1016,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":454}','2018-07-02 16:40:28','2018-07-02 16:40:28'),(1017,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":455}','2018-07-02 16:51:38','2018-07-02 16:51:38'),(1018,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":455}','2018-07-02 16:51:38','2018-07-02 16:51:38'),(1019,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":456}','2018-07-02 16:52:12','2018-07-02 16:52:12'),(1020,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":456}','2018-07-02 16:52:12','2018-07-02 16:52:12'),(1021,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":457}','2018-07-02 16:53:50','2018-07-02 16:53:50'),(1022,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":457}','2018-07-02 16:53:50','2018-07-02 16:53:50'),(1023,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":458}','2018-07-02 16:54:21','2018-07-02 16:54:21'),(1024,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":458}','2018-07-02 16:54:21','2018-07-02 16:54:21'),(1025,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":459}','2018-07-02 16:54:37','2018-07-02 16:54:37'),(1026,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":459}','2018-07-02 16:54:37','2018-07-02 16:54:37'),(1027,2,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":460}','2018-07-02 16:55:22','2018-07-02 16:55:22'),(1028,2,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":460}','2018-07-02 16:55:22','2018-07-02 16:55:22'),(1029,2,274,'Kredit','{\"jumlah\":50000,\"id_pengajuan\":461}','2018-07-02 19:20:57','2018-07-02 19:20:57'),(1030,2,266,'Kredit','{\"jumlah\":50000,\"id_pengajuan\":461}','2018-07-02 19:20:57','2018-07-02 19:20:57'),(1031,2,274,'Debit','{\"jumlah\":-20000,\"id_pengajuan\":462}','2018-07-02 19:22:03','2018-07-02 19:22:03'),(1032,2,266,'Debit','{\"jumlah\":-20000,\"id_pengajuan\":462}','2018-07-02 19:22:03','2018-07-02 19:22:03'),(1033,2,274,'Deposit Awal','{\"jumlah\":300000,\"id_pengajuan\":463}','2018-07-02 19:24:06','2018-07-02 19:24:06'),(1034,2,280,'Deposit Awal','{\"jumlah\":300000,\"id_pengajuan\":463}','2018-07-02 19:24:06','2018-07-02 19:24:06'),(1035,2,274,'Pencairan Deposito','{\"jumlah\":-300000,\"id_pengajuan\":464}','2018-07-02 19:25:11','2018-07-02 19:25:11'),(1036,2,280,'Pencairan Deposito','{\"jumlah\":300000,\"id_pengajuan\":464}','2018-07-02 19:25:11','2018-07-02 19:25:11'),(1037,2,274,'Deposit Awal','{\"jumlah\":300000,\"id_pengajuan\":465}','2018-07-02 19:29:41','2018-07-02 19:29:41'),(1038,2,280,'Deposit Awal','{\"jumlah\":300000,\"id_pengajuan\":465}','2018-07-02 19:29:41','2018-07-02 19:29:41'),(1039,2,274,'Pencairan Deposito','{\"jumlah\":-300000,\"id_pengajuan\":466}','2018-07-02 19:33:26','2018-07-02 19:33:26'),(1040,2,280,'Pencairan Deposito','{\"jumlah\":-300000,\"id_pengajuan\":466}','2018-07-02 19:33:26','2018-07-02 19:33:26'),(1041,2,309,'Kredit','{\"jumlah\":500000,\"id_pengajuan\":467}','2018-07-02 19:52:48','2018-07-02 19:52:48'),(1042,2,266,'Kredit','{\"jumlah\":500000,\"id_pengajuan\":467}','2018-07-02 19:52:48','2018-07-02 19:52:48'),(1043,2,309,'Debit','{\"jumlah\":-50000,\"id_pengajuan\":468}','2018-07-02 19:54:27','2018-07-02 19:54:27'),(1044,2,266,'Debit','{\"jumlah\":-50000,\"id_pengajuan\":468}','2018-07-02 19:54:27','2018-07-02 19:54:27'),(1045,2,274,'Deposit Awal','{\"jumlah\":1000000,\"id_pengajuan\":469}','2018-07-02 20:26:48','2018-07-02 20:26:48'),(1046,2,392,'Deposit Awal','{\"jumlah\":1000000,\"id_pengajuan\":469}','2018-07-02 20:26:48','2018-07-02 20:26:48'),(1047,2,274,'Deposit Awal','{\"jumlah\":10000000,\"id_pengajuan\":470}','2018-07-02 20:27:20','2018-07-02 20:27:20'),(1048,2,392,'Deposit Awal','{\"jumlah\":10000000,\"id_pengajuan\":470}','2018-07-02 20:27:20','2018-07-02 20:27:20'),(1055,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":473}','2018-07-02 20:46:34','2018-07-02 20:46:34'),(1056,2,322,'Pencairan Pembiayaan','{\"jumlah\":1100000,\"id_pengajuan\":473}','2018-07-02 20:46:34','2018-07-02 20:46:34'),(1057,2,323,'Pencairan Pembiayaan','{\"jumlah\":-100000,\"id_pengajuan\":473}','2018-07-02 20:46:34','2018-07-02 20:46:34'),(1058,2,274,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":474}','2018-07-02 20:47:44','2018-07-02 20:47:44'),(1059,2,322,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":474}','2018-07-02 20:47:44','2018-07-02 20:47:44'),(1060,2,274,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":475}','2018-07-03 05:56:23','2018-07-03 05:56:23'),(1061,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":475}','2018-07-03 05:56:24','2018-07-03 05:56:24'),(1062,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":475}','2018-07-03 05:56:24','2018-07-03 05:56:24'),(1063,2,323,'Angsuran','{\"jumlah\":-100000,\"id_pengajuan\":475}','2018-07-03 05:56:24','2018-07-03 05:56:24'),(1064,2,322,'Angsuran','{\"jumlah\":1100000,\"id_pengajuan\":475}','2018-07-03 05:56:24','2018-07-03 05:56:24'),(1065,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":476}','2018-07-03 06:01:35','2018-07-03 06:01:35'),(1066,2,322,'Pencairan Pembiayaan','{\"jumlah\":1100000,\"id_pengajuan\":476}','2018-07-03 06:01:35','2018-07-03 06:01:35'),(1067,2,323,'Pencairan Pembiayaan','{\"jumlah\":-100000,\"id_pengajuan\":476}','2018-07-03 06:01:35','2018-07-03 06:01:35'),(1068,2,274,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":480}','2018-07-03 06:54:30','2018-07-03 06:54:30'),(1069,2,322,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":480}','2018-07-03 06:54:30','2018-07-03 06:54:30'),(1070,2,308,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":481}','2018-07-03 06:58:52','2018-07-03 06:58:52'),(1071,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":481}','2018-07-03 06:58:52','2018-07-03 06:58:52'),(1072,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":481}','2018-07-03 06:58:52','2018-07-03 06:58:52'),(1073,2,323,'Angsuran','{\"jumlah\":-100000,\"id_pengajuan\":481}','2018-07-03 06:58:52','2018-07-03 06:58:52'),(1074,2,322,'Angsuran','{\"jumlah\":1100000,\"id_pengajuan\":481}','2018-07-03 06:58:52','2018-07-03 06:58:52'),(1075,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":482}','2018-07-03 07:19:35','2018-07-03 07:19:35'),(1076,2,322,'Pencairan Pembiayaan','{\"jumlah\":1100000,\"id_pengajuan\":482}','2018-07-03 07:19:35','2018-07-03 07:19:35'),(1077,2,323,'Pencairan Pembiayaan','{\"jumlah\":-100000,\"id_pengajuan\":482}','2018-07-03 07:19:35','2018-07-03 07:19:35'),(1078,2,274,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":483}','2018-07-03 07:21:29','2018-07-03 07:21:29'),(1079,2,322,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":483}','2018-07-03 07:21:29','2018-07-03 07:21:29'),(1080,2,274,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":484}','2018-07-03 07:39:12','2018-07-03 07:39:12'),(1081,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":484}','2018-07-03 07:39:12','2018-07-03 07:39:12'),(1082,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":484}','2018-07-03 07:39:12','2018-07-03 07:39:12'),(1083,2,323,'Angsuran','{\"jumlah\":-100000,\"id_pengajuan\":484}','2018-07-03 07:39:12','2018-07-03 07:39:12'),(1084,2,322,'Angsuran','{\"jumlah\":1100000,\"id_pengajuan\":484}','2018-07-03 07:39:12','2018-07-03 07:39:12'),(1085,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":485}','2018-07-03 07:53:42','2018-07-03 07:53:42'),(1086,2,322,'Pencairan Pembiayaan','{\"jumlah\":1100000,\"id_pengajuan\":485}','2018-07-03 07:53:42','2018-07-03 07:53:42'),(1087,2,323,'Pencairan Pembiayaan','{\"jumlah\":-100000,\"id_pengajuan\":485}','2018-07-03 07:53:42','2018-07-03 07:53:42'),(1088,2,274,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":486}','2018-07-03 07:59:48','2018-07-03 07:59:48'),(1089,2,322,'Angsuran','{\"jumlah\":-1000000,\"id_pengajuan\":486}','2018-07-03 07:59:48','2018-07-03 07:59:48'),(1090,2,274,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":487}','2018-07-03 08:26:00','2018-07-03 08:26:00'),(1091,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":487}','2018-07-03 08:26:00','2018-07-03 08:26:00'),(1092,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":487}','2018-07-03 08:26:00','2018-07-03 08:26:00'),(1093,2,323,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":487}','2018-07-03 08:26:00','2018-07-03 08:26:00'),(1094,2,322,'Angsuran','{\"jumlah\":-100000,\"id_pengajuan\":487}','2018-07-03 08:26:00','2018-07-03 08:26:00'),(1095,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":488}','2018-07-03 08:27:37','2018-07-03 08:27:37'),(1096,2,322,'Pencairan Pembiayaan','{\"jumlah\":1200000,\"id_pengajuan\":488}','2018-07-03 08:27:37','2018-07-03 08:27:37'),(1097,2,323,'Pencairan Pembiayaan','{\"jumlah\":-200000,\"id_pengajuan\":488}','2018-07-03 08:27:37','2018-07-03 08:27:37'),(1098,2,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":489}','2018-07-03 08:28:25','2018-07-03 08:28:25'),(1099,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":489}','2018-07-03 08:28:26','2018-07-03 08:28:26'),(1100,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":489}','2018-07-03 08:28:26','2018-07-03 08:28:26'),(1101,2,323,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":489}','2018-07-03 08:28:26','2018-07-03 08:28:26'),(1102,2,322,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":489}','2018-07-03 08:28:26','2018-07-03 08:28:26'),(1103,2,274,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":490}','2018-07-03 08:33:01','2018-07-03 08:33:01'),(1104,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":490}','2018-07-03 08:33:01','2018-07-03 08:33:01'),(1105,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":490}','2018-07-03 08:33:01','2018-07-03 08:33:01'),(1106,2,323,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":490}','2018-07-03 08:33:01','2018-07-03 08:33:01'),(1107,2,322,'Angsuran','{\"jumlah\":-100000,\"id_pengajuan\":490}','2018-07-03 08:33:01','2018-07-03 08:33:01'),(1108,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":491}','2018-07-03 08:36:53','2018-07-03 08:36:53'),(1109,2,322,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":491}','2018-07-03 08:36:53','2018-07-03 08:36:53'),(1110,2,274,'Pencairan Pembiayaan','{\"jumlah\":-5000000,\"id_pengajuan\":492}','2018-07-03 08:45:01','2018-07-03 08:45:01'),(1111,2,322,'Pencairan Pembiayaan','{\"jumlah\":7500000,\"id_pengajuan\":492}','2018-07-03 08:45:01','2018-07-03 08:45:01'),(1112,2,323,'Pencairan Pembiayaan','{\"jumlah\":-2500000,\"id_pengajuan\":492}','2018-07-03 08:45:01','2018-07-03 08:45:01'),(1113,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":493}','2018-07-03 08:50:28','2018-07-03 08:50:28'),(1114,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":493}','2018-07-03 08:50:28','2018-07-03 08:50:28'),(1115,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":493}','2018-07-03 08:50:28','2018-07-03 08:50:28'),(1116,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":493}','2018-07-03 08:50:28','2018-07-03 08:50:28'),(1117,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":493}','2018-07-03 08:50:28','2018-07-03 08:50:28'),(1118,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":494}','2018-07-03 08:50:32','2018-07-03 08:50:32'),(1119,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":494}','2018-07-03 08:50:32','2018-07-03 08:50:32'),(1120,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":494}','2018-07-03 08:50:32','2018-07-03 08:50:32'),(1121,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":494}','2018-07-03 08:50:32','2018-07-03 08:50:32'),(1122,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":494}','2018-07-03 08:50:32','2018-07-03 08:50:32'),(1123,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":497}','2018-07-03 08:52:52','2018-07-03 08:52:52'),(1124,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":497}','2018-07-03 08:52:52','2018-07-03 08:52:52'),(1125,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":497}','2018-07-03 08:52:52','2018-07-03 08:52:52'),(1126,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":497}','2018-07-03 08:52:52','2018-07-03 08:52:52'),(1127,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":497}','2018-07-03 08:52:52','2018-07-03 08:52:52'),(1128,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":496}','2018-07-03 08:53:24','2018-07-03 08:53:24'),(1129,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":496}','2018-07-03 08:53:24','2018-07-03 08:53:24'),(1130,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":496}','2018-07-03 08:53:24','2018-07-03 08:53:24'),(1131,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":496}','2018-07-03 08:53:24','2018-07-03 08:53:24'),(1132,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":496}','2018-07-03 08:53:24','2018-07-03 08:53:24'),(1133,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":495}','2018-07-03 08:54:01','2018-07-03 08:54:01'),(1134,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":495}','2018-07-03 08:54:01','2018-07-03 08:54:01'),(1135,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":495}','2018-07-03 08:54:01','2018-07-03 08:54:01'),(1136,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":495}','2018-07-03 08:54:01','2018-07-03 08:54:01'),(1137,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":495}','2018-07-03 08:54:01','2018-07-03 08:54:01'),(1138,2,274,'Pencairan Pembiayaan','{\"jumlah\":-5000000,\"id_pengajuan\":498}','2018-07-03 08:59:29','2018-07-03 08:59:29'),(1139,2,322,'Pencairan Pembiayaan','{\"jumlah\":7500000,\"id_pengajuan\":498}','2018-07-03 08:59:29','2018-07-03 08:59:29'),(1140,2,323,'Pencairan Pembiayaan','{\"jumlah\":-2500000,\"id_pengajuan\":498}','2018-07-03 08:59:29','2018-07-03 08:59:29'),(1141,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":499}','2018-07-03 09:02:15','2018-07-03 09:02:15'),(1142,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":499}','2018-07-03 09:02:15','2018-07-03 09:02:15'),(1143,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":499}','2018-07-03 09:02:15','2018-07-03 09:02:15'),(1144,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":499}','2018-07-03 09:02:15','2018-07-03 09:02:15'),(1145,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":499}','2018-07-03 09:02:15','2018-07-03 09:02:15'),(1146,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":503}','2018-07-03 09:20:22','2018-07-03 09:20:22'),(1147,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":503}','2018-07-03 09:20:22','2018-07-03 09:20:22'),(1148,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":503}','2018-07-03 09:20:22','2018-07-03 09:20:22'),(1149,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":503}','2018-07-03 09:20:22','2018-07-03 09:20:22'),(1150,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":503}','2018-07-03 09:20:22','2018-07-03 09:20:22'),(1151,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":502}','2018-07-03 09:20:45','2018-07-03 09:20:45'),(1152,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":502}','2018-07-03 09:20:45','2018-07-03 09:20:45'),(1153,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":502}','2018-07-03 09:20:45','2018-07-03 09:20:45'),(1154,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":502}','2018-07-03 09:20:45','2018-07-03 09:20:45'),(1155,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":502}','2018-07-03 09:20:45','2018-07-03 09:20:45'),(1156,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":501}','2018-07-03 09:20:52','2018-07-03 09:20:52'),(1157,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":501}','2018-07-03 09:20:52','2018-07-03 09:20:52'),(1158,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":501}','2018-07-03 09:20:52','2018-07-03 09:20:52'),(1159,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":501}','2018-07-03 09:20:52','2018-07-03 09:20:52'),(1160,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":501}','2018-07-03 09:20:52','2018-07-03 09:20:52'),(1161,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":500}','2018-07-03 09:22:34','2018-07-03 09:22:34'),(1162,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":500}','2018-07-03 09:22:35','2018-07-03 09:22:35'),(1163,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":500}','2018-07-03 09:22:35','2018-07-03 09:22:35'),(1164,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":500}','2018-07-03 09:22:35','2018-07-03 09:22:35'),(1165,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":500}','2018-07-03 09:22:35','2018-07-03 09:22:35'),(1166,2,274,'Pencairan Pembiayaan','{\"jumlah\":-5000000,\"id_pengajuan\":504}','2018-07-03 09:32:04','2018-07-03 09:32:04'),(1167,2,322,'Pencairan Pembiayaan','{\"jumlah\":7500000,\"id_pengajuan\":504}','2018-07-03 09:32:04','2018-07-03 09:32:04'),(1168,2,323,'Pencairan Pembiayaan','{\"jumlah\":-2500000,\"id_pengajuan\":504}','2018-07-03 09:32:04','2018-07-03 09:32:04'),(1169,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":510}','2018-07-03 09:33:18','2018-07-03 09:33:18'),(1170,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":510}','2018-07-03 09:33:18','2018-07-03 09:33:18'),(1171,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":510}','2018-07-03 09:33:18','2018-07-03 09:33:18'),(1172,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":510}','2018-07-03 09:33:18','2018-07-03 09:33:18'),(1173,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":510}','2018-07-03 09:33:18','2018-07-03 09:33:18'),(1174,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":509}','2018-07-03 09:33:50','2018-07-03 09:33:50'),(1175,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":509}','2018-07-03 09:33:50','2018-07-03 09:33:50'),(1176,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":509}','2018-07-03 09:33:50','2018-07-03 09:33:50'),(1177,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":509}','2018-07-03 09:33:50','2018-07-03 09:33:50'),(1178,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":509}','2018-07-03 09:33:50','2018-07-03 09:33:50'),(1179,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":508}','2018-07-03 09:34:24','2018-07-03 09:34:24'),(1180,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":508}','2018-07-03 09:34:24','2018-07-03 09:34:24'),(1181,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":508}','2018-07-03 09:34:24','2018-07-03 09:34:24'),(1182,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":508}','2018-07-03 09:34:24','2018-07-03 09:34:24'),(1183,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":508}','2018-07-03 09:34:24','2018-07-03 09:34:24'),(1184,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":507}','2018-07-03 09:37:44','2018-07-03 09:37:44'),(1185,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":507}','2018-07-03 09:37:44','2018-07-03 09:37:44'),(1186,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":507}','2018-07-03 09:37:44','2018-07-03 09:37:44'),(1187,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":507}','2018-07-03 09:37:44','2018-07-03 09:37:44'),(1188,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":507}','2018-07-03 09:37:44','2018-07-03 09:37:44'),(1189,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":506}','2018-07-03 09:37:53','2018-07-03 09:37:53'),(1190,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":506}','2018-07-03 09:37:53','2018-07-03 09:37:53'),(1191,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":506}','2018-07-03 09:37:53','2018-07-03 09:37:53'),(1192,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":506}','2018-07-03 09:37:53','2018-07-03 09:37:53'),(1193,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":506}','2018-07-03 09:37:53','2018-07-03 09:37:53'),(1199,2,274,'Pencairan Pembiayaan','{\"jumlah\":-5000000,\"id_pengajuan\":511}','2018-07-03 10:35:15','2018-07-03 10:35:15'),(1200,2,322,'Pencairan Pembiayaan','{\"jumlah\":7500000,\"id_pengajuan\":511}','2018-07-03 10:35:15','2018-07-03 10:35:15'),(1201,2,323,'Pencairan Pembiayaan','{\"jumlah\":-2500000,\"id_pengajuan\":511}','2018-07-03 10:35:15','2018-07-03 10:35:15'),(1202,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":512}','2018-07-03 10:36:00','2018-07-03 10:36:00'),(1203,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":512}','2018-07-03 10:36:00','2018-07-03 10:36:00'),(1204,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":512}','2018-07-03 10:36:00','2018-07-03 10:36:00'),(1205,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":512}','2018-07-03 10:36:00','2018-07-03 10:36:00'),(1206,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":512}','2018-07-03 10:36:00','2018-07-03 10:36:00'),(1207,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":518}','2018-07-04 07:18:39','2018-07-04 07:18:39'),(1208,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":518}','2018-07-04 07:18:39','2018-07-04 07:18:39'),(1209,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":518}','2018-07-04 07:18:39','2018-07-04 07:18:39'),(1210,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":518}','2018-07-04 07:18:39','2018-07-04 07:18:39'),(1211,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":518}','2018-07-04 07:18:39','2018-07-04 07:18:39'),(1212,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":517}','2018-07-04 07:20:33','2018-07-04 07:20:33'),(1213,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":517}','2018-07-04 07:20:33','2018-07-04 07:20:33'),(1214,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":517}','2018-07-04 07:20:33','2018-07-04 07:20:33'),(1215,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":517}','2018-07-04 07:20:33','2018-07-04 07:20:33'),(1216,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":517}','2018-07-04 07:20:33','2018-07-04 07:20:33'),(1217,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":516}','2018-07-04 07:22:05','2018-07-04 07:22:05'),(1218,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":516}','2018-07-04 07:22:05','2018-07-04 07:22:05'),(1219,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":516}','2018-07-04 07:22:05','2018-07-04 07:22:05'),(1220,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":516}','2018-07-04 07:22:05','2018-07-04 07:22:05'),(1221,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":516}','2018-07-04 07:22:05','2018-07-04 07:22:05'),(1222,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":515}','2018-07-04 07:22:31','2018-07-04 07:22:31'),(1223,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":515}','2018-07-04 07:22:31','2018-07-04 07:22:31'),(1224,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":515}','2018-07-04 07:22:31','2018-07-04 07:22:31'),(1225,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":515}','2018-07-04 07:22:31','2018-07-04 07:22:31'),(1226,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":515}','2018-07-04 07:22:31','2018-07-04 07:22:31'),(1227,2,274,'Pencairan Pembiayaan','{\"jumlah\":-5000000,\"id_pengajuan\":519}','2018-07-04 07:26:30','2018-07-04 07:26:30'),(1228,2,322,'Pencairan Pembiayaan','{\"jumlah\":7500000,\"id_pengajuan\":519}','2018-07-04 07:26:30','2018-07-04 07:26:30'),(1229,2,323,'Pencairan Pembiayaan','{\"jumlah\":-2500000,\"id_pengajuan\":519}','2018-07-04 07:26:30','2018-07-04 07:26:30'),(1230,2,274,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":520}','2018-07-04 07:26:46','2018-07-04 07:26:46'),(1231,2,322,'Angsuran','{\"jumlah\":-1000000,\"id_pengajuan\":520}','2018-07-04 07:26:46','2018-07-04 07:26:46'),(1232,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":521}','2018-07-04 07:27:29','2018-07-04 07:27:29'),(1233,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":521}','2018-07-04 07:27:29','2018-07-04 07:27:29'),(1234,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":521}','2018-07-04 07:27:29','2018-07-04 07:27:29'),(1235,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":521}','2018-07-04 07:27:29','2018-07-04 07:27:29'),(1236,2,322,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":521}','2018-07-04 07:27:29','2018-07-04 07:27:29'),(1237,2,274,'Angsuran','{\"jumlah\":1500000,\"id_pengajuan\":522}','2018-07-04 07:28:42','2018-07-04 07:28:42'),(1238,2,351,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":522}','2018-07-04 07:28:42','2018-07-04 07:28:42'),(1239,2,344,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":522}','2018-07-04 07:28:42','2018-07-04 07:28:42'),(1240,2,323,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":522}','2018-07-04 07:28:42','2018-07-04 07:28:42'),(1241,2,322,'Angsuran','{\"jumlah\":-1500000,\"id_pengajuan\":522}','2018-07-04 07:28:42','2018-07-04 07:28:42'),(1242,2,274,'Pencairan Pembiayaan','{\"jumlah\":-10000000,\"id_pengajuan\":523}','2018-07-11 13:29:52','2018-07-11 13:29:52'),(1243,2,322,'Pencairan Pembiayaan','{\"jumlah\":34000000,\"id_pengajuan\":523}','2018-07-11 13:29:52','2018-07-11 13:29:52'),(1244,2,323,'Pencairan Pembiayaan','{\"jumlah\":-24000000,\"id_pengajuan\":523}','2018-07-11 13:29:52','2018-07-11 13:29:52'),(1245,2,274,'Kredit','{\"jumlah\":5000000,\"id_pengajuan\":\"524\"}','2018-07-12 10:28:14','2018-07-12 10:28:14'),(1246,2,266,'Kredit','{\"jumlah\":5000000,\"id_pengajuan\":\"524\"}','2018-07-12 10:28:14','2018-07-12 10:28:14'),(1247,3,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":525}','2018-07-12 10:29:33','2018-07-12 10:29:33'),(1248,3,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":525}','2018-07-12 10:29:33','2018-07-12 10:29:33'),(1249,3,274,'Kredit','{\"jumlah\":1000000,\"id_pengajuan\":\"526\"}','2018-07-12 10:29:53','2018-07-12 10:29:53'),(1250,3,266,'Kredit','{\"jumlah\":1000000,\"id_pengajuan\":\"526\"}','2018-07-12 10:29:53','2018-07-12 10:29:53'),(1251,3,274,'Kredit','{\"jumlah\":100000,\"id_pengajuan\":\"527\"}','2018-07-12 10:31:08','2018-07-12 10:31:08'),(1252,3,266,'Kredit','{\"jumlah\":100000,\"id_pengajuan\":\"527\"}','2018-07-12 10:31:08','2018-07-12 10:31:08'),(1253,3,274,'Setoran Awal','{\"jumlah\":25000,\"id_pengajuan\":529}','2018-07-12 10:46:20','2018-07-12 10:46:20'),(1254,3,268,'Setoran Awal','{\"jumlah\":25000,\"id_pengajuan\":529}','2018-07-12 10:46:20','2018-07-12 10:46:20'),(1255,2,274,'Deposit Awal','{\"jumlah\":1000000,\"id_pengajuan\":532}','2018-07-12 13:44:54','2018-07-12 13:44:54'),(1256,2,281,'Deposit Awal','{\"jumlah\":1000000,\"id_pengajuan\":532}','2018-07-12 13:44:54','2018-07-12 13:44:54'),(1257,2,274,'Deposit Awal','{\"jumlah\":1000000,\"id_pengajuan\":534}','2018-07-12 13:57:59','2018-07-12 13:57:59'),(1258,2,390,'Deposit Awal','{\"jumlah\":1000000,\"id_pengajuan\":534}','2018-07-12 13:57:59','2018-07-12 13:57:59'),(1259,2,274,'Deposit Awal','{\"jumlah\":1000000,\"id_pengajuan\":535}','2018-07-12 14:14:07','2018-07-12 14:14:07'),(1260,2,390,'Deposit Awal','{\"jumlah\":1000000,\"id_pengajuan\":535}','2018-07-12 14:14:07','2018-07-12 14:14:07'),(1261,2,274,'Angsuran','{\"jumlah\":416666.66666670004,\"id_pengajuan\":536}','2018-07-12 20:04:56','2018-07-12 20:04:56'),(1262,2,322,'Angsuran','{\"jumlah\":-416666.66666670004,\"id_pengajuan\":536}','2018-07-12 20:04:56','2018-07-12 20:04:56'),(1263,2,274,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":537}','2018-07-12 20:07:30','2018-07-12 20:07:30'),(1264,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":537}','2018-07-12 20:07:30','2018-07-12 20:07:30'),(1265,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":537}','2018-07-12 20:07:30','2018-07-12 20:07:30'),(1266,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":537}','2018-07-12 20:07:30','2018-07-12 20:07:30'),(1267,2,322,'Angsuran','{\"jumlah\":-1000000,\"id_pengajuan\":537}','2018-07-12 20:07:30','2018-07-12 20:07:30'),(1268,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":538}','2018-07-12 20:10:42','2018-07-12 20:10:42'),(1269,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":538}','2018-07-12 20:10:42','2018-07-12 20:10:42'),(1270,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":538}','2018-07-12 20:10:42','2018-07-12 20:10:42'),(1271,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":538}','2018-07-12 20:10:42','2018-07-12 20:10:42'),(1272,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":538}','2018-07-12 20:10:42','2018-07-12 20:10:42'),(1273,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":539}','2018-07-12 20:11:32','2018-07-12 20:11:32'),(1274,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":539}','2018-07-12 20:11:32','2018-07-12 20:11:32'),(1275,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":539}','2018-07-12 20:11:32','2018-07-12 20:11:32'),(1276,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":539}','2018-07-12 20:11:32','2018-07-12 20:11:32'),(1277,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":539}','2018-07-12 20:11:32','2018-07-12 20:11:32'),(1278,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":540}','2018-07-12 20:11:41','2018-07-12 20:11:41'),(1279,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":540}','2018-07-12 20:11:41','2018-07-12 20:11:41'),(1280,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":540}','2018-07-12 20:11:41','2018-07-12 20:11:41'),(1281,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":540}','2018-07-12 20:11:41','2018-07-12 20:11:41'),(1282,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":540}','2018-07-12 20:11:41','2018-07-12 20:11:41'),(1283,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":541}','2018-07-12 20:11:50','2018-07-12 20:11:50'),(1284,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":541}','2018-07-12 20:11:50','2018-07-12 20:11:50'),(1285,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":541}','2018-07-12 20:11:50','2018-07-12 20:11:50'),(1286,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":541}','2018-07-12 20:11:50','2018-07-12 20:11:50'),(1287,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":541}','2018-07-12 20:11:50','2018-07-12 20:11:50'),(1288,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":542}','2018-07-12 20:12:13','2018-07-12 20:12:13'),(1289,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":542}','2018-07-12 20:12:13','2018-07-12 20:12:13'),(1290,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":542}','2018-07-12 20:12:13','2018-07-12 20:12:13'),(1291,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":542}','2018-07-12 20:12:13','2018-07-12 20:12:13'),(1292,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":542}','2018-07-12 20:12:13','2018-07-12 20:12:13'),(1293,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":543}','2018-07-12 20:12:22','2018-07-12 20:12:22'),(1294,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":543}','2018-07-12 20:12:22','2018-07-12 20:12:22'),(1295,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":543}','2018-07-12 20:12:22','2018-07-12 20:12:22'),(1296,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":543}','2018-07-12 20:12:22','2018-07-12 20:12:22'),(1297,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":543}','2018-07-12 20:12:22','2018-07-12 20:12:22'),(1298,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":544}','2018-07-12 20:12:31','2018-07-12 20:12:31'),(1299,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":544}','2018-07-12 20:12:31','2018-07-12 20:12:31'),(1300,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":544}','2018-07-12 20:12:31','2018-07-12 20:12:31'),(1301,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":544}','2018-07-12 20:12:31','2018-07-12 20:12:31'),(1302,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":544}','2018-07-12 20:12:31','2018-07-12 20:12:31'),(1303,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":545}','2018-07-12 20:12:50','2018-07-12 20:12:50'),(1304,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":545}','2018-07-12 20:12:50','2018-07-12 20:12:50'),(1305,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":545}','2018-07-12 20:12:50','2018-07-12 20:12:50'),(1306,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":545}','2018-07-12 20:12:50','2018-07-12 20:12:50'),(1307,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":545}','2018-07-12 20:12:50','2018-07-12 20:12:50'),(1308,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":546}','2018-07-12 20:12:58','2018-07-12 20:12:58'),(1309,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":546}','2018-07-12 20:12:58','2018-07-12 20:12:58'),(1310,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":546}','2018-07-12 20:12:58','2018-07-12 20:12:58'),(1311,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":546}','2018-07-12 20:12:58','2018-07-12 20:12:58'),(1312,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":546}','2018-07-12 20:12:58','2018-07-12 20:12:58'),(1313,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":547}','2018-07-12 20:13:09','2018-07-12 20:13:09'),(1314,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":547}','2018-07-12 20:13:09','2018-07-12 20:13:09'),(1315,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":547}','2018-07-12 20:13:09','2018-07-12 20:13:09'),(1316,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":547}','2018-07-12 20:13:09','2018-07-12 20:13:09'),(1317,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":547}','2018-07-12 20:13:09','2018-07-12 20:13:09'),(1318,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":548}','2018-07-12 20:13:17','2018-07-12 20:13:17'),(1319,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":548}','2018-07-12 20:13:17','2018-07-12 20:13:17'),(1320,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":548}','2018-07-12 20:13:17','2018-07-12 20:13:17'),(1321,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":548}','2018-07-12 20:13:17','2018-07-12 20:13:17'),(1322,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":548}','2018-07-12 20:13:17','2018-07-12 20:13:17'),(1323,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":549}','2018-07-12 20:13:24','2018-07-12 20:13:24'),(1324,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":549}','2018-07-12 20:13:24','2018-07-12 20:13:24'),(1325,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":549}','2018-07-12 20:13:24','2018-07-12 20:13:24'),(1326,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":549}','2018-07-12 20:13:24','2018-07-12 20:13:24'),(1327,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":549}','2018-07-12 20:13:24','2018-07-12 20:13:24'),(1328,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":550}','2018-07-12 20:13:36','2018-07-12 20:13:36'),(1329,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":550}','2018-07-12 20:13:36','2018-07-12 20:13:36'),(1330,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":550}','2018-07-12 20:13:36','2018-07-12 20:13:36'),(1331,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":550}','2018-07-12 20:13:36','2018-07-12 20:13:36'),(1332,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":550}','2018-07-12 20:13:36','2018-07-12 20:13:36'),(1333,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":551}','2018-07-12 20:13:47','2018-07-12 20:13:47'),(1334,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":551}','2018-07-12 20:13:47','2018-07-12 20:13:47'),(1335,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":551}','2018-07-12 20:13:47','2018-07-12 20:13:47'),(1336,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":551}','2018-07-12 20:13:47','2018-07-12 20:13:47'),(1337,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":551}','2018-07-12 20:13:47','2018-07-12 20:13:47'),(1338,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":552}','2018-07-12 20:14:14','2018-07-12 20:14:14'),(1339,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":552}','2018-07-12 20:14:14','2018-07-12 20:14:14'),(1340,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":552}','2018-07-12 20:14:14','2018-07-12 20:14:14'),(1341,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":552}','2018-07-12 20:14:14','2018-07-12 20:14:14'),(1342,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":552}','2018-07-12 20:14:14','2018-07-12 20:14:14'),(1343,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":553}','2018-07-12 20:14:22','2018-07-12 20:14:22'),(1344,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":553}','2018-07-12 20:14:22','2018-07-12 20:14:22'),(1345,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":553}','2018-07-12 20:14:22','2018-07-12 20:14:22'),(1346,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":553}','2018-07-12 20:14:22','2018-07-12 20:14:22'),(1347,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":553}','2018-07-12 20:14:22','2018-07-12 20:14:22'),(1348,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":554}','2018-07-12 20:14:34','2018-07-12 20:14:34'),(1349,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":554}','2018-07-12 20:14:34','2018-07-12 20:14:34'),(1350,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":554}','2018-07-12 20:14:34','2018-07-12 20:14:34'),(1351,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":554}','2018-07-12 20:14:34','2018-07-12 20:14:34'),(1352,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":554}','2018-07-12 20:14:34','2018-07-12 20:14:34'),(1353,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":555}','2018-07-12 20:14:42','2018-07-12 20:14:42'),(1354,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":555}','2018-07-12 20:14:43','2018-07-12 20:14:43'),(1355,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":555}','2018-07-12 20:14:43','2018-07-12 20:14:43'),(1356,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":555}','2018-07-12 20:14:43','2018-07-12 20:14:43'),(1357,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":555}','2018-07-12 20:14:43','2018-07-12 20:14:43'),(1358,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":556}','2018-07-12 20:15:05','2018-07-12 20:15:05'),(1359,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":556}','2018-07-12 20:15:05','2018-07-12 20:15:05'),(1360,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":556}','2018-07-12 20:15:05','2018-07-12 20:15:05'),(1361,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":556}','2018-07-12 20:15:05','2018-07-12 20:15:05'),(1362,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":556}','2018-07-12 20:15:05','2018-07-12 20:15:05'),(1363,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":557}','2018-07-12 20:15:19','2018-07-12 20:15:19'),(1364,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":557}','2018-07-12 20:15:19','2018-07-12 20:15:19'),(1365,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":557}','2018-07-12 20:15:19','2018-07-12 20:15:19'),(1366,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":557}','2018-07-12 20:15:19','2018-07-12 20:15:19'),(1367,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":557}','2018-07-12 20:15:19','2018-07-12 20:15:19'),(1368,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":558}','2018-07-12 20:15:37','2018-07-12 20:15:37'),(1369,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":558}','2018-07-12 20:15:37','2018-07-12 20:15:37'),(1370,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":558}','2018-07-12 20:15:37','2018-07-12 20:15:37'),(1371,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":558}','2018-07-12 20:15:37','2018-07-12 20:15:37'),(1372,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":558}','2018-07-12 20:15:37','2018-07-12 20:15:37'),(1373,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":559}','2018-07-12 20:15:46','2018-07-12 20:15:46'),(1374,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":559}','2018-07-12 20:15:46','2018-07-12 20:15:46'),(1375,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":559}','2018-07-12 20:15:46','2018-07-12 20:15:46'),(1376,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":559}','2018-07-12 20:15:46','2018-07-12 20:15:46'),(1377,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":559}','2018-07-12 20:15:46','2018-07-12 20:15:46'),(1378,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":560}','2018-07-12 20:16:15','2018-07-12 20:16:15'),(1379,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":560}','2018-07-12 20:16:15','2018-07-12 20:16:15'),(1380,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":560}','2018-07-12 20:16:15','2018-07-12 20:16:15'),(1381,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":560}','2018-07-12 20:16:15','2018-07-12 20:16:15'),(1382,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":560}','2018-07-12 20:16:15','2018-07-12 20:16:15'),(1383,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":561}','2018-07-12 20:17:30','2018-07-12 20:17:30'),(1384,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":561}','2018-07-12 20:17:30','2018-07-12 20:17:30'),(1385,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":561}','2018-07-12 20:17:30','2018-07-12 20:17:30'),(1386,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":561}','2018-07-12 20:17:30','2018-07-12 20:17:30'),(1387,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":561}','2018-07-12 20:17:30','2018-07-12 20:17:30'),(1388,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":562}','2018-07-12 20:28:23','2018-07-12 20:28:23'),(1389,2,322,'Pencairan Pembiayaan','{\"jumlah\":1200000,\"id_pengajuan\":562}','2018-07-12 20:28:23','2018-07-12 20:28:23'),(1390,2,323,'Pencairan Pembiayaan','{\"jumlah\":-200000,\"id_pengajuan\":562}','2018-07-12 20:28:23','2018-07-12 20:28:23'),(1391,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":567}','2018-07-12 20:35:22','2018-07-12 20:35:22'),(1392,2,322,'Pencairan Pembiayaan','{\"jumlah\":1200000,\"id_pengajuan\":567}','2018-07-12 20:35:22','2018-07-12 20:35:22'),(1393,2,323,'Pencairan Pembiayaan','{\"jumlah\":-200000,\"id_pengajuan\":567}','2018-07-12 20:35:22','2018-07-12 20:35:22'),(1394,2,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":568}','2018-07-12 20:35:32','2018-07-12 20:35:32'),(1395,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":568}','2018-07-12 20:35:32','2018-07-12 20:35:32'),(1396,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":568}','2018-07-12 20:35:32','2018-07-12 20:35:32'),(1397,2,323,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":568}','2018-07-12 20:35:32','2018-07-12 20:35:32'),(1398,2,322,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":568}','2018-07-12 20:35:32','2018-07-12 20:35:32'),(1399,2,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":569}','2018-07-12 20:35:43','2018-07-12 20:35:43'),(1400,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":569}','2018-07-12 20:35:43','2018-07-12 20:35:43'),(1401,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":569}','2018-07-12 20:35:43','2018-07-12 20:35:43'),(1402,2,323,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":569}','2018-07-12 20:35:43','2018-07-12 20:35:43'),(1403,2,322,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":569}','2018-07-12 20:35:43','2018-07-12 20:35:43'),(1404,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":570}','2018-07-12 20:39:03','2018-07-12 20:39:03'),(1405,2,322,'Pencairan Pembiayaan','{\"jumlah\":1200000,\"id_pengajuan\":570}','2018-07-12 20:39:03','2018-07-12 20:39:03'),(1406,2,323,'Pencairan Pembiayaan','{\"jumlah\":-200000,\"id_pengajuan\":570}','2018-07-12 20:39:03','2018-07-12 20:39:03'),(1407,2,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":571}','2018-07-12 20:39:16','2018-07-12 20:39:16'),(1408,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":571}','2018-07-12 20:39:16','2018-07-12 20:39:16'),(1409,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":571}','2018-07-12 20:39:16','2018-07-12 20:39:16'),(1410,2,323,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":571}','2018-07-12 20:39:16','2018-07-12 20:39:16'),(1411,2,322,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":571}','2018-07-12 20:39:16','2018-07-12 20:39:16'),(1412,2,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":572}','2018-07-12 20:39:25','2018-07-12 20:39:25'),(1413,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":572}','2018-07-12 20:39:25','2018-07-12 20:39:25'),(1414,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":572}','2018-07-12 20:39:25','2018-07-12 20:39:25'),(1415,2,323,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":572}','2018-07-12 20:39:25','2018-07-12 20:39:25'),(1416,2,322,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":572}','2018-07-12 20:39:25','2018-07-12 20:39:25'),(1417,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":573}','2018-07-12 20:54:44','2018-07-12 20:54:44'),(1418,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":573}','2018-07-12 20:54:44','2018-07-12 20:54:44'),(1419,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":573}','2018-07-12 20:54:44','2018-07-12 20:54:44'),(1420,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":573}','2018-07-12 20:54:44','2018-07-12 20:54:44'),(1421,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":573}','2018-07-12 20:54:44','2018-07-12 20:54:44'),(1422,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":574}','2018-07-12 21:01:41','2018-07-12 21:01:41'),(1423,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":574}','2018-07-12 21:01:41','2018-07-12 21:01:41'),(1424,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":574}','2018-07-12 21:01:41','2018-07-12 21:01:41'),(1425,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":574}','2018-07-12 21:01:41','2018-07-12 21:01:41'),(1426,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":574}','2018-07-12 21:01:41','2018-07-12 21:01:41'),(1427,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":575}','2018-07-12 21:06:37','2018-07-12 21:06:37'),(1428,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":575}','2018-07-12 21:06:37','2018-07-12 21:06:37'),(1429,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":575}','2018-07-12 21:06:37','2018-07-12 21:06:37'),(1430,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":575}','2018-07-12 21:06:37','2018-07-12 21:06:37'),(1431,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":575}','2018-07-12 21:06:37','2018-07-12 21:06:37'),(1432,2,274,'Angsuran','{\"jumlah\":1416666.6666667,\"id_pengajuan\":601}','2018-07-12 21:32:52','2018-07-12 21:32:52'),(1433,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":601}','2018-07-12 21:32:52','2018-07-12 21:32:52'),(1434,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":601}','2018-07-12 21:32:52','2018-07-12 21:32:52'),(1435,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":601}','2018-07-12 21:32:52','2018-07-12 21:32:52'),(1436,2,322,'Angsuran','{\"jumlah\":-1416666.6666667,\"id_pengajuan\":601}','2018-07-12 21:32:52','2018-07-12 21:32:52'),(1458,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":614}','2018-07-13 05:55:12','2018-07-13 05:55:12'),(1459,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"id_pengajuan\":614}','2018-07-13 05:55:12','2018-07-13 05:55:12'),(1460,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":615}','2018-07-13 05:57:25','2018-07-13 05:57:25'),(1461,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"id_pengajuan\":615}','2018-07-13 05:57:25','2018-07-13 05:57:25'),(1462,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":616}','2018-07-13 06:50:53','2018-07-13 06:50:53'),(1463,2,321,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":616}','2018-07-13 06:50:53','2018-07-13 06:50:53'),(1464,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":617}','2018-07-13 07:47:36','2018-07-13 07:47:36'),(1465,2,322,'Pencairan Pembiayaan','{\"jumlah\":1200000,\"id_pengajuan\":617}','2018-07-13 07:47:36','2018-07-13 07:47:36'),(1466,2,323,'Pencairan Pembiayaan','{\"jumlah\":-200000,\"id_pengajuan\":617}','2018-07-13 07:47:36','2018-07-13 07:47:36'),(1467,2,274,'Pencairan Pembiayaan','{\"jumlah\":-10000000,\"id_pengajuan\":618}','2018-07-13 07:48:33','2018-07-13 07:48:33'),(1468,2,322,'Pencairan Pembiayaan','{\"jumlah\":12000000,\"id_pengajuan\":618}','2018-07-13 07:48:33','2018-07-13 07:48:33'),(1469,2,323,'Pencairan Pembiayaan','{\"jumlah\":-2000000,\"id_pengajuan\":618}','2018-07-13 07:48:33','2018-07-13 07:48:33'),(1470,2,274,'Pencairan Pembiayaan','{\"jumlah\":-10000000,\"id_pengajuan\":619}','2018-07-13 07:49:07','2018-07-13 07:49:07'),(1471,2,322,'Pencairan Pembiayaan','{\"jumlah\":12000000,\"id_pengajuan\":619}','2018-07-13 07:49:07','2018-07-13 07:49:07'),(1472,2,323,'Pencairan Pembiayaan','{\"jumlah\":-2000000,\"id_pengajuan\":619}','2018-07-13 07:49:07','2018-07-13 07:49:07'),(1473,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":620}','2018-07-13 07:51:09','2018-07-13 07:51:09'),(1474,2,322,'Pencairan Pembiayaan','{\"jumlah\":1200000,\"id_pengajuan\":620}','2018-07-13 07:51:09','2018-07-13 07:51:09'),(1475,2,323,'Pencairan Pembiayaan','{\"jumlah\":-200000,\"id_pengajuan\":620}','2018-07-13 07:51:09','2018-07-13 07:51:09'),(1476,2,274,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":628}','2018-07-13 15:23:27','2018-07-13 15:23:27'),(1477,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":628}','2018-07-13 15:23:27','2018-07-13 15:23:27'),(1478,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":628}','2018-07-13 15:23:27','2018-07-13 15:23:27'),(1479,2,321,'Angsuran','{\"jumlah\":-100000,\"id_pengajuan\":628}','2018-07-13 15:23:27','2018-07-13 15:23:27'),(1480,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":629}','2018-07-13 15:27:12','2018-07-13 15:27:12'),(1481,2,321,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":629}','2018-07-13 15:27:12','2018-07-13 15:27:12'),(1482,2,274,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":632}','2018-07-14 06:19:36','2018-07-14 06:19:36'),(1483,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":632}','2018-07-14 06:19:37','2018-07-14 06:19:37'),(1484,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":632}','2018-07-14 06:19:37','2018-07-14 06:19:37'),(1485,2,323,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":632}','2018-07-14 06:19:37','2018-07-14 06:19:37'),(1486,2,322,'Angsuran','{\"jumlah\":-100000,\"id_pengajuan\":632}','2018-07-14 06:19:37','2018-07-14 06:19:37'),(1487,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":633}','2018-07-14 06:25:56','2018-07-14 06:25:56'),(1488,2,322,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":633}','2018-07-14 06:25:56','2018-07-14 06:25:56'),(1489,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":634}','2018-07-14 06:26:29','2018-07-14 06:26:29'),(1490,2,322,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":634}','2018-07-14 06:26:30','2018-07-14 06:26:30'),(1491,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":657}','2018-07-14 07:33:39','2018-07-14 07:33:39'),(1492,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":657}','2018-07-14 07:33:39','2018-07-14 07:33:39'),(1493,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":657}','2018-07-14 07:33:39','2018-07-14 07:33:39'),(1494,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":657}','2018-07-14 07:33:39','2018-07-14 07:33:39'),(1495,2,274,'Angsuran','{\"jumlah\":30000,\"id_pengajuan\":673}','2018-07-14 07:38:51','2018-07-14 07:38:51'),(1496,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":673}','2018-07-14 07:38:51','2018-07-14 07:38:51'),(1497,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":673}','2018-07-14 07:38:51','2018-07-14 07:38:51'),(1498,2,321,'Angsuran','{\"jumlah\":-30000,\"id_pengajuan\":673}','2018-07-14 07:38:51','2018-07-14 07:38:51'),(1499,2,274,'Angsuran','{\"jumlah\":10000,\"id_pengajuan\":674}','2018-07-14 07:39:34','2018-07-14 07:39:34'),(1500,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":674}','2018-07-14 07:39:34','2018-07-14 07:39:34'),(1501,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":674}','2018-07-14 07:39:34','2018-07-14 07:39:34'),(1502,2,321,'Angsuran','{\"jumlah\":-10000,\"id_pengajuan\":674}','2018-07-14 07:39:34','2018-07-14 07:39:34'),(1503,2,274,'Angsuran','{\"jumlah\":10000,\"id_pengajuan\":675}','2018-07-14 07:40:16','2018-07-14 07:40:16'),(1504,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":675}','2018-07-14 07:40:16','2018-07-14 07:40:16'),(1505,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":675}','2018-07-14 07:40:16','2018-07-14 07:40:16'),(1506,2,321,'Angsuran','{\"jumlah\":-10000,\"id_pengajuan\":675}','2018-07-14 07:40:16','2018-07-14 07:40:16'),(1507,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":676}','2018-07-14 07:41:24','2018-07-14 07:41:24'),(1508,2,321,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":676}','2018-07-14 07:41:24','2018-07-14 07:41:24'),(1509,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":677}','2018-07-14 07:42:30','2018-07-14 07:42:30'),(1510,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":677}','2018-07-14 07:42:30','2018-07-14 07:42:30'),(1511,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":677}','2018-07-14 07:42:30','2018-07-14 07:42:30'),(1512,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":677}','2018-07-14 07:42:30','2018-07-14 07:42:30'),(1513,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":678}','2018-07-14 07:43:31','2018-07-14 07:43:31'),(1514,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":678}','2018-07-14 07:43:31','2018-07-14 07:43:31'),(1515,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":678}','2018-07-14 07:43:31','2018-07-14 07:43:31'),(1516,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":678}','2018-07-14 07:43:31','2018-07-14 07:43:31'),(1517,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":679}','2018-07-14 07:43:34','2018-07-14 07:43:34'),(1518,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":679}','2018-07-14 07:43:34','2018-07-14 07:43:34'),(1519,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":679}','2018-07-14 07:43:34','2018-07-14 07:43:34'),(1520,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":679}','2018-07-14 07:43:34','2018-07-14 07:43:34'),(1521,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":680}','2018-07-14 07:43:38','2018-07-14 07:43:38'),(1522,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":680}','2018-07-14 07:43:38','2018-07-14 07:43:38'),(1523,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":680}','2018-07-14 07:43:38','2018-07-14 07:43:38'),(1524,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":680}','2018-07-14 07:43:38','2018-07-14 07:43:38'),(1525,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":681}','2018-07-14 07:43:41','2018-07-14 07:43:41'),(1526,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":681}','2018-07-14 07:43:42','2018-07-14 07:43:42'),(1527,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":681}','2018-07-14 07:43:42','2018-07-14 07:43:42'),(1528,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":681}','2018-07-14 07:43:42','2018-07-14 07:43:42'),(1529,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":682}','2018-07-14 07:43:54','2018-07-14 07:43:54'),(1530,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":682}','2018-07-14 07:43:54','2018-07-14 07:43:54'),(1531,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":682}','2018-07-14 07:43:54','2018-07-14 07:43:54'),(1532,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":682}','2018-07-14 07:43:54','2018-07-14 07:43:54'),(1533,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":683}','2018-07-14 07:44:04','2018-07-14 07:44:04'),(1534,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":683}','2018-07-14 07:44:04','2018-07-14 07:44:04'),(1535,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":683}','2018-07-14 07:44:04','2018-07-14 07:44:04'),(1536,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":683}','2018-07-14 07:44:04','2018-07-14 07:44:04'),(1537,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":684}','2018-07-14 07:44:40','2018-07-14 07:44:40'),(1538,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":684}','2018-07-14 07:44:40','2018-07-14 07:44:40'),(1539,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":684}','2018-07-14 07:44:40','2018-07-14 07:44:40'),(1540,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":684}','2018-07-14 07:44:40','2018-07-14 07:44:40'),(1541,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":685}','2018-07-14 07:44:47','2018-07-14 07:44:47'),(1542,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":685}','2018-07-14 07:44:47','2018-07-14 07:44:47'),(1543,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":685}','2018-07-14 07:44:47','2018-07-14 07:44:47'),(1544,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":685}','2018-07-14 07:44:47','2018-07-14 07:44:47'),(1545,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":686}','2018-07-14 07:44:55','2018-07-14 07:44:55'),(1546,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":686}','2018-07-14 07:44:55','2018-07-14 07:44:55'),(1547,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":686}','2018-07-14 07:44:55','2018-07-14 07:44:55'),(1548,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":686}','2018-07-14 07:44:55','2018-07-14 07:44:55'),(1549,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":687}','2018-07-14 07:45:40','2018-07-14 07:45:40'),(1550,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":687}','2018-07-14 07:45:40','2018-07-14 07:45:40'),(1551,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":687}','2018-07-14 07:45:40','2018-07-14 07:45:40'),(1552,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":687}','2018-07-14 07:45:40','2018-07-14 07:45:40'),(1553,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":688}','2018-07-14 07:46:43','2018-07-14 07:46:43'),(1554,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":688}','2018-07-14 07:46:43','2018-07-14 07:46:43'),(1555,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":688}','2018-07-14 07:46:43','2018-07-14 07:46:43'),(1556,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":688}','2018-07-14 07:46:43','2018-07-14 07:46:43'),(1557,2,274,'Angsuran','{\"jumlah\":30000,\"id_pengajuan\":689}','2018-07-14 07:51:30','2018-07-14 07:51:30'),(1558,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":689}','2018-07-14 07:51:30','2018-07-14 07:51:30'),(1559,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":689}','2018-07-14 07:51:30','2018-07-14 07:51:30'),(1560,2,321,'Angsuran','{\"jumlah\":-30000,\"id_pengajuan\":689}','2018-07-14 07:51:30','2018-07-14 07:51:30'),(1561,2,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":690}','2018-07-14 08:58:47','2018-07-14 08:58:47'),(1562,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":690}','2018-07-14 08:58:47','2018-07-14 08:58:47'),(1563,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":690}','2018-07-14 08:58:47','2018-07-14 08:58:47'),(1564,2,321,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":690}','2018-07-14 08:58:47','2018-07-14 08:58:47'),(1565,2,274,'Angsuran','{\"jumlah\":5000000,\"id_pengajuan\":691}','2018-07-14 09:01:23','2018-07-14 09:01:23'),(1566,2,322,'Angsuran','{\"jumlah\":-5000000,\"id_pengajuan\":691}','2018-07-14 09:01:23','2018-07-14 09:01:23'),(1567,2,274,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":692}','2018-07-14 09:03:45','2018-07-14 09:03:45'),(1568,2,351,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":692}','2018-07-14 09:03:45','2018-07-14 09:03:45'),(1569,2,344,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":692}','2018-07-14 09:03:45','2018-07-14 09:03:45'),(1570,2,323,'Angsuran','{\"jumlah\":1000000,\"id_pengajuan\":692}','2018-07-14 09:03:45','2018-07-14 09:03:45'),(1571,2,322,'Angsuran','{\"jumlah\":-1000000,\"id_pengajuan\":692}','2018-07-14 09:03:45','2018-07-14 09:03:45'),(1572,2,274,'Angsuran','{\"jumlah\":20000,\"id_pengajuan\":693}','2018-07-14 09:24:54','2018-07-14 09:24:54'),(1573,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":693}','2018-07-14 09:24:54','2018-07-14 09:24:54'),(1574,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":693}','2018-07-14 09:24:54','2018-07-14 09:24:54'),(1575,2,321,'Angsuran','{\"jumlah\":-20000,\"id_pengajuan\":693}','2018-07-14 09:24:54','2018-07-14 09:24:54'),(1576,2,274,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":694}','2018-07-14 09:46:47','2018-07-14 09:46:47'),(1577,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":694}','2018-07-14 09:46:47','2018-07-14 09:46:47'),(1578,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":694}','2018-07-14 09:46:47','2018-07-14 09:46:47'),(1579,2,321,'Angsuran','{\"jumlah\":-100000,\"id_pengajuan\":694}','2018-07-14 09:46:47','2018-07-14 09:46:47'),(1580,2,274,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":695}','2018-07-14 09:48:19','2018-07-14 09:48:19'),(1581,2,321,'Angsuran','{\"jumlah\":-200000,\"id_pengajuan\":695}','2018-07-14 09:48:19','2018-07-14 09:48:19'),(1582,14,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":696}','2018-07-14 11:16:47','2018-07-14 11:16:47'),(1583,14,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":696}','2018-07-14 11:16:47','2018-07-14 11:16:47'),(1584,14,274,'Kredit','{\"jumlah\":1000000,\"id_pengajuan\":\"697\"}','2018-07-14 11:21:30','2018-07-14 11:21:30'),(1585,14,266,'Kredit','{\"jumlah\":1000000,\"id_pengajuan\":\"697\"}','2018-07-14 11:21:30','2018-07-14 11:21:30'),(1586,14,274,'Deposit Awal','{\"jumlah\":5000000,\"id_pengajuan\":698}','2018-07-14 11:26:52','2018-07-14 11:26:52'),(1587,14,390,'Deposit Awal','{\"jumlah\":5000000,\"id_pengajuan\":698}','2018-07-14 11:26:52','2018-07-14 11:26:52'),(1588,2,274,'Deposit Awal','{\"jumlah\":5000000,\"id_pengajuan\":703}','2018-07-15 06:30:25','2018-07-15 06:30:25'),(1589,2,281,'Deposit Awal','{\"jumlah\":5000000,\"id_pengajuan\":703}','2018-07-15 06:30:25','2018-07-15 06:30:25'),(1600,15,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":708}','2018-07-15 13:26:49','2018-07-15 13:26:49'),(1601,15,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":708}','2018-07-15 13:26:49','2018-07-15 13:26:49'),(1602,15,274,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":708}','2018-07-15 13:26:49','2018-07-15 13:26:49'),(1603,15,274,'Simpanan Wajib','{\"jumlah\":10000,\"id_pengajuan\":708}','2018-07-15 13:26:49','2018-07-15 13:26:49'),(1604,15,339,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":708}','2018-07-15 13:26:49','2018-07-15 13:26:49'),(1605,15,341,'Simpanan Wajib','{\"jumlah\":10000,\"id_pengajuan\":708}','2018-07-15 13:26:49','2018-07-15 13:26:49'),(1606,16,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":709}','2018-07-15 13:34:55','2018-07-15 13:34:55'),(1607,16,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":709}','2018-07-15 13:34:55','2018-07-15 13:34:55'),(1608,16,274,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":709}','2018-07-15 13:34:55','2018-07-15 13:34:55'),(1609,16,274,'Simpanan Wajib','{\"jumlah\":25000,\"id_pengajuan\":709}','2018-07-15 13:34:55','2018-07-15 13:34:55'),(1610,16,339,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":709}','2018-07-15 13:34:55','2018-07-15 13:34:55'),(1611,16,341,'Simpanan Wajib','{\"jumlah\":25000,\"id_pengajuan\":709}','2018-07-15 13:34:55','2018-07-15 13:34:55'),(1624,16,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":710}','2018-07-17 09:32:27','2018-07-17 09:32:27'),(1625,16,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":710}','2018-07-17 09:32:27','2018-07-17 09:32:27'),(1626,16,274,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":710}','2018-07-17 09:32:27','2018-07-17 09:32:27'),(1627,16,274,'Simpanan Wajib','{\"jumlah\":10000,\"id_pengajuan\":710}','2018-07-17 09:32:27','2018-07-17 09:32:27'),(1628,16,339,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":710}','2018-07-17 09:32:27','2018-07-17 09:32:27'),(1629,16,341,'Simpanan Wajib','{\"jumlah\":10000,\"id_pengajuan\":710}','2018-07-17 09:32:27','2018-07-17 09:32:27'),(1630,3,308,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":715}','2018-07-17 14:12:19','2018-07-17 14:12:19'),(1631,3,339,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":715}','2018-07-17 14:12:19','2018-07-17 14:12:19'),(1632,3,274,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":717}','2018-07-17 14:13:43','2018-07-17 14:13:43'),(1633,3,339,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":717}','2018-07-17 14:13:44','2018-07-17 14:13:44'),(1634,3,274,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":718}','2018-07-17 14:15:32','2018-07-17 14:15:32'),(1635,3,339,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":718}','2018-07-17 14:15:32','2018-07-17 14:15:32'),(1636,9,274,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":719}','2018-07-17 14:20:02','2018-07-17 14:20:02'),(1637,9,339,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":719}','2018-07-17 14:20:02','2018-07-17 14:20:02'),(1638,3,274,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":720}','2018-07-17 14:31:35','2018-07-17 14:31:35'),(1639,3,339,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":720}','2018-07-17 14:31:36','2018-07-17 14:31:36'),(1640,3,274,'Simpanan Wajib','{\"jumlah\":\"1\",\"id_pengajuan\":721}','2018-07-17 19:25:30','2018-07-17 19:25:30'),(1641,3,339,'Simpanan Wajib','{\"jumlah\":\"1\",\"id_pengajuan\":721}','2018-07-17 19:25:30','2018-07-17 19:25:30'),(1642,3,274,'Simpanan Wajib','{\"jumlah\":\"9999\",\"id_pengajuan\":722}','2018-07-17 19:31:14','2018-07-17 19:31:14'),(1643,3,339,'Simpanan Wajib','{\"jumlah\":\"9999\",\"id_pengajuan\":722}','2018-07-17 19:31:14','2018-07-17 19:31:14'),(1644,15,274,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":723}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(1645,15,266,'Setoran Awal','{\"jumlah\":10000,\"id_pengajuan\":723}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(1646,15,274,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":723}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(1647,15,274,'Simpanan Wajib','{\"jumlah\":10000,\"id_pengajuan\":723}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(1648,15,339,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":723}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(1649,15,341,'Simpanan Wajib','{\"jumlah\":10000,\"id_pengajuan\":723}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(1650,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":724}','2018-07-18 06:25:24','2018-07-18 06:25:24'),(1651,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"id_pengajuan\":724}','2018-07-18 06:25:24','2018-07-18 06:25:24'),(1652,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":725}','2018-07-18 06:36:18','2018-07-18 06:36:18'),(1653,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"id_pengajuan\":725}','2018-07-18 06:36:18','2018-07-18 06:36:18'),(1654,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":726}','2018-07-18 06:48:43','2018-07-18 06:48:43'),(1655,2,322,'Pencairan Pembiayaan','{\"jumlah\":1200000,\"id_pengajuan\":726}','2018-07-18 06:48:43','2018-07-18 06:48:43'),(1656,2,323,'Pencairan Pembiayaan','{\"jumlah\":-200000,\"id_pengajuan\":726}','2018-07-18 06:48:43','2018-07-18 06:48:43'),(1673,2,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":728}','2018-07-18 09:47:26','2018-07-18 09:47:26'),(1674,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":728}','2018-07-18 09:47:26','2018-07-18 09:47:26'),(1675,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":728}','2018-07-18 09:47:26','2018-07-18 09:47:26'),(1676,2,321,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":728}','2018-07-18 09:47:26','2018-07-18 09:47:26'),(1677,2,274,'Angsuran','{\"jumlah\":400000,\"id_pengajuan\":730}','2018-07-18 10:10:21','2018-07-18 10:10:21'),(1678,2,350,'Angsuran','{\"jumlah\":0,\"id_pengajuan\":730}','2018-07-18 10:10:21','2018-07-18 10:10:21'),(1679,2,344,'Angsuran','{\"jumlah\":0,\"id_pengajuan\":730}','2018-07-18 10:10:21','2018-07-18 10:10:21'),(1680,2,321,'Angsuran','{\"jumlah\":-400000,\"id_pengajuan\":730}','2018-07-18 10:10:21','2018-07-18 10:10:21'),(1681,2,274,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":731}','2018-07-18 10:22:07','2018-07-18 10:22:07'),(1682,2,350,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":731}','2018-07-18 10:22:07','2018-07-18 10:22:07'),(1683,2,344,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":731}','2018-07-18 10:22:07','2018-07-18 10:22:07'),(1684,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":731}','2018-07-18 10:22:07','2018-07-18 10:22:07'),(1685,2,274,'Angsuran','{\"jumlah\":20000,\"id_pengajuan\":732}','2018-07-18 10:34:39','2018-07-18 10:34:39'),(1686,2,350,'Angsuran','{\"jumlah\":0,\"id_pengajuan\":732}','2018-07-18 10:34:39','2018-07-18 10:34:39'),(1687,2,344,'Angsuran','{\"jumlah\":0,\"id_pengajuan\":732}','2018-07-18 10:34:39','2018-07-18 10:34:39'),(1688,2,321,'Angsuran','{\"jumlah\":-20000,\"id_pengajuan\":732}','2018-07-18 10:34:39','2018-07-18 10:34:39'),(1689,2,274,'Angsuran','{\"jumlah\":10000,\"id_pengajuan\":733}','2018-07-18 11:01:50','2018-07-18 11:01:50'),(1690,2,350,'Angsuran','{\"jumlah\":0,\"id_pengajuan\":733}','2018-07-18 11:01:50','2018-07-18 11:01:50'),(1691,2,344,'Angsuran','{\"jumlah\":0,\"id_pengajuan\":733}','2018-07-18 11:01:50','2018-07-18 11:01:50'),(1692,2,321,'Angsuran','{\"jumlah\":-10000,\"id_pengajuan\":733}','2018-07-18 11:01:50','2018-07-18 11:01:50'),(1693,2,274,'Angsuran','{\"jumlah\":5000,\"id_pengajuan\":734}','2018-07-18 11:03:45','2018-07-18 11:03:45'),(1694,2,350,'Angsuran','{\"jumlah\":0,\"id_pengajuan\":734}','2018-07-18 11:03:46','2018-07-18 11:03:46'),(1695,2,344,'Angsuran','{\"jumlah\":0,\"id_pengajuan\":734}','2018-07-18 11:03:46','2018-07-18 11:03:46'),(1696,2,321,'Angsuran','{\"jumlah\":-5000,\"id_pengajuan\":734}','2018-07-18 11:03:46','2018-07-18 11:03:46'),(1697,2,274,'Angsuran','{\"jumlah\":5000,\"id_pengajuan\":735}','2018-07-18 11:11:26','2018-07-18 11:11:26'),(1698,2,321,'Angsuran','{\"jumlah\":-5000,\"id_pengajuan\":735}','2018-07-18 11:11:26','2018-07-18 11:11:26'),(1699,2,274,'Angsuran','{\"jumlah\":10000,\"id_pengajuan\":736}','2018-07-18 11:13:30','2018-07-18 11:13:30'),(1700,2,321,'Angsuran','{\"jumlah\":-10000,\"id_pengajuan\":736}','2018-07-18 11:13:30','2018-07-18 11:13:30'),(1701,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":737}','2018-07-18 11:27:43','2018-07-18 11:27:43'),(1702,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"id_pengajuan\":737}','2018-07-18 11:27:43','2018-07-18 11:27:43'),(1703,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":738}','2018-07-18 11:28:50','2018-07-18 11:28:50'),(1704,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":738}','2018-07-18 11:28:50','2018-07-18 11:28:50'),(1705,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":738}','2018-07-18 11:28:50','2018-07-18 11:28:50'),(1706,2,321,'Angsuran','{\"jumlah\":-400000,\"id_pengajuan\":738}','2018-07-18 11:28:50','2018-07-18 11:28:50'),(1707,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":739}','2018-07-18 14:46:51','2018-07-18 14:46:51'),(1708,2,350,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":739}','2018-07-18 14:46:52','2018-07-18 14:46:52'),(1709,2,344,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":739}','2018-07-18 14:46:52','2018-07-18 14:46:52'),(1710,2,321,'Angsuran','{\"jumlah\":-0,\"id_pengajuan\":739}','2018-07-18 14:46:52','2018-07-18 14:46:52'),(1711,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":740}','2018-07-18 15:38:09','2018-07-18 15:38:09'),(1712,2,321,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":740}','2018-07-18 15:38:09','2018-07-18 15:38:09'),(1713,2,274,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":741}','2018-07-18 19:20:08','2018-07-18 19:20:08'),(1714,2,350,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":741}','2018-07-18 19:20:08','2018-07-18 19:20:08'),(1715,2,344,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":741}','2018-07-18 19:20:08','2018-07-18 19:20:08'),(1716,2,321,'Angsuran','{\"jumlah\":-0,\"id_pengajuan\":741}','2018-07-18 19:20:08','2018-07-18 19:20:08'),(1717,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":742}','2018-07-18 19:27:14','2018-07-18 19:27:14'),(1718,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"id_pengajuan\":742}','2018-07-18 19:27:14','2018-07-18 19:27:14'),(1719,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":743}','2018-07-19 07:59:22','2018-07-19 07:59:22'),(1720,2,321,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":743}','2018-07-19 07:59:22','2018-07-19 07:59:22'),(1721,2,274,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":744}','2018-07-19 08:13:03','2018-07-19 08:13:03'),(1722,2,350,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":744}','2018-07-19 08:13:03','2018-07-19 08:13:03'),(1723,2,344,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":744}','2018-07-19 08:13:03','2018-07-19 08:13:03'),(1725,2,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":749}','2018-07-19 09:54:43','2018-07-19 09:54:43'),(1726,2,350,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":749}','2018-07-19 09:54:43','2018-07-19 09:54:43'),(1727,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":749}','2018-07-19 09:54:43','2018-07-19 09:54:43'),(1728,2,321,'Angsuran','{\"jumlah\":-400000,\"id_pengajuan\":749}','2018-07-19 09:54:44','2018-07-19 09:54:44'),(1729,2,274,'Angsuran','{\"jumlah\":700000,\"id_pengajuan\":750}','2018-07-19 10:04:11','2018-07-19 10:04:11'),(1730,2,350,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":750}','2018-07-19 10:04:11','2018-07-19 10:04:11'),(1731,2,344,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":750}','2018-07-19 10:04:11','2018-07-19 10:04:11'),(1732,2,321,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":750}','2018-07-19 10:04:11','2018-07-19 10:04:11'),(1733,2,274,'Angsuran','{\"jumlah\":400000,\"id_pengajuan\":747}','2018-07-19 10:52:08','2018-07-19 10:52:08'),(1734,2,321,'Angsuran','{\"jumlah\":-400000,\"id_pengajuan\":747}','2018-07-19 10:52:08','2018-07-19 10:52:08'),(1735,2,274,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":748}','2018-07-19 10:52:27','2018-07-19 10:52:27'),(1736,2,350,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":748}','2018-07-19 10:52:27','2018-07-19 10:52:27'),(1737,2,344,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":748}','2018-07-19 10:52:27','2018-07-19 10:52:27'),(1738,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":751}','2018-07-19 10:55:31','2018-07-19 10:55:31'),(1739,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"id_pengajuan\":751}','2018-07-19 10:55:31','2018-07-19 10:55:31'),(1740,2,274,'Angsuran','{\"jumlah\":700000,\"id_pengajuan\":755}','2018-07-19 21:55:47','2018-07-19 21:55:47'),(1741,2,350,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":755}','2018-07-19 21:55:47','2018-07-19 21:55:47'),(1742,2,344,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":755}','2018-07-19 21:55:47','2018-07-19 21:55:47'),(1743,2,321,'Angsuran','{\"jumlah\":-500000,\"id_pengajuan\":755}','2018-07-19 21:55:47','2018-07-19 21:55:47'),(1744,2,274,'Angsuran','{\"jumlah\":450000,\"id_pengajuan\":757}','2018-07-19 22:50:10','2018-07-19 22:50:10'),(1745,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":757}','2018-07-19 22:50:11','2018-07-19 22:50:11'),(1746,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":757}','2018-07-19 22:50:11','2018-07-19 22:50:11'),(1747,2,262,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":757}','2018-07-19 22:50:11','2018-07-19 22:50:11'),(1748,2,322,'Angsuran','{\"jumlah\":-450000,\"id_pengajuan\":757}','2018-07-19 22:50:11','2018-07-19 22:50:11'),(1749,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":766}','2018-07-19 22:53:03','2018-07-19 22:53:03'),(1750,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":766}','2018-07-19 22:53:03','2018-07-19 22:53:03'),(1751,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":766}','2018-07-19 22:53:03','2018-07-19 22:53:03'),(1752,2,262,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":766}','2018-07-19 22:53:03','2018-07-19 22:53:03'),(1753,2,322,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":766}','2018-07-19 22:53:03','2018-07-19 22:53:03'),(1754,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":767}','2018-07-19 22:54:12','2018-07-19 22:54:12'),(1755,2,322,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":767}','2018-07-19 22:54:12','2018-07-19 22:54:12'),(1756,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":768}','2018-07-19 22:58:05','2018-07-19 22:58:05'),(1757,2,322,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":768}','2018-07-19 22:58:05','2018-07-19 22:58:05'),(1758,2,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":769}','2018-07-19 23:00:09','2018-07-19 23:00:09'),(1759,2,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":769}','2018-07-19 23:00:09','2018-07-19 23:00:09'),(1760,2,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":769}','2018-07-19 23:00:09','2018-07-19 23:00:09'),(1761,2,262,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":769}','2018-07-19 23:00:09','2018-07-19 23:00:09'),(1762,2,322,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":769}','2018-07-19 23:00:09','2018-07-19 23:00:09'),(1763,2,274,'Angsuran','{\"jumlah\":400000,\"id_pengajuan\":775}','2018-07-19 23:41:21','2018-07-19 23:41:21'),(1764,2,321,'Angsuran','{\"jumlah\":-400000,\"id_pengajuan\":775}','2018-07-19 23:41:21','2018-07-19 23:41:21'),(1765,2,274,'Angsuran','{\"jumlah\":450000,\"id_pengajuan\":776}','2018-07-19 23:43:39','2018-07-19 23:43:39'),(1766,2,350,'Angsuran','{\"jumlah\":400000,\"id_pengajuan\":776}','2018-07-19 23:43:39','2018-07-19 23:43:39'),(1767,2,344,'Angsuran','{\"jumlah\":400000,\"id_pengajuan\":776}','2018-07-19 23:43:39','2018-07-19 23:43:39'),(1768,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":776}','2018-07-19 23:43:39','2018-07-19 23:43:39'),(1769,2,274,'Angsuran','{\"jumlah\":50000,\"id_pengajuan\":777}','2018-07-20 00:01:48','2018-07-20 00:01:48'),(1770,2,321,'Angsuran','{\"jumlah\":-50000,\"id_pengajuan\":777}','2018-07-20 00:01:48','2018-07-20 00:01:48'),(1771,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":778}','2018-07-20 00:04:19','2018-07-20 00:04:19'),(1772,2,322,'Pencairan Pembiayaan','{\"jumlah\":1200000,\"id_pengajuan\":778}','2018-07-20 00:04:19','2018-07-20 00:04:19'),(1773,2,323,'Pencairan Pembiayaan','{\"jumlah\":-200000,\"id_pengajuan\":778}','2018-07-20 00:04:19','2018-07-20 00:04:19'),(1774,1,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":780}','2018-07-20 00:19:04','2018-07-20 00:19:04'),(1775,1,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":780}','2018-07-20 00:19:04','2018-07-20 00:19:04'),(1776,1,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":780}','2018-07-20 00:19:04','2018-07-20 00:19:04'),(1777,1,262,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":780}','2018-07-20 00:19:04','2018-07-20 00:19:04'),(1778,1,322,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":780}','2018-07-20 00:19:04','2018-07-20 00:19:04'),(1779,2,274,'Setoran Awal','{\"jumlah\":25000,\"id_pengajuan\":781}','2018-07-24 16:24:36','2018-07-24 16:24:36'),(1780,2,268,'Setoran Awal','{\"jumlah\":25000,\"id_pengajuan\":781}','2018-07-24 16:24:36','2018-07-24 16:24:36'),(1781,1,274,'Angsuran','{\"jumlah\":600000,\"id_pengajuan\":782}','2018-07-25 05:14:26','2018-07-25 05:14:26'),(1782,1,351,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":782}','2018-07-25 05:14:26','2018-07-25 05:14:26'),(1783,1,344,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":782}','2018-07-25 05:14:26','2018-07-25 05:14:26'),(1784,1,262,'Angsuran','{\"jumlah\":100000,\"id_pengajuan\":782}','2018-07-25 05:14:26','2018-07-25 05:14:26'),(1785,1,322,'Angsuran','{\"jumlah\":-600000,\"id_pengajuan\":782}','2018-07-25 05:14:26','2018-07-25 05:14:26'),(1825,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"id_pengajuan\":783}','2018-07-25 08:56:41','2018-07-25 08:56:41'),(1826,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"id_pengajuan\":783}','2018-07-25 08:56:41','2018-07-25 08:56:41'),(1827,1,274,'Angsuran','{\"jumlah\":500000,\"id_pengajuan\":784}','2018-07-25 08:57:06','2018-07-25 08:57:06'),(1828,1,350,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":784}','2018-07-25 08:57:06','2018-07-25 08:57:06'),(1829,1,344,'Angsuran','{\"jumlah\":200000,\"id_pengajuan\":784}','2018-07-25 08:57:06','2018-07-25 08:57:06'),(1830,1,321,'Angsuran','{\"jumlah\":-300000,\"id_pengajuan\":784}','2018-07-25 08:57:06','2018-07-25 08:57:06'),(1908,9,274,'Setoran Awal','{\"jumlah\":0,\"id_pengajuan\":785}','2018-07-26 07:13:28','2018-07-26 07:13:28'),(1909,9,270,'Setoran Awal','{\"jumlah\":0,\"id_pengajuan\":785}','2018-07-26 07:13:29','2018-07-26 07:13:29'),(1910,9,274,'Setoran Awal','{\"jumlah\":0,\"id_pengajuan\":786}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(1911,9,270,'Setoran Awal','{\"jumlah\":0,\"id_pengajuan\":786}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(1912,9,274,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":786}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(1913,9,274,'Simpanan Wajib','{\"jumlah\":40000,\"id_pengajuan\":786}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(1914,9,339,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":786}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(1915,9,341,'Simpanan Wajib','{\"jumlah\":40000,\"id_pengajuan\":786}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(1916,10,274,'Setoran Awal','{\"jumlah\":25000,\"id_pengajuan\":787}','2018-07-26 07:21:56','2018-07-26 07:21:56'),(1917,10,268,'Setoran Awal','{\"jumlah\":25000,\"id_pengajuan\":787}','2018-07-26 07:21:56','2018-07-26 07:21:56'),(1918,10,274,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":787}','2018-07-26 07:21:56','2018-07-26 07:21:56'),(1919,10,274,'Simpanan Wajib','{\"jumlah\":30000,\"id_pengajuan\":787}','2018-07-26 07:21:56','2018-07-26 07:21:56'),(1920,10,339,'Simpanan Pokok','{\"jumlah\":25000,\"id_pengajuan\":787}','2018-07-26 07:21:56','2018-07-26 07:21:56'),(1921,10,341,'Simpanan Wajib','{\"jumlah\":30000,\"id_pengajuan\":787}','2018-07-26 07:21:56','2018-07-26 07:21:56'),(1922,1,397,'Jurnal Lain','{\"jumlah\":50000000,\"keterangan\":\"[Pemasukkan] Sisa SHU 2017\"}','2018-07-26 13:43:01','2018-07-26 13:43:01'),(1923,1,308,'Jurnal Lain','{\"jumlah\":25000000,\"keterangan\":\"[Pemasukkan] shu\"}','2018-07-26 13:44:12','2018-07-26 13:44:12'),(1924,1,309,'Jurnal Lain','{\"jumlah\":25000000,\"keterangan\":\"[Pemasukkan] shu\"}','2018-07-26 13:44:26','2018-07-26 13:44:26'),(1925,1,397,'Jurnal Lain','{\"jumlah\":-50000000,\"keterangan\":\"[Pengeluaran] shu keluar\"}','2018-07-26 13:58:56','2018-07-26 13:58:56'),(1926,1,308,'Jurnal Lain','{\"jumlah\":-25000000,\"keterangan\":\"[Pengeluaran] salah transfer\"}','2018-07-26 14:02:12','2018-07-26 14:02:12'),(1927,1,309,'Jurnal Lain','{\"jumlah\":-25000000,\"keterangan\":\"[Pengeluaran] salah\"}','2018-07-26 14:03:06','2018-07-26 14:03:06'),(1928,10,274,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":788}','2018-07-26 14:06:07','2018-07-26 14:06:07'),(1929,10,339,'Simpanan Wajib','{\"jumlah\":\"10000\",\"id_pengajuan\":788}','2018-07-26 14:06:07','2018-07-26 14:06:07'),(1930,1,397,'Jurnal Lain','{\"jumlah\":25000000,\"keterangan\":\"[Pemasukkan] shu dibagikan\"}','2018-07-26 14:07:52','2018-07-26 14:07:52'),(1931,1,308,'Jurnal Lain','{\"jumlah\":25000000,\"keterangan\":\"[Pemasukkan] shu\"}','2018-07-26 14:08:22','2018-07-26 14:08:22'),(2019,2,266,'Donasi Maal','{\"jumlah\":-50000,\"dari_rekening\":\"\",\"saldo_awal\":7770000,\"saldo_akhir\":7720000}','2018-08-05 16:57:06','2018-08-05 16:57:06'),(2020,2,400,'Donasi Maal','{\"jumlah\":50000,\"dari_rekening\":\"127\",\"untuk_rekening\":179,\"saldo_awal\":0,\"saldo_akhir\":50000}','2018-08-05 16:57:06','2018-08-05 16:57:06'),(2021,2,266,'Donasi Waqaf','{\"jumlah\":-100000,\"dari_rekening\":\"\",\"saldo_awal\":7720000,\"saldo_akhir\":7620000}','2018-08-05 16:57:45','2018-08-05 16:57:45'),(2022,2,340,'Donasi Waqaf','{\"jumlah\":100000,\"dari_rekening\":\"127\",\"untuk_rekening\":118,\"saldo_awal\":0,\"saldo_akhir\":100000}','2018-08-05 16:57:45','2018-08-05 16:57:45'),(2023,2,309,'Donasi Waqaf','{\"jumlah\":5000000,\"dari_rekening\":\"\",\"saldo_awal\":450000,\"saldo_akhir\":5450000}','2018-08-05 17:09:24','2018-08-05 17:09:24'),(2024,2,340,'Donasi Waqaf','{\"jumlah\":5000000,\"dari_rekening\":\"\",\"untuk_rekening\":118,\"saldo_awal\":100000,\"saldo_akhir\":5100000}','2018-08-05 17:09:24','2018-08-05 17:09:24'),(2025,2,308,'Donasi Maal','{\"jumlah\":1000000,\"dari_rekening\":\"\",\"saldo_awal\":25110000,\"saldo_akhir\":26110000}','2018-08-05 17:10:06','2018-08-05 17:10:06'),(2026,2,400,'Donasi Maal','{\"jumlah\":1000000,\"dari_rekening\":\"\",\"untuk_rekening\":179,\"saldo_awal\":50000,\"saldo_akhir\":1050000}','2018-08-05 17:10:06','2018-08-05 17:10:06'),(2027,2,274,'Angsuran','{\"jumlah\":200,\"saldo_awal\":\"60094000\",\"saldo_akhir\":60094200,\"id_pengajuan\":795}','2018-08-05 20:34:01','2018-08-05 20:34:01'),(2028,2,321,'Angsuran','{\"jumlah\":-200,\"saldo_awal\":\"800000\",\"saldo_akhir\":799800,\"id_pengajuan\":795}','2018-08-05 20:34:01','2018-08-05 20:34:01'),(2029,2,274,'Angsuran','{\"jumlah\":199800,\"saldo_awal\":\"60094200\",\"saldo_akhir\":60294000,\"id_pengajuan\":798}','2018-08-05 21:10:24','2018-08-05 21:10:24'),(2030,2,321,'Angsuran','{\"jumlah\":-199800,\"saldo_awal\":\"799800\",\"saldo_akhir\":600000,\"id_pengajuan\":798}','2018-08-05 21:10:24','2018-08-05 21:10:24'),(2031,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"saldo_awal\":\"60294000\",\"saldo_akhir\":59294000,\"id_pengajuan\":794}','2018-08-06 16:01:45','2018-08-06 16:01:45'),(2032,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"saldo_awal\":\"600000\",\"saldo_akhir\":1600000,\"id_pengajuan\":794}','2018-08-06 16:01:45','2018-08-06 16:01:45'),(2033,2,274,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"saldo_awal\":\"59294000\",\"saldo_akhir\":58294000,\"id_pengajuan\":794}','2018-08-06 16:01:58','2018-08-06 16:01:58'),(2034,2,321,'Pencairan Pembiayaan','{\"jumlah\":1000000,\"saldo_awal\":\"1600000\",\"saldo_akhir\":2600000,\"id_pengajuan\":794}','2018-08-06 16:01:58','2018-08-06 16:01:58'),(2046,2,274,'Pencairan Pembiayaan','{\"jumlah\":-10000000,\"saldo_awal\":\"58294000\",\"saldo_akhir\":48294000,\"id_pengajuan\":797}','2018-08-06 16:56:29','2018-08-06 16:56:29'),(2047,2,322,'Pencairan Pembiayaan','{\"jumlah\":11000000,\"saldo_awal\":\"17916000\",\"saldo_akhir\":29916000,\"id_pengajuan\":797}','2018-08-06 16:56:29','2018-08-06 16:56:29'),(2048,2,323,'Pencairan Pembiayaan','{\"jumlah\":-1000000,\"saldo_awal\":\"-400000\",\"saldo_akhir\":-1400000,\"id_pengajuan\":797}','2018-08-06 16:56:29','2018-08-06 16:56:29'),(2049,2,274,'Angsuran','{\"jumlah\":900000,\"saldo_awal\":\"48294000\",\"saldo_akhir\":49194000,\"id_pengajuan\":799}','2018-08-14 14:04:31','2018-08-14 14:04:31'),(2050,2,350,'Angsuran','{\"jumlah\":400000,\"saldo_awal\":\"4100000\",\"saldo_akhir\":4500000,\"id_pengajuan\":799}','2018-08-14 14:04:31','2018-08-14 14:04:31'),(2051,2,344,'Angsuran','{\"jumlah\":400000,\"saldo_awal\":\"4100000\",\"saldo_akhir\":4500000,\"id_pengajuan\":799}','2018-08-14 14:04:31','2018-08-14 14:04:31'),(2052,2,321,'Angsuran','{\"jumlah\":-500000,\"saldo_awal\":\"2600000\",\"saldo_akhir\":2100000,\"id_pengajuan\":799}','2018-08-14 14:04:31','2018-08-14 14:04:31'),(2053,2,274,'Angsuran','{\"jumlah\":2200000,\"saldo_awal\":\"49194000\",\"saldo_akhir\":51394000,\"id_pengajuan\":800}','2018-08-14 14:04:55','2018-08-14 14:04:55'),(2054,2,350,'Angsuran','{\"jumlah\":2000000,\"saldo_awal\":\"4500000\",\"saldo_akhir\":6500000,\"id_pengajuan\":800}','2018-08-14 14:04:55','2018-08-14 14:04:55'),(2055,2,344,'Angsuran','{\"jumlah\":2000000,\"saldo_awal\":\"4500000\",\"saldo_akhir\":6500000,\"id_pengajuan\":800}','2018-08-14 14:04:55','2018-08-14 14:04:55'),(2056,2,321,'Angsuran','{\"jumlah\":-200000,\"saldo_awal\":\"2100000\",\"saldo_akhir\":1900000,\"id_pengajuan\":800}','2018-08-14 14:04:55','2018-08-14 14:04:55');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_deposito` */



DROP TABLE IF EXISTS `penyimpanan_deposito`;



CREATE TABLE `penyimpanan_deposito` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_deposito` int(10) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_deposito_id_user_foreign` (`id_user`),
  KEY `penyimpanan_deposito_id_deposito_foreign` (`id_deposito`),
  CONSTRAINT `penyimpanan_deposito_id_deposito_foreign` FOREIGN KEY (`id_deposito`) REFERENCES `deposito` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyimpanan_deposito_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_deposito` */



LOCK TABLES `penyimpanan_deposito` WRITE;



insert  into `penyimpanan_deposito`(`id`,`id_user`,`id_deposito`,`status`,`transaksi`,`created_at`,`updated_at`) values (55,2,55,'Deposit Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"300000\",\"saldo_awal\":0,\"saldo_akhir\":\"300000\"}','2018-07-02 19:24:07','2018-07-02 19:24:07'),(56,2,55,'Pencairan Deposito','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"jumlah\":\"300000\",\"saldo_awal\":\"300000\",\"saldo_akhir\":0}','2018-07-02 19:25:11','2018-07-02 19:25:11'),(57,2,56,'Deposit Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"300000\",\"saldo_awal\":0,\"saldo_akhir\":\"300000\"}','2018-07-02 19:29:41','2018-07-02 19:29:41'),(58,2,56,'Pencairan Deposito','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"jumlah\":\"300000\",\"saldo_awal\":\"300000\",\"saldo_akhir\":0}','2018-07-02 19:33:26','2018-07-02 19:33:26'),(59,2,57,'Deposit Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"1000000\",\"saldo_awal\":0,\"saldo_akhir\":\"1000000\"}','2018-07-01 20:26:48','2018-07-01 20:26:48'),(66,2,60,'Deposit Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"1000000\",\"saldo_awal\":0,\"saldo_akhir\":\"1000000\"}','2018-07-12 13:57:59','2018-07-12 13:57:59'),(68,14,62,'Deposit Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"5000000\",\"saldo_awal\":0,\"saldo_akhir\":\"5000000\"}','2018-07-14 11:26:52','2018-07-14 11:26:52'),(69,2,63,'Deposit Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"5000000\",\"saldo_awal\":0,\"saldo_akhir\":\"5000000\"}','2018-07-15 06:30:25','2018-07-15 06:30:25');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_jaminan` */



DROP TABLE IF EXISTS `penyimpanan_jaminan`;



CREATE TABLE `penyimpanan_jaminan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_jaminan` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_pengajuan` int(10) unsigned NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_jaminan_id_user_foreign` (`id_user`),
  KEY `penyimpanan_jaminan_id_pengajuan_foreign` (`id_pengajuan`),
  KEY `penyimpanan_jaminan_id_jaminan_foreign` (`id_jaminan`),
  CONSTRAINT `penyimpanan_jaminan_id_jaminan_foreign` FOREIGN KEY (`id_jaminan`) REFERENCES `jaminan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyimpanan_jaminan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyimpanan_jaminan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_jaminan` */



LOCK TABLES `penyimpanan_jaminan` WRITE;



insert  into `penyimpanan_jaminan`(`id`,`id_jaminan`,`id_user`,`id_pengajuan`,`transaksi`,`created_at`,`updated_at`) values (1,2,2,793,'{\"field\":{\"Merk\":\"Warna Mobil\",\"Tahun Beli\":\"Warna Mobil\",\"Harga Jual\":\"2018\"},\"jaminan\":null}','2018-08-05 18:27:59','2018-08-05 18:27:59'),(2,2,2,794,'{\"field\":{\"Merk\":\"2018\",\"Tahun Beli\":\"2018\",\"Harga Jual\":\"Tahun Beli\"},\"jaminan\":null}','2018-08-05 18:29:40','2018-08-05 18:29:40'),(3,1,2,797,'{\"field\":{\"Merk\":\"2\",\"Tahun Beli\":\"2\",\"Harga Jual\":\"2\",\"Nomor Polisi\":\"2012\"},\"jaminan\":{\"saksi1\":\"saksi 1\",\"saksi2\":\"saksi 2\",\"alamat2\":\"alamat 2\",\"ktp2\":\"ktp 2\"}}','2018-08-05 21:09:19','2018-08-06 16:56:29');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_maal` */



DROP TABLE IF EXISTS `penyimpanan_maal`;



CREATE TABLE `penyimpanan_maal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_donatur` int(10) unsigned NOT NULL,
  `id_maal` int(10) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_maal_id_donatur_foreign` (`id_donatur`),
  KEY `penyimpanan_maal_id_maal_foreign` (`id_maal`),
  CONSTRAINT `penyimpanan_maal_id_donatur_foreign` FOREIGN KEY (`id_donatur`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyimpanan_maal_id_maal_foreign` FOREIGN KEY (`id_maal`) REFERENCES `maal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_maal` */



LOCK TABLES `penyimpanan_maal` WRITE;



insert  into `penyimpanan_maal`(`id`,`id_donatur`,`id_maal`,`status`,`transaksi`,`created_at`,`updated_at`) values (11,2,1,'Tabungan','{\"jumlah\":50000,\"dari_rekening\":\"127\",\"untuk_rekening\":179,\"saldo_awal\":0,\"saldo_akhir\":50000}','2018-08-05 16:57:06','2018-08-05 16:57:06'),(12,2,1,'Transfer','{\"jumlah\":1000000,\"dari_rekening\":\"\",\"untuk_rekening\":179,\"saldo_awal\":50000,\"saldo_akhir\":1050000}','2018-08-05 17:10:06','2018-08-05 17:10:06');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_pembiayaan` */



DROP TABLE IF EXISTS `penyimpanan_pembiayaan`;



CREATE TABLE `penyimpanan_pembiayaan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_pembiayaan` int(10) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_pembiayaan_id_user_foreign` (`id_user`),
  KEY `penyimpanan_pembiayaan_id_pembiayaan_foreign` (`id_pembiayaan`),
  CONSTRAINT `penyimpanan_pembiayaan_id_pembiayaan_foreign` FOREIGN KEY (`id_pembiayaan`) REFERENCES `pembiayaan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyimpanan_pembiayaan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_pembiayaan` */



LOCK TABLES `penyimpanan_pembiayaan` WRITE;



insert  into `penyimpanan_pembiayaan`(`id`,`id_user`,`id_pembiayaan`,`status`,`transaksi`,`created_at`,`updated_at`) values (178,2,78,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":10000000,\"tagihan\":1416666.6666666667,\"sisa_angsuran\":10000000,\"sisa_margin\":24000000,\"sisa_pinjaman\":34000000}','2018-07-11 13:29:52','2018-07-11 13:29:52'),(179,2,78,'Angsuran Pembiayaan [Angsuran]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"416666.66666670004\",\"tagihan\":999999.9999999667,\"sisa_angsuran\":9583333.3333333,\"sisa_margin\":24000000,\"sisa_pinjaman\":33583333.3333333,\"tipe_pembayaran\":\"1\"}','2018-07-12 20:04:56','2018-07-12 20:04:56'),(180,2,78,'Angsuran Pembiayaan [Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1000000\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":9583333.3333333,\"sisa_margin\":23000000,\"sisa_pinjaman\":32583333.3333333,\"tipe_pembayaran\":\"0\"}','2018-07-12 20:07:30','2018-07-12 20:07:30'),(181,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":9166666.6666666,\"sisa_margin\":22000000,\"sisa_pinjaman\":31166666.666666597,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:10:42','2018-07-12 20:10:42'),(182,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":3,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":8749999.999999901,\"sisa_margin\":21000000,\"sisa_pinjaman\":29749999.999999896,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:11:32','2018-07-12 20:11:32'),(183,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":4,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":8333333.333333202,\"sisa_margin\":20000000,\"sisa_pinjaman\":28333333.333333194,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:11:41','2018-07-12 20:11:41'),(184,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":5,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":7916666.666666502,\"sisa_margin\":19000000,\"sisa_pinjaman\":26916666.666666493,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:11:50','2018-07-12 20:11:50'),(185,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":6,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":7499999.999999803,\"sisa_margin\":18000000,\"sisa_pinjaman\":25499999.99999979,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:12:13','2018-07-12 20:12:13'),(186,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":7,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":7083333.333333103,\"sisa_margin\":17000000,\"sisa_pinjaman\":24083333.33333309,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:12:22','2018-07-12 20:12:22'),(187,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":8,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":6666666.666666403,\"sisa_margin\":16000000,\"sisa_pinjaman\":22666666.66666639,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:12:31','2018-07-12 20:12:31'),(188,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":9,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":6249999.999999704,\"sisa_margin\":15000000,\"sisa_pinjaman\":21249999.999999687,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:12:50','2018-07-12 20:12:50'),(189,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":10,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":5833333.333333004,\"sisa_margin\":14000000,\"sisa_pinjaman\":19833333.333332986,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:12:58','2018-07-12 20:12:58'),(190,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":11,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":5416666.666666305,\"sisa_margin\":13000000,\"sisa_pinjaman\":18416666.666666284,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:13:09','2018-07-12 20:13:09'),(191,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":12,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":4999999.999999605,\"sisa_margin\":12000000,\"sisa_pinjaman\":16999999.999999583,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:13:17','2018-07-12 20:13:17'),(192,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":13,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":4583333.333332906,\"sisa_margin\":11000000,\"sisa_pinjaman\":15583333.333332883,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:13:24','2018-07-12 20:13:24'),(193,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":14,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":4166666.6666662055,\"sisa_margin\":10000000,\"sisa_pinjaman\":14166666.666666184,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:13:36','2018-07-12 20:13:36'),(194,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":15,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":3749999.9999995055,\"sisa_margin\":9000000,\"sisa_pinjaman\":12749999.999999484,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:13:47','2018-07-12 20:13:47'),(195,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":16,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":3333333.3333328054,\"sisa_margin\":8000000,\"sisa_pinjaman\":11333333.333332784,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:14:14','2018-07-12 20:14:14'),(196,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":17,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":2916666.6666661054,\"sisa_margin\":7000000,\"sisa_pinjaman\":9916666.666666085,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:14:22','2018-07-12 20:14:22'),(197,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":18,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":2499999.9999994054,\"sisa_margin\":6000000,\"sisa_pinjaman\":8499999.999999385,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:14:34','2018-07-12 20:14:34'),(198,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":19,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":2083333.3333327053,\"sisa_margin\":5000000,\"sisa_pinjaman\":7083333.333332686,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:14:43','2018-07-12 20:14:43'),(199,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":20,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":1666666.6666660053,\"sisa_margin\":4000000,\"sisa_pinjaman\":5666666.666665986,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:15:05','2018-07-12 20:15:05'),(200,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":21,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":1249999.9999993052,\"sisa_margin\":3000000,\"sisa_pinjaman\":4249999.999999287,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:15:19','2018-07-12 20:15:19'),(201,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":22,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":833333.3333326052,\"sisa_margin\":2000000,\"sisa_pinjaman\":2833333.3333325866,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:15:37','2018-07-12 20:15:37'),(202,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":23,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":1416666.6666667,\"sisa_angsuran\":416666.66666590516,\"sisa_margin\":1000000,\"sisa_pinjaman\":1416666.6666658865,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:15:46','2018-07-12 20:15:46'),(206,2,80,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":600000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":1000000,\"tagihan\":600000,\"sisa_angsuran\":1000000,\"sisa_margin\":200000,\"sisa_pinjaman\":1200000}','2018-07-12 20:35:22','2018-07-12 20:35:22'),(207,2,80,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":\"600000\",\"tagihan\":600000,\"sisa_angsuran\":500000,\"sisa_margin\":100000,\"sisa_pinjaman\":600000,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:35:32','2018-07-12 20:35:32'),(208,2,80,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":\"600000\",\"tagihan\":0,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:35:43','2018-07-12 20:35:43'),(209,2,81,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":600000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":1000000,\"tagihan\":600000,\"sisa_angsuran\":1000000,\"sisa_margin\":200000,\"sisa_pinjaman\":1200000}','2018-07-12 20:39:03','2018-07-12 20:39:03'),(210,2,81,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":\"600000\",\"tagihan\":600000,\"sisa_angsuran\":500000,\"sisa_margin\":100000,\"sisa_pinjaman\":600000,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:39:16','2018-07-12 20:39:16'),(211,2,81,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":\"600000\",\"tagihan\":0,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"tipe_pembayaran\":\"2\"}','2018-07-12 20:39:25','2018-07-12 20:39:25'),(215,2,78,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":1416666.6666666667,\"angsuran_ke\":24,\"nisbah\":0.1,\"margin\":24000000,\"jumlah\":\"1416666.6666667\",\"tagihan\":0,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"tipe_pembayaran\":\"2\"}','2018-07-12 21:32:52','2018-07-12 21:32:52'),(254,2,97,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":600000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":1000000,\"tagihan\":600000,\"sisa_angsuran\":1000000,\"sisa_margin\":200000,\"sisa_pinjaman\":1200000}','2018-07-18 06:48:43','2018-07-18 06:48:43'),(275,2,100,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":600000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":1000000,\"tagihan\":600000,\"sisa_angsuran\":1000000,\"sisa_margin\":200000,\"sisa_pinjaman\":1200000}','2018-07-19 10:55:31','2018-07-19 10:55:31'),(276,2,100,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":700000,\"tagihan\":0,\"sisa_angsuran\":\"500000\",\"sisa_margin\":100000,\"sisa_pinjaman\":600000,\"margin_bulanan\":250000}','2018-07-19 21:55:47','2018-07-19 21:55:47'),(277,2,97,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":450000,\"tagihan\":150000,\"sisa_angsuran\":600000,\"sisa_margin\":150000,\"sisa_pinjaman\":750000}','2018-07-19 22:50:11','2018-07-19 22:50:11'),(278,2,97,'Angsuran Pembiayaan [Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":50000,\"tagihan\":100000,\"sisa_angsuran\":600000,\"sisa_margin\":100000,\"sisa_pinjaman\":700000}','2018-07-19 22:53:03','2018-07-19 22:53:03'),(279,2,97,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":50000,\"tagihan\":50000,\"sisa_angsuran\":550000,\"sisa_margin\":100000,\"sisa_pinjaman\":650000}','2018-07-19 22:54:12','2018-07-19 22:54:12'),(280,2,97,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":50000,\"tagihan\":0,\"sisa_angsuran\":500000,\"sisa_margin\":100000,\"sisa_pinjaman\":600000}','2018-07-19 22:58:05','2018-07-19 22:58:05'),(281,2,97,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":600000,\"tagihan\":0,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0}','2018-07-19 23:00:09','2018-07-19 23:00:09'),(282,2,100,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":400000,\"tagihan\":350000,\"sisa_angsuran\":100000,\"sisa_margin\":250000,\"sisa_pinjaman\":350000,\"margin_bulanan\":250000}','2018-07-19 23:41:21','2018-07-19 23:41:21'),(283,2,100,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":450000,\"tagihan\":50000,\"sisa_angsuran\":50000,\"sisa_margin\":0,\"sisa_pinjaman\":50000,\"margin_bulanan\":250000}','2018-07-19 23:43:39','2018-07-19 23:43:39'),(284,2,100,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":50000,\"tagihan\":0,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"margin_bulanan\":0}','2018-07-20 00:01:48','2018-07-20 00:01:48'),(285,2,101,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":600000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":1000000,\"tagihan\":600000,\"sisa_angsuran\":1000000,\"sisa_margin\":200000,\"sisa_pinjaman\":1200000}','2018-07-20 00:04:19','2018-07-20 00:04:19'),(286,1,101,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":600000,\"tagihan\":0,\"sisa_angsuran\":500000,\"sisa_margin\":100000,\"sisa_pinjaman\":600000}','2018-07-20 00:19:04','2018-07-20 00:19:04'),(287,1,101,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":600000,\"tagihan\":0,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0}','2018-07-25 05:14:26','2018-07-25 05:14:26'),(288,2,102,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":600000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":1000000,\"tagihan\":600000,\"sisa_angsuran\":1000000,\"sisa_margin\":200000,\"sisa_pinjaman\":1200000}','2018-07-25 08:56:41','2018-07-25 08:56:41'),(289,1,102,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":500000,\"tagihan\":200000,\"sisa_angsuran\":700000,\"sisa_margin\":100000,\"sisa_pinjaman\":800000,\"margin_bulanan\":300000}','2018-07-25 08:57:06','2018-07-25 08:57:06'),(290,2,102,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":200,\"tagihan\":199800,\"sisa_angsuran\":699800,\"sisa_margin\":100000,\"sisa_pinjaman\":799800,\"margin_bulanan\":0}','2018-08-05 20:34:01','2018-08-05 20:34:01'),(291,2,102,'Angsuran Pembiayaan [Pokok]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":199800,\"tagihan\":0,\"sisa_angsuran\":500000,\"sisa_margin\":100000,\"sisa_pinjaman\":600000,\"margin_bulanan\":0}','2018-08-05 21:10:24','2018-08-05 21:10:24'),(292,2,103,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":300000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":500000,\"jumlah\":1000000,\"tagihan\":300000,\"sisa_angsuran\":1000000,\"sisa_margin\":500000,\"sisa_pinjaman\":1500000}','2018-08-06 16:01:45','2018-08-06 16:01:45'),(293,2,104,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":300000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":500000,\"jumlah\":1000000,\"tagihan\":300000,\"sisa_angsuran\":1000000,\"sisa_margin\":500000,\"sisa_pinjaman\":1500000}','2018-08-06 16:01:58','2018-08-06 16:01:58'),(301,2,110,'Pencairan Pembiayaan','{\"teller\":1,\"dari_rekening\":\"13\",\"untuk_rekening\":\"Tunai\",\"angsuran_pokok\":11000000,\"angsuran_ke\":0,\"nisbah\":0.1,\"margin\":1000000,\"jumlah\":10000000,\"tagihan\":11000000,\"sisa_angsuran\":10000000,\"sisa_margin\":1000000,\"sisa_pinjaman\":11000000}','2018-08-06 16:56:29','2018-08-06 16:56:29'),(302,2,102,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":600000,\"angsuran_ke\":2,\"nisbah\":0.1,\"margin\":200000,\"jumlah\":900000,\"tagihan\":0,\"sisa_angsuran\":0,\"sisa_margin\":0,\"sisa_pinjaman\":0,\"margin_bulanan\":500000}','2018-08-14 14:04:31','2018-08-14 14:04:31'),(303,2,104,'Angsuran Pembiayaan [Pokok+Margin]','{\"teller\":1,\"dari_rekening\":\"TUNAI\",\"untuk_rekening\":\"13\",\"angsuran_pokok\":300000,\"angsuran_ke\":1,\"nisbah\":0.1,\"margin\":500000,\"jumlah\":2200000,\"tagihan\":0,\"sisa_angsuran\":800000,\"sisa_margin\":400000,\"sisa_pinjaman\":1200000,\"margin_bulanan\":200000}','2018-08-14 14:04:55','2018-08-14 14:04:55');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_rekening` */



DROP TABLE IF EXISTS `penyimpanan_rekening`;



CREATE TABLE `penyimpanan_rekening` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_rekening` int(10) unsigned NOT NULL,
  `periode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_rekening_id_rekening_foreign` (`id_rekening`),
  CONSTRAINT `penyimpanan_rekening_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=547 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_rekening` */



LOCK TABLES `penyimpanan_rekening` WRITE;



insert  into `penyimpanan_rekening`(`id`,`id_rekening`,`periode`,`saldo`,`created_at`,`updated_at`) values (290,1,'201807','500000','2018-07-30 11:01:16','2018-07-30 11:01:16'),(291,11,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(292,12,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(293,13,'201807','60094000','2018-07-30 11:01:16','2018-07-30 11:01:16'),(294,14,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(295,70,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(296,74,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(297,77,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(298,78,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(299,79,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(300,80,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(301,81,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(302,62,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(303,82,'201807','','2018-07-30 11:01:16','2018-07-30 11:01:16'),(304,86,'201807','25110000','2018-07-30 11:01:16','2018-07-30 11:01:16'),(305,87,'201807','450000','2018-07-30 11:01:17','2018-07-30 11:01:17'),(306,84,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(307,88,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(308,63,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(309,89,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(310,90,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(311,91,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(312,92,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(313,64,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(314,93,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(315,94,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(316,65,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(317,95,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(318,99,'201807','800000','2018-07-30 11:01:17','2018-07-30 11:01:17'),(319,96,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(320,100,'201807','17916000','2018-07-30 11:01:17','2018-07-30 11:01:17'),(321,101,'201807','-400000','2018-07-30 11:01:17','2018-07-30 11:01:17'),(322,97,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(323,102,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(324,66,'201807','','2018-07-30 11:01:17','2018-07-30 11:01:17'),(325,98,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(326,103,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(327,67,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(328,104,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(329,68,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(330,69,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(331,2,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(332,3,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(333,4,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(334,5,'201807','7770000','2018-07-30 11:01:18','2018-07-30 15:30:44'),(335,6,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(336,7,'201807','74999.99999999907','2018-07-30 11:01:18','2018-07-30 15:30:44'),(337,8,'201807','0','2018-07-30 11:01:18','2018-07-30 12:33:18'),(338,9,'201807','0','2018-07-30 11:01:18','2018-07-30 15:30:44'),(339,10,'201807','0','2018-07-30 11:01:18','2018-07-30 12:33:18'),(340,105,'201807','','2018-07-30 11:01:18','2018-07-30 11:01:18'),(341,106,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(342,44,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(343,45,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(344,46,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(345,47,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(346,48,'201807','0','2018-07-30 11:01:19','2018-07-30 11:01:19'),(347,49,'201807','6000000','2018-07-30 11:01:19','2018-07-30 11:01:19'),(348,169,'201807','7000000','2018-07-30 11:01:19','2018-07-30 11:01:19'),(349,170,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(350,171,'201807','11000000','2018-07-30 11:01:19','2018-07-30 11:01:19'),(351,50,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(352,107,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(353,109,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(354,110,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(355,108,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(356,54,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(357,55,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(358,57,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(359,58,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(360,56,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(361,59,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(362,60,'201807','','2018-07-30 11:01:19','2018-07-30 11:01:19'),(363,61,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(364,111,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(365,112,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(366,113,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(367,114,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(368,174,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(369,175,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(370,123,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(371,115,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(372,116,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(373,117,'201807','225000','2018-07-30 11:01:20','2018-07-30 11:01:20'),(374,118,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(375,119,'201807','200000','2018-07-30 11:01:20','2018-07-30 11:01:20'),(376,120,'201807','','2018-07-30 11:01:20','2018-07-30 11:01:20'),(377,121,'201807','0','2018-07-30 11:01:20','2018-07-30 15:30:43'),(378,122,'201807','47200000','2018-07-30 11:01:20','2018-07-30 12:53:42'),(379,176,'201807','25000000','2018-07-30 11:01:20','2018-07-30 15:30:45'),(380,165,'201807','0','2018-07-30 11:05:04','2018-07-30 12:53:41'),(381,166,'201807','0','2018-07-30 11:05:04','2018-07-30 12:53:41'),(382,139,'201807','0','2018-07-30 11:05:05','2018-07-30 12:53:41'),(383,141,'201807','0','2018-07-30 11:05:05','2018-07-30 11:05:05'),(384,125,'201807','','2018-07-30 11:19:22','2018-07-30 11:19:22'),(385,127,'201807','','2018-07-30 11:19:22','2018-07-30 11:19:22'),(386,128,'201807','','2018-07-30 11:19:22','2018-07-30 11:19:22'),(387,129,'201807','4100000','2018-07-30 11:19:22','2018-07-30 11:19:22'),(388,130,'201807','43100000','2018-07-30 11:19:22','2018-07-30 11:19:22'),(389,131,'201807','','2018-07-30 11:19:22','2018-07-30 11:19:22'),(390,132,'201807','','2018-07-30 11:19:22','2018-07-30 11:19:22'),(391,133,'201807','','2018-07-30 11:19:22','2018-07-30 11:19:22'),(392,172,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(393,173,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(394,178,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(395,126,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(396,134,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(397,138,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(398,140,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(399,142,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(400,143,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(401,144,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(402,145,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(403,146,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(404,135,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(405,147,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(406,148,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(407,149,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(408,136,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(409,150,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(410,151,'201807','','2018-07-30 11:19:23','2018-07-30 11:19:23'),(411,152,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(412,137,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(413,153,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(414,154,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(415,155,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(416,156,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(417,157,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(418,158,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(419,159,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(420,160,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(421,161,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(422,162,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(423,163,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(424,164,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(425,167,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(426,168,'201807','','2018-07-30 11:19:24','2018-07-30 11:19:24'),(427,1,'201808','500000','2018-08-02 07:36:08','2018-08-02 07:36:08'),(428,11,'201808','','2018-08-02 07:36:08','2018-08-02 07:36:08'),(429,12,'201808','','2018-08-02 07:36:08','2018-08-02 07:36:08'),(430,13,'201808','53394000','2018-08-02 07:36:09','2018-08-14 14:04:55'),(431,14,'201808','','2018-08-02 07:36:09','2018-08-02 07:36:09'),(432,70,'201808','','2018-08-02 07:36:09','2018-08-02 07:36:09'),(433,74,'201808','','2018-08-02 07:36:10','2018-08-02 07:36:10'),(434,77,'201808','','2018-08-02 07:36:10','2018-08-02 07:36:10'),(435,78,'201808','','2018-08-02 07:36:10','2018-08-02 07:36:10'),(436,79,'201808','','2018-08-02 07:36:10','2018-08-02 07:36:10'),(437,80,'201808','','2018-08-02 07:36:10','2018-08-02 07:36:10'),(438,81,'201808','','2018-08-02 07:36:11','2018-08-02 07:36:11'),(439,62,'201808','','2018-08-02 07:36:11','2018-08-02 07:36:11'),(440,82,'201808','','2018-08-02 07:36:11','2018-08-02 07:36:11'),(441,86,'201808','26110000','2018-08-02 07:36:11','2018-08-05 17:10:06'),(442,87,'201808','5450000','2018-08-02 07:36:12','2018-08-05 17:09:24'),(443,84,'201808','','2018-08-02 07:36:12','2018-08-02 07:36:12'),(444,88,'201808','','2018-08-02 07:36:12','2018-08-02 07:36:12'),(445,63,'201808','','2018-08-02 07:36:14','2018-08-02 07:36:14'),(446,89,'201808','','2018-08-02 07:36:15','2018-08-02 07:36:15'),(447,90,'201808','','2018-08-02 07:36:15','2018-08-02 07:36:15'),(448,91,'201808','','2018-08-02 07:36:15','2018-08-02 07:36:15'),(449,92,'201808','','2018-08-02 07:36:15','2018-08-02 07:36:15'),(450,64,'201808','','2018-08-02 07:36:15','2018-08-02 07:36:15'),(451,93,'201808','','2018-08-02 07:36:15','2018-08-02 07:36:15'),(452,94,'201808','','2018-08-02 07:36:16','2018-08-02 07:36:16'),(453,65,'201808','','2018-08-02 07:36:16','2018-08-02 07:36:16'),(454,95,'201808','','2018-08-02 07:36:16','2018-08-02 07:36:16'),(455,99,'201808','-100000','2018-08-02 07:36:17','2018-08-14 14:04:55'),(456,96,'201808','','2018-08-02 07:36:17','2018-08-02 07:36:17'),(457,100,'201808','28916000','2018-08-02 07:36:17','2018-08-06 16:56:30'),(458,101,'201808','-1400000','2018-08-02 07:36:17','2018-08-06 16:56:30'),(459,97,'201808','','2018-08-02 07:36:17','2018-08-02 07:36:17'),(460,102,'201808','','2018-08-02 07:36:18','2018-08-02 07:36:18'),(461,66,'201808','','2018-08-02 07:36:18','2018-08-02 07:36:18'),(462,98,'201808','','2018-08-02 07:36:18','2018-08-02 07:36:18'),(463,103,'201808','','2018-08-02 07:36:18','2018-08-02 07:36:18'),(464,67,'201808','','2018-08-02 07:36:19','2018-08-02 07:36:19'),(465,104,'201808','','2018-08-02 07:36:19','2018-08-02 07:36:19'),(466,68,'201808','','2018-08-02 07:36:20','2018-08-02 07:36:20'),(467,69,'201808','','2018-08-02 07:36:20','2018-08-02 07:36:20'),(468,5,'201808','7620000','2018-08-02 07:36:41','2018-08-05 16:57:46'),(469,7,'201808','74999.99999999907','2018-08-02 07:36:41','2018-08-02 07:36:41'),(470,8,'201808','0','2018-08-02 07:36:41','2018-08-02 07:36:41'),(471,9,'201808','0','2018-08-02 07:36:42','2018-08-02 07:36:42'),(472,10,'201808','0','2018-08-02 07:36:43','2018-08-02 07:36:43'),(473,105,'201808','','2018-08-02 07:36:43','2018-08-02 07:36:43'),(474,106,'201808','','2018-08-02 07:36:44','2018-08-02 07:36:44'),(475,45,'201808','','2018-08-02 07:36:44','2018-08-02 07:36:44'),(476,48,'201808','0','2018-08-02 07:36:44','2018-08-02 07:36:44'),(477,49,'201808','6000000','2018-08-02 07:36:44','2018-08-02 07:36:44'),(478,169,'201808','7000000','2018-08-02 07:36:44','2018-08-02 07:36:44'),(479,170,'201808','','2018-08-02 07:36:44','2018-08-02 07:36:44'),(480,171,'201808','11000000','2018-08-02 07:36:44','2018-08-02 07:36:44'),(481,109,'201808','','2018-08-02 07:36:45','2018-08-02 07:36:45'),(482,110,'201808','','2018-08-02 07:36:45','2018-08-02 07:36:45'),(483,108,'201808','','2018-08-02 07:36:45','2018-08-02 07:36:45'),(484,57,'201808','','2018-08-02 07:36:45','2018-08-02 07:36:45'),(485,58,'201808','','2018-08-02 07:36:45','2018-08-02 07:36:45'),(486,59,'201808','','2018-08-02 07:36:45','2018-08-02 07:36:45'),(487,60,'201808','','2018-08-02 07:36:45','2018-08-02 07:36:45'),(488,61,'201808','','2018-08-02 07:36:45','2018-08-02 07:36:45'),(489,112,'201808','','2018-08-02 07:36:46','2018-08-02 07:36:46'),(490,113,'201808','','2018-08-02 07:36:47','2018-08-02 07:36:47'),(491,175,'201808','','2018-08-02 07:36:47','2018-08-02 07:36:47'),(492,117,'201808','225000','2018-08-02 07:36:47','2018-08-02 07:36:47'),(493,118,'201808','5100000','2018-08-02 07:36:47','2018-08-05 17:09:24'),(494,119,'201808','200000','2018-08-02 07:36:47','2018-08-02 07:36:47'),(495,120,'201808','','2018-08-02 07:36:48','2018-08-02 07:36:48'),(496,121,'201808','0','2018-08-02 07:36:48','2018-08-02 07:36:48'),(497,122,'201808','49600000','2018-08-02 07:36:48','2018-08-14 14:04:55'),(498,176,'201808','25000000','2018-08-02 07:36:48','2018-08-02 07:36:48'),(499,179,'201808','1050000','2018-08-05 16:57:07','2018-08-05 17:10:06'),(500,125,'201808','','2018-08-14 14:03:49','2018-08-14 14:03:49'),(501,127,'201808','','2018-08-14 14:03:49','2018-08-14 14:03:49'),(502,128,'201808','','2018-08-14 14:03:49','2018-08-14 14:03:49'),(503,129,'201808','6500000','2018-08-14 14:03:49','2018-08-14 14:04:55'),(504,130,'201808','43100000','2018-08-14 14:03:49','2018-08-14 14:03:49'),(505,131,'201808','','2018-08-14 14:03:49','2018-08-14 14:03:49'),(506,132,'201808','','2018-08-14 14:03:49','2018-08-14 14:03:49'),(507,133,'201808','','2018-08-14 14:03:49','2018-08-14 14:03:49'),(508,172,'201808','','2018-08-14 14:03:49','2018-08-14 14:03:49'),(509,173,'201808','','2018-08-14 14:03:49','2018-08-14 14:03:49'),(510,178,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(511,126,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(512,134,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(513,138,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(514,139,'201808','0','2018-08-14 14:03:50','2018-08-14 14:03:50'),(515,140,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(516,141,'201808','0','2018-08-14 14:03:50','2018-08-14 14:03:50'),(517,142,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(518,143,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(519,144,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(520,145,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(521,146,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(522,135,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(523,147,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(524,148,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(525,149,'201808','','2018-08-14 14:03:50','2018-08-14 14:03:50'),(526,136,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(527,150,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(528,151,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(529,152,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(530,137,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(531,153,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(532,154,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(533,155,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(534,156,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(535,157,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(536,158,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(537,159,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(538,160,'201808','','2018-08-14 14:03:51','2018-08-14 14:03:51'),(539,161,'201808','','2018-08-14 14:03:52','2018-08-14 14:03:52'),(540,162,'201808','','2018-08-14 14:03:52','2018-08-14 14:03:52'),(541,163,'201808','','2018-08-14 14:03:52','2018-08-14 14:03:52'),(542,164,'201808','','2018-08-14 14:03:52','2018-08-14 14:03:52'),(543,165,'201808','0','2018-08-14 14:03:52','2018-08-14 14:03:52'),(544,166,'201808','0','2018-08-14 14:03:52','2018-08-14 14:03:52'),(545,167,'201808','','2018-08-14 14:03:52','2018-08-14 14:03:52'),(546,168,'201808','','2018-08-14 14:03:52','2018-08-14 14:03:52');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_shu` */



DROP TABLE IF EXISTS `penyimpanan_shu`;



CREATE TABLE `penyimpanan_shu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shu` int(10) unsigned NOT NULL,
  `periode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_shu_id_shu_foreign` (`id_shu`),
  CONSTRAINT `penyimpanan_shu_id_shu_foreign` FOREIGN KEY (`id_shu`) REFERENCES `shu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_shu` */



LOCK TABLES `penyimpanan_shu` WRITE;



UNLOCK TABLES;



/*Table structure for table `penyimpanan_tabungan` */



DROP TABLE IF EXISTS `penyimpanan_tabungan`;



CREATE TABLE `penyimpanan_tabungan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_tabungan` int(10) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_tabungan_id_user_foreign` (`id_user`),
  KEY `penyimpanan_tabungan_id_tabungan_foreign` (`id_tabungan`),
  CONSTRAINT `penyimpanan_tabungan_id_tabungan_foreign` FOREIGN KEY (`id_tabungan`) REFERENCES `tabungan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyimpanan_tabungan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10819 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_tabungan` */



LOCK TABLES `penyimpanan_tabungan` WRITE;



insert  into `penyimpanan_tabungan`(`id`,`id_user`,`id_tabungan`,`status`,`transaksi`,`created_at`,`updated_at`) values (169,2,115,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:32:22','2018-07-02 16:32:22'),(171,2,117,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:34:41','2018-07-02 16:34:41'),(172,2,118,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:35:08','2018-07-02 16:35:08'),(175,2,121,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:40:28','2018-07-02 16:40:28'),(176,2,122,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:51:39','2018-07-02 16:51:39'),(177,2,123,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:52:12','2018-07-02 16:52:12'),(178,2,124,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:53:50','2018-07-02 16:53:50'),(179,2,125,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:54:21','2018-07-02 16:54:21'),(180,2,126,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:54:38','2018-07-02 16:54:38'),(181,2,127,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-02 16:55:22','2018-07-02 16:55:22'),(182,2,115,'Kredit','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"jumlah\":\"50000\",\"saldo_awal\":\"10000\",\"saldo_akhir\":60000,\"untuk_rekening\":\"13\"}','2018-07-02 19:20:57','2018-07-02 19:20:57'),(183,2,115,'Debit','{\"teller\":1,\"dari_rekening\":\"13\",\"jumlah\":\"20000\",\"saldo_awal\":60000,\"saldo_akhir\":40000,\"untuk_rekening\":\"TUNAI\"}','2018-07-02 19:22:03','2018-07-02 19:22:03'),(184,2,115,'Kredit','{\"teller\":1,\"dari_rekening\":\"[12] bgx\",\"jumlah\":\"500000\",\"saldo_awal\":40000,\"saldo_akhir\":540000,\"untuk_rekening\":\"87\"}','2018-07-02 19:52:48','2018-07-02 19:52:48'),(185,2,115,'Debit','{\"teller\":1,\"dari_rekening\":\"87\",\"jumlah\":\"50000\",\"saldo_awal\":540000,\"saldo_akhir\":490000,\"untuk_rekening\":\"f[321]\"}','2018-07-02 19:54:27','2018-07-02 19:54:27'),(186,2,127,'Kredit','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"jumlah\":\"5000000\",\"saldo_awal\":\"10000\",\"saldo_akhir\":5010000,\"untuk_rekening\":\"13\"}','2018-07-12 10:28:14','2018-07-12 10:28:14'),(187,3,128,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-12 10:29:33','2018-07-12 10:29:33'),(188,3,128,'Kredit','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"jumlah\":\"1000000\",\"saldo_awal\":\"10000\",\"saldo_akhir\":1010000,\"untuk_rekening\":\"13\"}','2018-07-12 10:29:53','2018-07-12 10:29:53'),(189,3,128,'Kredit','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"jumlah\":\"100000\",\"saldo_awal\":1010000,\"saldo_akhir\":1110000,\"untuk_rekening\":\"13\"}','2018-07-12 10:31:08','2018-07-12 10:31:08'),(201,3,129,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"25000\",\"saldo_awal\":0,\"saldo_akhir\":\"25000\"}','2018-07-12 10:46:20','2018-07-12 10:46:20'),(9829,14,130,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-14 11:16:47','2018-07-14 11:16:47'),(9830,14,130,'Kredit','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"jumlah\":\"1000000\",\"saldo_awal\":\"10000\",\"saldo_akhir\":1010000,\"untuk_rekening\":\"13\"}','2018-07-14 11:21:30','2018-07-14 11:21:30'),(9833,16,137,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-17 09:32:28','2018-07-17 09:32:28'),(9834,15,138,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"10000\",\"saldo_awal\":0,\"saldo_akhir\":\"10000\"}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(9925,2,139,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"25000\",\"saldo_awal\":0,\"saldo_akhir\":\"25000\"}','2018-07-24 16:24:36','2018-07-24 16:24:36'),(10535,9,141,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"0\",\"saldo_awal\":0,\"saldo_akhir\":\"0\"}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(10536,10,142,'Setoran Awal','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":\"13\",\"jumlah\":\"25000\",\"saldo_awal\":0,\"saldo_akhir\":\"25000\"}','2018-07-26 07:21:57','2018-07-26 07:21:57'),(10817,2,127,'Donasi Maal','{\"teller\":2,\"dari_rekening\":\"Tabungan\",\"untuk_rekening\":179,\"jumlah\":\"50000\",\"saldo_awal\":5010000,\"saldo_akhir\":4960000}','2018-08-05 16:57:06','2018-08-05 16:57:06'),(10818,2,127,'Donasi Waqaf','{\"teller\":2,\"dari_rekening\":\"Tabungan\",\"untuk_rekening\":118,\"jumlah\":\"100000\",\"saldo_awal\":4960000,\"saldo_akhir\":4860000}','2018-08-05 16:57:45','2018-08-05 16:57:45');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_users` */



DROP TABLE IF EXISTS `penyimpanan_users`;



CREATE TABLE `penyimpanan_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `periode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_users_id_user_foreign` (`id_user`),
  CONSTRAINT `penyimpanan_users_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_users` */



LOCK TABLES `penyimpanan_users` WRITE;



insert  into `penyimpanan_users`(`id`,`id_user`,`periode`,`transaksi`,`created_at`,`updated_at`) values (1,2,'2017','{\"pokok\":\"25000\",\"wajib\":\"10000\"}','2018-07-30 13:37:11','2018-07-30 13:37:11'),(2,3,'2017','{\"pokok\":\"25000\",\"wajib\":50000}','2018-07-30 13:37:11','2018-07-30 13:37:11'),(3,9,'2017','{\"pokok\":\"25000\",\"wajib\":\"40000\"}','2018-07-30 13:37:11','2018-07-30 13:37:11'),(4,10,'2017','{\"pokok\":\"25000\",\"wajib\":70000}','2018-07-30 13:37:11','2018-07-30 13:37:11'),(5,14,'2017','{\"pokok\":\"25000\",\"wajib\":\"10000\"}','2018-07-30 13:37:11','2018-07-30 13:37:11'),(6,15,'2017','{\"pokok\":\"25000\",\"wajib\":\"10000\"}','2018-07-30 13:37:11','2018-07-30 13:37:11'),(7,16,'2017','{\"pokok\":\"25000\",\"wajib\":\"10000\"}','2018-07-30 13:37:12','2018-07-30 13:37:12'),(8,2,'2018','{\"wajib\":\"10000\",\"pokok\":\"25000\",\"margin\":2400000}','2018-08-14 14:04:31','2018-08-14 14:04:55');



UNLOCK TABLES;



/*Table structure for table `penyimpanan_wajib_pokok` */



DROP TABLE IF EXISTS `penyimpanan_wajib_pokok`;



CREATE TABLE `penyimpanan_wajib_pokok` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_rekening` int(10) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyimpanan_wajib_pokok_id_user_foreign` (`id_user`),
  KEY `penyimpanan_wajib_pokok_id_rekening_foreign` (`id_rekening`),
  CONSTRAINT `penyimpanan_wajib_pokok_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyimpanan_wajib_pokok_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `penyimpanan_wajib_pokok` */



LOCK TABLES `penyimpanan_wajib_pokok` WRITE;



insert  into `penyimpanan_wajib_pokok`(`id`,`id_user`,`id_rekening`,`status`,`transaksi`,`created_at`,`updated_at`) values (1,16,117,'Simpanan Pokok','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":117,\"jumlah\":25000,\"saldo_awal\":0,\"saldo_akhir\":25000}','2018-07-17 09:32:27','2018-07-17 09:32:27'),(2,16,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":119,\"jumlah\":10000,\"saldo_awal\":0,\"saldo_akhir\":10000}','2018-07-17 09:32:27','2018-07-17 09:32:27'),(3,2,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"untuk_rekening\":119,\"jumlah\":10000,\"saldo_awal\":\"30000\",\"saldo_akhir\":40000}','2018-07-17 14:12:19','2018-07-17 14:12:19'),(4,3,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"untuk_rekening\":119,\"jumlah\":10000,\"saldo_awal\":\"10000\",\"saldo_akhir\":20000}','2018-07-17 14:13:44','2018-07-17 14:13:44'),(5,3,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"untuk_rekening\":119,\"jumlah\":10000,\"saldo_awal\":\"20000\",\"saldo_akhir\":30000}','2018-07-17 14:15:32','2018-07-17 14:15:32'),(6,9,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"untuk_rekening\":119,\"jumlah\":10000,\"saldo_awal\":\"10000\",\"saldo_akhir\":20000}','2018-07-17 14:20:02','2018-07-17 14:20:02'),(7,3,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"untuk_rekening\":119,\"jumlah\":10000,\"saldo_awal\":\"30000\",\"saldo_akhir\":40000}','2018-07-17 14:31:36','2018-07-17 14:31:36'),(8,3,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"untuk_rekening\":119,\"jumlah\":1,\"saldo_awal\":40000,\"saldo_akhir\":40001}','2018-07-17 19:25:30','2018-07-17 19:25:30'),(9,3,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"untuk_rekening\":119,\"jumlah\":9999,\"saldo_awal\":40001,\"saldo_akhir\":50000}','2018-07-17 19:31:15','2018-07-17 19:31:15'),(10,15,117,'Simpanan Pokok','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":117,\"jumlah\":25000,\"saldo_awal\":0,\"saldo_akhir\":25000}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(11,15,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":119,\"jumlah\":10000,\"saldo_awal\":0,\"saldo_akhir\":10000}','2018-07-17 19:55:27','2018-07-17 19:55:27'),(12,9,117,'Simpanan Pokok','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":117,\"jumlah\":25000,\"saldo_awal\":0,\"saldo_akhir\":25000}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(13,9,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":119,\"jumlah\":40000,\"saldo_awal\":0,\"saldo_akhir\":40000}','2018-07-26 07:18:45','2018-07-26 07:18:45'),(14,10,117,'Simpanan Pokok','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":117,\"jumlah\":25000,\"saldo_awal\":0,\"saldo_akhir\":25000}','2018-07-26 07:21:56','2018-07-26 07:21:56'),(15,10,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"\",\"untuk_rekening\":119,\"jumlah\":30000,\"saldo_awal\":0,\"saldo_akhir\":30000}','2018-07-26 07:21:56','2018-07-26 07:21:56'),(16,10,119,'Simpanan Wajib','{\"teller\":1,\"dari_rekening\":\"Tunai\",\"untuk_rekening\":119,\"jumlah\":10000,\"saldo_awal\":\"60000\",\"saldo_akhir\":70000}','2018-07-26 14:06:07','2018-07-26 14:06:07');



UNLOCK TABLES;



/*Table structure for table `rekening` */



DROP TABLE IF EXISTS `rekening`;



CREATE TABLE `rekening` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_rekening` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_induk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_rekening` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_rekening` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `katagori_rekening` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rekening_id_rekening_unique` (`id_rekening`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `rekening` */



LOCK TABLES `rekening` WRITE;



insert  into `rekening`(`id`,`id_rekening`,`id_induk`,`nama_rekening`,`tipe_rekening`,`katagori_rekening`,`detail`,`created_at`,`updated_at`) values (1,'1','master','AKTIVA','master','','','2018-05-07 10:06:59','2018-06-10 09:05:16'),(2,'2','master','HUTANG','master','','','2018-05-07 10:07:14','2018-05-07 10:15:49'),(3,'2.1','2','SIMPANAN SYARIAH','induk','','','2018-05-07 10:17:42','2018-05-07 10:17:42'),(4,'2.1.1','2.1','SIMPANAN MUDHAROBAH UMUM','induk','','','2018-05-07 10:18:20','2018-05-07 10:18:20'),(5,'2.1.1.1','2.1.1','SIMPANAN MUDHAROBAH UMUM','detail','TABUNGAN','{\"nisbah_anggota\":\"10\",\"nisbah_bank\":90,\"rek_margin\":\"5.1.1.1\",\"rek_pendapatan\":\"5.1.1.1\",\"nasabah_wajib_pajak\":\"0\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"25000\",\"setoran_awal\":\"10000\",\"setoran_min\":\"10000\",\"saldo_min_margin\":\"25000\",\"adm_tutup_tab\":\"00\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"5000\",\"adm_ganti_buku\":\"5000\"}','2018-05-07 10:18:43','2018-07-04 07:58:25'),(6,'2.1.2','2.1','SIMPANAN MUDHAROBAH BERJANGKA','induk','','','2018-05-07 10:19:10','2018-05-07 10:19:10'),(7,'2.1.2.1','2.1.2','SIMPANAN TARBIYAH / PENDIDIKAN','detail','TABUNGAN','{\"nisbah_anggota\":\"0\",\"nisbah_bank\":100,\"rek_margin\":\"5.1.2.1\",\"rek_pendapatan\":\"4.1.3.2\",\"nasabah_wajib_pajak\":\"1\",\"nasabah_bayar_zis\":\"1\",\"saldo_min\":\"0\",\"setoran_awal\":\"25000\",\"setoran_min\":\"0\",\"saldo_min_margin\":\"0\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}','2018-05-07 10:21:24','2018-07-24 19:40:41'),(8,'2.1.2.2','2.1.2','SIMPANAN IDUL FITRI','detail','TABUNGAN','{\"nisbah_anggota\":\"20\",\"nisbah_bank\":80,\"rek_margin\":\"5.1.2.2\",\"rek_pendapatan\":\"4.1.3.2\",\"nasabah_wajib_pajak\":\"1\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"25000\",\"setoran_awal\":\"20000\",\"setoran_min\":\"10000\",\"saldo_min_margin\":\"25000\",\"adm_tutup_tab\":\"5000\",\"pemeliharaan\":\"5000\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"2000\",\"adm_ganti_buku\":\"0\"}','2018-05-07 10:21:51','2018-07-24 19:41:00'),(9,'2.1.2.3','2.1.2','SIMPANAN IDUL ADHA','detail','TABUNGAN','{\"nisbah_anggota\":\"0\",\"nisbah_bank\":100,\"rek_margin\":\"5.1.2.3\",\"rek_pendapatan\":\"4.1.3.2\",\"nasabah_wajib_pajak\":\"1\",\"nasabah_bayar_zis\":\"1\",\"saldo_min\":\"0\",\"setoran_awal\":\"0\",\"setoran_min\":\"0\",\"saldo_min_margin\":\"0\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}','2018-05-07 10:22:15','2018-07-24 19:41:21'),(10,'2.1.2.4','2.1.2','SIMPANAN WALIMAH','detail','TABUNGAN','{\"nisbah_anggota\":\"30\",\"nisbah_bank\":70,\"rek_margin\":\"5.1.2.4\",\"rek_pendapatan\":\"4.1.3.2\",\"nasabah_wajib_pajak\":\"1\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"0\",\"setoran_awal\":\"0\",\"setoran_min\":\"0\",\"saldo_min_margin\":\"0\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}','2018-05-07 10:22:45','2018-07-24 19:41:46'),(11,'1.1','1','KAS','induk','','','2018-05-15 05:40:53','2018-05-15 05:40:53'),(12,'1.1.1','1.1','KAS','induk','','','2018-05-15 05:41:10','2018-05-15 05:41:10'),(13,'1.1.1.1','1.1.1','KAS TELLER 1','detail','TELLER','','2018-05-15 05:41:25','2018-05-25 16:25:54'),(14,'1.1.1.2','1.1.1','KAS TELLER 2','detail','TELLER','','2018-05-15 05:41:44','2018-05-25 16:26:01'),(44,'2.1.3','2.1','SIMPANAN WADIAH','induk','','','2018-05-15 06:11:50','2018-05-15 06:11:50'),(45,'2.1.3.1','2.1.3','SIMPANAN WADIAH','detail','','','2018-05-15 06:12:14','2018-05-15 06:12:14'),(46,'2.2','2','MUDHARABAH SYARIAH','induk','','','2018-05-15 06:13:35','2018-05-15 06:13:35'),(47,'2.2.1','2.2','MUDHARABAH BERJANGKA','induk','','','2018-05-15 06:14:00','2018-05-15 06:14:00'),(48,'2.2.1.1','2.2.1','MUDHARABAH 1 BULAN','detail','DEPOSITO','{\"rek_margin\":\"5.5.1.1.1\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"1\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"0\"}','2018-05-15 06:14:27','2018-05-22 18:21:44'),(49,'2.2.1.2','2.2.1','MUDHARABAH  3 BULAN','detail','DEPOSITO','{\"rek_margin\":\"5.5.1.1.2\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"3\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"0\"}','2018-05-15 06:14:51','2018-05-22 18:24:16'),(50,'2.3','2','ANTAR KOPERASI PASIVA','induk','','','2018-05-15 06:15:21','2018-05-15 06:15:21'),(54,'2.4','2','PINJAMAN DARI BANK DAN NON BANK','induk','','','2018-05-15 06:18:34','2018-05-15 06:18:34'),(55,'2.4.1','2.4','PINJAMAN BANK','induk','','','2018-05-15 06:18:51','2018-05-15 06:18:51'),(56,'2.4.2','2.4','PINJAMAN NON BANK','induk','','','2018-05-15 06:19:04','2018-05-15 06:19:04'),(57,'2.4.1.1','2.4.1','PINJAMAN BANK MANDIRI SYARIAH JEMUR','detail','','','2018-05-15 06:19:34','2018-05-15 06:19:34'),(58,'2.4.1.2','2.4.1','PINJAMAN BPR SYARIAH MOJOKERTO','detail','','','2018-05-15 06:20:12','2018-05-15 06:20:12'),(59,'2.4.2.1','2.4.2','JAMSOSTEK KARIMUNJAWA SURABAYA','detail','','','2018-05-15 06:20:37','2018-05-15 06:20:37'),(60,'2.4.2.2','2.4.2','JAMSOSTEK DARMO SURABAYA','detail','','','2018-05-15 06:21:11','2018-05-15 06:21:11'),(61,'2.4.2.3','2.4.2','JAMSOSTEK PERAK SURABAYA','detail','','','2018-05-15 06:21:40','2018-05-15 06:21:40'),(62,'1.2','1','BANK','induk','','','2018-05-19 14:25:36','2018-05-19 14:25:36'),(63,'1.3','1','ANTAR KOPERASI AKTIVA','induk','','','2018-05-19 14:26:10','2018-05-19 14:26:29'),(64,'1.4','1','INVESTASI','induk','','','2018-05-19 14:27:52','2018-05-19 14:27:52'),(65,'1.5','1','PEMBIAYAAN','induk','','','2018-05-19 14:28:11','2018-05-19 14:28:11'),(66,'1.6','1','PEMBIAYAAN LAIN-LAIN','induk','','','2018-05-19 14:28:33','2018-05-19 14:28:33'),(67,'1.7','1','PENYISIHAN PIUTANG','induk','','','2018-05-19 14:32:20','2018-05-19 16:08:13'),(68,'1.8','1','BIAYA DIBAYAR DIMUKA','induk','','','2018-05-19 14:33:05','2018-05-19 14:33:05'),(69,'1.9','1','PENYERTAAN PADA ENTITAS LAIN','induk','','','2018-05-19 14:33:32','2018-05-19 14:33:32'),(70,'1.10','1','TANAH DAN BANGUNGAN','induk','','','2018-05-19 14:33:56','2018-05-19 14:33:56'),(74,'1.11','1','GEDUNG KANTOR','induk','','','2018-05-19 14:54:19','2018-05-19 14:54:19'),(77,'1.12','1','AKUMULASI PENYUST. GEDUNG KANTOR','induk','','','2018-05-19 15:25:28','2018-05-19 15:25:28'),(78,'1.13','1','KENDARAAN','induk','','','2018-05-19 15:25:43','2018-05-19 15:28:40'),(79,'1.14','1','AKUMULASI PENYUST. KENDARAAN','induk','','','2018-05-19 15:33:47','2018-05-19 15:33:47'),(80,'1.15','1','INVENTARIS KANTOR','induk','','','2018-05-19 15:34:05','2018-05-19 15:34:05'),(81,'1.16','1','BIAYA PRA OPERASIONAL','induk','','','2018-05-19 15:34:25','2018-05-19 15:34:25'),(82,'1.2.1','1.2','BANK SYARIAH','induk','','','2018-05-19 15:39:14','2018-05-22 23:15:35'),(84,'1.2.2','1.2','BANK KONVENSIONAL','induk','','','2018-05-19 15:43:11','2018-05-19 15:43:11'),(86,'1.2.1.1','1.2.1','BANK KRIAN','detail','BANK','','2018-05-19 15:45:14','2018-05-22 23:15:45'),(87,'1.2.1.2','1.2.1','BANK JEMUR SARI SURABAYA','detail','BANK','','2018-05-19 15:45:54','2018-05-22 23:15:56'),(88,'1.2.2.1','1.2.2','MANDIRI CABANG UNAIR','detail','BANK','','2018-05-19 15:46:48','2018-05-22 23:16:07'),(89,'1.3.1','1.3','A.K.A. KOPERASI','induk','','','2018-05-19 15:55:05','2018-05-19 15:55:05'),(90,'1.3.1.1','1.3.1','A.K.A. PUSAT KJKS JATIM MICROFIN','detail','','','2018-05-19 15:55:54','2018-05-19 15:57:04'),(91,'1.3.1.2','1.3.1','INKOPSYAH BMT','detail','','','2018-05-19 15:57:36','2018-05-19 15:57:36'),(92,'1.3.1.3','1.3.1','PUSKOPSYAH JATIM AL AKBAR','detail','','','2018-05-19 15:58:00','2018-05-19 15:58:00'),(93,'1.4.1','1.4','INVESTASI LAINNYA','induk','','','2018-05-19 15:58:44','2018-05-19 15:58:44'),(94,'1.4.1.1','1.4.1','INVESTASI CABANG BUNGA GRESIK','detail','','','2018-05-19 16:03:29','2018-05-19 16:03:29'),(95,'1.5.1','1.5','PEMBIAYAAN MDA','induk','','','2018-05-19 16:03:54','2018-05-19 16:03:54'),(96,'1.5.2','1.5','PEMBIAYAAN MRB','induk','','','2018-05-19 16:04:20','2018-05-19 16:04:20'),(97,'1.5.3','1.5','PEMBIAYAAN QORD','induk','','','2018-05-19 16:04:36','2018-05-19 16:04:36'),(98,'1.6.1','1.6','PEMBIAYAAN LAIN EKSTERNAL','induk','','','2018-05-19 16:05:15','2018-05-19 16:05:15'),(99,'1.5.1.1','1.5.1','PEMBIAYAAN MDA','detail','PEMBIAYAAN','{\"rek_margin\":\"4.1.1.1\",\"m_ditangguhkan\":null,\"rek_denda\":\"4.1.3.1\",\"rek_administrasi\":\"4.1.3.1\",\"rek_notaris\":\"4.1.3.1\",\"rek_pend_WO\":null,\"rek_materai\":\"4.1.3.1\",\"rek_asuransi\":\"4.1.3.1\",\"rek_provisi\":\"4.1.3.1\",\"rek_pend_prov\":\"4.1.3.1\",\"rek_zis\":\"4.1.3.1\",\"piutang\":\"0\",\"jenis_pinjaman\":\"2\"}','2018-05-19 16:05:51','2018-07-12 19:49:32'),(100,'1.5.2.1','1.5.2','PEMBIAYAAN MRB','detail','PEMBIAYAAN','{\"rek_margin\":\"4.1.1.2\",\"m_ditangguhkan\":\"1.5.2.2\",\"rek_denda\":\"4.1.1.2\",\"rek_administrasi\":\"4.1.3.1\",\"rek_notaris\":\"4.1.3.1\",\"rek_pend_WO\":\"4.1.3.1\",\"rek_materai\":\"4.1.3.1\",\"rek_asuransi\":\"2.9.1\",\"rek_provisi\":\"4.1.3.1\",\"rek_pend_prov\":\"4.1.3.1\",\"rek_zis\":\"2.9.1\",\"piutang\":\"1\",\"jenis_pinjaman\":\"1\"}','2018-05-19 16:06:06','2018-07-11 12:41:46'),(101,'1.5.2.2','1.5.2','PIUTANG MRB YANG DITANGGUHKAN','detail','','','2018-05-19 16:06:28','2018-05-19 16:06:28'),(102,'1.5.3.1','1.5.3','PEMBIAYAAN QORD','detail','','','2018-05-19 16:06:41','2018-05-19 16:06:41'),(103,'1.6.1.1','1.6.1','PEMBY. MDA LAIN-LAIN','detail','','','2018-05-19 16:07:19','2018-05-19 16:07:19'),(104,'1.7.1','1.7','PENYISIHAN PIUTANG UMUM','detail','','','2018-05-19 16:08:03','2018-05-19 16:08:03'),(105,'2.1.2.5','2.1.2','SIMPANAN ZIAROH/WISATA','detail','','','2018-05-19 16:14:06','2018-05-19 16:14:06'),(106,'2.1.2.6','2.1.2','SIMPANAN UNIT LAIN','detail','','','2018-05-19 16:14:52','2018-05-19 16:14:52'),(107,'2.3.1','2.3','A.K.P KOPERASI','induk','','','2018-05-19 16:17:55','2018-05-19 16:18:50'),(108,'2.3.1.3','2.3.1','A.K.P PUSAT KJKS JATIM MICROFIN','detail','','','2018-05-19 16:20:04','2018-05-19 18:31:51'),(109,'2.3.1.1','2.3.1','INKOPSYAH','detail','','','2018-05-19 16:20:27','2018-05-19 16:20:27'),(110,'2.3.1.2','2.3.1','BMT MBS','detail','','','2018-05-19 16:20:41','2018-05-19 16:20:41'),(111,'2.5','2','DANA PENDIDIKAN','induk','','','2018-05-19 16:21:46','2018-05-19 16:21:46'),(112,'2.6','2','ZAKAT','detail','','','2018-05-19 16:22:06','2018-05-19 16:22:06'),(113,'2.7','2','DANA SOSIAL','detail','','','2018-05-19 16:22:25','2018-07-25 18:05:57'),(114,'2.8','2','RUPA-RUPA PASIVA','induk','','','2018-05-19 16:22:43','2018-05-19 16:22:43'),(115,'3.1','3','MODAL','induk','','','2018-05-19 16:22:51','2018-05-19 16:22:51'),(116,'3.2','3','KEKAYAAN & SHU','induk','','','2018-05-19 16:23:09','2018-05-19 16:23:09'),(117,'3.2.1','3.2','SIMPANAN POKOK ANGGOTA','detail','','','2018-05-19 16:23:38','2018-05-19 16:23:38'),(118,'3.2.2','3.2','WAQAF UANG','detail','','','2018-05-19 16:23:53','2018-05-19 16:23:53'),(119,'3.2.3','3.2','SIMPANAN WAJIB ANGGOTA','detail','','','2018-05-19 16:24:14','2018-05-19 16:24:14'),(120,'3.2.4','3.2','SIMPANAN SUKA RELA ANGGOTA','detail','','','2018-05-19 16:24:44','2018-05-19 16:24:44'),(121,'3.2.5','3.2','DANA CADANGAN UMUM','detail','','','2018-05-19 16:25:04','2018-05-19 16:25:04'),(122,'3.2.6','3.2','SHU BERJALAN','detail','SHU','','2018-05-19 16:25:18','2018-05-29 05:45:06'),(123,'3','master','MODAL','master','','','2018-05-19 16:58:23','2018-05-19 16:58:23'),(125,'4','master','PENDAPATAN','master','','','2018-05-19 17:00:40','2018-05-19 17:00:40'),(126,'5','master','BIAYA','master','','','2018-05-19 17:00:48','2018-05-19 17:00:48'),(127,'4.1','4','PENDAPATAN OPERASIONAL','induk','','','2018-05-19 17:01:14','2018-05-19 17:01:14'),(128,'4.1.1','4.1','PENDAPATAN PEMBIAYAAN','induk','','','2018-05-19 17:01:43','2018-05-19 17:01:59'),(129,'4.1.1.1','4.1.1','PENDAPATAN BH PEMBY MDA','detail','','','2018-05-19 17:02:49','2018-05-19 17:02:49'),(130,'4.1.1.2','4.1.1','PENDAPATAN BH PEMBY MRB','detail','','','2018-05-19 17:03:15','2018-05-19 17:03:15'),(131,'4.1.1.3','4.1.1','PENDAPATAN BH PEMBY QORD','detail','','','2018-05-19 17:03:43','2018-05-19 17:03:43'),(132,'4.1.2','4.1','PENDAPATAN OPERASIONAL LAINNYA','induk','','','2018-05-19 17:04:10','2018-05-19 17:04:29'),(133,'4.1.2.1','4.1.2','PENDAPATAN BH LAINNYA','detail','','','2018-05-19 17:04:45','2018-05-19 17:04:45'),(134,'5.1','5','BEBAN LANGSUNG TABUNGAN','induk','','','2018-05-19 17:05:39','2018-05-19 17:05:39'),(135,'5.2','5','BEBAN LANGSUNG ANTAR KOPERASI PASIVA','induk','','','2018-05-19 17:06:04','2018-05-19 17:06:04'),(136,'5.3','5','BEBAN LANGSUNG PINJAM BANK DAN NON BANK','induk','','','2018-05-19 17:06:32','2018-05-19 17:06:32'),(137,'5.4','5','BEBAN OPERASIONAL DAN ADMINISTRASI','induk','','','2018-05-19 17:07:14','2018-05-19 17:07:14'),(138,'5.1.1','5.1','BEBAN BH TABUNGAN MDA UMUM','induk','','','2018-05-19 17:07:50','2018-05-19 17:07:50'),(139,'5.1.1.1','5.1.1','BEBAN BH TABUNGAN MDA UMUM','detail','','','2018-05-19 17:08:20','2018-05-19 17:08:20'),(140,'5.1.2','5.1','BEBAN BH TABUNGAN MDA BERJANGKA','induk','','','2018-05-19 17:08:49','2018-05-19 17:08:49'),(141,'5.1.2.1','5.1.2','BEBAN TAB. TARBIYAH / PENDIDIKAN','detail','','','2018-05-19 17:09:17','2018-05-19 17:09:17'),(142,'5.1.2.2','5.1.2','BEBAN TAB. IDUL FITRI','detail','','','2018-05-19 17:09:57','2018-05-19 17:09:57'),(143,'5.1.2.3','5.1.2','BEBAN TAB. IDUL ADHA','detail','','','2018-05-19 17:10:34','2018-05-19 17:10:34'),(144,'5.1.2.4','5.1.2','BEBAN TAB. WALIMAH','detail','','','2018-05-19 17:10:56','2018-05-19 17:10:56'),(145,'5.1.2.5','5.1.2','BEBAN TAB. ZIARAH / WISATA','detail','','','2018-05-19 17:11:18','2018-05-19 17:11:18'),(146,'5.1.2.6','5.1.2','BEBAN TAB. UNIT LAIN','detail','','','2018-05-19 17:11:42','2018-05-19 17:11:42'),(147,'5.2.1','5.2','BEBAN BH ANTAR KOP. SYARIAH','induk','','','2018-05-19 17:12:26','2018-05-19 17:12:26'),(148,'5.2.1.1','5.2.1','BEBAN BH INKOPSYAH','detail','','','2018-05-19 17:12:49','2018-05-19 17:12:49'),(149,'5.2.1.2','5.2.1','BEBAN BH  MICROFIN','detail','','','2018-05-19 17:13:24','2018-05-19 17:13:24'),(150,'5.3.1','5.3','BEBAN BH PINJAMAN BANK','induk','','','2018-05-19 17:13:52','2018-05-19 17:13:52'),(151,'5.3.1.1','5.3.1','BEBAN BH PINJAMAN MANDIRI SYARIAH JEMUR','detail','','','2018-05-19 17:14:36','2018-05-19 17:14:36'),(152,'5.3.1.2','5.3.1','BEBAN BH PINJAMAN BPR SYARIAH MOJOKERTO','detail','','','2018-05-19 17:15:24','2018-05-19 17:15:24'),(153,'5.4.1','5.4','BIAYA KARYAWAN','induk','','','2018-05-19 17:16:31','2018-05-19 17:16:31'),(154,'5.4.1.1','5.4.1','BEBAN BISYAROH KARYAWAN','detail','','','2018-05-19 17:16:52','2018-05-19 17:16:52'),(155,'5.4.2','5.4','BIAYA KANTOR','induk','','','2018-05-19 17:17:10','2018-05-19 17:17:10'),(156,'5.4.2.1','5.4.2','BIAYA PERLENGKAPAN KANTOR','detail','','','2018-05-19 17:17:29','2018-05-19 17:17:29'),(157,'5.4.2.2','5.4.2','BIAYA LISTRIK, PDAM, DAN TELEPON','detail','','','2018-05-19 17:17:56','2018-05-19 17:17:56'),(158,'5.4.2.3','5.4.2','BIAYA TRANSPORTASI','detail','','','2018-05-19 17:18:43','2018-05-19 17:18:43'),(159,'5.4.2.4','5.4.2','BIAYA ORGANISASI','detail','','','2018-05-19 17:18:59','2018-05-19 17:18:59'),(160,'5.4.2.5','5.4.2','BIAYA PROMOSI','detail','','','2018-05-19 17:19:14','2018-08-06 05:38:54'),(161,'5.5','5','BEBAN DEPOSITO','induk','','','2018-05-22 18:08:18','2018-05-22 18:08:18'),(162,'5.5.1','5.5','BEBAN MUDHARABAH SYARIAH','induk','','','2018-05-22 18:09:43','2018-05-22 18:09:43'),(163,'5.5.1.1','5.5.1','BEBAN MUDHARABAH BERJANGKA','induk','','','2018-05-22 18:10:03','2018-05-22 18:10:03'),(164,'5.5.1.1.1','5.5.1.1','BEBAN MUDHARABAH 1 BULAN','detail','','','2018-05-22 18:11:19','2018-05-22 18:11:19'),(165,'5.5.1.1.2','5.5.1.1','BEBAN MUDHARABAH 3 BULAN','detail','','','2018-05-22 18:11:38','2018-05-22 18:11:38'),(166,'5.5.1.1.3','5.5.1.1','BEBAN MUDHARABAH 6 BULAN','detail','','','2018-05-22 18:11:55','2018-05-22 18:11:55'),(167,'5.5.1.1.4','5.5.1.1','BEBAN MUDHARABAH 9 BULAN','detail','','','2018-05-22 18:12:18','2018-05-22 18:12:18'),(168,'5.5.1.1.5','5.5.1.1','BEBAN MUDHARABAH 12 BULAN','detail','','','2018-05-22 18:12:35','2018-05-22 18:12:35'),(169,'2.2.1.3','2.2.1','MUDHARABAH 6 BULAN','detail','DEPOSITO','{\"rek_margin\":\"5.5.1.1.3\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"6\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"1\"}','2018-05-22 18:13:20','2018-05-22 18:30:00'),(170,'2.2.1.4','2.2.1','MUDHARABAH 9 BULAN','detail','DEPOSITO','{\"rek_margin\":\"5.5.1.1.4\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"9\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"1\"}','2018-05-22 18:14:05','2018-05-22 18:30:27'),(171,'2.2.1.5','2.2.1','MUDHARABAH 12 BULAN','detail','DEPOSITO','{\"rek_margin\":\"5.5.1.1.5\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"12\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"1\"}','2018-05-22 18:14:20','2018-05-22 18:30:56'),(172,'4.1.3','4.1','PENDAPATAN ADMINISTRASI','induk','','','2018-05-22 18:33:42','2018-05-22 18:33:42'),(173,'4.1.3.1','4.1.3','PENDAPATAN ADMINISTRASI PEMBIAYAAN','detail','','','2018-05-22 18:34:09','2018-05-22 18:34:09'),(174,'2.9','2','PEMINDAHAN BUKUAN','induk','','','2018-05-27 06:00:14','2018-05-27 06:00:14'),(175,'2.9.1','2.9','PEMINDAHAN BUKUAN','detail','','','2018-05-27 06:00:25','2018-05-27 06:00:25'),(176,'3.2.7','3.2','SHU YANG HARUS DIBAGIKAN','detail','','','2018-07-14 10:53:23','2018-07-14 10:53:23'),(178,'4.1.3.2','4.1.3','PENDAPATAN ADMINISTRASI TABUNGAN','detail','','','2018-07-24 19:39:45','2018-07-24 19:39:45'),(179,'2.1.2.7','2.1.2','SIMPANAN MAAL','detail','TABUNGAN','{\"nisbah_anggota\":\"0\",\"nisbah_bank\":100,\"rek_margin\":\"2.1.2.7\",\"rek_pendapatan\":\"1.1.1.1\",\"nasabah_wajib_pajak\":\"0\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"0\",\"setoran_awal\":\"0\",\"setoran_min\":\"0\",\"saldo_min_margin\":\"0\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}','2018-08-02 07:45:35','2018-08-02 07:50:14'),(180,'2.10','2','PAJAK YANG DITANGGUHKAN','detail','PAJAK','','2018-08-14 14:05:33','2018-08-14 14:05:42');



UNLOCK TABLES;



/*Table structure for table `shu` */



DROP TABLE IF EXISTS `shu`;



CREATE TABLE `shu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_rekening` int(10) unsigned NOT NULL,
  `nama_shu` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `persentase` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `shu` */



LOCK TABLES `shu` WRITE;



insert  into `shu`(`id`,`id_rekening`,`nama_shu`,`persentase`,`status`,`created_at`,`updated_at`) values (1,0,'PENGELOLAH','0.2','active',NULL,'2018-07-25 18:10:03'),(2,0,'PENGURUS','0.2','active',NULL,'2018-07-25 18:10:04'),(3,0,'ANGGOTA','0.3','active',NULL,'2018-07-25 18:10:04'),(4,121,'DANA CADANGAN UMUM','0.3','active','2018-07-25 15:41:48','2018-07-25 18:28:36'),(5,113,'DANA SOSIAL','0.1','not active','2018-07-25 18:09:25','2018-07-25 18:17:29');



UNLOCK TABLES;



/*Table structure for table `tabungan` */



DROP TABLE IF EXISTS `tabungan`;



CREATE TABLE `tabungan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tabungan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rekening` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_pengajuan` int(10) unsigned NOT NULL,
  `jenis_tabungan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tabungan_id_tabungan_unique` (`id_tabungan`),
  KEY `tabungan_id_rekening_foreign` (`id_rekening`),
  KEY `tabungan_id_user_foreign` (`id_user`),
  KEY `tabungan_id_pengajuan_foreign` (`id_pengajuan`),
  CONSTRAINT `tabungan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tabungan_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tabungan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `tabungan` */



LOCK TABLES `tabungan` WRITE;



insert  into `tabungan`(`id`,`id_tabungan`,`id_rekening`,`id_user`,`id_pengajuan`,`jenis_tabungan`,`detail`,`created_at`,`updated_at`,`status`) values (115,'2.1',5,2,448,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":1300000,\"id_pengajuan\":529}','2018-07-02 16:32:22','2018-07-30 15:30:45','active'),(117,'2.2',5,2,450,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":\"10000\",\"id_pengajuan\":450}','2018-07-02 16:34:41','2018-07-30 12:53:38','active'),(118,'2.3',5,2,451,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":\"10000\",\"id_pengajuan\":451}','2018-07-02 16:35:07','2018-07-30 12:53:38','active'),(121,'2.4',5,2,454,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":\"10000\",\"id_pengajuan\":454}','2018-07-02 16:40:28','2018-07-30 12:53:39','active'),(122,'2.5',5,2,455,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":\"10000\",\"id_pengajuan\":455}','2018-07-02 16:51:38','2018-07-30 12:53:39','active'),(123,'2.6',5,2,456,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":\"10000\",\"id_pengajuan\":456}','2018-07-02 16:52:12','2018-07-30 12:53:39','active'),(124,'2.7',5,2,457,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":\"10000\",\"id_pengajuan\":457}','2018-07-02 16:53:49','2018-07-30 12:53:39','active'),(125,'2.8',5,2,458,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":\"10000\",\"id_pengajuan\":458}','2018-07-02 16:54:21','2018-07-30 12:53:39','active'),(126,'2.9',5,2,459,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":\"10000\",\"id_pengajuan\":459}','2018-07-02 16:54:37','2018-07-30 12:53:40','active'),(127,'2.10',5,2,460,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":4860000,\"id_pengajuan\":529}','2018-07-02 16:55:22','2018-08-05 16:57:45','active'),(128,'3.1',5,3,525,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":1300000,\"id_pengajuan\":\"527\"}','2018-07-12 10:29:33','2018-07-30 15:30:45','active'),(129,'3.2',7,3,529,'SIMPANAN TARBIYAH / PENDIDIKAN','{\"saldo\":\"25000\",\"id_pengajuan\":529}','2018-07-12 10:46:20','2018-07-30 12:53:40','active'),(130,'14.1',5,14,696,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":1710000,\"id_pengajuan\":\"697\"}','2018-07-14 11:16:47','2018-07-30 15:30:46','active'),(137,'16.1',5,16,710,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":710000,\"id_pengajuan\":710}','2018-07-17 09:32:27','2018-07-30 13:16:22','active'),(138,'15.1',5,15,723,'SIMPANAN MUDHAROBAH UMUM','{\"saldo\":710000,\"id_pengajuan\":723}','2018-07-17 19:55:27','2018-07-30 13:16:22','active'),(139,'2.11',7,2,781,'SIMPANAN TARBIYAH / PENDIDIKAN','{\"saldo\":\"25000\",\"id_pengajuan\":781}','2018-07-24 16:24:36','2018-07-30 12:53:40','active'),(141,'9.1',9,9,786,'SIMPANAN IDUL ADHA','{\"saldo\":1300000,\"id_pengajuan\":786}','2018-07-26 07:18:45','2018-07-30 15:30:32','active'),(142,'10.1',7,10,787,'SIMPANAN TARBIYAH / PENDIDIKAN','{\"saldo\":1925000.0000000002,\"id_pengajuan\":787}','2018-07-26 07:21:56','2018-07-30 15:30:32','active');



UNLOCK TABLES;



/*Table structure for table `users` */



DROP TABLE IF EXISTS `users`;



CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `no_ktp` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pathfile` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `wajib_pokok` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_no_ktp_unique` (`no_ktp`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Data for the table `users` */



LOCK TABLES `users` WRITE;



insert  into `users`(`id`,`no_ktp`,`password`,`nama`,`alamat`,`tipe`,`status`,`detail`,`remember_token`,`created_at`,`updated_at`,`pathfile`,`wajib_pokok`,`role`) values (1,'admin','$2y$10$EnjCvjx7SmXqFyjbzevk6eH2N7Y4rh39dHI5Uxr2DUwzLJ247s3QK','admin','admin','admin','','{\"id_rekening\":\"13\"}','8xNjhVf2L0Lr35n4pHNByv5QRdFwHs0c6wUus4cJcxDCTULre0RLc9RE3nOY',NULL,'2018-07-12 18:45:24','{\"profile\":\"r7GcMSBD3LDkfpB87JuqIw1fBbHjGGbB7a5PoD61.jpeg\",\"KTP\":null,\"KSK\":null,\"Nikah\":null}','',''),(2,'user','$2y$10$Mi014s85vHHlVoaUFASHaOHSaQKLcVIrwQhTnPqCv/1ezYCVwcZKK','Ghani Ramadhan','ALAMAT DOMISILI GHANI','anggota','2','{\"nama\":\"Ghani Ramadhan\",\"no_ktp\":\"user\",\"nik\":\"user\",\"telepon\":\"087853596908\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"GHANI LAHIR\",\"tgl_lahir\":\"11\\/30\\/1995\",\"alamat_ktp\":\"ALAMAT KTP GHANI\",\"alamat_domisili\":\"ALAMAT DOMISILI GHANI\",\"pendidikan\":\"S1\",\"pekerjaan\":\"PEKERJAAN GHANI\",\"alamat_kerja\":\"ALAMAT KERJA GHANI\",\"status\":\"M\",\"nama_wali\":\"ISTRI GHANI\",\"ayah\":\"AYAH GHANI\",\"ibu\":\"IBU GHANI\",\"jml_sumis\":\"1\",\"jml_anak\":\"2\",\"jml_ortu\":\"3\",\"lain\":\"4\",\"rumah\":\"HM\"}','sIvpXxfSJowq6tyDcdY61wB1O2AbnSldcy5fDrDcCfSgsWUSEgNFNVJ2XM0V','2018-05-14 09:24:16','2018-08-14 14:04:55','{\"profile\":\"kBDq6ZYhSEtCRtMxcULIcJLnhgWRVYUPZ0DZMB4A.jpeg\",\"KTP\":\"2QyKxTEWB78bhXShT3XcJAhrHWpMCKMti2mwL2kb.jpeg\",\"KSK\":\"xPYmd1W1TldgPSlbmKNvAk8Vn1SR0Z85PZBlpSK1.jpeg\",\"Nikah\":\"xx5OJBMYF0CeuywvKgVWJj0NWbWENPxEEZlgQCPW.jpeg\"}','{\"wajib\":\"10000\",\"pokok\":\"25000\",\"margin\":2400000}','pengurus'),(3,'3575033008950001','$2y$10$7pTp0SvFRfdJO0MkPr20WOyp89DuAnEzx68VpSy692xMpqzHcBF0K','Ghulam','Pasuruan','anggota','2','{\"nama\":\"Ghulam\",\"no_ktp\":\"3575033008950001\",\"nik\":\"d\",\"telepon\":\"9009\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"djko\",\"tgl_lahir\":\"05\\/18\\/2018\",\"alamat_ktp\":\"safsd\",\"alamat_domisili\":\"Fajri\",\"pendidikan\":\"0\",\"pekerjaan\":\"dsad\",\"alamat_kerja\":\"dsaad\",\"status\":\"S\",\"nama_wali\":\"sad\",\"ayah\":\"ddd\",\"ibu\":\"dd\",\"jml_sumis\":\"9\",\"jml_anak\":\"9\",\"jml_ortu\":\"9\",\"lain\":\"9\",\"rumah\":\"HM\",\"id_rekening\":\"14\"}','RjZAwPMlIbBISXq034qqgiLFXc5SOQcnQjTLXkJagIlMtjlJ23yS7VFQtfdy','2018-05-18 08:16:29','2018-07-17 19:31:15','{\"profile\":\"FDfRVM6aXlaZUaXAgpqsGYx60vhWmcDV4u0rwbcC.jpeg\",\"KTP\":\"2cASXiHwcAZWhutdImTmzpvgmIUY8gZOz0Q7EZCd.jpeg\",\"KSK\":\"iyiGGPZdbrMITv6EvNX98mZJAb7hPv7wjlKR3qF9.png\",\"Nikah\":\"5QZxghSmudpoh1ALfQwBGTgzcQgKfn88f3daa419.png\"}','{\"pokok\":\"25000\",\"wajib\":50000}','pengelolah'),(7,'teller2','$2y$10$R6S.1vkIxV5tKi/264q.4eMXqk6gcy.zvapt80pijN7Rt2y.Qe9du','Dian','Jakarta','teller','2','{\"nama\":\"Dian\",\"no_ktp\":null,\"nik\":\"teller\",\"telepon\":\"087853596908\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"Surabaya\",\"tgl_lahir\":\"02\\/08\\/2000\",\"alamat_ktp\":\"Surabaya\",\"alamat_domisili\":\"Surabaya\",\"pendidikan\":\"0\",\"pekerjaan\":\"mahasiswi\",\"alamat_kerja\":\"Surabaya\",\"status\":\"S\",\"nama_wali\":\"-\",\"ayah\":\"ayah\",\"ibu\":\"ibu\",\"jml_sumis\":\"0\",\"jml_anak\":\"0\",\"jml_ortu\":\"2\",\"lain\":\"1\",\"rumah\":\"HM\",\"id_rekening\":\"14\"}','zys9uYmoLYK5tVVjd1YHioHbYdfMgcTGQ2E9VsgIDYidqx30NMVI2iYiKpvj','2018-05-29 20:21:31','2018-07-15 08:12:18','{\"profile\":\"QDlLxvoF4a4pesp4YAXj7iL8XQi35YmNYyXxwmIF.jpeg\",\"KTP\":\"b8h6BjtE7NCvVo8VnOMCwTFfQh8s8EHeY6otToSC.png\",\"KSK\":null,\"Nikah\":\"AQhXpKth0arFVE45Cq7btGhE5pvSaYnByUjPLz0U.jpeg\"}','','pengurus'),(8,'teller3','$2y$10$ePceVsEitaOX9wPzranMkuBogDyEXGRGpwQsFNWkDQ.dehgtTpUey','1','1','teller','','{\"nama\":\"1\",\"no_ktp\":null,\"nik\":\"1\",\"telepon\":\"1\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"1\",\"tgl_lahir\":\"01\\/01\\/2018\",\"alamat_ktp\":\"1\",\"alamat_domisili\":\"1\",\"pendidikan\":\"SMP\",\"pekerjaan\":\"1\",\"alamat_kerja\":\"1\",\"status\":\"S\",\"nama_wali\":\"1\",\"ayah\":\"1\",\"ibu\":\"1\",\"jml_sumis\":\"1\",\"jml_anak\":\"1\",\"jml_ortu\":\"1\",\"lain\":\"1\",\"rumah\":\"HM\",\"id_rekening\":\"13\"}','wUlFAZ2PJmTWtYTTcI1xE9QnSCT2Zdjd4DzK1B3sYJYMGBmT33qgz4u9s4Ld','2018-05-30 07:12:52','2018-07-15 09:08:33','{\"profile\":null,\"KTP\":null,\"KSK\":null,\"Nikah\":null}','','pengurus'),(9,'3575033008950002','$2y$10$k7V4OGMaHBmg7FelPJsN9ehPXjkt8xwhIOusOGprw6T4vZ2YWt1Xi','Ghulam Fajri','Surabaya','anggota','2','{\"nama\":\"Ghulam Fajri\",\"no_ktp\":\"3575033008950002\",\"nik\":\"3575033008950001\",\"telepon\":\"087853596908\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"Pasuruan\",\"tgl_lahir\":\"08\\/30\\/1995\",\"alamat_ktp\":\"Pasuruan\",\"alamat_domisili\":\"Surabaya\",\"pendidikan\":\"0\",\"pekerjaan\":\"Mahasiswa\",\"alamat_kerja\":\"Surabaya\",\"status\":\"S\",\"nama_wali\":\"-\",\"ayah\":\"ayah\",\"ibu\":\"ibu\",\"jml_sumis\":\"0\",\"jml_anak\":\"0\",\"jml_ortu\":\"2\",\"lain\":\"0\",\"rumah\":\"HM\"}','xLKkQO2LP4p2wcNx3dAukTd2AVKjUdmek4AnQ3CDCVAKTPCgCy14dvpo1gIy','2018-05-31 08:51:15','2018-07-26 07:18:45','{\"profile\":null,\"KTP\":\"CteSqkFHvDllLKDleQQcKI0UJLatPloRlQDdaBt7.jpeg\",\"KSK\":null,\"Nikah\":null}','{\"pokok\":\"25000\",\"wajib\":\"40000\"}','anggota'),(10,'3575033008950003','$2y$10$16m0Wf8DdBQZrBQMI79xiuHDT0CemkWsf.GeXFNv3DiMLWKBYQy8y','dadang','Surabaya','anggota','2','{\"nama\":\"dadang\",\"no_ktp\":\"3575033008950003\",\"nik\":\"23\",\"telepon\":\"90\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"Surabaya\",\"tgl_lahir\":\"06\\/01\\/2018\",\"alamat_ktp\":\"Surabaya\",\"alamat_domisili\":\"Surabaya\",\"pendidikan\":\"0\",\"pekerjaan\":\"guru\",\"alamat_kerja\":\"Surabaya\",\"status\":\"S\",\"nama_wali\":\"1\",\"ayah\":\"1\",\"ibu\":\"1\",\"jml_sumis\":\"1\",\"jml_anak\":\"1\",\"jml_ortu\":\"1\",\"lain\":\"1\",\"rumah\":\"HM\"}','IkEl79bu1dHbm30PSa5uF1B1T75jnOvCMA5dl54XOyRIMd9DPOPujgiIyglq','2018-06-01 22:18:09','2018-07-26 14:06:07','{\"profile\":\"lHUPy8Ey8HSH4jKYr4W5KQjRlJXubdrxnQrXTzXl.jpeg\",\"KTP\":null,\"KSK\":null,\"Nikah\":null}','{\"pokok\":\"25000\",\"wajib\":70000}','anggota'),(13,'teller','$2y$10$2vcbyHMDHIs.7pkHn/Qmnu8AlY0.2tdsc4k0BB1Dxorokk.jz1GS.','Dian','surabaya','teller','2','{\"id_rekening\":\"13\"}','oRut3sPdOp2BDBNzKhoRWRP8xyTmiGIimIylIy9jReCszT9WjegMhFwLqWEw','2018-06-10 09:00:22','2018-07-15 09:08:18','','','pengurus'),(14,'3575033008950005','$2y$10$ofh6sk7kK3n381HYkApnFemxB2/Jd/UhCgSx29CM//O932e8E6jZ2','Dadang','Surabaya','anggota','2','{\"nama\":\"Dadang\",\"no_ktp\":\"3575033008950005\",\"nik\":\"30\",\"telepon\":\"09\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"Surabaya\",\"tgl_lahir\":\"07\\/19\\/1995\",\"alamat_ktp\":\"Surabaya\",\"alamat_domisili\":\"Surabaya\",\"pendidikan\":\"S1\",\"pekerjaan\":\"mahasiswa\",\"alamat_kerja\":\"Surabaya\",\"status\":\"S\",\"nama_wali\":\"-\",\"ayah\":\"Ayah\",\"ibu\":\"Ibu\",\"jml_sumis\":\"0\",\"jml_anak\":\"0\",\"jml_ortu\":\"2\",\"lain\":\"0\",\"rumah\":\"KK\"}',NULL,'2018-07-14 10:38:51','2018-07-15 09:09:05','{\"profile\":null,\"KTP\":\"7UOdYQf7EQgeHHw8owt0RXdY8oaxXrFhI0nrbqYt.jpeg\",\"KSK\":null,\"Nikah\":null}','{\"pokok\":\"25000\",\"wajib\":\"10000\"}','pengurus'),(15,'3575033008950006','$2y$10$PlzROR5CFNbVscRw.F1xuePg/a90sBu3t7EwcQbq.3UYJfbwT6k76','Demsy','Lumajang','anggota','2','{\"nama\":\"Demsy\",\"no_ktp\":\"3575033008950006\",\"nik\":\"12\",\"telepon\":\"2\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"2\",\"tgl_lahir\":\"07\\/15\\/2018\",\"alamat_ktp\":\"2\",\"alamat_domisili\":\"Lumajang\",\"pendidikan\":\"0\",\"pekerjaan\":\"2\",\"alamat_kerja\":\"2\",\"status\":\"S\",\"nama_wali\":\"istri\",\"ayah\":\"ayah\",\"ibu\":\"ibu\",\"jml_sumis\":\"0\",\"jml_anak\":\"0\",\"jml_ortu\":\"0\",\"lain\":\"0\",\"rumah\":\"HM\"}','w71OVAfaedJOyckkGXt8oGHbyF4zyojIubAUd717CM74mxuaYqNW88FwViS9','2018-07-15 07:52:33','2018-07-17 19:55:27','{\"profile\":\"OP7T2sSd9oaIZWPZwXnsodjua6Z3IcbkzoE1RZiA.jpeg\",\"KTP\":\"1IPcoGpGx9A6H8PH4UTggi4DedaseAn93IJVIRss.jpeg\",\"KSK\":null,\"Nikah\":null}','{\"pokok\":\"25000\",\"wajib\":\"10000\"}','anggota'),(16,'3575033008950007','$2y$10$n6Io6f82uN95rRNM1/1vp.KvsYTdIrnjVpBouUU7OrDBqTetlHBx6','Iman','Surabaya','anggota','2','{\"nama\":\"Iman\",\"no_ktp\":\"3575033008950007\",\"nik\":\"11\",\"telepon\":\"1\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"1\",\"tgl_lahir\":\"07\\/15\\/2018\",\"alamat_ktp\":\"1\",\"alamat_domisili\":\"Surabaya\",\"pendidikan\":\"0\",\"pekerjaan\":\"1\",\"alamat_kerja\":\"1\",\"status\":\"S\",\"nama_wali\":\"istri\",\"ayah\":\"ayah\",\"ibu\":\"ibu\",\"jml_sumis\":\"0\",\"jml_anak\":\"0\",\"jml_ortu\":\"0\",\"lain\":\"0\",\"rumah\":\"HM\"}',NULL,'2018-07-15 13:34:02','2018-07-17 09:32:28','{\"profile\":null,\"KTP\":null,\"KSK\":null,\"Nikah\":null}','{\"pokok\":\"25000\",\"wajib\":\"10000\"}','anggota');



UNLOCK TABLES;



/* Procedure structure for procedure `sp_angsur` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_angsur` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_angsur`(

	IN `id_user` INT,

	IN `id_usrPem` INT,

	IN `id_tellbank` INT,

	IN `id_pem` INT,

	IN `id_margin` INT
,

	IN `id_pot` INT,

	IN `id_shu` INT,

	IN `id_pembiayaan` INT,

	IN `id_pengajuan` INT,

	IN `detail_tellbank` TEXT,

	IN `detail_pem` TEXT,

	IN `detail_ppem` TEXT,

	IN `detail_margin` TEXT,

	IN `detail_pot` TEXT,

	IN `detail_pembiayaan` TEXT
,

	IN `saldo_all` FLOAT,

	IN `saldo_pem` FLOAT
,

	IN `saldo_tell` FLOAT,

	IN `angsur` INT




,

	IN `status_angsur` INT



,

	IN `status_lunas` INT



,

	IN `status_bayar` INT,

	IN `piutang` INT

)
BEGIN

	DECLARE S_MARGIN FLOAT;

	DECLARE S_PEM FLOAT;

	DECLARE S_SHU FLOAT;

	DECLARE S_POT FLOAT;

	DECLARE S_BANK FLOAT;

	DECLARE S_ANG INT;

	DECLARE ANGSURAN VARCHAR(50);

	START TRANSACTION;

		

		SET S_BANK = (SELECT saldo FROM bmt WHERE id = id_tellbank);

		INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_tellbank,"Angsuran",detail_tellbank,NOW(),NOW());

		UPDATE bmt SET saldo=(saldo_tell+S_BANK), updated_at=NOW() WHERE id=id_tellbank;

					

		IF(status_bayar = 1 || status_bayar = 2  ) THEN

			SET ANGSURAN ="Angsuran Pembiayaan [Margin]";

			SET S_MARGIN = (SELECT saldo FROM bmt WHERE id = id_margin);

			SET S_SHU = (SELECT saldo FROM bmt WHERE id = id_shu);

			UPDATE bmt SET saldo=(saldo_all+S_MARGIN), updated_at=NOW() WHERE id=id_margin;

			UPDATE bmt SET saldo=(saldo_all+S_SHU), updated_at=NOW() WHERE id=id_shu;

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_margin,"Angsuran",detail_margin,NOW(),NOW());

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_shu,"Angsuran",detail_margin,NOW(),NOW());

		

			IF(id_pot != 0) THEN

				SET S_POT = (SELECT saldo FROM bmt WHERE id = id_pot);

				UPDATE bmt SET saldo=(saldo_all+S_POT), updated_at	=NOW() WHERE id=id_pot;

				INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pot,"Angsuran",detail_pot,NOW(),NOW());

			END IF;

		END IF;

		

		IF(status_bayar = 1)  THEN SET ANGSURAN ="Angsuran Pembiayaan [Margin]";

		ELSEIF(status_bayar = 0)  THEN SET ANGSURAN ="Angsuran Pembiayaan [Pokok]";

		ELSEIF(status_bayar = 2)  THEN SET ANGSURAN ="Angsuran Pembiayaan [Pokok+Margin]"; 

		END IF;			

		



		IF(status_bayar != 1 && piutang = 0) THEN

			SET S_PEM = (SELECT saldo FROM bmt WHERE id = id_pem);

			UPDATE bmt SET saldo=(S_PEM-saldo_pem), updated_at=NOW() WHERE id=id_pem;

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pem,"Angsuran",detail_pem,NOW(),NOW());

		ELSEIF( piutang = 1) THEN

			SET S_PEM = (SELECT saldo FROM bmt WHERE id = id_pem);

			UPDATE bmt SET saldo=(S_PEM-saldo_pem), updated_at=NOW() WHERE id=id_pem;

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pem,"Angsuran",detail_pem,NOW(),NOW());

		END IF;

		

		UPDATE pembiayaan SET detail=detail_pembiayaan, updated_at=NOW(), status_angsuran=status_angsur,angsuran_ke=angsur  WHERE id=id_pembiayaan;

		IF(status_lunas) THEN

			UPDATE pembiayaan SET status= "not active"  WHERE id=id_pembiayaan;

		END IF;

		INSERT INTO penyimpanan_pembiayaan VALUES(NULL,id_user,id_usrPem,ANGSURAN,detail_ppem,NOW(),NOW());

		UPDATE pengajuan SET pengajuan.status="Sudah Dikonfirmasi", updated_at=NOW() WHERE id=id_pengajuan;

			

		IF(SELECT ROW_COUNT()) THEN

			COMMIT;

		ELSE ROLLBACK;

		END IF;

END */$$

DELIMITER ;



/* Procedure structure for procedure `sp_debit` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_debit` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_debit`(

	IN `detail_pasiva` TEXT,

	IN `detail_activa` TEXT,

	IN `detail_ptabungan` TEXT,

	IN `detail_tabungan` TEXT,

	IN `saldo_activa` VARCHAR(50),

	IN `saldo_pasiva` VARCHAR(50),

	IN `status_activa` VARCHAR(50),

	IN `status_pasiva` VARCHAR(50),

	IN `status_tabungan` VARCHAR(50),

	IN `status_pengajuan` VARCHAR(50),

	IN `id_activa` INT,

	IN `id_pasiva` INT,

	IN `id_user` INT
,

	IN `id_tabungan` INT
,

	IN `id_pengajuan` INT




)
    DETERMINISTIC
    COMMENT 'Transaksi Debit/Setor'
BEGIN 

	START TRANSACTION;

	

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,status_activa,detail_activa,NOW(),NOW());

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,status_pasiva,detail_pasiva,NOW(),NOW());

	

	INSERT INTO penyimpanan_tabungan VALUES(NULL,id_user,id_tabungan,status_tabungan,detail_ptabungan,NOW(),NOW());

	UPDATE tabungan SET detail=detail_tabungan,updated_at=NOW() WHERE id=id_tabungan;	

	UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW() WHERE id=id_pengajuan;

		

	IF(status_tabungan ="Kredit") THEN

		UPDATE bmt SET saldo= (saldo_activa), updated_at=NOW() WHERE id=id_activa;	

		UPDATE bmt SET saldo= (saldo_pasiva), updated_at=NOW() WHERE id=id_pasiva;	

	ELSEIF(status_tabungan ="Debit") THEN

		UPDATE bmt SET saldo= (saldo_activa), updated_at=NOW() WHERE id=id_activa;	

		UPDATE bmt SET saldo= (saldo_pasiva), updated_at=NOW() WHERE id=id_pasiva;	

	END IF;

	

	IF(SELECT ROW_COUNT()) THEN

		COMMIT;

	ELSE ROLLBACK;

	END IF;

END */$$

DELIMITER ;



/* Procedure structure for procedure `sp_donasi_` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_donasi_` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_donasi_`(

	IN `id_dari` INT

,

	IN `id_tujuan` INT,

	IN `id_user` INT,

	IN `id_maal` INT,

	IN `jumlah` FLOAT,

	IN `detail_dari` TEXT,

	IN `detail_tujuan` TEXT,

	IN `detail_tabungan` TEXT,

	IN `detail_maal` TEXT,

	IN `jenis` VARCHAR(50)













,

	IN `maal_` INT,

	IN `id_rek` INT,

	IN `detail_rek` TEXT











)
BEGIN 	



	DECLARE S_BMT FLOAT;

	DECLARE jenis_ VARCHAR(32);

	START TRANSACTION;

	

	SET jenis_ = "Donasi Maal";

	IF(jenis ="Tabungan") THEN

		IF(maal_ = 0) THEN

			INSERT INTO penyimpanan_maal VALUES(NULL,id_user,id_maal,"Tabungan",detail_tujuan,NOW(),NOW());	

			UPDATE maal SET detail= detail_maal, updated_at=NOW() WHERE id=id_maal;		

		ELSE SET jenis_ = "Donasi Waqaf";

		END IF;		

		

		INSERT INTO penyimpanan_tabungan VALUES(NULL,id_user,id_dari, jenis_ ,detail_dari,NOW(),NOW());

		UPDATE tabungan SET detail=detail_tabungan,updated_at=NOW() WHERE id=id_dari;	

		

		SET S_BMT = (SELECT saldo FROM bmt WHERE id = id_rek);

		UPDATE bmt SET saldo= (S_BMT-jumlah), updated_at=NOW() WHERE id=id_rek;

			

	ELSEIF(jenis ="Transfer") THEN

		IF(maal_ = 0) THEN

			INSERT INTO penyimpanan_maal VALUES(NULL,id_user,id_maal,"Transfer",detail_tujuan,NOW(),NOW());

			UPDATE maal SET detail= detail_maal, updated_at=NOW() WHERE id=id_maal;		

		ELSE SET jenis_ = "Donasi Waqaf";

		END IF;

		

		SET S_BMT = (SELECT saldo FROM bmt WHERE id = id_rek);

		UPDATE bmt SET saldo= (S_BMT+jumlah), updated_at=NOW() WHERE id=id_rek;	

	

	END IF;



	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_rek,jenis_,detail_rek,NOW(),NOW());		

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_tujuan,jenis_,detail_tujuan,NOW(),NOW());		

	SET S_BMT = (SELECT saldo FROM bmt WHERE id = id_tujuan);

	UPDATE bmt SET saldo= (S_BMT+jumlah), updated_at=NOW() WHERE id=id_tujuan;	

	

	IF(SELECT ROW_COUNT()) THEN

		COMMIT;

	ELSE ROLLBACK;

	END IF;

END */$$

DELIMITER ;



/* Procedure structure for procedure `sp_jurnal_` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_jurnal_` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_jurnal_`(

	IN `id_tujuan` INT,

	IN `id_user` INT,

	IN `jumlah` FLOAT,

	IN `detail_tujuan` TEXT

)
BEGIN



	DECLARE S_TUJUAN FLOAT;

	

	START TRANSACTION;

	

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_tujuan,"Jurnal Lain",detail_tujuan,NOW(),NOW());

	

	SET S_TUJUAN = (SELECT saldo FROM bmt WHERE id = id_tujuan);

	

	UPDATE bmt SET saldo= (S_TUJUAN+jumlah), updated_at=NOW() WHERE id=id_tujuan;	

	

	IF(SELECT ROW_COUNT()) THEN

		COMMIT;

	ELSE ROLLBACK;

	END IF;

END */$$

DELIMITER ;



/* Procedure structure for procedure `sp_un_block_` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_un_block_` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_un_block_`(
	IN `id_` INT
,
	IN `tipe` VARCHAR(50)
,
	IN `status_` VARCHAR(50)
)
    COMMENT 'Blokir Tabungan, Deposito, Pembiayaan Nasabah'
BEGIN
	IF(tipe ="Tabungan") THEN
		START TRANSACTION;	
			UPDATE tabungan SET status=status_ WHERE tabungan.id=id_;	
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	ELSEIF(tipe ="Deposito") THEN
		START TRANSACTION;	
			UPDATE deposito SET status=status_ WHERE deposito.id=id_;	
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	ELSEIF(tipe ="Pembiayaan") THEN
		START TRANSACTION;	
			UPDATE pembiayaan SET status=status_ WHERE pembiayaan.id=id_;	
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	END IF;
END */$$

DELIMITER ;



/* Procedure structure for procedure `sp_create_` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_create_` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_create_`(

	IN `tipe` VARCHAR(50)
,

	IN `id_usrtab` VARCHAR(50),

	IN `jenis_tabungan` VARCHAR(50),

	IN `id_rektab` INT,

	IN `detail_pasiva` TEXT,

	IN `detail_activa` TEXT,

	IN `detail_ptabungan` TEXT,

	IN `detail_tabungan` TEXT,

	IN `saldo_activa` FLOAT,

	IN `saldo_pasiva` FLOAT,

	IN `status_pengajuan` VARCHAR(50),

	IN `id_activa` INT,

	IN `id_pasiva` INT,

	IN `id_user` INT
,

	IN `id_pengajuan` INT
,

	IN `id_margin` INT,

	IN `detail_margin` TEXT,

	IN `jumlah_margin` FLOAT




,

	IN `detail_user` TEXT,

	IN `detail_pokok` TEXT,

	IN `detail_wajib` TEXT,

	IN `saldo_pokok` FLOAT,

	IN `saldo_wajib` FLOAT

,

	IN `detail_simp` TEXT,

	IN `detail_simw` TEXT

,

	IN `id_jam` INT,

	IN `detail_jam` TEXT









)
BEGIN

	DECLARE ID_TABU INT;

	DECLARE S_ACT FLOAT;

	DECLARE S_PAS FLOAT;

	DECLARE S_MAR FLOAT;

	IF(tipe ="Tabungan") THEN

		START TRANSACTION;	

			INSERT INTO tabungan VALUES(NULL,id_usrtab,id_rektab,id_user,id_pengajuan,jenis_tabungan,detail_tabungan,NOW(),NOW(),"active");

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Setoran Awal",detail_activa,NOW(),NOW());

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Setoran Awal",detail_pasiva,NOW(),NOW());

			

			UPDATE tabungan SET detail=detail_tabungan,updated_at=NOW() WHERE id_tabungan=id_usrtab;	

			UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW() WHERE id=id_pengajuan;

			

			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);

			SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);

			UPDATE bmt SET saldo= (S_ACT+saldo_activa), updated_at=NOW() WHERE id=id_activa;	

			UPDATE bmt SET saldo= (saldo_pasiva+S_PAS), updated_at=NOW() WHERE id=id_pasiva;	

			SET ID_TABU = (SELECT id FROM tabungan WHERE id_tabungan = id_usrtab);		

			INSERT INTO penyimpanan_tabungan VALUES(NULL,id_user,ID_TABU,"Setoran Awal",detail_ptabungan,NOW(),NOW());

			IF(SELECT ROW_COUNT()) THEN

				COMMIT;

			ELSE ROLLBACK;

			END IF;

	ELSEIF(tipe ="Tabungan Awal") THEN

		START TRANSACTION;	

			INSERT INTO tabungan VALUES(NULL,id_usrtab,id_rektab,id_user,id_pengajuan,jenis_tabungan,detail_tabungan,NOW(),NOW(),"active");

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Setoran Awal",detail_activa,NOW(),NOW());

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Setoran Awal",detail_pasiva,NOW(),NOW());

		

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Simpanan Pokok",detail_pokok,NOW(),NOW());

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Simpanan Wajib",detail_wajib,NOW(),NOW());

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,339,"Simpanan Pokok",detail_pokok,NOW(),NOW());

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,341,"Simpanan Wajib",detail_wajib,NOW(),NOW());

			INSERT INTO penyimpanan_wajib_pokok VALUES(NULL,id_user,117,"Simpanan Pokok",detail_simp,NOW(),NOW());

			INSERT INTO penyimpanan_wajib_pokok VALUES(NULL,id_user,119,"Simpanan Wajib",detail_simw,NOW(),NOW());

		

			SET S_ACT = (SELECT saldo FROM bmt WHERE id = 339);

			UPDATE bmt SET saldo= (S_ACT+saldo_pokok), updated_at=NOW() WHERE id=339;	

			SET S_ACT = (SELECT saldo FROM bmt WHERE id = 341);

			UPDATE bmt SET saldo= (S_ACT+saldo_wajib), updated_at=NOW() WHERE id=341;	

			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);

			UPDATE bmt SET saldo= (S_ACT+saldo_pokok), updated_at=NOW() WHERE id=id_activa;	

			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);

			UPDATE bmt SET saldo= (S_ACT+saldo_wajib), updated_at=NOW() WHERE id=id_activa;	

			

			UPDATE users SET wajib_pokok=detail_user, users.status=2, updated_at=NOW() WHERE id=id_user;

			

			UPDATE tabungan SET detail=detail_tabungan,updated_at=NOW() WHERE id_tabungan=id_usrtab;	

			UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW() WHERE id=id_pengajuan;

			

			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);

			SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);

			UPDATE bmt SET saldo= (S_ACT+saldo_activa), updated_at=NOW() WHERE id=id_activa;	

			UPDATE bmt SET saldo= (saldo_pasiva+S_PAS), updated_at=NOW() WHERE id=id_pasiva;	

			SET ID_TABU = (SELECT id FROM tabungan WHERE id_tabungan = id_usrtab);		

			INSERT INTO penyimpanan_tabungan VALUES(NULL,id_user,ID_TABU,"Setoran Awal",detail_ptabungan,NOW(),NOW());

			IF(SELECT ROW_COUNT()) THEN

				COMMIT;

			ELSE ROLLBACK;

			END IF;

	ELSEIF(tipe ="Deposito") THEN

		START TRANSACTION;	

			INSERT INTO deposito VALUES(NULL,id_usrtab,id_rektab,id_user,id_pengajuan,jenis_tabungan,detail_tabungan,NOW(),NOW(),NOW(),"active");

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Deposit Awal",detail_activa,NOW(),NOW());

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Deposit Awal",detail_pasiva,NOW(),NOW());

			

			UPDATE deposito SET detail=detail_tabungan,updated_at=NOW() WHERE id_deposito=id_usrtab;	

			UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW() WHERE id=id_pengajuan;

			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);

			SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);

			UPDATE bmt SET saldo= (S_ACT+saldo_activa), updated_at=NOW() WHERE id=id_activa;	

			UPDATE bmt SET saldo= (saldo_pasiva+S_PAS), updated_at=NOW() WHERE id=id_pasiva;	

			

			SET ID_TABU = (SELECT id FROM deposito WHERE id_deposito = id_usrtab);	

			INSERT INTO penyimpanan_deposito VALUES(NULL,id_user,ID_TABU,"Deposit Awal",detail_ptabungan,NOW(),NOW());

			IF(SELECT ROW_COUNT()) THEN

				COMMIT;

			ELSE ROLLBACK;

			END IF;

	ELSEIF(tipe ="Pembiayaan") THEN

		START TRANSACTION;	

			INSERT INTO pembiayaan VALUES(NULL,id_usrtab,id_rektab,id_user,id_pengajuan,jenis_tabungan,detail_tabungan,NOW(),NOW(),NOW(),"active",0,0);

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Pencairan Pembiayaan",detail_activa,NOW(),NOW());

			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Pencairan Pembiayaan",detail_pasiva,NOW(),NOW());

			

			UPDATE pembiayaan SET detail=detail_tabungan,updated_at=NOW() WHERE id_pembiayaan=id_usrtab;	

			UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW() WHERE id=id_pengajuan;

			

			

			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);

			SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);

			

			UPDATE bmt SET saldo= (S_ACT+saldo_activa), updated_at=NOW() WHERE id=id_activa;	

			UPDATE bmt SET saldo= (saldo_pasiva+S_PAS), updated_at=NOW() WHERE id=id_pasiva;	

			IF(id_margin != 0) THEN

				SET S_MAR = (SELECT saldo FROM bmt WHERE id = id_margin);

				UPDATE bmt SET saldo= (jumlah_margin+S_MAR), updated_at=NOW() WHERE id=id_margin;	

				INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,ID_MARGIN,"Pencairan Pembiayaan",detail_margin,NOW(),NOW());			

			END IF;

	

			SET ID_TABU = (SELECT id FROM pembiayaan WHERE id_pembiayaan = id_usrtab);

			INSERT INTO penyimpanan_pembiayaan VALUES(NULL,id_user,ID_TABU,"Pencairan Pembiayaan",detail_ptabungan,NOW(),NOW());

			UPDATE penyimpanan_jaminan SET transaksi = detail_jam, updated_at=NOW() WHERE id=id_jam;

			IF(SELECT ROW_COUNT()) THEN

				COMMIT;

			ELSE ROLLBACK;

			END IF;

	END IF;

END */$$

DELIMITER ;



/* Procedure structure for procedure `sp_pencairan_` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_pencairan_` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_pencairan_`(

	IN `id_user` INT,

	IN `id_deposito` INT,

	IN `id_activa` INT,

	IN `id_pasiva` INT,

	IN `id_pengajuan` INT,

	IN `detail_activa` TEXT,

	IN `detail_pasiva` TEXT,

	IN `detail_pdeposito` TEXT,

	IN `detail_deposito` TEXT

,

	IN `jumlah` FLOAT

)
BEGIN 

	DECLARE S_ACT FLOAT;

	DECLARE S_PAS FLOAT;

	

	START TRANSACTION;

	

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Pencairan Deposito",detail_activa,NOW(),NOW());

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Pencairan Deposito",detail_pasiva,NOW(),NOW());

	

	INSERT INTO penyimpanan_deposito VALUES(NULL,id_user,id_deposito,"Pencairan Deposito",detail_pdeposito,NOW(),NOW());

	UPDATE deposito SET detail=detail_deposito, deposito.status="not active", updated_at=NOW() WHERE id=id_deposito;	

	UPDATE pengajuan SET pengajuan.status="Sudah Dikonfirmasi",updated_at=NOW() WHERE id=id_pengajuan;

	

	SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);

	SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);

	

	UPDATE bmt SET saldo= (S_ACT-jumlah), updated_at=NOW() WHERE id=id_activa;	

	UPDATE bmt SET saldo= (S_PAS-jumlah), updated_at=NOW() WHERE id=id_pasiva;	

	

	IF(SELECT ROW_COUNT()) THEN

		COMMIT;

	ELSE ROLLBACK;

	END IF;

END */$$

DELIMITER ;



/* Procedure structure for procedure `sp_transfer_` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_transfer_` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_transfer_`(

	IN `id_dari` INT,

	IN `id_tujuan` INT,

	IN `id_user` INT,

	IN `jumlah` FLOAT,

	IN `detail_dari` TEXT,

	IN `detail_tujuan` TEXT

)
BEGIN 

	DECLARE S_DARI FLOAT;

	DECLARE S_TUJUAN FLOAT;

	

	START TRANSACTION;

	

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_dari,"Transfer antar Rekening",detail_dari,NOW(),NOW());

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_tujuan,"Transfer antar Rekening",detail_tujuan,NOW(),NOW());

	

	SET S_DARI = (SELECT saldo FROM bmt WHERE id = id_dari);

	SET S_TUJUAN = (SELECT saldo FROM bmt WHERE id = id_tujuan);

	

	UPDATE bmt SET saldo= (S_DARI-jumlah), updated_at=NOW() WHERE id=id_dari;	

	UPDATE bmt SET saldo= (S_TUJUAN+jumlah), updated_at=NOW() WHERE id=id_tujuan;	

	

	IF(SELECT ROW_COUNT()) THEN

		COMMIT;

	ELSE ROLLBACK;

	END IF;

END */$$

DELIMITER ;



/* Procedure structure for procedure `sp_wajib_` */



/*!50003 DROP PROCEDURE IF EXISTS  `sp_wajib_` */;



DELIMITER $$



/*!50003 CREATE DEFINER=`u5422597`@`localhost` PROCEDURE `sp_wajib_`(

	IN `id_user` INT,

	IN `id_pengajuan` INT,

	IN `id_activa` INT,

	IN `detail_bmt` TEXT,

	IN `detail_user` TEXT,

	IN `detail_simw` TEXT,

	IN `jumlah` FLOAT

)
BEGIN

	DECLARE S_ACT FLOAT;

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Simpanan Wajib",detail_bmt,NOW(),NOW());

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,339,"Simpanan Wajib",detail_bmt,NOW(),NOW());

	INSERT INTO penyimpanan_wajib_pokok VALUES(NULL,id_user,119,"Simpanan Wajib",detail_simw,NOW(),NOW());

		

	SET S_ACT = (SELECT saldo FROM bmt WHERE id = 341);

	UPDATE bmt SET saldo= (S_ACT+jumlah), updated_at=NOW() WHERE id=341;	

	SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);

	UPDATE bmt SET saldo= (S_ACT+jumlah), updated_at=NOW() WHERE id=id_activa;	

	UPDATE users SET wajib_pokok= detail_user, updated_at=NOW() WHERE id=id_user;	

	UPDATE pengajuan SET pengajuan.status= "Sudah Dikonfirmasi", updated_at=NOW() WHERE id=id_pengajuan;	

	

END */$$

DELIMITER ;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

