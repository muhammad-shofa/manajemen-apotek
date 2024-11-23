-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2024 at 08:54 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manajemen_apotek`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `barang_id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kategori` enum('Obat','Makanan','Minuman','Lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `harga_satuan` int NOT NULL,
  `harga_butir` int DEFAULT NULL,
  `stok` int NOT NULL,
  `satuan` enum('Pcs','Butir','Strip','Botol') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`barang_id`, `nama`, `kategori`, `harga_satuan`, `harga_butir`, `stok`, `satuan`) VALUES
(29, 'Kandistatin', 'Obat', 52000, 0, 19, 'Botol'),
(30, 'KSR', 'Obat', 30000, 0, 3, 'Strip'),
(31, 'Ketoconazole sk', 'Obat', 7000, 0, 19, 'Pcs'),
(32, 'Klorfeson sk', 'Obat', 10000, 0, 0, 'Pcs'),
(33, 'Kalnex 500', 'Obat', 31000, 0, 10, 'Strip'),
(34, 'Kloderma sk', 'Obat', 0, 0, 2, 'Pcs'),
(35, 'Jarum', 'Lainnya', 21000, 0, 12, 'Pcs'),
(36, 'Kamolas sy', 'Obat', 8000, 0, 9, 'Botol'),
(37, 'Kasa', 'Lainnya', 5000, 0, 124, 'Pcs'),
(38, 'Kompolax sy', 'Obat', 0, 0, 5, 'Botol'),
(39, 'Kalnex 250', 'Obat', 20000, 0, 0, 'Strip'),
(40, 'Kaotin sy', 'Obat', 10000, 0, 0, 'Botol'),
(41, 'Koniflam', 'Obat', 0, 0, 3, 'Strip'),
(42, 'Kenalog', 'Obat', 80000, 0, 0, 'Pcs'),
(43, 'Kamolas', 'Obat', 5000, 0, 0, 'Strip'),
(44, 'Kalcinol sk', 'Obat', 15000, 0, 8, 'Pcs'),
(45, 'Ketoconazole tab', 'Obat', 7000, 0, 53, 'Strip'),
(46, 'Kaditic', 'Obat', 5000, 0, 9, 'Strip'),
(47, 'Kalmethasone', 'Obat', 2000, 0, 375, 'Strip'),
(48, 'Lorahistin', 'Obat', 7000, 0, 6, 'Strip'),
(49, 'Licokalk', 'Obat', 4000, 0, 8, 'Strip'),
(50, 'Limacyl', 'Obat', 7000, 0, 6, 'Strip'),
(51, 'Lambucid', 'Obat', 5000, 0, 1, 'Strip'),
(52, 'Lapistan', 'Obat', 18000, 0, 0, 'Strip'),
(53, 'Licofel', 'Obat', 5000, 0, 0, 'Strip'),
(54, 'Laxadin sy', 'Obat', 80000, 0, 5, 'Botol'),
(55, 'Lerzin drop', 'Obat', 10000, 0, 0, 'Botol'),
(56, 'Lasix', 'Obat', 0, 7000, 66, 'Butir'),
(57, 'Lidocain', 'Obat', 4000, 0, 87, 'Pcs'),
(58, 'Lerzin', 'Obat', 7000, 0, 7, 'Strip'),
(59, 'Lodia', 'Obat', 15000, 0, 11, 'Strip'),
(60, 'Lostacef sy', 'Obat', 12000, 0, 24, 'Botol'),
(61, 'Lanareuma', 'Obat', 7000, 0, 18, 'Strip'),
(62, 'Lactulax sy', 'Obat', 0, 0, 2, 'Botol'),
(63, 'Lansoprazole', 'Obat', 15000, 0, 12, 'Strip'),
(64, 'Lambucid sy', 'Obat', 12000, 0, 6, 'Botol'),
(65, 'Levofloxacin', 'Obat', 14000, 0, 4, 'Strip'),
(66, 'Loratadin', 'Obat', 5000, 0, 1, 'Strip'),
(67, 'Lecozinc sy', 'Obat', 10000, 0, 5, 'Botol'),
(68, 'Lameson 8', 'Obat', 0, 0, 2, 'Strip'),
(69, 'Livron B Plex', 'Obat', 7000, 0, 9, 'Strip'),
(70, 'Lecozinc tab', 'Obat', 10000, 0, 7, 'Strip'),
(71, 'Lecozinc tab', 'Obat', 10000, 0, 7, 'Strip'),
(72, 'LIcodexon 0,75', 'Obat', 2000, 0, 12, 'Strip'),
(73, 'Lisinopril 5', 'Obat', 6000, 0, 18, 'Strip'),
(74, 'Licokalk Plus', 'Obat', 4000, 0, 12, 'Strip'),
(75, 'Licodexon 0,5', 'Obat', 2000, 0, 16, 'Strip'),
(76, 'Lerzin sy', 'Obat', 8000, 0, 6, 'Botol'),
(77, 'Lopamid', 'Obat', 5000, 0, 9, 'Strip'),
(78, 'Lameson 4', 'Obat', 60000, 0, 4, 'Strip'),
(79, 'Lisinopril 10', 'Obat', 8000, 0, 47, 'Strip'),
(80, 'Linogra', 'Obat', 5000, 0, 0, 'Strip'),
(81, 'Lapraz', 'Obat', 0, 0, 3, 'Strip'),
(82, 'Lostacef tab', 'Obat', 13000, 0, 38, 'Strip'),
(83, 'Licostan', 'Obat', 5000, 20, 0, 'Strip');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `barang_keluar_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `kategori` enum('Obat','Makanan','Minuman') NOT NULL,
  `jumlah_keluar` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `total_harga` int NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `barang_masuk_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `nomor_bacth` varchar(50) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jumlah_masuk` int NOT NULL,
  `exp` date DEFAULT NULL,
  `supplier` varchar(100) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(150) NOT NULL,
  `role` enum('admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `role`) VALUES
(1, 'Admin Apotek', 'admin', 'admin123', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`barang_id`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`barang_keluar_id`),
  ADD KEY `fk_keluar_barang_id` (`barang_id`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`barang_masuk_id`),
  ADD KEY `fk_masuk_barang_id` (`barang_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `barang_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `barang_keluar_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `barang_masuk_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `fk_keluar_barang_id` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `fk_masuk_barang_id` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
