-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 08, 2024 at 08:48 AM
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
(29, 'Kandistatin', 'Obat', 52000, 0, 1748, 'Botol'),
(30, 'KSR', 'Obat', 30000, 0, 95, 'Strip'),
(31, 'Ketoconazole sk', 'Obat', 7000, 0, -3, 'Pcs'),
(32, 'Klorfeson sk', 'Obat', 10000, 0, 19, 'Pcs'),
(33, 'Kalnex 500', 'Obat', 31000, 0, 4, 'Strip'),
(34, 'Kloderma sk', 'Obat', 0, 9000, 1, 'Pcs'),
(35, 'Jarum', 'Lainnya', 21000, 0, 10, 'Pcs'),
(36, 'Kamolas sy', 'Obat', 8000, 0, 9, 'Botol'),
(37, 'Kasa', 'Lainnya', 5000, 0, 124, 'Pcs'),
(38, 'Kompolax sy', 'Obat', 0, 0, 5, 'Botol'),
(39, 'Kalnex 250', 'Obat', 20000, 0, 0, 'Strip'),
(40, 'Kaotin sy', 'Obat', 10000, 0, 0, 'Botol'),
(41, 'Koniflam', 'Obat', 0, 0, 3, 'Strip'),
(42, 'Kenalog', 'Obat', 80000, 0, 0, 'Pcs'),
(43, 'Kamolas', 'Obat', 5000, 0, -3, 'Strip'),
(44, 'Kalcinol sk', 'Obat', 15000, 0, 8, 'Pcs'),
(45, 'Ketoconazole tab', 'Obat', 7000, 0, 53, 'Strip'),
(46, 'Kaditic', 'Obat', 5000, 0, 9, 'Strip'),
(47, 'Kalmethasone', 'Obat', 2000, 0, 375, 'Strip'),
(48, 'Lorahistin', 'Obat', 7000, 0, 6, 'Strip'),
(49, 'Licokalk', 'Obat', 4000, 0, 8, 'Strip'),
(50, 'Limacyl', 'Obat', 7000, 0, 6, 'Strip'),
(51, 'Lambucid', 'Obat', 5000, 0, 1, 'Strip'),
(52, 'Lapistan', 'Obat', 18000, 0, 80, 'Strip'),
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
(66, 'Loratadin', 'Obat', 5000, 0, -2, 'Strip'),
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
  `kategori` enum('Obat','Makanan','Minuman','Lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `jumlah_keluar` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `total_harga` int NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`barang_keluar_id`, `barang_id`, `kategori`, `jumlah_keluar`, `harga_satuan`, `total_harga`, `tanggal_keluar`, `keterangan`) VALUES
(111, 34, 'Obat', 5, 9000, 45000, '2024-11-24', 'Penjualan'),
(112, 29, 'Obat', 1, 52000, 52000, '2024-11-24', 'Penjualan'),
(113, 31, 'Obat', 1, 7000, 7000, '2024-11-24', 'Penjualan'),
(114, 34, 'Obat', 2, 9000, 18000, '2024-11-24', 'Penjualan'),
(115, 32, 'Obat', 2, 10000, 20000, '2024-11-24', 'Penjualan'),
(116, 66, 'Obat', 3, 5000, 15000, '2024-11-24', 'Penjualan'),
(117, 34, 'Obat', 3, 9000, 27000, '2024-11-24', 'Penjualan'),
(118, 30, 'Obat', 1, 30000, 30000, '2024-11-24', 'Penjualan'),
(119, 29, 'Obat', 1, 52000, 52000, '2024-11-24', 'Penjualan'),
(120, 32, 'Obat', 1, 10000, 10000, '2024-11-24', 'Penjualan'),
(121, 31, 'Obat', 1, 7000, 7000, '2024-11-24', 'Penjualan'),
(122, 30, 'Obat', 1, 30000, 30000, '2024-11-24', 'Penjualan'),
(123, 29, 'Obat', 1, 52000, 52000, '2024-11-24', 'Penjualan'),
(124, 31, 'Obat', 1, 7000, 7000, '2024-11-24', 'Penjualan'),
(125, 34, 'Obat', 1, 9000, 9000, '2024-11-24', 'Penjualan'),
(126, 32, 'Obat', 1, 10000, 10000, '2024-11-24', 'Penjualan'),
(127, 43, 'Obat', 3, 5000, 15000, '2024-11-24', 'Penjualan'),
(128, 34, 'Obat', 5, 9000, 45000, '2024-11-24', 'Penjualan'),
(129, 32, 'Obat', 2, 10000, 20000, '2024-11-24', 'Penjualan'),
(130, 34, 'Obat', 2, 9000, 18000, '2024-11-24', 'Penjualan'),
(131, 32, 'Obat', 2, 10000, 20000, '2024-11-24', 'Penjualan'),
(132, 31, 'Obat', 6, 7000, 42000, '2024-11-24', 'Penjualan'),
(133, 33, 'Obat', 6, 31000, 186000, '2024-11-24', 'Penjualan'),
(134, 35, 'Lainnya', 2, 21000, 42000, '2024-11-24', 'Penjualan'),
(135, 31, 'Obat', 1, 7000, 7000, '2024-11-24', 'Penjualan'),
(136, 29, 'Obat', 1, 52000, 52000, '2024-11-24', 'Penjualan'),
(137, 31, 'Obat', 1, 7000, 7000, '2024-11-24', 'Penjualan'),
(138, 29, 'Obat', 1, 52000, 52000, '2024-11-24', 'Penjualan'),
(139, 30, 'Obat', 1, 30000, 30000, '2024-11-24', 'Penjualan'),
(140, 30, 'Obat', 3, 30000, 90000, '2024-11-24', 'Penjualan'),
(141, 29, 'Obat', 1, 52000, 52000, '2024-11-24', 'Penjualan'),
(142, 34, 'Obat', 1, 9000, 9000, '2024-11-24', 'Penjualan'),
(143, 34, 'Obat', 3, 9000, 27000, '2024-11-24', 'Penjualan'),
(144, 32, 'Obat', 2, 10000, 20000, '2024-11-24', 'Penjualan'),
(145, 30, 'Obat', 3, 30000, 90000, '2024-11-24', 'Penjualan'),
(146, 30, 'Obat', 1, 30000, 30000, '2024-11-24', 'Penjualan'),
(147, 32, 'Obat', 1, 10000, 10000, '2024-11-24', 'Penjualan'),
(148, 34, 'Obat', 2, 9000, 18000, '2024-11-24', 'Penjualan'),
(149, 30, 'Obat', 7, 30000, 210000, '2024-11-24', 'Penjualan'),
(150, 30, 'Obat', 2, 30000, 60000, '2024-11-24', 'Penjualan'),
(151, 34, 'Obat', 3, 9000, 27000, '2024-11-24', 'Penjualan'),
(152, 32, 'Obat', 2, 10000, 20000, '2024-11-24', 'Penjualan'),
(153, 34, 'Obat', 1, 9000, 9000, '2024-11-24', 'Penjualan'),
(154, 32, 'Obat', 1, 10000, 10000, '2024-11-24', 'Penjualan'),
(155, 30, 'Obat', 2, 30000, 60000, '2024-11-24', 'Penjualan'),
(156, 32, 'Obat', 2, 10000, 20000, '2024-11-24', 'Penjualan'),
(157, 32, 'Obat', 3, 10000, 30000, '2024-11-26', 'Penjualan'),
(158, 31, 'Obat', 1, 7000, 7000, '2024-11-26', 'Penjualan'),
(159, 29, 'Obat', 1, 52000, 52000, '2024-11-26', 'Penjualan'),
(160, 30, 'Obat', 5, 30000, 150000, '2024-11-26', 'Penjualan'),
(161, 31, 'Obat', 3, 7000, 21000, '2024-11-26', 'Penjualan'),
(162, 34, 'Obat', 10, 9000, 90000, '2024-11-26', 'Penjualan'),
(163, 32, 'Obat', 5, 10000, 50000, '2024-11-26', 'Penjualan'),
(164, 30, 'Obat', 4, 30000, 120000, '2024-11-26', 'Penjualan'),
(165, 30, 'Obat', 4, 30000, 120000, '2024-11-26', 'Penjualan'),
(166, 34, 'Obat', 3, 9000, 27000, '2024-11-26', 'Penjualan'),
(167, 32, 'Obat', 1, 10000, 10000, '2024-11-26', 'Penjualan'),
(168, 29, 'Obat', 5, 52000, 260000, '2024-11-26', 'Penjualan'),
(169, 30, 'Obat', 3, 30000, 90000, '2024-11-26', 'Penjualan'),
(170, 31, 'Obat', 5, 7000, 35000, '2024-11-26', 'Penjualan'),
(171, 34, 'Obat', 5, 9000, 45000, '2024-11-29', 'Penjualan'),
(172, 30, 'Obat', 2, 30000, 60000, '2024-11-29', 'Penjualan'),
(173, 30, 'Obat', 1, 30000, 30000, '2024-11-29', 'Penjualan'),
(174, 34, 'Obat', 2, 9000, 18000, '2024-11-29', 'Penjualan'),
(175, 32, 'Obat', 3, 10000, 30000, '2024-11-29', 'Penjualan'),
(176, 30, 'Obat', 2, 30000, 60000, '2024-11-29', 'Penjualan'),
(177, 34, 'Obat', 1, 9000, 9000, '2024-11-29', 'Penjualan'),
(178, 30, 'Obat', 9, 30000, 270000, '2024-11-29', 'Penjualan'),
(179, 32, 'Obat', 3, 10000, 30000, '2024-12-03', 'Penjualan'),
(180, 34, 'Obat', 5, 9000, 45000, '2024-12-03', 'Penjualan'),
(181, 32, 'Obat', 1, 10000, 10000, '2024-12-08', 'Penjualan'),
(182, 32, 'Obat', 1, 10000, 10000, '2024-12-08', 'Penjualan'),
(183, 29, 'Obat', 3, 52000, 156000, '2024-12-08', 'Penjualan'),
(184, 31, 'Obat', 5, 6000, 30000, '2024-12-08', 'Penjualan'),
(185, 31, 'Obat', 2, 7000, 14000, '2024-12-08', 'Penjualan'),
(186, 34, 'Obat', 3, 9000, 27000, '2024-12-08', 'Penjualan'),
(187, 30, 'Obat', 3, 30000, 90000, '2024-12-08', 'Penjualan'),
(188, 30, 'Obat', 5, 30000, 150000, '2024-12-08', 'Penjualan');

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

--
-- Dumping data for table `barang_masuk`
--

INSERT INTO `barang_masuk` (`barang_masuk_id`, `barang_id`, `nomor_bacth`, `tanggal_masuk`, `jumlah_masuk`, `exp`, `supplier`, `keterangan`) VALUES
(40, 52, '', '2024-11-24', 80, NULL, '', ''),
(43, 29, '', '2024-11-13', 500, '2025-06-06', 'PT Serba Ada', ''),
(44, 29, '9812hajkd', '2024-12-03', 400, NULL, 'as', ''),
(45, 29, '', '2024-12-03', 12, NULL, '', ''),
(46, 29, '', '2024-12-03', 0, NULL, '', ''),
(47, 29, 'asd', '2024-12-03', 233, '2024-12-27', 'as', 'as'),
(48, 30, '', '2024-12-03', 0, '2024-12-03', '', ''),
(49, 30, '', '2024-12-03', 50, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_kasir`
--

CREATE TABLE `transaksi_kasir` (
  `transaksi_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `kuantitas` int NOT NULL,
  `harga` int NOT NULL,
  `total_harga` int NOT NULL,
  `transaksi_ke` int NOT NULL,
  `status` enum('O','C') NOT NULL DEFAULT 'O'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_kasir`
--

INSERT INTO `transaksi_kasir` (`transaksi_id`, `barang_id`, `kuantitas`, `harga`, `total_harga`, `transaksi_ke`, `status`) VALUES
(82, 32, 1, 10000, 10000, 1, 'C'),
(83, 29, 3, 52000, 156000, 2, 'C'),
(84, 31, 5, 6000, 30000, 3, 'C'),
(85, 31, 2, 7000, 14000, 4, 'C'),
(86, 34, 3, 9000, 27000, 5, 'C'),
(87, 30, 3, 30000, 90000, 6, 'C'),
(88, 30, 5, 30000, 150000, 7, 'C');

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
-- Indexes for table `transaksi_kasir`
--
ALTER TABLE `transaksi_kasir`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `fk_barang_id` (`barang_id`);

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
  MODIFY `barang_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `barang_keluar_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `barang_masuk_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `transaksi_kasir`
--
ALTER TABLE `transaksi_kasir`
  MODIFY `transaksi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

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

--
-- Constraints for table `transaksi_kasir`
--
ALTER TABLE `transaksi_kasir`
  ADD CONSTRAINT `fk_barang_id` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
