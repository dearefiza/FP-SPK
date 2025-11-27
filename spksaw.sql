-- phpMyAdmin SQL Dump (Versi SDM/Karyawan dengan AUTO_INCREMENT dan admin)
-- Database: `spksaw`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Tabel: divisi
-- --------------------------------------------------------
CREATE TABLE `divisi` (
  `id_divisi` int(3) NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(50) NOT NULL,
  PRIMARY KEY (`id_divisi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`) VALUES
(1, 'IT'),
(2, 'Finance'),
(3, 'HRD');

-- --------------------------------------------------------
-- Tabel: karyawan
-- --------------------------------------------------------
CREATE TABLE `karyawan` (
  `id_karyawan` int(3) NOT NULL AUTO_INCREMENT,
  `nama_karyawan` varchar(50) NOT NULL,
  `id_divisi` int(3) NOT NULL,
  PRIMARY KEY (`id_karyawan`),
  KEY `id_divisi` (`id_divisi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`, `id_divisi`) VALUES
(1, 'Andi Pratama', 1),
(2, 'Siti Rahmawati', 2),
(3, 'Budi Santoso', 1);

-- --------------------------------------------------------
-- Tabel: kriteria
-- --------------------------------------------------------
CREATE TABLE `kriteria` (
  `id_kriteria` int(3) NOT NULL AUTO_INCREMENT,
  `nama_kriteria` varchar(50) NOT NULL,
  `sifat` enum('Benefit','Cost') NOT NULL,
  PRIMARY KEY (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `sifat`) VALUES
(1, 'Kedisiplinan', 'Benefit'),
(2, 'Kompetensi Kerja', 'Benefit'),
(3, 'Absensi', 'Cost'),
(4, 'Loyalitas', 'Benefit'),
(5, 'Kinerja', 'Benefit');

-- --------------------------------------------------------
-- Tabel: nilai_kriteria
-- --------------------------------------------------------
CREATE TABLE `nilai_kriteria` (
  `id_nilaikriteria` int(3) NOT NULL AUTO_INCREMENT,
  `id_kriteria` int(3) NOT NULL,
  `nilai` float NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_nilaikriteria`),
  KEY `id_kriteria` (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `nilai_kriteria` (`id_nilaikriteria`, `id_kriteria`, `nilai`, `keterangan`) VALUES
(1, 1, 1, 'Sangat Baik'),
(2, 1, 0.75, 'Baik'),
(3, 1, 0.5, 'Cukup'),
(4, 1, 0.25, 'Kurang'),
(5, 2, 1, 'Sangat Kompeten'),
(6, 2, 0.75, 'Kompeten'),
(7, 2, 0.5, 'Cukup'),
(8, 2, 0.25, 'Kurang'),
(9, 3, 1, '0–2% Tidak Hadir'),
(10, 3, 0.75, '3–5% Tidak Hadir'),
(11, 3, 0.5, '6–10% Tidak Hadir'),
(12, 3, 0.25, '10%+ Tidak Hadir'),
(13, 4, 1, 'Sangat Loyal'),
(14, 4, 0.75, 'Loyal'),
(15, 4, 0.5, 'Cukup Loyal'),
(16, 4, 0.25, 'Kurang Loyal'),
(17, 5, 1, 'Sangat Baik'),
(18, 5, 0.75, 'Baik'),
(19, 5, 0.5, 'Cukup'),
(20, 5, 0.25, 'Kurang');

-- --------------------------------------------------------
-- Tabel: bobot_kriteria
-- --------------------------------------------------------
CREATE TABLE `bobot_kriteria` (
  `id_bobotkriteria` int(3) NOT NULL AUTO_INCREMENT,
  `id_kriteria` int(3) NOT NULL,
  `bobot` float NOT NULL,
  PRIMARY KEY (`id_bobotkriteria`),
  KEY `id_kriteria` (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `bobot_kriteria` (`id_bobotkriteria`, `id_kriteria`, `bobot`) VALUES
(1, 1, 0.2),
(2, 2, 0.3),
(3, 3, 0.2),
(4, 4, 0.15),
(5, 5, 0.15);

-- --------------------------------------------------------
-- Tabel: absensi
-- --------------------------------------------------------
CREATE TABLE `absensi` (
  `id_absensi` int(5) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(3) NOT NULL,
  `hari_kerja` int(3) NOT NULL,
  `hadir` int(3) NOT NULL,
  `sakit` int(3) NOT NULL,
  `izin` int(3) NOT NULL,
  `alpha` int(3) NOT NULL,
  `kehadiran_persen` float NOT NULL,
  `skor_kehadiran` int(2) NOT NULL,
  PRIMARY KEY (`id_absensi`),
  KEY `id_karyawan` (`id_karyawan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `absensi` (`id_absensi`, `id_karyawan`, `hari_kerja`, `hadir`, `sakit`, `izin`, `alpha`, `kehadiran_persen`, `skor_kehadiran`) VALUES
(1, 1, 21, 19, 2, 0, 0, 90.48, 5),
(2, 2, 21, 19, 0, 2, 0, 90.48, 5),
(3, 3, 21, 18, 0, 1, 1, 85.71, 5);

-- --------------------------------------------------------
-- Tabel: nilai_karyawan
-- --------------------------------------------------------
CREATE TABLE `nilai_karyawan` (
  `id_nilaikaryawan` int(3) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(3) NOT NULL,
  `id_kriteria` int(3) NOT NULL,
  `id_nilaikriteria` int(3) NOT NULL,
  PRIMARY KEY (`id_nilaikaryawan`),
  KEY `id_karyawan` (`id_karyawan`),
  KEY `id_kriteria` (`id_kriteria`),
  KEY `id_nilaikriteria` (`id_nilaikriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `nilai_karyawan` (`id_nilaikaryawan`, `id_karyawan`, `id_kriteria`, `id_nilaikriteria`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 6),
(3, 1, 4, 14),
(4, 1, 5, 18),
(5, 2, 1, 3),
(6, 2, 2, 7),
(7, 2, 4, 15),
(8, 2, 5, 19);

-- --------------------------------------------------------
-- Tabel: hasil
-- --------------------------------------------------------
CREATE TABLE `hasil` (
  `id_hasil` int(3) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(3) NOT NULL,
  `hasil` float NOT NULL,
  PRIMARY KEY (`id_hasil`),
  KEY `id_karyawan` (`id_karyawan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `hasil` (`id_hasil`, `id_karyawan`, `hasil`) VALUES
(1, 1, 0.82),
(2, 2, 0.79);

-- --------------------------------------------------------
-- Tabel: admin
-- --------------------------------------------------------
CREATE TABLE `admin` (
  `id_admin` int(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', 'admin'),
('superuser', 'superpass');

COMMIT;
