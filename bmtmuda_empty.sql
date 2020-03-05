-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2019 at 06:38 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bmtmuda`
--
CREATE DATABASE IF NOT EXISTS `bmtmuda` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `bmtmuda`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `sp_angsur`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_angsur` (IN `id_user` INT, IN `id_usrPem` INT, IN `id_tellbank` INT, IN `id_pem` INT, IN `id_margin` INT, IN `id_pot` INT, IN `id_shu` INT, IN `id_pembiayaan` INT, IN `id_pengajuan` INT, IN `teller` INT, IN `detail_tellbank` TEXT, IN `detail_pem` TEXT, IN `detail_ppem` TEXT, IN `detail_margin` TEXT, IN `detail_pot` TEXT, IN `detail_pembiayaan` TEXT, IN `saldo_all` FLOAT, IN `saldo_pem` FLOAT, IN `saldo_tell` FLOAT, IN `angsur` INT, IN `status_angsur` INT, IN `status_lunas` INT, IN `status_bayar` INT, IN `piutang` INT)  BEGIN
	DECLARE S_MARGIN FLOAT;
	DECLARE S_PEM FLOAT;
	DECLARE S_SHU FLOAT;
	DECLARE S_POT FLOAT;
	DECLARE S_BANK FLOAT;
	DECLARE S_ANG INT;
	DECLARE ANGSURAN VARCHAR(50);
	START TRANSACTION;
		
		SET S_BANK = (SELECT saldo FROM bmt WHERE id = id_tellbank);
		INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_tellbank,"Angsuran",detail_tellbank,NOW(),NOW(),teller);
		UPDATE bmt SET saldo=(saldo_tell+S_BANK), updated_at=NOW() WHERE id=id_tellbank;
					
		IF(status_bayar = 1 || status_bayar = 2  ) THEN
			SET ANGSURAN ="Angsuran Pembiayaan [Margin]";
			SET S_MARGIN = (SELECT saldo FROM bmt WHERE id = id_margin);
			SET S_SHU = (SELECT saldo FROM bmt WHERE id = id_shu);
			UPDATE bmt SET saldo=(saldo_all+S_MARGIN), updated_at=NOW() WHERE id=id_margin;
			UPDATE bmt SET saldo=(saldo_all+S_SHU), updated_at=NOW() WHERE id=id_shu;
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_margin,"Angsuran",detail_margin,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_shu,"Angsuran",detail_margin,NOW(),NOW(),teller);
		
			IF(id_pot != 0) THEN
				SET S_POT = (SELECT saldo FROM bmt WHERE id = id_pot);
				UPDATE bmt SET saldo=(saldo_all+S_POT), updated_at	=NOW() WHERE id=id_pot;
				INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pot,"Angsuran",detail_pot,NOW(),NOW(),teller);
			END IF;
		END IF;
		
		IF(status_bayar = 1)  THEN SET ANGSURAN ="Angsuran Pembiayaan [Margin]";
		ELSEIF(status_bayar = 0)  THEN SET ANGSURAN ="Angsuran Pembiayaan [Pokok]";
		ELSEIF(status_bayar = 2)  THEN SET ANGSURAN ="Angsuran Pembiayaan [Pokok+Margin]"; 
		END IF;			
		

		IF(status_bayar != 1 && piutang = 0) THEN
			SET S_PEM = (SELECT saldo FROM bmt WHERE id = id_pem);
			UPDATE bmt SET saldo=(S_PEM-saldo_pem), updated_at=NOW() WHERE id=id_pem;
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pem,"Angsuran",detail_pem,NOW(),NOW(),teller);
		ELSEIF( piutang = 1) THEN
			SET S_PEM = (SELECT saldo FROM bmt WHERE id = id_pem);
			UPDATE bmt SET saldo=(S_PEM-saldo_pem), updated_at=NOW() WHERE id=id_pem;
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pem,"Angsuran",detail_pem,NOW(),NOW(),teller);
		END IF;
		
		UPDATE pembiayaan SET detail=detail_pembiayaan, updated_at=NOW(), status_angsuran=status_angsur,angsuran_ke=angsur  WHERE id=id_pembiayaan;
		IF(status_lunas) THEN
			UPDATE pembiayaan SET STATUS= "not active"  WHERE id=id_pembiayaan;
		END IF;
		INSERT INTO penyimpanan_pembiayaan VALUES(NULL,id_user,id_usrPem,ANGSURAN,detail_ppem,NOW(),NOW(),teller);
		UPDATE pengajuan SET pengajuan.status="Sudah Dikonfirmasi", updated_at=NOW(),teller=teller WHERE id=id_pengajuan;
			
		IF(SELECT ROW_COUNT()) THEN
			COMMIT;
		ELSE ROLLBACK;
		END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_create_`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_` (IN `tipe` VARCHAR(50), IN `id_usrtab` VARCHAR(50), IN `jenis_tabungan` VARCHAR(50), IN `id_rektab` INT, IN `teller` INT, IN `detail_pasiva` TEXT, IN `detail_activa` TEXT, IN `detail_ptabungan` TEXT, IN `detail_tabungan` TEXT, IN `saldo_activa` FLOAT, IN `saldo_pasiva` FLOAT, IN `status_pengajuan` VARCHAR(50), IN `id_activa` INT, IN `id_pasiva` INT, IN `id_user` INT, IN `id_pengajuan` INT, IN `id_margin` INT, IN `detail_margin` TEXT, IN `jumlah_margin` FLOAT, IN `detail_user` TEXT, IN `detail_pokok` TEXT, IN `detail_wajib` TEXT, IN `saldo_pokok` FLOAT, IN `saldo_wajib` FLOAT, IN `detail_simp` TEXT, IN `detail_simw` TEXT, IN `id_jam` INT, IN `detail_jam` TEXT)  BEGIN
	DECLARE ID_TABU INT;
	DECLARE S_ACT FLOAT;
	DECLARE S_PAS FLOAT;
	DECLARE S_MAR FLOAT;
	IF(tipe ="Tabungan") THEN
		START TRANSACTION;	
			INSERT INTO tabungan VALUES(NULL,id_usrtab,id_rektab,id_user,id_pengajuan,jenis_tabungan,detail_tabungan,NOW(),NOW(),"active");
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Setoran Awal",detail_activa,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Setoran Awal",detail_pasiva,NOW(),NOW(),teller);
			
			UPDATE tabungan SET detail=detail_tabungan,updated_at=NOW() WHERE id_tabungan=id_usrtab;	
			UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW(),teller=teller WHERE id=id_pengajuan;
			
			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);
			SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);
			UPDATE bmt SET saldo= (S_ACT+saldo_activa), updated_at=NOW() WHERE id=id_activa;	
			UPDATE bmt SET saldo= (saldo_pasiva+S_PAS), updated_at=NOW() WHERE id=id_pasiva;	
			SET ID_TABU = (SELECT id FROM tabungan WHERE id_tabungan = id_usrtab);		
			INSERT INTO penyimpanan_tabungan VALUES(NULL,id_user,ID_TABU,"Setoran Awal",detail_ptabungan,NOW(),NOW(),teller);
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	ELSEIF(tipe ="Tabungan Awal") THEN
		START TRANSACTION;	
			INSERT INTO tabungan VALUES(NULL,id_usrtab,id_rektab,id_user,id_pengajuan,jenis_tabungan,detail_tabungan,NOW(),NOW(),"active");
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Setoran Awal",detail_activa,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Setoran Awal",detail_pasiva,NOW(),NOW(),teller);
		
			/*INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Simpanan Pokok",detail_pokok,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Simpanan Wajib",detail_wajib,NOW(),NOW(),teller);
			*/
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,339,"Simpanan Pokok",detail_pokok,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,341,"Simpanan Wajib",detail_wajib,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_wajib_pokok VALUES(NULL,id_user,117,"Simpanan Pokok",detail_simp,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_wajib_pokok VALUES(NULL,id_user,119,"Simpanan Wajib",detail_simw,NOW(),NOW(),teller);
		
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
			UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW(),teller=teller WHERE id=id_pengajuan;
			
			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);
			SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);
			UPDATE bmt SET saldo= (S_ACT+saldo_activa), updated_at=NOW() WHERE id=id_activa;	
			UPDATE bmt SET saldo= (saldo_pasiva+S_PAS), updated_at=NOW() WHERE id=id_pasiva;	
			SET ID_TABU = (SELECT id FROM tabungan WHERE id_tabungan = id_usrtab);		
			INSERT INTO penyimpanan_tabungan VALUES(NULL,id_user,ID_TABU,"Setoran Awal",detail_ptabungan,NOW(),NOW(),teller);
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	ELSEIF(tipe ="Deposito") THEN
		START TRANSACTION;	
			INSERT INTO deposito VALUES(NULL,id_usrtab,id_rektab,id_user,id_pengajuan,jenis_tabungan,detail_tabungan,NOW(),NOW(),NOW(),"active");
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Deposit Awal",detail_activa,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Deposit Awal",detail_pasiva,NOW(),NOW(),teller);
			
			UPDATE deposito SET detail=detail_tabungan,updated_at=NOW() WHERE id_deposito=id_usrtab;	
			UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW(),teller=teller WHERE id=id_pengajuan;
			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);
			SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);
			UPDATE bmt SET saldo= (S_ACT+saldo_activa), updated_at=NOW() WHERE id=id_activa;	
			UPDATE bmt SET saldo= (saldo_pasiva+S_PAS), updated_at=NOW() WHERE id=id_pasiva;	
			
			SET ID_TABU = (SELECT id FROM deposito WHERE id_deposito = id_usrtab);	
			INSERT INTO penyimpanan_deposito VALUES(NULL,id_user,ID_TABU,"Deposit Awal",detail_ptabungan,NOW(),NOW(),teller);
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	ELSEIF(tipe ="Pembiayaan") THEN
		START TRANSACTION;	
			INSERT INTO pembiayaan VALUES(NULL,id_usrtab,id_rektab,id_user,id_pengajuan,jenis_tabungan,detail_tabungan,NOW(),NOW(),NOW(),"active",0,0);
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Pencairan Pembiayaan",detail_activa,NOW(),NOW(),teller);
			INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Pencairan Pembiayaan",detail_pasiva,NOW(),NOW(),teller);
			
			UPDATE pembiayaan SET detail=detail_tabungan,updated_at=NOW() WHERE id_pembiayaan=id_usrtab;	
			UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW(),teller=teller WHERE id=id_pengajuan;
			
			
			SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);
			SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);
			
			UPDATE bmt SET saldo= (S_ACT+saldo_activa), updated_at=NOW() WHERE id=id_activa;	
			UPDATE bmt SET saldo= (saldo_pasiva+S_PAS), updated_at=NOW() WHERE id=id_pasiva;	
			IF(id_margin != 0) THEN
				SET S_MAR = (SELECT saldo FROM bmt WHERE id = id_margin);
				UPDATE bmt SET saldo= (jumlah_margin+S_MAR), updated_at=NOW() WHERE id=id_margin;	
				INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,ID_MARGIN,"Pencairan Pembiayaan",detail_margin,NOW(),NOW(),teller);			
			END IF;
	
			SET ID_TABU = (SELECT id FROM pembiayaan WHERE id_pembiayaan = id_usrtab);
			INSERT INTO penyimpanan_pembiayaan VALUES(NULL,id_user,ID_TABU,"Pencairan Pembiayaan",detail_ptabungan,NOW(),NOW(),teller);
			UPDATE penyimpanan_jaminan SET transaksi = detail_jam, updated_at=NOW() WHERE id=id_jam;
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_debit`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_debit` (IN `detail_pasiva` TEXT, IN `detail_activa` TEXT, IN `detail_ptabungan` TEXT, IN `detail_tabungan` TEXT, IN `saldo_activa` VARCHAR(50), IN `saldo_pasiva` VARCHAR(50), IN `status_activa` VARCHAR(50), IN `status_pasiva` VARCHAR(50), IN `status_tabungan` VARCHAR(50), IN `status_pengajuan` VARCHAR(50), IN `id_activa` INT, IN `id_pasiva` INT, IN `id_user` INT, IN `id_tabungan` INT, IN `id_pengajuan` INT, IN `teller` INT)  BEGIN 
	START TRANSACTION;
	
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,status_activa,detail_activa,NOW(),NOW(),teller);
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,status_pasiva,detail_pasiva,NOW(),NOW(),teller);
	
	INSERT INTO penyimpanan_tabungan VALUES(NULL,id_user,id_tabungan,status_tabungan,detail_ptabungan,NOW(),NOW(),teller);
	UPDATE tabungan SET detail=detail_tabungan,updated_at=NOW() WHERE id=id_tabungan;	
	UPDATE pengajuan SET pengajuan.status=status_pengajuan,updated_at=NOW(),teller=teller WHERE id=id_pengajuan;
		
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
END$$

