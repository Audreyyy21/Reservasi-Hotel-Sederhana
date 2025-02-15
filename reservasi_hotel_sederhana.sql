-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2025 at 10:28 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reservasi_hotel_sederhana`
--

-- --------------------------------------------------------

--
-- Table structure for table `hari_libur`
--

CREATE TABLE `hari_libur` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hari_libur`
--

INSERT INTO `hari_libur` (`id`, `tanggal`, `keterangan`) VALUES
(1, '2025-01-01', 'Tahun Baru Masehi'),
(2, '2025-02-28', 'Isra Miraj Nabi Muhammad SAW'),
(3, '2025-03-29', 'Hari Raya Nyepi'),
(4, '2025-04-17', 'Wafat Isa Almasih'),
(5, '2025-05-01', 'Hari Buruh Internasional'),
(6, '2025-05-15', 'Kenaikan Isa Almasih'),
(7, '2025-05-25', 'Hari Raya Waisak'),
(8, '2025-06-07', 'Hari Lahir Pancasila'),
(9, '2025-07-07', 'Idul Adha 1446 H'),
(10, '2025-08-17', 'Hari Kemerdekaan Indonesia'),
(11, '2025-09-24', 'Maulid Nabi Muhammad SAW'),
(12, '2025-12-25', 'Hari Natal'),
(13, '2025-02-14', 'Hari Libur Nasional');

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `kamar_id` int(11) NOT NULL,
  `nomor_kamar` varchar(10) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `markup_weekend` decimal(5,2) DEFAULT 1.20,
  `markup_hari_libur` decimal(5,2) DEFAULT 1.30,
  `status` enum('Tersedia','Dipesan') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kamar`
--

INSERT INTO `kamar` (`kamar_id`, `nomor_kamar`, `tipe`, `harga`, `markup_weekend`, `markup_hari_libur`, `status`) VALUES
(2, '102', 'Deluxe', 500000.00, 1.20, 1.30, 'Tersedia'),
(3, '103', 'Suite', 800000.00, 1.25, 1.35, 'Dipesan'),
(4, '104', 'Standard', 300000.00, 1.15, 1.25, 'Dipesan'),
(5, '105', 'Standard', 300000.00, 1.15, 1.25, 'Tersedia'),
(7, '106', 'Superior', 1000000.00, 1.20, 1.30, 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `reservasi_id` int(11) NOT NULL,
  `tamu_id` int(11) NOT NULL,
  `kamar_id` int(11) NOT NULL,
  `tanggal_checkin` date NOT NULL,
  `tanggal_checkout` date NOT NULL,
  `status` enum('Dipesan','Check-in','Selesai','Dibatalkan') DEFAULT 'Dipesan',
  `total_harga` decimal(10,2) NOT NULL,
  `status_pembayaran` varchar(50) NOT NULL DEFAULT 'Belum Dibayar',
  `metode_pembayaran` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`reservasi_id`, `tamu_id`, `kamar_id`, `tanggal_checkin`, `tanggal_checkout`, `status`, `total_harga`, `status_pembayaran`, `metode_pembayaran`) VALUES
(7, 11, 3, '2025-02-21', '2025-02-22', 'Dipesan', 800000.00, 'Sudah Bayar', 'Tunai'),
(8, 12, 4, '2025-02-21', '2025-02-22', 'Dipesan', 300000.00, 'Sudah Bayar', 'Transfer Bank');

-- --------------------------------------------------------

--
-- Table structure for table `tamu`
--

CREATE TABLE `tamu` (
  `tamu_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tamu`
--

INSERT INTO `tamu` (`tamu_id`, `nama`, `email`, `telepon`, `alamat`, `kota`) VALUES
(11, 'wildan', 'audreymediliani@gmail.com', '0895702829606', 'Link. Baru', 'Kota Cilegon'),
(12, 'laras', 'laras.10122285@mahasiswa.unikom.ac.id', '089578342522', 'Jl. Tubagus Ismail Dalam No.11', 'Kota Bandung');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int(11) NOT NULL,
  `reservasi_id` int(11) NOT NULL,
  `metode_pembayaran` enum('Tunai','Kartu Kredit','Transfer Bank','E-Wallet') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `status` enum('Lunas','Belum Lunas') DEFAULT 'Belum Lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hari_libur`
--
ALTER TABLE `hari_libur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`kamar_id`),
  ADD UNIQUE KEY `nomor_kamar` (`nomor_kamar`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`reservasi_id`),
  ADD KEY `tamu_id` (`tamu_id`),
  ADD KEY `kamar_id` (`kamar_id`),
  ADD KEY `fk_reservasi_hari_libur` (`tanggal_checkin`);

--
-- Indexes for table `tamu`
--
ALTER TABLE `tamu`
  ADD PRIMARY KEY (`tamu_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `reservasi_id` (`reservasi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hari_libur`
--
ALTER TABLE `hari_libur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `kamar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `reservasi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tamu`
--
ALTER TABLE `tamu`
  MODIFY `tamu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`tamu_id`) REFERENCES `tamu` (`tamu_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasi_ibfk_2` FOREIGN KEY (`kamar_id`) REFERENCES `kamar` (`kamar_id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`reservasi_id`) REFERENCES `reservasi` (`reservasi_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
