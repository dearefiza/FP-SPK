-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 04, 2025 at 11:50 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spksaww`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin'),
(2, 'user', '123');

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id` int UNSIGNED NOT NULL,
  `nama_divisi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id`, `nama_divisi`) VALUES
(1, 'Customer Support'),
(2, 'Engineering'),
(3, 'Finance'),
(4, 'HR'),
(5, 'Legal'),
(6, 'Marketing'),
(7, 'Operation'),
(8, 'Sales');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id` int UNSIGNED NOT NULL,
  `nama_karyawan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `divisi_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id`, `nama_karyawan`, `divisi_id`) VALUES
(1, 'Andi Pratama', 1),
(2, 'Siti Rahmawati', 2),
(3, 'Budi Santoso', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int UNSIGNED NOT NULL,
  `nama_kriteria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sifat_kriteria_id` int UNSIGNED NOT NULL,
  `bobot` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama_kriteria`, `sifat_kriteria_id`, `bobot`) VALUES
(1, 'Tanggung Jawab', 1, '0.37'),
(2, 'Komunikasi', 1, '0.23'),
(3, 'Absensi', 2, '0.20'),
(4, 'Kerja Sama', 1, '0.07'),
(5, 'Sikap & Etika', 1, '0.12');

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id` int UNSIGNED NOT NULL,
  `karyawan_id` int UNSIGNED NOT NULL,
  `divisi_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id`, `karyawan_id`, `divisi_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `penilaian_kriteria`
--

CREATE TABLE `penilaian_kriteria` (
  `id` int UNSIGNED NOT NULL,
  `penilaian_id` int UNSIGNED NOT NULL,
  `kriteria_id` int UNSIGNED NOT NULL,
  `nilai` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penilaian_kriteria`
--

INSERT INTO penilaian_kriteria (id, penilaian_id, kriteria_id, nilai) VALUES
(1, 1, 1, 64.00),
(2, 1, 2, 81.00),
(3, 1, 3, 90.48),
(4, 1, 4, 62.00),
(5, 1, 5, 66.00);

-- Siti Rahmawati (penilaian_id = 2)
INSERT INTO penilaian_kriteria (id, penilaian_id, kriteria_id, nilai) VALUES
(6, 2, 1, 59.00),
(7, 2, 2, 80.00),
(8, 2, 3, 90.48),
(9, 2, 4, 52.00),
(10, 2, 5, 78.00);

-- Budi Santoso (penilaian_id = 3)
INSERT INTO penilaian_kriteria (id, penilaian_id, kriteria_id, nilai) VALUES
(11, 3, 1, 89.00),
(12, 3, 2, 74.00),
(13, 3, 3, 85.71),
(14, 3, 4, 84.00),
(15, 3, 5, 90.00);

-- --------------------------------------------------------

--
-- Table structure for table `sifat_kriteria`
--

CREATE TABLE `sifat_kriteria` (
  `id` int UNSIGNED NOT NULL,
  `nama_sifat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sifat_kriteria`
--

INSERT INTO `sifat_kriteria` (`id`, `nama_sifat`) VALUES
(1, 'Benefit'),
(2, 'Cost');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