DROP PROCEDURE IF EXISTS `sp_donasi_`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_donasi_` (IN `id_dari` INT, IN `id_tujuan` INT, IN `id_user` INT, IN `id_maal` INT, IN `teller` INT, IN `jumlah` FLOAT, IN `detail_dari` TEXT, IN `detail_tujuan` TEXT, IN `detail_tabungan` TEXT, IN `detail_maal` TEXT, IN `jenis` VARCHAR(50), IN `maal_` INT, IN `id_rek` INT, IN `detail_rek` TEXT)  BEGIN 	

	DECLARE S_BMT FLOAT;
	DECLARE jenis_ VARCHAR(32);
	START TRANSACTION;
	
	SET jenis_ = "Donasi Maal";
	IF(jenis ="Tabungan") THEN
		IF(maal_ = 0) THEN
			INSERT INTO penyimpanan_maal VALUES(NULL,id_user,id_maal,"Tabungan",detail_tujuan,NOW(),NOW(),teller);	
			UPDATE maal SET detail= detail_maal, updated_at=NOW(),teller=teller WHERE id=id_maal;		
		ELSE SET jenis_ = "Donasi Waqaf";
		END IF;		
		
		INSERT INTO penyimpanan_tabungan VALUES(NULL,id_user,id_dari, jenis_ ,detail_dari,NOW(),NOW(),teller);
		UPDATE tabungan SET detail=detail_tabungan,updated_at=NOW() WHERE id=id_dari;	
		
		SET S_BMT = (SELECT saldo FROM bmt WHERE id = id_rek);
		UPDATE bmt SET saldo= (S_BMT-jumlah), updated_at=NOW() WHERE id=id_rek;
			
	ELSEIF(jenis ="Transfer") THEN
		IF(maal_ = 0) THEN
			INSERT INTO penyimpanan_maal VALUES(NULL,id_user,id_maal,"Transfer",detail_tujuan,NOW(),NOW(),teller);
			UPDATE maal SET detail= detail_maal, updated_at=NOW(),teller=teller WHERE id=id_maal;		
		ELSE SET jenis_ = "Donasi Waqaf";
		END IF;
		
		SET S_BMT = (SELECT saldo FROM bmt WHERE id = id_rek);
		UPDATE bmt SET saldo= (S_BMT+jumlah), updated_at=NOW() WHERE id=id_rek;	
	
	ELSEIF(jenis ="Tunai") THEN
		IF(maal_ = 0) THEN
			INSERT INTO penyimpanan_maal VALUES(NULL,id_user,id_maal,"Tunai",detail_tujuan,NOW(),NOW(),teller);
			UPDATE maal SET detail= detail_maal, updated_at=NOW(),teller=teller WHERE id=id_maal;		
		ELSE SET jenis_ = "Donasi Waqaf";
		END IF;
		
		SET S_BMT = (SELECT saldo FROM bmt WHERE id = id_rek);
		UPDATE bmt SET saldo= (S_BMT+jumlah), updated_at=NOW() WHERE id=id_rek;	
	
	END IF;

	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_rek,jenis_,detail_rek,NOW(),NOW(),teller);		
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_tujuan,jenis_,detail_tujuan,NOW(),NOW(),teller);		
	SET S_BMT = (SELECT saldo FROM bmt WHERE id = id_tujuan);
	UPDATE bmt SET saldo= (S_BMT+jumlah), updated_at=NOW() WHERE id=id_tujuan;	
	
	IF(SELECT ROW_COUNT()) THEN
		COMMIT;
	ELSE ROLLBACK;
	END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_jurnal_`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_jurnal_` (IN `id_tujuan` INT, IN `id_user` INT, IN `jumlah` FLOAT, IN `detail_tujuan` TEXT)  BEGIN

	DECLARE S_TUJUAN FLOAT;
	
	START TRANSACTION;
	
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_tujuan,"Jurnal Lain",detail_tujuan,NOW(),NOW(),id_user);
	
	SET S_TUJUAN = (SELECT saldo FROM bmt WHERE id = id_tujuan);
	
	UPDATE bmt SET saldo= (S_TUJUAN+jumlah), updated_at=NOW() WHERE id=id_tujuan;	
	
	IF(SELECT ROW_COUNT()) THEN
		COMMIT;
	ELSE ROLLBACK;
	END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_pencairan_`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pencairan_` (IN `id_user` INT, IN `id_deposito` INT, IN `id_activa` INT, IN `id_pasiva` INT, IN `teller` INT, IN `id_pengajuan` INT, IN `detail_activa` TEXT, IN `detail_pasiva` TEXT, IN `detail_pdeposito` TEXT, IN `detail_deposito` TEXT, IN `jumlah` FLOAT)  BEGIN 
	DECLARE S_ACT FLOAT;
	DECLARE S_PAS FLOAT;
	
	START TRANSACTION;
	
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Pencairan Deposito",detail_activa,NOW(),NOW(),teller);
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_pasiva,"Pencairan Deposito",detail_pasiva,NOW(),NOW(),teller);
	
	INSERT INTO penyimpanan_deposito VALUES(NULL,id_user,id_deposito,"Pencairan Deposito",detail_pdeposito,NOW(),NOW(),teller);
	UPDATE deposito SET detail=detail_deposito, deposito.status="not active", updated_at=NOW() WHERE id=id_deposito;	
	UPDATE pengajuan SET pengajuan.status="Sudah Dikonfirmasi",updated_at=NOW(),teller=teller WHERE id=id_pengajuan;
	
	SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);
	SET S_PAS = (SELECT saldo FROM bmt WHERE id = id_pasiva);
	
	UPDATE bmt SET saldo= (S_ACT-jumlah), updated_at=NOW() WHERE id=id_activa;	
	UPDATE bmt SET saldo= (S_PAS-jumlah), updated_at=NOW() WHERE id=id_pasiva;	
	
	IF(SELECT ROW_COUNT()) THEN
		COMMIT;
	ELSE ROLLBACK;
	END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_transfer_`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_transfer_` (IN `id_dari` INT, IN `id_tujuan` INT, IN `id_user` INT, IN `teller` INT, IN `jumlah` FLOAT, IN `detail_dari` TEXT, IN `detail_tujuan` TEXT)  BEGIN 
	DECLARE S_DARI FLOAT;
	DECLARE S_TUJUAN FLOAT;
	
	START TRANSACTION;
	
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_dari,"Transfer antar Rekening",detail_dari,NOW(),NOW(),teller);
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_tujuan,"Transfer antar Rekening",detail_tujuan,NOW(),NOW(),teller);
	
	SET S_DARI = (SELECT saldo FROM bmt WHERE id = id_dari);
	SET S_TUJUAN = (SELECT saldo FROM bmt WHERE id = id_tujuan);
	
	UPDATE bmt SET saldo= (S_DARI-jumlah), updated_at=NOW() WHERE id=id_dari;	
	UPDATE bmt SET saldo= (S_TUJUAN+jumlah), updated_at=NOW() WHERE id=id_tujuan;	
	
	IF(SELECT ROW_COUNT()) THEN
		COMMIT;
	ELSE ROLLBACK;
	END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_un_block_`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_un_block_` (IN `id_` INT, IN `tipe` VARCHAR(50), IN `status_` VARCHAR(50))  BEGIN
	IF(tipe ="Tabungan") THEN
		START TRANSACTION;	
			UPDATE tabungan SET STATUS=status_ WHERE tabungan.id=id_;	
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	ELSEIF(tipe ="Deposito") THEN
		START TRANSACTION;	
			UPDATE deposito SET STATUS=status_ WHERE deposito.id=id_;	
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	ELSEIF(tipe ="Pembiayaan") THEN
		START TRANSACTION;	
			UPDATE pembiayaan SET STATUS=status_ WHERE pembiayaan.id=id_;	
			IF(SELECT ROW_COUNT()) THEN
				COMMIT;
			ELSE ROLLBACK;
			END IF;
	END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_wajib_`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_wajib_` (IN `id_user` INT, IN `id_pengajuan` INT, IN `id_activa` INT, IN `teller` INT, IN `detail_bmt` TEXT, IN `detail_user` TEXT, IN `detail_simw` TEXT, IN `jumlah` FLOAT)  BEGIN
	DECLARE S_ACT FLOAT;
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,id_activa,"Simpanan Wajib",detail_bmt,NOW(),NOW(),teller);
	INSERT INTO penyimpanan_bmt VALUES(NULL,id_user,341,"Simpanan Wajib",detail_bmt,NOW(),NOW(),teller);
	INSERT INTO penyimpanan_wajib_pokok VALUES(NULL,id_user,119,"Simpanan Wajib",detail_simw,NOW(),NOW(),teller);
		
	SET S_ACT = (SELECT saldo FROM bmt WHERE id = 341);
	UPDATE bmt SET saldo= (S_ACT+jumlah), updated_at=NOW() WHERE id=341;	
	SET S_ACT = (SELECT saldo FROM bmt WHERE id = id_activa);
	UPDATE bmt SET saldo= (S_ACT+jumlah), updated_at=NOW() WHERE id=id_activa;	
	UPDATE users SET wajib_pokok= detail_user, updated_at=NOW() WHERE id=id_user;	
	UPDATE pengajuan SET pengajuan.status= "Sudah Dikonfirmasi", updated_at=NOW(),teller=teller WHERE id=id_pengajuan;	
	
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bmt`
--

DROP TABLE IF EXISTS `bmt`;
CREATE TABLE `bmt` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_bmt` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `nama` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `saldo` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bmt`
--

INSERT INTO `bmt` (`id`, `id_bmt`, `id_rekening`, `nama`, `saldo`, `detail`, `created_at`, `updated_at`) VALUES
(262, '1', 1, 'AKTIVA', '', '', '2018-06-24 09:51:31', '2019-08-14 13:59:29'),
(263, '2', 2, 'HUTANG', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(264, '2.1', 3, 'SIMPANAN SYARIAH', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(265, '2.1.1', 4, 'SIMPANAN MUDHAROBAH UMUM', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(266, '2.1.1.1', 5, 'SIMPANAN MUDHAROBAH UMUM', '', '', '2018-06-24 09:51:31', '2019-11-11 06:57:11'),
(267, '2.1.2', 6, 'SIMPANAN MUDHAROBAH BERJANGKA', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(268, '2.1.2.1', 7, 'SIMPANAN TARBIYAH / PENDIDIKAN', '', '', '2018-06-24 09:51:31', '2019-11-13 05:22:50'),
(269, '2.1.2.2', 8, 'SIMPANAN IDUL FITRI', '', '', '2018-06-24 09:51:31', '2019-03-16 02:46:09'),
(270, '2.1.2.3', 9, 'SIMPANAN IDUL ADHA', '', '', '2018-06-24 09:51:31', '2019-11-11 07:02:35'),
(271, '2.1.2.4', 10, 'SIMPANAN WALIMAH', '', '', '2018-06-24 09:51:31', '2018-10-13 02:05:53'),
(272, '1.1', 11, 'KAS', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(273, '1.1.1', 12, 'KAS', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(274, '1.1.1.1', 13, 'KAS TELLER 1', '', '', '2018-06-24 09:51:31', '2019-10-28 04:47:51'),
(275, '1.1.1.2', 14, 'KAS TELLER 2', '', '', '2018-06-24 09:51:31', '2019-03-16 01:06:10'),
(276, '2.1.3', 44, 'SIMPANAN WADIAH', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(277, '2.1.3.1', 45, 'SIMPANAN WADIAH', '', '', '2018-06-24 09:51:31', '2019-08-19 03:32:40'),
(278, '2.2', 46, 'MUDHARABAH SYARIAH', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(279, '2.2.1', 47, 'MUDHARABAH BERJANGKA', '', '', '2018-06-24 09:51:31', '2018-06-24 09:51:31'),
(280, '2.2.1.1', 48, 'MUDHARABAH 1 BULAN', '', '', '2018-06-24 09:51:32', '2019-10-28 09:16:17'),
(281, '2.2.1.2', 49, 'MUDHARABAH  3 BULAN', '', '', '2018-06-24 09:51:32', '2019-11-11 07:12:01'),
(282, '2.3', 50, 'ANTAR KOPERASI PASIVA', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(283, '2.4', 54, 'PINJAMAN DARI BANK DAN NON BANK', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(284, '2.4.1', 55, 'PINJAMAN BANK', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(285, '2.4.2', 56, 'PINJAMAN NON BANK', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(286, '2.4.1.1', 57, 'PINJAMAN BANK MANDIRI SYARIAH JEMUR', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(287, '2.4.1.2', 58, 'PINJAMAN BPR SYARIAH MOJOKERTO', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(288, '2.4.2.1', 59, 'JAMSOSTEK KARIMUNJAWA SURABAYA', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(289, '2.4.2.2', 60, 'JAMSOSTEK DARMO SURABAYA', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(290, '2.4.2.3', 61, 'JAMSOSTEK PERAK SURABAYA', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(291, '1.2', 62, 'BANK', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(292, '1.3', 63, 'ANTAR KOPERASI AKTIVA', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(293, '1.4', 64, 'INVESTASI', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(294, '1.5', 65, 'PEMBIAYAAN', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(295, '1.6', 66, 'PEMBIAYAAN LAIN-LAIN', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(296, '1.7', 67, 'PENYISIHAN PIUTANG', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(297, '1.8', 68, 'BIAYA DIBAYAR DIMUKA', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(298, '1.9', 69, 'PENYERTAAN PADA ENTITAS LAIN', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(299, '1.10', 70, 'TANAH DAN BANGUNGAN', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(300, '1.11', 74, 'GEDUNG KANTOR', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(301, '1.12', 77, 'AKUMULASI PENYUST. GEDUNG KANTOR', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(302, '1.13', 78, 'KENDARAAN', '', '', '2018-06-24 09:51:32', '2018-06-24 09:51:32'),
(303, '1.14', 79, 'AKUMULASI PENYUST. KENDARAAN', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(304, '1.15', 80, 'INVENTARIS KANTOR', '', '', '2018-06-24 09:51:33', '2019-08-29 07:22:19'),
(305, '1.16', 81, 'BIAYA PRA OPERASIONAL', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(306, '1.2.1', 82, 'BANK SYARIAH', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(307, '1.2.2', 84, 'BANK KONVENSIONAL', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(308, '1.2.1.1', 86, 'BANK KRIAN', '', '', '2018-06-24 09:51:33', '2019-11-11 05:35:24'),
(309, '1.2.1.2', 87, 'BANK JEMUR SARI SURABAYA', '', '', '2018-06-24 09:51:33', '2019-08-29 07:04:47'),
(310, '1.2.2.1', 88, 'MANDIRI CABANG UNAIR', '', '', '2018-06-24 09:51:33', '2018-10-01 06:45:11'),
(311, '1.3.1', 89, 'A.K.A. KOPERASI', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(312, '1.3.1.1', 90, 'A.K.A. PUSAT KJKS JATIM MICROFIN', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(313, '1.3.1.2', 91, 'INKOPSYAH BMT', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(314, '1.3.1.3', 92, 'PUSKOPSYAH JATIM AL AKBAR', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(315, '1.4.1', 93, 'INVESTASI LAINNYA', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(316, '1.4.1.1', 94, 'INVESTASI CABANG BUNGA GRESIK', '', '', '2018-06-24 09:51:33', '2019-08-29 07:20:45'),
(317, '1.5.1', 95, 'PEMBIAYAAN MDA', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(318, '1.5.2', 96, 'PEMBIAYAAN MRB', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(319, '1.5.3', 97, 'PEMBIAYAAN QORD', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(320, '1.6.1', 98, 'PEMBIAYAAN LAIN EKSTERNAL', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(321, '1.5.1.1', 99, 'PEMBIAYAAN MDA', '', '', '2018-06-24 09:51:33', '2019-11-11 05:35:24'),
(322, '1.5.2.1', 100, 'PEMBIAYAAN MRB', '', '', '2018-06-24 09:51:33', '2019-10-28 05:28:25'),
(323, '1.5.2.2', 101, 'PIUTANG MRB YANG DITANGGUHKAN', '', '', '2018-06-24 09:51:33', '2019-10-28 05:28:25'),
(324, '1.5.3.1', 102, 'PEMBIAYAAN QORD', '', '', '2018-06-24 09:51:33', '2018-06-24 09:51:33'),
(325, '1.6.1.1', 103, 'PEMBY. MDA LAIN-LAIN', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(326, '1.7.1', 104, 'PENYISIHAN PIUTANG UMUM', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(327, '2.1.2.5', 105, 'SIMPANAN ZIAROH/WISATA', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(328, '2.1.2.6', 106, 'SIMPANAN UNIT LAIN', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(329, '2.3.1', 107, 'A.K.P KOPERASI', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(330, '2.3.1.3', 108, 'A.K.P PUSAT KJKS JATIM MICROFIN', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(331, '2.3.1.1', 109, 'INKOPSYAH', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(332, '2.3.1.2', 110, 'BMT MBS', '', '', '2018-06-24 09:51:34', '2018-12-22 02:09:28'),
(333, '2.5', 111, 'DANA PENDIDIKAN', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(334, '2.6', 112, 'ZAKAT', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(335, '2.7', 113, 'DANA SOSIAL', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(336, '2.8', 114, 'RUPA-RUPA PASIVA', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(337, '3.1', 115, 'MODAL', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(338, '3.2', 116, 'KEKAYAAN & SHU', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(339, '3.2.1', 117, 'SIMPANAN POKOK ANGGOTA', '', '', '2018-06-24 09:51:34', '2019-11-11 06:39:49'),
(340, '3.2.2', 118, 'WAQAF UANG', '', '', '2018-06-24 09:51:34', '2019-10-10 05:14:29'),
(341, '3.2.3', 119, 'SIMPANAN WAJIB ANGGOTA', '', '', '2018-06-24 09:51:34', '2019-11-11 06:39:49'),
(342, '3.2.4', 120, 'SIMPANAN SUKA RELA ANGGOTA', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(343, '3.2.5', 121, 'DANA CADANGAN UMUM', '', '', '2018-06-24 09:51:34', '2019-02-16 06:49:43'),
(344, '3.2.6', 122, 'SHU BERJALAN', '', '', '2018-06-24 09:51:34', '2019-09-21 07:37:14'),
(345, '3', 123, 'MODAL', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(346, '4', 125, 'PENDAPATAN', '', '', '2018-06-24 09:51:34', '2018-06-24 09:51:34'),
(347, '5', 126, 'BIAYA', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(348, '4.1', 127, 'PENDAPATAN OPERASIONAL', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(349, '4.1.1', 128, 'PENDAPATAN PEMBIAYAAN', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(350, '4.1.1.1', 129, 'PENDAPATAN BH PEMBY MDA', '', '', '2018-06-24 09:51:35', '2019-09-21 07:37:14'),
(351, '4.1.1.2', 130, 'PENDAPATAN BH PEMBY MRB', '', '', '2018-06-24 09:51:35', '2019-08-14 13:59:29'),
(352, '4.1.1.3', 131, 'PENDAPATAN BH PEMBY QORD', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(353, '4.1.2', 132, 'PENDAPATAN OPERASIONAL LAINNYA', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(354, '4.1.2.1', 133, 'PENDAPATAN BH LAINNYA', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(355, '5.1', 134, 'BEBAN LANGSUNG TABUNGAN', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(356, '5.2', 135, 'BEBAN LANGSUNG ANTAR KOPERASI PASIVA', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(357, '5.3', 136, 'BEBAN LANGSUNG PINJAM BANK DAN NON BANK', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(358, '5.4', 137, 'BEBAN OPERASIONAL DAN ADMINISTRASI', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(359, '5.1.1', 138, 'BEBAN BH TABUNGAN MDA UMUM', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(360, '5.1.1.1', 139, 'BEBAN BH TABUNGAN MDA UMUM', '', '', '2018-06-24 09:51:35', '2019-02-28 06:04:05'),
(361, '5.1.2', 140, 'BEBAN BH TABUNGAN MDA BERJANGKA', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(362, '5.1.2.1', 141, 'BEBAN TAB. TARBIYAH / PENDIDIKAN', '', '', '2018-06-24 09:51:35', '2019-02-28 06:04:05'),
(363, '5.1.2.2', 142, 'BEBAN TAB. IDUL FITRI', '', '', '2018-06-24 09:51:35', '2018-11-19 23:52:07'),
(364, '5.1.2.3', 143, 'BEBAN TAB. IDUL ADHA', '', '', '2018-06-24 09:51:35', '2018-10-21 04:08:00'),
(365, '5.1.2.4', 144, 'BEBAN TAB. WALIMAH', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(366, '5.1.2.5', 145, 'BEBAN TAB. ZIARAH / WISATA', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(367, '5.1.2.6', 146, 'BEBAN TAB. UNIT LAIN', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(368, '5.2.1', 147, 'BEBAN BH ANTAR KOP. SYARIAH', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(369, '5.2.1.1', 148, 'BEBAN BH INKOPSYAH', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(370, '5.2.1.2', 149, 'BEBAN BH  MICROFIN', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(371, '5.3.1', 150, 'BEBAN BH PINJAMAN BANK', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(372, '5.3.1.1', 151, 'BEBAN BH PINJAMAN MANDIRI SYARIAH JEMUR', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(373, '5.3.1.2', 152, 'BEBAN BH PINJAMAN BPR SYARIAH MOJOKERTO', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(374, '5.4.1', 153, 'BIAYA KARYAWAN', '', '', '2018-06-24 09:51:35', '2018-06-24 09:51:35'),
(375, '5.4.1.1', 154, 'BEBAN BISYAROH KARYAWAN', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(376, '5.4.2', 155, 'BIAYA KANTOR', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(377, '5.4.2.1', 156, 'BIAYA PERLENGKAPAN KANTOR', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(378, '5.4.2.2', 157, 'BIAYA LISTRIK, PDAM, DAN TELEPON', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(379, '5.4.2.3', 158, 'BIAYA TRANSPORTASI', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(380, '5.4.2.4', 159, 'BIAYA ORGANISASI', '', '', '2018-06-24 09:51:36', '2018-10-08 13:03:36'),
(381, '5.4.2.5', 160, 'BIAYA PROMOSI', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(382, '5.5', 161, 'BEBAN DEPOSITO', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(383, '5.5.1', 162, 'BEBAN MUDHARABAH SYARIAH', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(384, '5.5.1.1', 163, 'BEBAN MUDHARABAH BERJANGKA', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(385, '5.5.1.1.1', 164, 'BEBAN MUDHARABAH 1 BULAN', '', '', '2018-06-24 09:51:36', '2018-11-19 23:52:07'),
(386, '5.5.1.1.2', 165, 'BEBAN MUDHARABAH 3 BULAN', '', '', '2018-06-24 09:51:36', '2019-02-28 06:04:05'),
(387, '5.5.1.1.3', 166, 'BEBAN MUDHARABAH 6 BULAN', '', '', '2018-06-24 09:51:36', '2018-11-19 23:52:07'),
(388, '5.5.1.1.4', 167, 'BEBAN MUDHARABAH 9 BULAN', '', '', '2018-06-24 09:51:36', '2018-11-19 23:52:07'),
(389, '5.5.1.1.5', 168, 'BEBAN MUDHARABAH 12 BULAN', '', '', '2018-06-24 09:51:36', '2019-02-28 06:04:05'),
(390, '2.2.1.3', 169, 'MUDHARABAH 6 BULAN', '', '', '2018-06-24 09:51:36', '2019-11-11 08:34:32'),
(391, '2.2.1.4', 170, 'MUDHARABAH 9 BULAN', '', '', '2018-06-24 09:51:36', '2019-10-28 05:16:37'),
(392, '2.2.1.5', 171, 'MUDHARABAH 12 BULAN', '', '', '2018-06-24 09:51:36', '2019-10-28 09:17:59'),
(393, '4.1.3', 172, 'PENDAPATAN ADMINISTRASI', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(394, '4.1.3.1', 173, 'PENDAPATAN ADMINISTRASI PEMBIAYAAN', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(395, '2.9', 174, 'PEMINDAHAN BUKUAN', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(396, '2.9.1', 175, 'PEMINDAHAN BUKUAN', '', '', '2018-06-24 09:51:36', '2018-06-24 09:51:36'),
(397, '3.2.7', 176, 'SHU YANG HARUS DIBAGIKAN', '', '', '2018-07-14 03:53:54', '2018-07-30 08:30:45'),
(399, '4.1.3.2', 178, 'PENDAPATAN ADMINISTRASI TABUNGAN', '', '', '2018-07-24 12:39:48', '2018-07-24 12:39:48'),
(400, '2.1.2.7', 179, 'SIMPANAN MAAL', '', '', '2018-08-02 00:45:36', '2019-10-07 09:00:40'),
(401, '2.10', 180, 'PAJAK YANG DITANGGUHKAN', '', '', '2018-08-14 07:05:33', '2019-02-28 06:04:05'),
(402, '5.1.1.2', 181, 'BEBAN BH TABUNGAN WADIAH', '', '', '2018-11-16 08:24:40', '2018-11-16 08:24:40'),
(403, '5.6', 182, 'BIAYA PAJAK', '', '', '2019-02-28 06:08:25', '2019-02-28 06:11:33'),
(405, '1.1.2', 184, 'KAS TELLER1', '', '', '2019-09-21 14:14:21', '2019-11-13 05:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `deposito`
--

DROP TABLE IF EXISTS `deposito`;
CREATE TABLE `deposito` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_deposito` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_pengajuan` int(10) UNSIGNED NOT NULL,
  `jenis_deposito` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `tempo` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jaminan`
--

DROP TABLE IF EXISTS `jaminan`;
CREATE TABLE `jaminan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_jaminan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `jaminan`
--

INSERT INTO `jaminan` (`id`, `nama_jaminan`, `status`, `detail`, `created_at`, `updated_at`) VALUES
(3, 'mobil', 'active', '[\"mobil\"]', '2018-12-28 12:59:08', '2018-12-28 12:59:08'),
(4, 'sepeda motor', 'active', '[\"sepeda motor\"]', '2018-12-28 12:59:52', '2018-12-28 12:59:52'),
(5, 'rumah & tanah', 'active', '[\"rumah & tanah\"]', '2018-12-28 13:00:16', '2018-12-28 13:00:16'),
(6, 'rumah & tanah shm', 'active', '[\"luas\"]', '2019-04-12 07:55:10', '2019-04-12 07:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `maal`
--

DROP TABLE IF EXISTS `maal`;
CREATE TABLE `maal` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_maal` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `nama_kegiatan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `tanggal_pelaksaaan` date NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `maal`
--

INSERT INTO `maal` (`id`, `id_maal`, `id_rekening`, `nama_kegiatan`, `tanggal_pelaksaaan`, `status`, `detail`, `created_at`, `updated_at`, `teller`) VALUES
(17, '1', 179, 'KHITANAN MASSA', '2019-10-09', 'active', '{\"detail\":\"beli alat2 khitan\",\"dana\":50000000,\"terkumpul\":17500000,\"path_poster\":\"\\/pMeggEnuK7miR3vrK6b5mnpbVA66sPs0edYk3Mtd.jpeg\"}', '2019-10-02 03:27:48', '2019-10-02 03:52:03', 52),
(18, '18', 179, 'peduli wamena', '2019-10-30', 'active', '{\"detail\":\"sembako u masyarakat wamena\",\"dana\":100000000,\"terkumpul\":7500000,\"path_poster\":\"\\/2dhCgEckp6wbykpnawMJvfuCEqXAjdpGnmVtfiHx.jpeg\"}', '2019-10-02 03:47:24', '2019-10-02 03:51:35', 52),
(19, '19', 179, 'RENOVASI LANGGAR YUSUF', '2019-11-10', 'active', '{\"detail\":\"ganti atap, ganti tembok sebagian,bikin kamar inap,bangun pagar depan\",\"dana\":25000000,\"terkumpul\":2000000,\"path_poster\":\"\\/TiPRNlyzT9jfQ0UXloI2fHeCXuC5jCLM6MnKjo3L.jpeg\"}', '2019-10-07 03:03:52', '2019-10-07 09:00:40', 52);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_04_25_225926_create_datamaster_table', 1),
(4, '2018_04_25_232548_create_datatransaksi_table', 1),
(5, '2018_05_26_064044_add_status_table', 2),
(6, '2018_05_28_055737_add_status_angsur', 3),
(10, '2018_06_24_162719_add_penyimpanan_rekening', 4),
(11, '2018_07_15_063403_add_wajib_pokok', 4),
(12, '2018_07_15_063519_add_penyimpanan_wajib_pokok', 4),
(13, '2018_07_15_064459_add_role', 5),
(14, '2018_07_25_110259_create_shu_table', 6),
(15, '2018_07_26_204217_create_jaminan_table', 7),
(16, '2018_07_30_132240_create_penyimpanan_user', 7),
(17, '2018_08_03_143836_create_penyimpanan_jaminan', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembiayaan`
--

DROP TABLE IF EXISTS `pembiayaan`;
CREATE TABLE `pembiayaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_pembiayaan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_pengajuan` int(10) UNSIGNED NOT NULL,
  `jenis_pembiayaan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `tempo` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `status_angsuran` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `angsuran_ke` varchar(191) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan`
--

DROP TABLE IF EXISTS `pengajuan`;
CREATE TABLE `pengajuan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `jenis_pengajuan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `kategori` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_bmt`
--

DROP TABLE IF EXISTS `penyimpanan_bmt`;
CREATE TABLE `penyimpanan_bmt` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_bmt` int(10) UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_deposito`
--

DROP TABLE IF EXISTS `penyimpanan_deposito`;
CREATE TABLE `penyimpanan_deposito` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_deposito` int(10) UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_jaminan`
--

DROP TABLE IF EXISTS `penyimpanan_jaminan`;
CREATE TABLE `penyimpanan_jaminan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_jaminan` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_pengajuan` int(10) UNSIGNED NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_maal`
--

DROP TABLE IF EXISTS `penyimpanan_maal`;
CREATE TABLE `penyimpanan_maal` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_donatur` int(10) UNSIGNED NOT NULL,
  `id_maal` int(10) UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_pembiayaan`
--

DROP TABLE IF EXISTS `penyimpanan_pembiayaan`;
CREATE TABLE `penyimpanan_pembiayaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_pembiayaan` int(10) UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_rekening`
--

DROP TABLE IF EXISTS `penyimpanan_rekening`;
CREATE TABLE `penyimpanan_rekening` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `periode` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `saldo` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_shu`
--

DROP TABLE IF EXISTS `penyimpanan_shu`;
CREATE TABLE `penyimpanan_shu` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_shu` int(10) UNSIGNED NOT NULL,
  `periode` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_tabungan`
--

DROP TABLE IF EXISTS `penyimpanan_tabungan`;
CREATE TABLE `penyimpanan_tabungan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_tabungan` int(10) UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_users`
--

DROP TABLE IF EXISTS `penyimpanan_users`;
CREATE TABLE `penyimpanan_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `periode` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `penyimpanan_users`
--

INSERT INTO `penyimpanan_users` (`id`, `id_user`, `periode`, `transaksi`, `created_at`, `updated_at`) VALUES
(20, 57, '2019', '{\"wajib\":480000,\"pokok\":\"50000\",\"margin\":380000}', '2019-08-14 02:52:31', '2019-09-21 07:37:14'),
(21, 58, '2018', '{\"pokok\":\"50000\",\"wajib\":\"50000\"}', '2019-09-09 09:02:49', '2019-09-09 09:02:49'),
(22, 58, '2018', '{\"pokok\":\"50000\",\"wajib\":\"50000\"}', '2019-09-21 08:16:19', '2019-09-21 08:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `penyimpanan_wajib_pokok`
--

DROP TABLE IF EXISTS `penyimpanan_wajib_pokok`;
CREATE TABLE `penyimpanan_wajib_pokok` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `transaksi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekening`
--

DROP TABLE IF EXISTS `rekening`;
CREATE TABLE `rekening` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_rekening` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `id_induk` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `nama_rekening` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `tipe_rekening` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `katagori_rekening` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rekening`
--

INSERT INTO `rekening` (`id`, `id_rekening`, `id_induk`, `nama_rekening`, `tipe_rekening`, `katagori_rekening`, `detail`, `created_at`, `updated_at`) VALUES
(1, '1', 'master', 'AKTIVA', 'master', '', '', '2018-05-07 03:06:59', '2018-06-10 02:05:16'),
(2, '2', 'master', 'HUTANG', 'master', '', '', '2018-05-07 03:07:14', '2018-05-07 03:15:49'),
(3, '2.1', '2', 'SIMPANAN SYARIAH', 'induk', '', '', '2018-05-07 03:17:42', '2018-05-07 03:17:42'),
(4, '2.1.1', '2.1', 'SIMPANAN MUDHAROBAH UMUM', 'induk', '', '', '2018-05-07 03:18:20', '2018-05-07 03:18:20'),
(5, '2.1.1.1', '2.1.1', 'SIMPANAN MUDHAROBAH UMUM', 'detail', 'TABUNGAN', '{\"nisbah_anggota\":\"10\",\"nisbah_bank\":90,\"rek_margin\":\"5.1.1.1\",\"rek_pendapatan\":\"5.1.1.1\",\"nasabah_wajib_pajak\":\"0\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"25000\",\"setoran_awal\":\"0\",\"setoran_min\":\"100\",\"saldo_min_margin\":\"25000\",\"adm_tutup_tab\":\"00\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}', '2018-05-07 03:18:43', '2019-08-29 06:57:08'),
(6, '2.1.2', '2.1', 'SIMPANAN MUDHAROBAH KHUSUS', 'induk', '', '', '2018-05-07 03:19:10', '2018-11-11 11:27:53'),
(7, '2.1.2.1', '2.1.2', 'SIMPANAN TARBIYAH / PENDIDIKAN', 'detail', 'TABUNGAN', '{\"nisbah_anggota\":\"10\",\"nisbah_bank\":90,\"rek_margin\":\"5.1.2.1\",\"rek_pendapatan\":\"5.1.2.1\",\"nasabah_wajib_pajak\":\"1\",\"nasabah_bayar_zis\":\"1\",\"saldo_min\":\"10000\",\"setoran_awal\":\"0\",\"setoran_min\":\"0\",\"saldo_min_margin\":\"10000\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}', '2018-05-07 03:21:24', '2019-08-16 08:23:24'),
(8, '2.1.2.2', '2.1.2', 'SIMPANAN IDUL FITRI', 'detail', 'TABUNGAN', '{\"nisbah_anggota\":\"20\",\"nisbah_bank\":80,\"rek_margin\":\"5.1.2.2\",\"rek_pendapatan\":\"4.1.3.2\",\"nasabah_wajib_pajak\":\"1\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"25000\",\"setoran_awal\":\"0\",\"setoran_min\":\"1000\",\"saldo_min_margin\":\"25000\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}', '2018-05-07 03:21:51', '2019-08-16 07:55:16'),
(9, '2.1.2.3', '2.1.2', 'SIMPANAN IDUL ADHA', 'detail', 'TABUNGAN', '{\"nisbah_anggota\":\"0\",\"nisbah_bank\":100,\"rek_margin\":\"5.1.2.3\",\"rek_pendapatan\":\"4.1.3.2\",\"nasabah_wajib_pajak\":\"1\",\"nasabah_bayar_zis\":\"1\",\"saldo_min\":\"25000\",\"setoran_awal\":\"0\",\"setoran_min\":\"01000\",\"saldo_min_margin\":\"25000\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}', '2018-05-07 03:22:15', '2019-08-16 07:56:04'),
(10, '2.1.2.4', '2.1.2', 'SIMPANAN WALIMAH', 'detail', 'TABUNGAN', '{\"nisbah_anggota\":\"30\",\"nisbah_bank\":70,\"rek_margin\":\"5.1.2.4\",\"rek_pendapatan\":\"4.1.3.2\",\"nasabah_wajib_pajak\":\"1\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"25000\",\"setoran_awal\":\"0\",\"setoran_min\":\"1000\",\"saldo_min_margin\":\"25000\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}', '2018-05-07 03:22:45', '2019-08-16 07:58:04'),
(11, '1.1', '1', 'KAS', 'induk', '', '', '2018-05-14 22:40:53', '2019-01-07 01:44:18'),
(12, '1.1.1', '1.1', 'KAS ADMIN', 'detail', 'ADMIN', '', '2018-05-14 22:41:10', '2019-09-21 14:17:59'),
(13, '1.1.3', '1.1', 'KAS TELLER 2', 'detail', 'TELLER', '', '2018-05-14 22:41:25', '2019-09-21 14:19:43'),
(14, '1.1.4', '1.1', 'KAS TELLER 3', 'detail', 'TELLER', '', '2018-05-14 22:41:44', '2019-09-21 14:19:54'),
(44, '2.1.3', '2.1', 'SIMPANAN WADIAH', 'induk', '', '', '2018-05-14 23:11:50', '2018-05-14 23:11:50'),
(45, '2.1.3.1', '2.1.3', 'SIMPANAN WADIAH', 'detail', 'TABUNGAN', '{\"nisbah_anggota\":\"0.1\",\"nisbah_bank\":99.900000000000005684341886080801486968994140625,\"rek_margin\":\"5.1.1.1\",\"rek_pendapatan\":\"5.1.1.1\",\"nasabah_wajib_pajak\":\"0\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"5000\",\"setoran_awal\":\"0\",\"setoran_min\":\"100\",\"saldo_min_margin\":\"5000\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}', '2018-05-14 23:12:14', '2019-08-16 07:58:34'),
(46, '2.2', '2', 'MUDHARABAH SYARIAH', 'induk', '', '', '2018-05-14 23:13:35', '2018-05-14 23:13:35'),
(47, '2.2.1', '2.2', 'MUDHARABAH BERJANGKA', 'induk', '', '', '2018-05-14 23:14:00', '2018-05-14 23:14:00'),
(48, '2.2.1.1', '2.2.1', 'MUDHARABAH 1 BULAN', 'detail', 'DEPOSITO', '{\"rek_margin\":\"5.5.1.1.1\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"1\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"0\"}', '2018-05-14 23:14:27', '2019-09-03 03:50:49'),
(49, '2.2.1.2', '2.2.1', 'MUDHARABAH  3 BULAN', 'detail', 'DEPOSITO', '{\"rek_margin\":\"5.5.1.1.2\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"3\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"0\"}', '2018-05-14 23:14:51', '2018-05-22 11:24:16'),
(50, '2.3', '2', 'ANTAR KOPERASI PASIVA', 'induk', '', '', '2018-05-14 23:15:21', '2018-05-14 23:15:21'),
(54, '2.4', '2', 'PINJAMAN DARI BANK DAN NON BANK', 'induk', '', '', '2018-05-14 23:18:34', '2018-05-14 23:18:34'),
(55, '2.4.1', '2.4', 'PINJAMAN BANK', 'induk', '', '', '2018-05-14 23:18:51', '2018-05-14 23:18:51'),
(56, '2.4.2', '2.4', 'PINJAMAN NON BANK', 'induk', '', '', '2018-05-14 23:19:04', '2018-05-14 23:19:04'),
(57, '2.4.1.1', '2.4.1', 'PINJAMAN BANK MANDIRI SYARIAH JEMUR', 'detail', '', '', '2018-05-14 23:19:34', '2018-05-14 23:19:34'),
(58, '2.4.1.2', '2.4.1', 'PINJAMAN BPR SYARIAH MOJOKERTO', 'detail', '', '', '2018-05-14 23:20:12', '2018-05-14 23:20:12'),
(59, '2.4.2.1', '2.4.2', 'JAMSOSTEK KARIMUNJAWA SURABAYA', 'detail', '', '', '2018-05-14 23:20:37', '2018-05-14 23:20:37'),
(60, '2.4.2.2', '2.4.2', 'JAMSOSTEK DARMO SURABAYA', 'detail', '', '', '2018-05-14 23:21:11', '2018-05-14 23:21:11'),
(61, '2.4.2.3', '2.4.2', 'JAMSOSTEK PERAK SURABAYA', 'detail', '', '', '2018-05-14 23:21:40', '2018-05-14 23:21:40'),
(62, '1.2', '1', 'BANK', 'induk', '', '', '2018-05-19 07:25:36', '2018-05-19 07:25:36'),
(63, '1.3', '1', 'ANTAR KOPERASI AKTIVA', 'induk', '', '', '2018-05-19 07:26:10', '2018-05-19 07:26:29'),
(64, '1.4', '1', 'INVESTASI', 'induk', '', '', '2018-05-19 07:27:52', '2018-05-19 07:27:52'),
(65, '1.5', '1', 'PEMBIAYAAN', 'induk', '', '', '2018-05-19 07:28:11', '2018-05-19 07:28:11'),
(66, '1.6', '1', 'PEMBIAYAAN LAIN-LAIN', 'induk', '', '', '2018-05-19 07:28:33', '2018-05-19 07:28:33'),
(67, '1.7', '1', 'PENYISIHAN PIUTANG', 'induk', '', '', '2018-05-19 07:32:20', '2018-05-19 09:08:13'),
(68, '1.8', '1', 'BIAYA DIBAYAR DIMUKA', 'detail', '', '', '2018-05-19 07:33:05', '2018-11-11 11:26:41'),
(69, '1.9', '1', 'PENYERTAAN PADA ENTITAS LAIN', 'induk', '', '', '2018-05-19 07:33:32', '2018-05-19 07:33:32'),
(70, '1.10', '1', 'TANAH DAN BANGUNGAN', 'detail', '', '', '2018-05-19 07:33:56', '2018-11-11 11:20:16'),
(74, '1.11', '1', 'GEDUNG KANTOR', 'detail', '', '', '2018-05-19 07:54:19', '2018-11-11 11:20:47'),
(77, '1.12', '1', 'AKUMULASI PENYUST. GEDUNG KANTOR', 'detail', '', '', '2018-05-19 08:25:28', '2018-11-11 11:20:59'),
(78, '1.13', '1', 'KENDARAAN', 'detail', '', '', '2018-05-19 08:25:43', '2018-11-11 11:21:18'),
(79, '2.11', '2', 'AKUMULASI PENYUST. KENDARAAN', 'induk', '', '', '2018-05-19 08:33:47', '2019-03-05 02:35:58'),
(80, '1.15', '1', 'INVENTARIS KANTOR', 'detail', '', '', '2018-05-19 08:34:05', '2018-11-11 11:22:01'),
(81, '1.16', '1', 'BIAYA PRA OPERASIONAL', 'detail', '', '', '2018-05-19 08:34:25', '2018-11-11 11:22:29'),
(82, '1.2.1', '1.2', 'BANK SYARIAH', 'induk', '', '', '2018-05-19 08:39:14', '2018-05-22 16:15:35'),
(84, '1.2.2', '1.2', 'BANK KONVENSIONAL', 'induk', '', '', '2018-05-19 08:43:11', '2018-05-19 08:43:11'),
(86, '1.2.1.1', '1.2.1', 'BANK SYARIAH MANDIRI KRIAN', 'detail', 'BANK', '', '2018-05-19 08:45:14', '2018-11-11 11:24:03'),
(87, '1.2.1.2', '1.2.1', 'BSM JEMUR SARI SURABAYA', 'detail', 'BANK', '', '2018-05-19 08:45:54', '2018-11-11 11:24:50'),
(88, '1.2.2.1', '1.2.2', 'MANDIRI CABANG UNAIR', 'detail', 'BANK', '', '2018-05-19 08:46:48', '2018-05-22 16:16:07'),
(89, '1.3.1', '1.3', 'A.K.A. KOPERASI', 'induk', '', '', '2018-05-19 08:55:05', '2018-05-19 08:55:05'),
(90, '1.3.1.1', '1.3.1', 'A.K.A. PUSAT KJKS JATIM MICROFIN', 'detail', '', '', '2018-05-19 08:55:54', '2018-05-19 08:57:04'),
(91, '1.3.1.2', '1.3.1', 'INKOPSYAH BMT', 'detail', '', '', '2018-05-19 08:57:36', '2018-05-19 08:57:36'),
(92, '1.3.1.3', '1.3.1', 'PUSKOPSYAH JATIM AL AKBAR', 'detail', '', '', '2018-05-19 08:58:00', '2018-05-19 08:58:00'),
(93, '1.4.1', '1.4', 'INVESTASI LAINNYA', 'induk', '', '', '2018-05-19 08:58:44', '2018-05-19 08:58:44'),
(94, '1.4.1.1', '1.4.1', 'INVESTASI CABANG BUNGA GRESIK', 'detail', '', '', '2018-05-19 09:03:29', '2018-05-19 09:03:29'),
(95, '1.5.1', '1.5', 'PEMBIAYAAN MDA', 'induk', '', '', '2018-05-19 09:03:54', '2018-05-19 09:03:54'),
(96, '1.5.2', '1.5', 'PEMBIAYAAN MRB', 'induk', '', '', '2018-05-19 09:04:20', '2018-05-19 09:04:20'),
(97, '1.5.3', '1.5', 'PEMBIAYAAN QORD', 'induk', '', '', '2018-05-19 09:04:36', '2018-05-19 09:04:36'),
(98, '1.6.1', '1.6', 'PEMBIAYAAN LAIN EKSTERNAL', 'induk', '', '', '2018-05-19 09:05:15', '2018-05-19 09:05:15'),
(99, '1.5.1.1', '1.5.1', 'PEMBIAYAAN MDA', 'detail', 'PEMBIAYAAN', '{\"rek_margin\":\"4.1.1.1\",\"m_ditangguhkan\":null,\"rek_denda\":\"4.1.3.1\",\"rek_administrasi\":\"4.1.3.1\",\"rek_notaris\":\"4.1.3.1\",\"rek_pend_WO\":null,\"rek_materai\":\"4.1.3.1\",\"rek_asuransi\":\"4.1.3.1\",\"rek_provisi\":\"4.1.3.1\",\"rek_pend_prov\":\"4.1.3.1\",\"rek_zis\":\"4.1.3.1\",\"piutang\":\"0\",\"jenis_pinjaman\":\"2\",\"path_akad\":\"\"}', '2018-05-19 09:05:51', '2019-08-16 08:26:17'),
(100, '1.5.2.1', '1.5.2', 'PEMBIAYAAN MRB', 'detail', 'PEMBIAYAAN', '{\"rek_margin\":\"4.1.1.2\",\"m_ditangguhkan\":\"1.5.2.2\",\"rek_denda\":\"4.1.1.2\",\"rek_administrasi\":\"4.1.3.1\",\"rek_notaris\":\"4.1.3.1\",\"rek_pend_WO\":\"4.1.3.1\",\"rek_materai\":\"4.1.3.1\",\"rek_asuransi\":\"2.9.1\",\"rek_provisi\":\"4.1.3.1\",\"rek_pend_prov\":\"4.1.3.1\",\"rek_zis\":\"2.9.1\",\"piutang\":\"1\",\"jenis_pinjaman\":\"1\"}', '2018-05-19 09:06:06', '2018-07-11 05:41:46'),
(101, '1.5.2.2', '1.5.2', 'PIUTANG MRB YANG DITANGGUHKAN', 'detail', '', '', '2018-05-19 09:06:28', '2018-05-19 09:06:28'),
(102, '1.5.3.1', '1.5.3', 'PEMBIAYAAN QORD', 'detail', '', '', '2018-05-19 09:06:41', '2018-05-19 09:06:41'),
(103, '1.6.1.1', '1.6.1', 'PEMBY. MDA LAIN-LAIN', 'detail', '', '', '2018-05-19 09:07:19', '2018-05-19 09:07:19'),
(104, '1.7.1', '1.7', 'PENYISIHAN PIUTANG UMUM', 'detail', '', '', '2018-05-19 09:08:03', '2018-05-19 09:08:03'),
(105, '2.1.2.5', '2.1.2', 'SIMPANAN ZIAROH/WISATA', 'detail', '', '', '2018-05-19 09:14:06', '2018-05-19 09:14:06'),
(106, '2.1.2.6', '2.1.2', 'SIMPANAN UNIT LAIN', 'detail', '', '', '2018-05-19 09:14:52', '2018-05-19 09:14:52'),
(107, '2.3.1', '2.3', 'A.K.P KOPERASI', 'induk', '', '', '2018-05-19 09:17:55', '2018-05-19 09:18:50'),
(108, '2.3.1.3', '2.3.1', 'A.K.P PUSAT KJKS JATIM MICROFIN', 'detail', '', '', '2018-05-19 09:20:04', '2018-05-19 11:31:51'),
(109, '2.3.1.1', '2.3.1', 'INKOPSYAH', 'detail', '', '', '2018-05-19 09:20:27', '2018-05-19 09:20:27'),
(110, '2.3.1.2', '2.3.1', 'BMT MBS', 'detail', '', '', '2018-05-19 09:20:41', '2018-05-19 09:20:41'),
(111, '2.5', '2', 'DANA PENDIDIKAN', 'induk', '', '', '2018-05-19 09:21:46', '2018-05-19 09:21:46'),
(112, '2.6', '2', 'ZAKAT', 'detail', '', '', '2018-05-19 09:22:06', '2018-05-19 09:22:06'),
(113, '2.7', '2', 'DANA SOSIAL', 'detail', '', '', '2018-05-19 09:22:25', '2018-07-25 11:05:57'),
(114, '2.8', '2', 'RUPA-RUPA PASIVA', 'induk', '', '', '2018-05-19 09:22:43', '2018-05-19 09:22:43'),
(115, '3.1', '3', 'MODAL', 'induk', '', '', '2018-05-19 09:22:51', '2018-05-19 09:22:51'),
(116, '3.2', '3', 'KEKAYAAN & SHU', 'induk', '', '', '2018-05-19 09:23:09', '2018-05-19 09:23:09'),
(117, '3.2.1', '3.2', 'SIMPANAN POKOK ANGGOTA', 'detail', '', '', '2018-05-19 09:23:38', '2018-05-19 09:23:38'),
(118, '3.2.2', '3.2', 'WAQAF UANG', 'detail', '', '', '2018-05-19 09:23:53', '2019-09-27 08:55:23'),
(119, '3.2.3', '3.2', 'SIMPANAN WAJIB ANGGOTA', 'detail', '', '', '2018-05-19 09:24:14', '2018-05-19 09:24:14'),
(120, '3.2.4', '3.2', 'SIMPANAN KHUSUS ANGGOTA', 'detail', '', '', '2018-05-19 09:24:44', '2019-09-27 08:58:57'),
(121, '3.2.5', '3.2', 'DANA CADANGAN UMUM', 'detail', '', '', '2018-05-19 09:25:04', '2018-05-19 09:25:04'),
(122, '3.2.6', '3.2', 'SHU BERJALAN', 'detail', 'SHU', '', '2018-05-19 09:25:18', '2018-05-28 22:45:06'),
(123, '3', 'master', 'MODAL', 'master', '', '', '2018-05-19 09:58:23', '2018-05-19 09:58:23'),
(125, '4', 'master', 'PENDAPATAN', 'master', '', '', '2018-05-19 10:00:40', '2018-05-19 10:00:40'),
(126, '5', 'master', 'BIAYA', 'master', '', '', '2018-05-19 10:00:48', '2018-05-19 10:00:48'),
(127, '4.1', '4', 'PENDAPATAN OPERASIONAL', 'induk', '', '', '2018-05-19 10:01:14', '2018-05-19 10:01:14'),
(128, '4.1.1', '4.1', 'PENDAPATAN PEMBIAYAAN', 'induk', '', '', '2018-05-19 10:01:43', '2018-05-19 10:01:59'),
(129, '4.1.1.1', '4.1.1', 'PENDAPATAN BH PEMBY MDA', 'detail', '', '', '2018-05-19 10:02:49', '2018-05-19 10:02:49'),
(130, '4.1.1.2', '4.1.1', 'PENDAPATAN BH PEMBY MRB', 'detail', '', '', '2018-05-19 10:03:15', '2018-05-19 10:03:15'),
(131, '4.1.1.3', '4.1.1', 'PENDAPATAN BH PEMBY QORD', 'detail', '', '', '2018-05-19 10:03:43', '2018-05-19 10:03:43'),
(132, '4.1.2', '4.1', 'PENDAPATAN OPERASIONAL LAINNYA', 'induk', '', '', '2018-05-19 10:04:10', '2018-05-19 10:04:29'),
(133, '4.1.2.1', '4.1.2', 'PENDAPATAN BH LAINNYA', 'detail', '', '', '2018-05-19 10:04:45', '2018-05-19 10:04:45'),
(134, '5.1', '5', 'BEBAN LANGSUNG TABUNGAN', 'induk', '', '', '2018-05-19 10:05:39', '2018-05-19 10:05:39'),
(135, '5.2', '5', 'BEBAN LANGSUNG ANTAR KOPERASI PASIVA', 'induk', '', '', '2018-05-19 10:06:04', '2018-05-19 10:06:04'),
(136, '5.3', '5', 'BEBAN LANGSUNG PINJAM BANK DAN NON BANK', 'induk', '', '', '2018-05-19 10:06:32', '2018-05-19 10:06:32'),
(137, '5.4', '5', 'BEBAN OPERASIONAL DAN ADMINISTRASI', 'induk', '', '', '2018-05-19 10:07:14', '2018-05-19 10:07:14'),
(138, '5.1.1', '5.1', 'BEBAN BH TABUNGAN MDA UMUM', 'induk', '', '', '2018-05-19 10:07:50', '2018-05-19 10:07:50'),
(139, '5.1.1.1', '5.1.1', 'BEBAN BH TABUNGAN MDA UMUM', 'detail', '', '', '2018-05-19 10:08:20', '2018-05-19 10:08:20'),
(140, '5.1.2', '5.1', 'BEBAN BH TABUNGAN MDA BERJANGKA', 'induk', '', '', '2018-05-19 10:08:49', '2018-05-19 10:08:49'),
(141, '5.1.2.1', '5.1.2', 'BEBAN TAB. TARBIYAH / PENDIDIKAN', 'detail', '', '', '2018-05-19 10:09:17', '2018-05-19 10:09:17'),
(142, '5.1.2.2', '5.1.2', 'BEBAN TAB. IDUL FITRI', 'detail', '', '', '2018-05-19 10:09:57', '2018-05-19 10:09:57'),
(143, '5.1.2.3', '5.1.2', 'BEBAN TAB. IDUL ADHA', 'detail', '', '', '2018-05-19 10:10:34', '2018-05-19 10:10:34'),
(144, '5.1.2.4', '5.1.2', 'BEBAN TAB. WALIMAH', 'detail', '', '', '2018-05-19 10:10:56', '2018-05-19 10:10:56'),
(145, '5.1.2.5', '5.1.2', 'BEBAN TAB. ZIARAH / WISATA', 'detail', '', '', '2018-05-19 10:11:18', '2018-05-19 10:11:18'),
(146, '5.1.2.6', '5.1.2', 'BEBAN TAB. UNIT LAIN', 'detail', '', '', '2018-05-19 10:11:42', '2018-05-19 10:11:42'),
(147, '5.2.1', '5.2', 'BEBAN BH ANTAR KOP. SYARIAH', 'induk', '', '', '2018-05-19 10:12:26', '2018-05-19 10:12:26'),
(148, '5.2.1.1', '5.2.1', 'BEBAN BH INKOPSYAH', 'detail', '', '', '2018-05-19 10:12:49', '2018-05-19 10:12:49'),
(149, '5.2.1.2', '5.2.1', 'BEBAN BH  MICROFIN', 'detail', '', '', '2018-05-19 10:13:24', '2018-05-19 10:13:24'),
(150, '5.3.1', '5.3', 'BEBAN BH PINJAMAN BANK', 'induk', '', '', '2018-05-19 10:13:52', '2018-05-19 10:13:52'),
(151, '5.3.1.1', '5.3.1', 'BEBAN BH PINJAMAN MANDIRI SYARIAH JEMUR', 'detail', '', '', '2018-05-19 10:14:36', '2018-05-19 10:14:36'),
(152, '5.3.1.2', '5.3.1', 'BEBAN BH PINJAMAN BPR SYARIAH MOJOKERTO', 'detail', '', '', '2018-05-19 10:15:24', '2018-05-19 10:15:24'),
(153, '5.4.1', '5.4', 'BIAYA KARYAWAN', 'induk', '', '', '2018-05-19 10:16:31', '2018-05-19 10:16:31'),
(154, '5.4.1.1', '5.4.1', 'BEBAN BISYAROH KARYAWAN', 'detail', '', '', '2018-05-19 10:16:52', '2018-05-19 10:16:52'),
(155, '5.4.2', '5.4', 'BIAYA KANTOR', 'induk', '', '', '2018-05-19 10:17:10', '2018-05-19 10:17:10'),
(156, '5.4.2.1', '5.4.2', 'BIAYA PERLENGKAPAN KANTOR', 'detail', '', '', '2018-05-19 10:17:29', '2018-05-19 10:17:29'),
(157, '5.4.2.2', '5.4.2', 'BIAYA LISTRIK, PDAM, DAN TELEPON', 'detail', '', '', '2018-05-19 10:17:56', '2018-05-19 10:17:56'),
(158, '5.4.2.3', '5.4.2', 'BIAYA TRANSPORTASI', 'detail', '', '', '2018-05-19 10:18:43', '2018-05-19 10:18:43'),
(159, '5.4.2.4', '5.4.2', 'BIAYA ORGANISASI', 'detail', '', '', '2018-05-19 10:18:59', '2018-05-19 10:18:59'),
(160, '5.4.2.5', '5.4.2', 'BIAYA PROMOSI', 'detail', '', '', '2018-05-19 10:19:14', '2018-08-05 22:38:54'),
(161, '5.5', '5', 'BEBAN DEPOSITO', 'induk', '', '', '2018-05-22 11:08:18', '2018-05-22 11:08:18'),
(162, '5.5.1', '5.5', 'BEBAN MUDHARABAH SYARIAH', 'induk', '', '', '2018-05-22 11:09:43', '2018-05-22 11:09:43'),
(163, '5.5.1.1', '5.5.1', 'BEBAN MUDHARABAH BERJANGKA', 'induk', '', '', '2018-05-22 11:10:03', '2018-05-22 11:10:03'),
(164, '5.5.1.1.1', '5.5.1.1', 'BEBAN MUDHARABAH 1 BULAN', 'detail', '', '', '2018-05-22 11:11:19', '2018-05-22 11:11:19'),
(165, '5.5.1.1.2', '5.5.1.1', 'BEBAN MUDHARABAH 3 BULAN', 'detail', '', '', '2018-05-22 11:11:38', '2018-05-22 11:11:38'),
(166, '5.5.1.1.3', '5.5.1.1', 'BEBAN MUDHARABAH 6 BULAN', 'detail', '', '', '2018-05-22 11:11:55', '2018-05-22 11:11:55'),
(167, '5.5.1.1.4', '5.5.1.1', 'BEBAN MUDHARABAH 9 BULAN', 'detail', '', '', '2018-05-22 11:12:18', '2018-05-22 11:12:18'),
(168, '5.5.1.1.5', '5.5.1.1', 'BEBAN MUDHARABAH 12 BULAN', 'detail', '', '', '2018-05-22 11:12:35', '2018-05-22 11:12:35'),
(169, '2.2.1.3', '2.2.1', 'MUDHARABAH 6 BULAN', 'detail', 'DEPOSITO', '{\"rek_margin\":\"5.5.1.1.3\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"6\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"1\"}', '2018-05-22 11:13:20', '2018-05-22 11:30:00'),
(170, '2.2.1.4', '2.2.1', 'MUDHARABAH 9 BULAN', 'detail', 'DEPOSITO', '{\"rek_margin\":\"5.5.1.1.4\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"9\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"1\"}', '2018-05-22 11:14:05', '2018-05-22 11:30:27'),
(171, '2.2.1.5', '2.2.1', 'MUDHARABAH 12 BULAN', 'detail', 'DEPOSITO', '{\"rek_margin\":\"5.5.1.1.5\",\"rek_pajak_margin\":null,\"rek_jatuh_tempo\":null,\"rek_cadangan_margin\":null,\"rek_pinalti\":null,\"jangka_waktu\":\"12\",\"nisbah_anggota\":\"25\",\"nisbah_bank\":75,\"nasabah_wajib_pajak\":\"1\"}', '2018-05-22 11:14:20', '2019-08-16 08:24:22'),
(172, '4.1.3', '4.1', 'PENDAPATAN ADMINISTRASI', 'induk', '', '', '2018-05-22 11:33:42', '2018-05-22 11:33:42'),
(173, '4.1.3.1', '4.1.3', 'PENDAPATAN ADMINISTRASI PEMBIAYAAN', 'detail', '', '', '2018-05-22 11:34:09', '2018-05-22 11:34:09'),
(174, '2.9', '2', 'PEMINDAHAN BUKUAN', 'induk', '', '', '2018-05-26 23:00:14', '2018-05-26 23:00:14'),
(175, '2.9.1', '2.9', 'PEMINDAHAN BUKUAN', 'detail', '', '', '2018-05-26 23:00:25', '2018-05-26 23:00:25'),
(176, '3.2.7', '3.2', 'SHU YANG HARUS DIBAGIKAN', 'detail', '', '', '2018-07-14 03:53:23', '2018-07-14 03:53:23'),
(178, '4.1.3.2', '4.1.3', 'PENDAPATAN ADMINISTRASI TABUNGAN', 'detail', '', '', '2018-07-24 12:39:45', '2018-07-24 12:39:45'),
(179, '2.1.2.7', '2.1.2', 'SIMPANAN MAAL', 'detail', 'TABUNGAN', '{\"nisbah_anggota\":\"0\",\"nisbah_bank\":100,\"rek_margin\":\"2.1.2.7\",\"rek_pendapatan\":\"1.1.1.1\",\"nasabah_wajib_pajak\":\"0\",\"nasabah_bayar_zis\":\"0\",\"saldo_min\":\"0\",\"setoran_awal\":\"0\",\"setoran_min\":\"0\",\"saldo_min_margin\":\"0\",\"adm_tutup_tab\":\"0\",\"pemeliharaan\":\"0\",\"adm_passif\":\"0\",\"adm_buka_baru\":\"0\",\"adm_ganti_buku\":\"0\"}', '2018-08-02 00:45:35', '2018-08-02 00:50:14'),
(180, '2.10', '2', 'PAJAK YANG DITANGGUHKAN', 'detail', 'PAJAK', '', '2018-08-14 07:05:33', '2018-08-14 07:05:42'),
(181, '5.1.1.2', '5.1.1', 'BEBAN BH TABUNGAN WADIAH', 'detail', '', '', '2018-11-16 08:24:40', '2018-11-16 08:24:40'),
(182, '5.6', '5', 'BIAYA PAJAK', 'detail', '', '', '2019-02-28 06:08:25', '2019-02-28 06:08:25'),
(184, '1.1.2', '1.1', 'KAS TELLER 1', 'detail', 'TELLER', '', '2019-09-21 14:14:21', '2019-09-21 14:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `shu`
--

DROP TABLE IF EXISTS `shu`;
CREATE TABLE `shu` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `nama_shu` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `persentase` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `shu`
--

INSERT INTO `shu` (`id`, `id_rekening`, `nama_shu`, `persentase`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'PENGELOLAH', '0.2', 'active', NULL, '2018-07-25 11:10:03'),
(2, 0, 'PENGURUS', '0.2', 'active', NULL, '2018-07-25 11:10:04'),
(3, 0, 'ANGGOTA', '0.3', 'active', NULL, '2018-07-25 11:10:04'),
(4, 121, 'DANA CADANGAN UMUM', '0.3', 'active', '2018-07-25 08:41:48', '2018-07-25 11:28:36'),
(5, 113, 'DANA SOSIAL', '0.1', 'not active', '2018-07-25 11:09:25', '2018-07-25 11:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `tabungan`
--

DROP TABLE IF EXISTS `tabungan`;
CREATE TABLE `tabungan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_tabungan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `id_rekening` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_pengajuan` int(10) UNSIGNED NOT NULL,
  `jenis_tabungan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `no_ktp` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `nama` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `alamat` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `tipe` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pathfile` text COLLATE utf8_unicode_ci NOT NULL,
  `wajib_pokok` text COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `no_ktp`, `password`, `nama`, `alamat`, `tipe`, `status`, `detail`, `remember_token`, `created_at`, `updated_at`, `pathfile`, `wajib_pokok`, `role`) VALUES
(1, 'admin', '$2y$10$EnjCvjx7SmXqFyjbzevk6eH2N7Y4rh39dHI5Uxr2DUwzLJ247s3QK', 'admin', 'admin', 'admin', '', '{\"id_rekening\":\"13\"}', 'O1oupIn350Nwmrb2yqKlmChjtN1bFR6nDoWN23furncuHAx2NDm5iqAwiz7o', NULL, '2018-07-12 11:45:24', '{\"profile\":\"r7GcMSBD3LDkfpB87JuqIw1fBbHjGGbB7a5PoD61.jpeg\",\"KTP\":null,\"KSK\":null,\"Nikah\":null}', '', ''),
(38, 'teller2', '$2y$10$btWjBK4Wv7d.xvr80JaWButo3hb9otYdEV4/mVtJoJjXaXGvVp.AO', 'TELLER 2', 'TELLER 2', 'teller', '2', '{\"id_rekening\":\"13\"}', 'obIAKeAfwR0c31aTXDiNAhcPKrKm6q1ZcpUVdBVJBc5uLuVGCeuFMxcee75N', '2018-12-19 11:25:51', '2019-10-28 05:00:13', '', '', 'pengurus'),
(52, 'teller1', '$2y$10$VwPBJw3bsO.yAgbNTE4e4euzw3Y1xuiR/z6h/fxXEaopSBP25GdLm', 'teller1', 'teller1', 'teller', '2', '{\"id_rekening\":\"184\"}', '9xka1TvMBpgXXt0nsfL6SjtdsTUNN8FjVTs2Mutupa2Gy5hWc4LqM9LLU7bM', '2019-03-05 09:04:52', '2019-10-28 05:00:03', '', '', 'pengelolah'),
(57, '3578172411720001', '$2y$10$bX7y8gFBdPA2RIDrTJvaq.HJWnbfKfQ7zDp5jwTJjSBTds0jGunTe', 'sunoyo', 'jl. kedinding lor tanjung 47-49 sby', 'anggota', '2', '{\"nama\":\"sunoyo\",\"no_ktp\":\"3578172411720001\",\"nik\":\"3578170101080941\",\"telepon\":\"085850819919\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"mojokerto\",\"tgl_lahir\":\"11\\/24\\/1972\",\"alamat_ktp\":\"jl. kedinding lor gg tanjung 47 - 49 surabaya\",\"alamat_domisili\":\"jl. kedinding lor tanjung 47-49 sby\",\"pendidikan\":\"S1\",\"pekerjaan\":\"swasta\",\"pendapatan\":\"5000000\",\"alamat_kerja\":\"jl. kedinding lor gg tanjung 49 surabaya\",\"status\":\"M\",\"nama_wali\":\"umu kholifah\",\"ayah\":\"hasyim alm.\",\"ibu\":\"sukama\",\"jml_sumis\":\"1\",\"jml_anak\":\"3\",\"jml_ortu\":\"1\",\"lain\":\"1\",\"rumah\":\"HM\"}', 'jQdegqEmeoWPIQsmjmomT15i1GON344XKTcTHy1xtnN9Erm1B7P7BxdPHRmU', '2019-08-13 03:35:00', '2019-09-21 07:37:14', '{\"profile\":\"Tt8Bp0BbGxa1ELqOS7nrDwPQT7tV7Ifs8UdMSCql.jpeg\",\"KTP\":\"gBl8SJL8KnCyIeF4CptbhBC2ldaq0ppYhFr6ABHk.png\",\"KSK\":\"KZapGTIXaBHdxUq9pjJdoP1etfTATnGkL6M6Zm0j.jpeg\",\"Nikah\":\"rPi0efFOWrqEGVmhsGuKuzvYf6UTaVfrxMcIbOIi.jpeg\"}', '{\"wajib\":480000,\"pokok\":\"50000\",\"margin\":380000}', 'anggota'),
(58, '3578092202920003', '$2y$10$biJEquUi/dwHLp6tryQxWu11fnhoYCxam86zgkc7Kdc0UkDWtfMgK', 'Galang Amanda Dwi Pamungkas', 'Perum ITS T-54 Surabaya', 'anggota', '2', '{\"nama\":\"Galang Amanda Dwi Pamungkas\",\"no_ktp\":\"3578092202920003\",\"nik\":\"12345678123123123123\",\"telepon\":\"081230024660\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"Surabaya\",\"tgl_lahir\":\"02\\/22\\/1992\",\"alamat_ktp\":\"Perum ITS T-54 Surabaya\",\"alamat_domisili\":\"Perum ITS T-54 Surabaya\",\"pendidikan\":\"S2\",\"pekerjaan\":\"Wiraswasta\",\"pendapatan\":\"4000000\",\"alamat_kerja\":\"Jl Ngaglik 50\",\"status\":\"S\",\"nama_wali\":\"-\",\"ayah\":\"asdasd\",\"ibu\":\"asdasd\",\"jml_sumis\":\"0\",\"jml_anak\":\"0\",\"jml_ortu\":\"2\",\"lain\":\"0\",\"rumah\":\"MW\"}', 'J3y16qP9MvtdlWiUlGdaYeJlvyWvL3SxKJRsIOQf7Iy9HgM7U6OPsvWynM6h', '2019-09-03 03:02:11', '2019-09-03 03:21:14', '{\"profile\":\"ZAuzI4StdRrJ3Lb4APfVgJ1wLt3Wjg7scBF4jepz.jpeg\",\"KTP\":\"y8xoCq1P53hLO6xktvLoKzFDo37Af0gVg8MMlHun.jpeg\",\"KSK\":\"T2JWqQnCwC0zptCFEMWfW0YDhWV9daxKPcYuoe9X.jpeg\",\"Nikah\":\"YBLA9xpGkWz95c2oCSYBxcMCDnkVLIicODUHwCOm.jpeg\"}', '{\"pokok\":\"50000\",\"wajib\":\"50000\"}', 'anggota'),
(59, '1234567890123456', '$2y$10$GRQkiYRL0l79OxmwpfuVNeCdDR6VYIV2m60I0QEfbsjITQSgvSwVW', 'demo', 'demo', 'anggota', '2', '{\"nama\":\"demo\",\"no_ktp\":\"1234567890123456\",\"nik\":\"12345678\",\"telepon\":\"1234567890\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"surabaya\",\"tgl_lahir\":\"09\\/23\\/2019\",\"alamat_ktp\":\"surabaya\",\"alamat_domisili\":\"demo\",\"pendidikan\":\"SMA\",\"pekerjaan\":\"demo\",\"pendapatan\":\"1000000\",\"alamat_kerja\":\"demo\",\"status\":\"S\",\"nama_wali\":\"demo\",\"ayah\":\"demo\",\"ibu\":\"demo\",\"jml_sumis\":\"0\",\"jml_anak\":\"0\",\"jml_ortu\":\"2\",\"lain\":\"0\",\"rumah\":\"HM\"}', '68OysIipS3Mz0D8OgnDeZPLUP5SDJzYEPJeXXPvdJhdf6nSdOFWuKBKELgLS', '2019-09-30 02:14:50', '2019-10-24 06:20:08', '{\"profile\":null,\"KTP\":\"QOzxLqtTjKUaIMLdWmPj7G2Q4FCMVDNJeGTO6lqB.png\",\"KSK\":\"Dx2hld2SMpjbkWgaiAK0GOGcbGIp0oaRn11K27Xm.png\",\"Nikah\":null}', '{\"pokok\":\"100000\",\"wajib\":\"50000\"}', ''),
(60, '1234123412341234', '$2y$10$zbNTr.L59/BFU7aota6FPunRI9uUEyY2hw4.dz0kWm91oDpCCbuc6', 'Alam', 'alamalam', 'anggota', '2', '{\"nama\":\"Alam\",\"no_ktp\":\"1234123412341234\",\"nik\":\"1234123412341234\",\"telepon\":\"081295195688\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"Sidoarjo\",\"tgl_lahir\":\"10\\/09\\/2019\",\"alamat_ktp\":\"alamalam\",\"alamat_domisili\":\"alamalam\",\"pendidikan\":\"SD\",\"pekerjaan\":\"developer\",\"pendapatan\":\"100000000\",\"alamat_kerja\":\"hexavara\",\"status\":\"S\",\"nama_wali\":\"alamalam\",\"ayah\":\"alamalam\",\"ibu\":\"alamalam\",\"jml_sumis\":\"12\",\"jml_anak\":\"12\",\"jml_ortu\":\"12\",\"lain\":\"12\",\"rumah\":\"HM\"}', 'rfynuoACFUzHv0jLdGgBFhEWwdHuFrM8htMUfxG9IEYg1uhOOYUcNDZnpXe2', '2019-10-09 03:10:18', '2019-10-09 03:17:02', '{\"profile\":null,\"KTP\":\"6hiMsLM4C4BhBmQJmDieeXXvhsiOLGeXDrbS21mM.png\",\"KSK\":\"XltUw3jJbzPz9P2gANtjEJfmhGc6VHw6ERue6hii.png\",\"Nikah\":\"pcVx2EFw2R8zMbJbDjaI0e2p06vxAiRj3P6jFXku.png\"}', '{\"pokok\":\"10000\",\"wajib\":\"10000\"}', ''),
(61, '1234123443214321', '$2y$10$r6Y/GwY.GCdxVHTWICVsjuLoy5hppunf/6my75QZLrL5DCXoLcPHG', 'ALAM_TEST', 'SIDOARJO', 'anggota', '2', '{\"nama\":\"ALAM_TEST\",\"no_ktp\":\"1234123443214321\",\"nik\":\"3275082212970013\",\"telepon\":\"081295195688\",\"jenis_kelamin\":\"L\",\"tempat_lahir\":\"Surabaya\",\"tgl_lahir\":\"11\\/01\\/2019\",\"alamat_ktp\":\"SIDOARJO\",\"alamat_domisili\":\"SIDOARJO\",\"pendidikan\":\"SMP\",\"pekerjaan\":\"ngoding\",\"pendapatan\":\"10000000\",\"alamat_kerja\":\"sidoarjo\",\"status\":\"S\",\"nama_wali\":\"skip\",\"ayah\":\"skip\",\"ibu\":\"skip\",\"jml_sumis\":\"2\",\"jml_anak\":\"2\",\"jml_ortu\":\"2\",\"lain\":\"2\",\"rumah\":\"HM\"}', NULL, '2019-11-11 06:33:46', '2019-11-11 06:39:49', '{\"profile\":null,\"KTP\":\"uTYkgQx5QxUIsfhBRCGw4GPXAHyHYN74tQSVX8IL.png\",\"KSK\":\"sm4XdcnilmdboMLBfiYunS7OgdU8gU2z2flBzh2R.png\",\"Nikah\":\"wXtm2K5RD5a3vok5UKBcZJCjzHJ4dAMcKtVZULe6.png\"}', '{\"pokok\":\"15000000\",\"wajib\":\"10000000\"}', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bmt`
--
ALTER TABLE `bmt`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bmt_id_bmt_unique` (`id_bmt`),
  ADD KEY `bmt_id_rekening_foreign` (`id_rekening`);

--
-- Indexes for table `deposito`
--
ALTER TABLE `deposito`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deposito_id_deposito_unique` (`id_deposito`),
  ADD KEY `deposito_id_rekening_foreign` (`id_rekening`),
  ADD KEY `deposito_id_user_foreign` (`id_user`),
  ADD KEY `deposito_id_pengajuan_foreign` (`id_pengajuan`);

--
-- Indexes for table `jaminan`
--
ALTER TABLE `jaminan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maal`
--
ALTER TABLE `maal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `maal_id_maal_unique` (`id_maal`),
  ADD KEY `maal_id_rekening_foreign` (`id_rekening`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pembiayaan`
--
ALTER TABLE `pembiayaan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembiayaan_id_pembiayaan_unique` (`id_pembiayaan`),
  ADD KEY `pembiayaan_id_rekening_foreign` (`id_rekening`),
  ADD KEY `pembiayaan_id_user_foreign` (`id_user`),
  ADD KEY `pembiayaan_id_pengajuan_foreign` (`id_pengajuan`);

--
-- Indexes for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_id_user_foreign` (`id_user`),
  ADD KEY `pengajuan_id_rekening_foreign` (`id_rekening`);

--
-- Indexes for table `penyimpanan_bmt`
--
ALTER TABLE `penyimpanan_bmt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_bmt_id_user_foreign` (`id_user`),
  ADD KEY `penyimpanan_bmt_id_bmt_foreign` (`id_bmt`);

--
-- Indexes for table `penyimpanan_deposito`
--
ALTER TABLE `penyimpanan_deposito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_deposito_id_user_foreign` (`id_user`),
  ADD KEY `penyimpanan_deposito_id_deposito_foreign` (`id_deposito`);

--
-- Indexes for table `penyimpanan_jaminan`
--
ALTER TABLE `penyimpanan_jaminan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_jaminan_id_user_foreign` (`id_user`),
  ADD KEY `penyimpanan_jaminan_id_pengajuan_foreign` (`id_pengajuan`),
  ADD KEY `penyimpanan_jaminan_id_jaminan_foreign` (`id_jaminan`);

--
-- Indexes for table `penyimpanan_maal`
--
ALTER TABLE `penyimpanan_maal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_maal_id_donatur_foreign` (`id_donatur`),
  ADD KEY `penyimpanan_maal_id_maal_foreign` (`id_maal`);

--
-- Indexes for table `penyimpanan_pembiayaan`
--
ALTER TABLE `penyimpanan_pembiayaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_pembiayaan_id_user_foreign` (`id_user`),
  ADD KEY `penyimpanan_pembiayaan_id_pembiayaan_foreign` (`id_pembiayaan`);

--
-- Indexes for table `penyimpanan_rekening`
--
ALTER TABLE `penyimpanan_rekening`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_rekening_id_rekening_foreign` (`id_rekening`);

--
-- Indexes for table `penyimpanan_shu`
--
ALTER TABLE `penyimpanan_shu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_shu_id_shu_foreign` (`id_shu`);

--
-- Indexes for table `penyimpanan_tabungan`
--
ALTER TABLE `penyimpanan_tabungan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_tabungan_id_user_foreign` (`id_user`),
  ADD KEY `penyimpanan_tabungan_id_tabungan_foreign` (`id_tabungan`);

--
-- Indexes for table `penyimpanan_users`
--
ALTER TABLE `penyimpanan_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_users_id_user_foreign` (`id_user`);

--
-- Indexes for table `penyimpanan_wajib_pokok`
--
ALTER TABLE `penyimpanan_wajib_pokok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyimpanan_wajib_pokok_id_user_foreign` (`id_user`),
  ADD KEY `penyimpanan_wajib_pokok_id_rekening_foreign` (`id_rekening`);

--
-- Indexes for table `rekening`
--
ALTER TABLE `rekening`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rekening_id_rekening_unique` (`id_rekening`);

--
-- Indexes for table `shu`
--
ALTER TABLE `shu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabungan`
--
ALTER TABLE `tabungan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tabungan_id_tabungan_unique` (`id_tabungan`),
  ADD KEY `tabungan_id_rekening_foreign` (`id_rekening`),
  ADD KEY `tabungan_id_user_foreign` (`id_user`),
  ADD KEY `tabungan_id_pengajuan_foreign` (`id_pengajuan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_no_ktp_unique` (`no_ktp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bmt`
--
ALTER TABLE `bmt`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=406;

--
-- AUTO_INCREMENT for table `deposito`
--
ALTER TABLE `deposito`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `jaminan`
--
ALTER TABLE `jaminan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `maal`
--
ALTER TABLE `maal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pembiayaan`
--
ALTER TABLE `pembiayaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1085;

--
-- AUTO_INCREMENT for table `penyimpanan_bmt`
--
ALTER TABLE `penyimpanan_bmt`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2836;

--
-- AUTO_INCREMENT for table `penyimpanan_deposito`
--
ALTER TABLE `penyimpanan_deposito`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `penyimpanan_jaminan`
--
ALTER TABLE `penyimpanan_jaminan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `penyimpanan_maal`
--
ALTER TABLE `penyimpanan_maal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `penyimpanan_pembiayaan`
--
ALTER TABLE `penyimpanan_pembiayaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353;

--
-- AUTO_INCREMENT for table `penyimpanan_rekening`
--
ALTER TABLE `penyimpanan_rekening`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2126;

--
-- AUTO_INCREMENT for table `penyimpanan_shu`
--
ALTER TABLE `penyimpanan_shu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penyimpanan_tabungan`
--
ALTER TABLE `penyimpanan_tabungan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11055;

--
-- AUTO_INCREMENT for table `penyimpanan_users`
--
ALTER TABLE `penyimpanan_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `penyimpanan_wajib_pokok`
--
ALTER TABLE `penyimpanan_wajib_pokok`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `rekening`
--
ALTER TABLE `rekening`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `shu`
--
ALTER TABLE `shu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tabungan`
--
ALTER TABLE `tabungan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bmt`
--
ALTER TABLE `bmt`
  ADD CONSTRAINT `bmt_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deposito`
--
ALTER TABLE `deposito`
  ADD CONSTRAINT `deposito_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deposito_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deposito_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maal`
--
ALTER TABLE `maal`
  ADD CONSTRAINT `maal_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembiayaan`
--
ALTER TABLE `pembiayaan`
  ADD CONSTRAINT `pembiayaan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembiayaan_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembiayaan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `pengajuan_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_bmt`
--
ALTER TABLE `penyimpanan_bmt`
  ADD CONSTRAINT `penyimpanan_bmt_id_bmt_foreign` FOREIGN KEY (`id_bmt`) REFERENCES `bmt` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penyimpanan_bmt_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_deposito`
--
ALTER TABLE `penyimpanan_deposito`
  ADD CONSTRAINT `penyimpanan_deposito_id_deposito_foreign` FOREIGN KEY (`id_deposito`) REFERENCES `deposito` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penyimpanan_deposito_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_jaminan`
--
ALTER TABLE `penyimpanan_jaminan`
  ADD CONSTRAINT `penyimpanan_jaminan_id_jaminan_foreign` FOREIGN KEY (`id_jaminan`) REFERENCES `jaminan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penyimpanan_jaminan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penyimpanan_jaminan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_maal`
--
ALTER TABLE `penyimpanan_maal`
  ADD CONSTRAINT `penyimpanan_maal_id_donatur_foreign` FOREIGN KEY (`id_donatur`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penyimpanan_maal_id_maal_foreign` FOREIGN KEY (`id_maal`) REFERENCES `maal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_pembiayaan`
--
ALTER TABLE `penyimpanan_pembiayaan`
  ADD CONSTRAINT `penyimpanan_pembiayaan_id_pembiayaan_foreign` FOREIGN KEY (`id_pembiayaan`) REFERENCES `pembiayaan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penyimpanan_pembiayaan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_rekening`
--
ALTER TABLE `penyimpanan_rekening`
  ADD CONSTRAINT `penyimpanan_rekening_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_shu`
--
ALTER TABLE `penyimpanan_shu`
  ADD CONSTRAINT `penyimpanan_shu_id_shu_foreign` FOREIGN KEY (`id_shu`) REFERENCES `shu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_tabungan`
--
ALTER TABLE `penyimpanan_tabungan`
  ADD CONSTRAINT `penyimpanan_tabungan_id_tabungan_foreign` FOREIGN KEY (`id_tabungan`) REFERENCES `tabungan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penyimpanan_tabungan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_users`
--
ALTER TABLE `penyimpanan_users`
  ADD CONSTRAINT `penyimpanan_users_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyimpanan_wajib_pokok`
--
ALTER TABLE `penyimpanan_wajib_pokok`
  ADD CONSTRAINT `penyimpanan_wajib_pokok_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penyimpanan_wajib_pokok_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tabungan`
--
ALTER TABLE `tabungan`
  ADD CONSTRAINT `tabungan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tabungan_id_rekening_foreign` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tabungan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
