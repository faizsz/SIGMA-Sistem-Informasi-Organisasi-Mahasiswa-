-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 24 Feb 2026 pada 16.10
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sigma`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `divisi_ukm`
--

CREATE TABLE `divisi_ukm` (
  `id_divisi` int NOT NULL,
  `id_ukm` int DEFAULT NULL,
  `nama_divisi` varchar(100) NOT NULL,
  `deskripsi` text,
  `hierarki` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipe_divisi` enum('inti','divisi') NOT NULL DEFAULT 'divisi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `divisi_ukm`
--

INSERT INTO `divisi_ukm` (`id_divisi`, `id_ukm`, `nama_divisi`, `deskripsi`, `hierarki`, `created_at`, `updated_at`, `tipe_divisi`) VALUES
(1, 1, 'inti', 'pengurus inti', 1, '2024-11-23 08:20:27', '2024-11-30 19:13:17', 'inti'),
(2, 1, 'devisi abal abal', 'ling lau', 2, '2024-11-23 08:20:27', '2024-11-23 09:53:28', 'divisi'),
(3, 1, 'devisi peternakan', 'eek pus', 2, '2024-11-23 08:20:27', '2024-11-30 18:36:38', 'divisi'),
(4, 7, 'devisi musang', 'king', 0, '2024-11-23 08:20:27', '2024-11-23 08:20:27', 'divisi'),
(5, 7, 'devisi makan besar', 'mukbang uy', 0, '2024-11-23 08:20:27', '2024-11-23 08:20:27', 'divisi'),
(6, 1, 'devisi danus', 'safsagrga', 2, '2024-11-30 19:22:15', '2024-11-30 19:22:15', 'divisi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumentasi_kegiatan`
--

CREATE TABLE `dokumentasi_kegiatan` (
  `id_dokumentasi` int NOT NULL,
  `id_timeline` int DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `foto_path` varchar(255) NOT NULL,
  `ukuran_file` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumentasi_rapat`
--

CREATE TABLE `dokumentasi_rapat` (
  `id_dokumentasi` int NOT NULL,
  `id_rapat` int DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `dokumentasi_rapat`
--

INSERT INTO `dokumentasi_rapat` (`id_dokumentasi`, `id_rapat`, `foto_path`) VALUES
(17, 13, 'dokumentasi-67405c6cc4c0d.jpg'),
(19, 15, 'dokumentasi-6743c989d671c.jpg'),
(20, 16, 'dokumentasi-6743d61fc596f.jpg'),
(21, 17, 'dokumentasi-675f7a2abf597.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_pendaftaran`
--

CREATE TABLE `dokumen_pendaftaran` (
  `id_dokumen` int NOT NULL,
  `id_pendaftaran` int DEFAULT NULL,
  `id_jenis_dokumen` int DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `dokumen_pendaftaran`
--

INSERT INTO `dokumen_pendaftaran` (`id_dokumen`, `id_pendaftaran`, `id_jenis_dokumen`, `file_path`) VALUES
(67, 34, 1, 'izin_ortu-6755fb3aca302.jpeg'),
(68, 34, 2, 'sertifikat_wa_rna-6755fb3acbb08.pdf'),
(69, 34, 3, 'sertifikat_lkmm-6755fb3acc6ae.jpg'),
(70, 35, 1, 'izin_ortu-675637cbd762d.pdf'),
(71, 35, 2, 'sertifikat_wa_rna-675637cbd86fd.png'),
(72, 35, 3, 'sertifikat_lkmm-675637cbd8dee.jpeg'),
(73, 35, 4, 'ktm-675638452d03f.png'),
(74, 35, 5, 'khs-675638452d85b.jpeg'),
(75, 35, 6, 'cv-675638452ddc0.pdf'),
(76, 35, 7, 'motivation-675638452e2c2.pdf'),
(77, 37, 1, 'izin_ortu-675ea1a1368d5.jpg'),
(78, 37, 2, 'sertifikat_wa_rna-675ea1a1378a3.jpeg'),
(79, 37, 3, 'sertifikat_lkmm-675ea1a138269.jpg'),
(80, 37, 4, 'ktm-675ea233036ea.jpg'),
(81, 37, 5, 'khs-675ea23303cfb.png'),
(82, 37, 6, 'cv-675ea23304068.pdf'),
(83, 37, 7, 'motivation-675ea2330458b.pdf'),
(84, 38, 1, 'izin_ortu-675f7929c528c.png'),
(85, 38, 2, 'sertifikat_wa_rna-675f7929c5a80.png'),
(86, 38, 3, 'sertifikat_lkmm-675f7929c5f4a.png'),
(87, 38, 4, 'ktm-675f7960a0f8f.png'),
(88, 38, 5, 'khs-675f7960a13d3.png'),
(89, 38, 6, 'cv-675f7960a16f3.pdf'),
(90, 38, 7, 'motivation-675f7960a19d4.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `fakultas`
--

CREATE TABLE `fakultas` (
  `id_fakultas` int NOT NULL,
  `nama_fakultas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `fakultas`
--

INSERT INTO `fakultas` (`id_fakultas`, `nama_fakultas`) VALUES
(1, 'Elektro');

-- --------------------------------------------------------

--
-- Struktur dari tabel `history_pendaftaran`
--

CREATE TABLE `history_pendaftaran` (
  `id_history` int NOT NULL,
  `id_pendaftaran` int DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `id_ukm` int DEFAULT NULL,
  `tanggal_pendaftaran` timestamp NULL DEFAULT NULL,
  `tanggal_update_status` timestamp NULL DEFAULT NULL,
  `catatan_tahap1` text,
  `catatan_tahap2` text,
  `catatan_tahap3` text,
  `status` enum('belum_daftar','pending_tahap1','acc_tahap1','pending_tahap2','acc_tahap2','pending_tahap3','acc_tahap3','ditolak','selesai') NOT NULL DEFAULT 'belum_daftar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `history_pendaftaran`
--

INSERT INTO `history_pendaftaran` (`id_history`, `id_pendaftaran`, `nim`, `id_ukm`, `tanggal_pendaftaran`, `tanggal_update_status`, `catatan_tahap1`, `catatan_tahap2`, `catatan_tahap3`, `status`) VALUES
(1, 2, '43323223', 7, '2024-11-17 15:58:29', '2024-11-17 15:58:29', NULL, NULL, NULL, 'belum_daftar'),
(4, 5, '43323210', 1, '2024-12-08 01:57:33', '2024-12-09 01:57:33', NULL, NULL, NULL, 'belum_daftar'),
(5, 6, '43323223', 7, '2024-11-23 17:30:05', '2024-11-23 17:30:05', NULL, NULL, NULL, 'belum_daftar'),
(6, 7, '43323223', 7, '2024-11-25 01:50:13', '2024-11-25 01:50:13', NULL, NULL, NULL, 'belum_daftar'),
(7, 8, '43323210', 1, '2024-12-07 13:07:41', '2024-12-07 13:07:41', NULL, NULL, NULL, 'belum_daftar'),
(8, 9, '43323209', 1, '2024-12-07 15:58:03', '2024-12-07 15:58:03', NULL, NULL, NULL, 'belum_daftar'),
(9, 8, '43323210', 1, '2024-12-07 16:45:59', '2024-12-07 16:45:59', NULL, NULL, NULL, 'belum_daftar'),
(10, 8, '43323210', 1, '2024-12-07 16:45:59', '2024-12-07 16:45:59', NULL, NULL, NULL, 'belum_daftar'),
(11, 9, '43323209', 1, '2024-12-07 16:48:54', '2024-12-07 16:48:54', NULL, NULL, NULL, 'belum_daftar'),
(12, 9, '43323209', 1, '2024-12-07 16:48:54', '2024-12-07 16:48:54', NULL, NULL, NULL, 'belum_daftar'),
(13, 9, '43323209', 1, '2024-12-07 16:54:16', '2024-12-07 16:54:16', NULL, NULL, NULL, 'belum_daftar'),
(14, 9, '43323209', 1, '2024-12-07 16:54:16', '2024-12-07 16:54:16', NULL, NULL, NULL, 'belum_daftar'),
(15, 8, '43323210', 1, '2024-12-07 16:54:23', '2024-12-07 16:54:23', NULL, NULL, NULL, 'belum_daftar'),
(16, 8, '43323210', 1, '2024-12-07 16:54:23', '2024-12-07 16:54:23', NULL, NULL, NULL, 'belum_daftar'),
(17, 9, '43323209', 1, '2024-12-07 16:54:33', '2024-12-07 16:54:33', NULL, NULL, NULL, 'belum_daftar'),
(18, 9, '43323209', 1, '2024-12-07 16:54:33', '2024-12-07 16:54:33', NULL, NULL, NULL, 'belum_daftar'),
(19, 8, '43323210', 1, '2024-12-07 16:54:37', '2024-12-07 16:54:37', NULL, NULL, NULL, 'belum_daftar'),
(20, 8, '43323210', 1, '2024-12-07 16:54:37', '2024-12-07 16:54:37', NULL, NULL, NULL, 'belum_daftar'),
(21, 10, '43323210', 1, '2024-12-07 16:58:34', '2024-12-07 16:58:34', NULL, NULL, NULL, 'belum_daftar'),
(22, 10, '43323210', 1, '2024-12-07 16:58:53', '2024-12-07 16:58:53', NULL, NULL, NULL, 'belum_daftar'),
(23, 10, '43323210', 1, '2024-12-07 16:58:53', '2024-12-07 16:58:53', NULL, NULL, NULL, 'belum_daftar'),
(24, 11, '43323210', 1, '2024-12-07 17:05:05', '2024-12-07 17:05:05', NULL, NULL, NULL, 'belum_daftar'),
(25, 11, '43323210', 1, '2024-12-07 17:05:18', '2024-12-07 17:05:18', NULL, NULL, NULL, 'belum_daftar'),
(26, 11, '43323210', 1, '2024-12-07 17:05:18', '2024-12-07 17:05:18', NULL, NULL, NULL, 'belum_daftar'),
(27, 12, '43323210', 1, '2024-12-08 00:26:23', '2024-12-08 00:26:23', NULL, NULL, NULL, 'belum_daftar'),
(28, 12, '43323210', 1, '2024-12-08 00:26:50', '2024-12-08 00:26:50', NULL, NULL, NULL, 'belum_daftar'),
(29, 12, '43323210', 1, '2024-12-08 00:26:50', '2024-12-08 00:26:50', NULL, NULL, NULL, 'belum_daftar'),
(30, 13, '43323209', 1, '2024-12-08 07:02:32', '2024-12-08 07:02:32', NULL, NULL, NULL, 'belum_daftar'),
(31, 14, '43323210', 1, '2024-12-08 07:22:25', '2024-12-08 07:22:25', NULL, NULL, NULL, 'pending_tahap1'),
(32, 14, '43323210', 1, '2024-12-08 07:36:15', '2024-12-08 07:36:15', NULL, NULL, NULL, 'pending_tahap2'),
(33, 15, '43323210', 1, '2024-12-08 07:38:21', '2024-12-08 07:38:21', NULL, NULL, NULL, 'pending_tahap1'),
(34, 15, '43323210', 1, '2024-12-08 07:50:00', '2024-12-08 07:50:00', 'rowrr', NULL, NULL, 'acc_tahap1'),
(35, 15, '43323210', 1, '2024-12-08 07:50:00', '2024-12-08 07:50:00', 'rowrr', NULL, NULL, 'acc_tahap1'),
(36, 16, '43323210', 1, '2024-12-08 07:54:30', '2024-12-08 07:54:30', NULL, NULL, NULL, 'pending_tahap1'),
(37, 16, '43323210', 1, '2024-12-08 07:54:42', '2024-12-08 07:54:42', 'mantappp', NULL, NULL, 'acc_tahap1'),
(38, 16, '43323210', 1, '2024-12-08 07:54:42', '2024-12-08 07:54:42', 'mantappp', NULL, NULL, 'acc_tahap1'),
(39, 16, '43323210', 1, '2024-12-08 07:55:50', '2024-12-08 07:55:50', NULL, 'uhuyy', NULL, 'acc_tahap2'),
(40, 16, '43323210', 1, '2024-12-08 07:55:50', '2024-12-08 07:55:50', NULL, 'uhuyy', NULL, 'acc_tahap2'),
(41, 16, '43323210', 1, '2024-12-08 07:56:11', '2024-12-08 07:56:11', NULL, NULL, 'sip', 'acc_tahap3'),
(42, 16, '43323210', 1, '2024-12-08 07:56:11', '2024-12-08 07:56:11', NULL, NULL, 'sip', 'acc_tahap3'),
(43, 17, '43323210', 1, '2024-12-08 08:08:36', '2024-12-08 08:08:36', NULL, NULL, NULL, 'pending_tahap1'),
(44, 17, '43323210', 1, '2024-12-08 08:09:00', '2024-12-08 08:09:00', 'okee', NULL, NULL, 'acc_tahap1'),
(45, 17, '43323210', 1, '2024-12-08 08:09:00', '2024-12-08 08:09:00', 'okee', NULL, NULL, 'acc_tahap1'),
(46, 17, '43323210', 1, '2024-12-08 08:09:48', '2024-12-08 08:09:48', NULL, NULL, NULL, 'pending_tahap2'),
(47, 17, '43323210', 1, '2024-12-08 08:14:07', '2024-12-08 08:14:07', NULL, 'yayay', NULL, 'acc_tahap2'),
(48, 17, '43323210', 1, '2024-12-08 08:14:07', '2024-12-08 08:14:07', NULL, 'yayay', NULL, 'acc_tahap2'),
(49, 17, '43323210', 1, '2024-12-08 08:15:23', '2024-12-08 08:15:23', NULL, NULL, NULL, 'pending_tahap3'),
(50, 17, '43323210', 1, '2024-12-08 08:15:41', '2024-12-08 08:15:41', NULL, NULL, 'aafsfa', 'acc_tahap3'),
(51, 17, '43323210', 1, '2024-12-08 08:15:41', '2024-12-08 08:15:41', NULL, NULL, 'aafsfa', 'acc_tahap3'),
(52, 18, '43323210', 1, '2024-12-08 08:18:10', '2024-12-08 08:18:10', NULL, NULL, NULL, 'pending_tahap1'),
(53, 18, '43323210', 1, '2024-12-08 08:18:17', '2024-12-08 08:18:17', 'dasfasf', NULL, NULL, 'acc_tahap1'),
(54, 18, '43323210', 1, '2024-12-08 08:18:17', '2024-12-08 08:18:17', 'dasfasf', NULL, NULL, 'acc_tahap1'),
(55, 18, '43323210', 1, '2024-12-08 08:18:39', '2024-12-08 08:18:39', NULL, NULL, NULL, 'pending_tahap2'),
(56, 18, '43323210', 1, '2024-12-08 08:18:49', '2024-12-08 08:18:49', NULL, 'sdaf', NULL, 'acc_tahap2'),
(57, 18, '43323210', 1, '2024-12-08 08:18:49', '2024-12-08 08:18:49', NULL, 'sdaf', NULL, 'acc_tahap2'),
(58, 19, '43323210', 1, '2024-12-08 08:24:51', '2024-12-08 08:24:51', NULL, NULL, NULL, 'pending_tahap1'),
(59, 19, '43323210', 1, '2024-12-08 08:24:57', '2024-12-08 08:24:57', 'adgdsg', NULL, NULL, 'acc_tahap1'),
(60, 19, '43323210', 1, '2024-12-08 08:24:57', '2024-12-08 08:24:57', 'adgdsg', NULL, NULL, 'acc_tahap1'),
(61, 19, '43323210', 1, '2024-12-08 08:25:37', '2024-12-08 08:25:37', NULL, NULL, NULL, 'pending_tahap2'),
(62, 19, '43323210', 1, '2024-12-08 08:26:00', '2024-12-08 08:26:00', NULL, 'adsfasfas', NULL, 'acc_tahap2'),
(63, 19, '43323210', 1, '2024-12-08 08:26:00', '2024-12-08 08:26:00', NULL, 'adsfasfas', NULL, 'acc_tahap2'),
(64, 19, '43323210', 1, '2024-12-08 09:36:05', '2024-12-08 09:36:05', NULL, NULL, NULL, 'pending_tahap3'),
(65, 19, '43323210', 1, '2024-12-08 09:36:16', '2024-12-08 09:36:16', NULL, NULL, 'afasfas', 'acc_tahap3'),
(66, 19, '43323210', 1, '2024-12-08 09:36:16', '2024-12-08 09:36:16', NULL, NULL, 'afasfas', 'acc_tahap3'),
(67, 20, '43323209', 1, '2024-12-08 10:08:39', '2024-12-08 10:08:39', NULL, NULL, NULL, 'pending_tahap1'),
(68, 20, '43323209', 1, '2024-12-08 10:10:57', '2024-12-08 10:10:57', 'safsaf', NULL, NULL, 'acc_tahap1'),
(69, 20, '43323209', 1, '2024-12-08 10:10:57', '2024-12-08 10:10:57', 'safsaf', NULL, NULL, 'acc_tahap1'),
(70, 20, '43323209', 1, '2024-12-08 10:14:33', '2024-12-08 10:14:33', NULL, NULL, NULL, 'pending_tahap2'),
(71, 21, '43323209', 1, '2024-12-08 10:29:49', '2024-12-08 10:29:49', NULL, NULL, NULL, 'pending_tahap1'),
(72, 21, '43323209', 1, '2024-12-08 10:30:03', '2024-12-08 10:30:03', 'safas', NULL, NULL, 'acc_tahap1'),
(73, 21, '43323209', 1, '2024-12-08 10:30:03', '2024-12-08 10:30:03', 'safas', NULL, NULL, 'acc_tahap1'),
(74, 21, '43323209', 1, '2024-12-08 10:30:54', '2024-12-08 10:30:54', NULL, NULL, NULL, 'pending_tahap2'),
(75, 21, '43323209', 1, '2024-12-08 10:37:19', '2024-12-08 10:37:19', NULL, 'fasfas', NULL, 'acc_tahap2'),
(76, 21, '43323209', 1, '2024-12-08 10:37:19', '2024-12-08 10:37:19', NULL, 'fasfas', NULL, 'acc_tahap2'),
(77, 21, '43323209', 1, '2024-12-08 10:39:50', '2024-12-08 10:39:50', NULL, NULL, NULL, 'pending_tahap3'),
(78, 22, '43323209', 1, '2024-12-08 10:40:48', '2024-12-08 10:40:48', NULL, NULL, NULL, 'pending_tahap1'),
(79, 22, '43323209', 1, '2024-12-08 10:41:04', '2024-12-08 10:41:04', 'dasd', NULL, NULL, 'acc_tahap1'),
(80, 22, '43323209', 1, '2024-12-08 10:41:04', '2024-12-08 10:41:04', 'dasd', NULL, NULL, 'acc_tahap1'),
(81, 22, '43323209', 1, '2024-12-08 10:41:22', '2024-12-08 10:41:22', NULL, NULL, NULL, 'pending_tahap2'),
(82, 23, '43323209', 1, '2024-12-08 10:44:01', '2024-12-08 10:44:01', NULL, NULL, NULL, 'pending_tahap1'),
(83, 23, '43323209', 1, '2024-12-08 10:44:14', '2024-12-08 10:44:14', 'saddfsa', NULL, NULL, 'acc_tahap1'),
(84, 23, '43323209', 1, '2024-12-08 10:44:14', '2024-12-08 10:44:14', 'saddfsa', NULL, NULL, 'acc_tahap1'),
(85, 23, '43323209', 1, '2024-12-08 10:44:46', '2024-12-08 10:44:46', NULL, NULL, NULL, 'pending_tahap2'),
(86, 24, '43323209', 1, '2024-12-08 10:46:24', '2024-12-08 10:46:24', NULL, NULL, NULL, 'pending_tahap1'),
(87, 24, '43323209', 1, '2024-12-08 10:46:34', '2024-12-08 10:46:34', 'safas', NULL, NULL, 'acc_tahap1'),
(88, 24, '43323209', 1, '2024-12-08 10:46:34', '2024-12-08 10:46:34', 'safas', NULL, NULL, 'acc_tahap1'),
(89, 24, '43323209', 1, '2024-12-08 10:49:41', '2024-12-08 10:49:41', NULL, NULL, NULL, 'pending_tahap2'),
(90, 24, '43323209', 1, '2024-12-08 10:50:18', '2024-12-08 10:50:18', NULL, 'saffas', NULL, 'acc_tahap2'),
(91, 24, '43323209', 1, '2024-12-08 10:50:18', '2024-12-08 10:50:18', NULL, 'saffas', NULL, 'acc_tahap2'),
(92, 24, '43323209', 1, '2024-12-08 10:51:00', '2024-12-08 10:51:00', NULL, NULL, NULL, 'pending_tahap3'),
(93, 24, '43323209', 1, '2024-12-08 10:52:45', '2024-12-08 10:52:45', NULL, NULL, 'afdgd', 'acc_tahap3'),
(94, 24, '43323209', 1, '2024-12-08 10:52:45', '2024-12-08 10:52:45', NULL, NULL, 'afdgd', 'acc_tahap3'),
(95, 25, '43323209', 1, '2024-12-08 11:02:43', '2024-12-08 11:02:43', NULL, NULL, NULL, 'pending_tahap1'),
(96, 25, '43323209', 1, '2024-12-08 11:04:16', '2024-12-08 11:04:16', 'ewgtegw', NULL, NULL, 'acc_tahap1'),
(97, 25, '43323209', 1, '2024-12-08 11:04:16', '2024-12-08 11:04:16', 'ewgtegw', NULL, NULL, 'acc_tahap1'),
(98, 26, '43323209', 1, '2024-12-08 11:12:44', '2024-12-08 11:12:44', NULL, NULL, NULL, 'pending_tahap1'),
(99, 26, '43323209', 1, '2024-12-08 11:13:02', '2024-12-08 11:13:02', 'dasdasf', NULL, NULL, 'acc_tahap1'),
(100, 26, '43323209', 1, '2024-12-08 11:13:02', '2024-12-08 11:13:02', 'dasdasf', NULL, NULL, 'acc_tahap1'),
(101, 27, '43323209', 1, '2024-12-08 11:17:39', '2024-12-08 11:17:39', NULL, NULL, NULL, 'pending_tahap1'),
(102, 27, '43323209', 1, '2024-12-08 11:18:00', '2024-12-08 11:18:00', 'dsafasfas', NULL, NULL, 'acc_tahap1'),
(103, 27, '43323209', 1, '2024-12-08 11:18:00', '2024-12-08 11:18:00', 'dsafasfas', NULL, NULL, 'acc_tahap1'),
(104, 28, '43323209', 1, '2024-12-08 11:22:11', '2024-12-08 11:22:11', NULL, NULL, NULL, 'pending_tahap1'),
(105, 28, '43323209', 1, '2024-12-08 11:22:46', '2024-12-08 11:22:46', 'dsfasgasdsad', NULL, NULL, 'acc_tahap1'),
(106, 28, '43323209', 1, '2024-12-08 11:22:46', '2024-12-08 11:22:46', 'dsfasgasdsad', NULL, NULL, 'acc_tahap1'),
(107, 28, '43323209', 1, '2024-12-08 11:26:39', '2024-12-08 11:26:39', NULL, 'fWAGHJYTDKUG', NULL, 'acc_tahap2'),
(108, 28, '43323209', 1, '2024-12-08 11:26:40', '2024-12-08 11:26:40', NULL, 'fWAGHJYTDKUG', NULL, 'acc_tahap2'),
(109, 29, '43323209', 1, '2024-12-08 11:27:37', '2024-12-08 11:27:37', NULL, NULL, NULL, 'pending_tahap1'),
(110, 29, '43323209', 1, '2024-12-08 11:28:00', '2024-12-08 11:28:00', 'faEASDA', NULL, NULL, 'acc_tahap1'),
(111, 29, '43323209', 1, '2024-12-08 11:28:00', '2024-12-08 11:28:00', 'faEASDA', NULL, NULL, 'acc_tahap1'),
(112, 29, '43323209', 1, '2024-12-08 11:35:05', '2024-12-08 11:35:05', NULL, NULL, NULL, 'pending_tahap2'),
(113, 29, '43323209', 1, '2024-12-08 11:35:30', '2024-12-08 11:35:30', NULL, 'mantap uhuy', NULL, 'acc_tahap2'),
(114, 29, '43323209', 1, '2024-12-08 11:35:30', '2024-12-08 11:35:30', NULL, 'mantap uhuy', NULL, 'acc_tahap2'),
(115, 29, '43323209', 1, '2024-12-08 11:36:24', '2024-12-08 11:36:24', NULL, NULL, NULL, 'pending_tahap3'),
(116, 29, '43323209', 1, '2024-12-08 11:36:44', '2024-12-08 11:36:44', NULL, NULL, 'y', 'acc_tahap3'),
(117, 29, '43323209', 1, '2024-12-08 11:36:44', '2024-12-08 11:36:44', NULL, NULL, 'y', 'acc_tahap3'),
(118, 30, '43323210', 1, '2024-12-08 12:03:44', '2024-12-08 12:03:44', NULL, NULL, NULL, 'pending_tahap1'),
(119, 31, '43323210', 1, '2024-12-08 15:25:08', '2024-12-08 15:25:08', NULL, NULL, NULL, 'pending_tahap1'),
(120, 31, '43323210', 1, '2024-12-08 15:27:08', '2024-12-08 15:27:08', 'betul betul betul', NULL, NULL, 'acc_tahap1'),
(121, 31, '43323210', 1, '2024-12-08 15:27:08', '2024-12-08 15:27:08', 'betul betul betul', NULL, NULL, 'acc_tahap1'),
(122, 31, '43323210', 1, '2024-12-08 15:29:11', '2024-12-08 15:29:11', NULL, NULL, NULL, 'pending_tahap2'),
(123, 31, '43323210', 1, '2024-12-08 15:33:08', '2024-12-08 15:33:08', NULL, 'okey ditunggu ya', NULL, 'acc_tahap2'),
(124, 31, '43323210', 1, '2024-12-08 15:33:08', '2024-12-08 15:33:08', NULL, 'okey ditunggu ya', NULL, 'acc_tahap2'),
(125, 31, '43323210', 1, '2024-12-08 15:33:30', '2024-12-08 15:33:30', NULL, NULL, NULL, 'pending_tahap3'),
(126, 31, '43323210', 1, '2024-12-08 19:09:57', '2024-12-08 19:09:57', NULL, NULL, 'f', 'acc_tahap3'),
(127, 31, '43323210', 1, '2024-12-08 19:09:58', '2024-12-08 19:09:58', NULL, NULL, 'f', 'acc_tahap3'),
(128, 31, '43323210', 1, '2024-12-08 19:12:32', '2024-12-08 19:12:32', NULL, NULL, 'f', 'ditolak'),
(129, 31, '43323210', 1, '2024-12-08 19:12:32', '2024-12-08 19:12:32', NULL, NULL, 'f', 'ditolak'),
(130, 32, '43323210', 1, '2024-12-08 19:54:02', '2024-12-08 19:54:02', NULL, NULL, NULL, 'pending_tahap1'),
(131, 33, '43323210', 1, '2024-12-08 19:55:38', '2024-12-08 19:55:38', NULL, NULL, NULL, 'pending_tahap1'),
(132, 34, '43323210', 1, '2024-12-08 19:59:56', '2024-12-08 19:59:56', NULL, NULL, NULL, 'pending_tahap1'),
(133, 34, '43323210', 1, '2024-12-08 20:00:20', '2024-12-08 20:00:20', 'kucingg', NULL, NULL, 'acc_tahap1'),
(134, 34, '43323210', 1, '2024-12-08 20:00:20', '2024-12-08 20:00:20', 'kucingg', NULL, NULL, 'acc_tahap1'),
(135, 34, '43323210', 1, '2024-12-08 20:02:02', '2024-12-08 20:02:02', NULL, NULL, NULL, 'pending_tahap2'),
(136, 35, '43323210', 1, '2024-12-09 00:17:17', '2024-12-09 00:17:17', NULL, NULL, NULL, 'pending_tahap1'),
(137, 35, '43323210', 1, '2024-12-09 00:17:57', '2024-12-09 00:17:57', 'ya gapapa\n', NULL, NULL, 'acc_tahap1'),
(138, 35, '43323210', 1, '2024-12-09 00:17:57', '2024-12-09 00:17:57', 'ya gapapa\n', NULL, NULL, 'acc_tahap1'),
(139, 35, '43323210', 1, '2024-12-09 00:20:27', '2024-12-09 00:20:27', NULL, NULL, NULL, 'pending_tahap2'),
(140, 35, '43323210', 1, '2024-12-09 00:21:00', '2024-12-09 00:21:00', NULL, 'yayaya\n', NULL, 'acc_tahap2'),
(141, 35, '43323210', 1, '2024-12-09 00:21:00', '2024-12-09 00:21:00', NULL, 'yayaya\n', NULL, 'acc_tahap2'),
(142, 35, '43323210', 1, '2024-12-09 00:22:29', '2024-12-09 00:22:29', NULL, NULL, NULL, 'pending_tahap3'),
(143, 35, '43323210', 1, '2024-12-09 00:23:26', '2024-12-09 00:23:26', NULL, NULL, 'acc', 'acc_tahap3'),
(144, 35, '43323210', 1, '2024-12-09 00:23:26', '2024-12-09 00:23:26', NULL, NULL, 'acc', 'acc_tahap3'),
(145, 36, '43323210', 1, '2024-12-14 17:36:40', '2024-12-14 17:36:40', NULL, NULL, NULL, 'pending_tahap1'),
(146, 37, '43323210', 1, '2024-12-15 09:26:37', '2024-12-15 09:26:37', NULL, NULL, NULL, 'pending_tahap1'),
(147, 37, '43323210', 1, '2024-12-15 09:30:09', '2024-12-15 09:30:09', NULL, NULL, NULL, 'pending_tahap2'),
(148, 37, '43323210', 1, '2024-12-15 09:32:35', '2024-12-15 09:32:35', NULL, NULL, NULL, 'pending_tahap3'),
(149, 37, '43323210', 1, '2024-12-15 09:51:05', '2024-12-15 09:51:05', NULL, NULL, 'ugu', 'acc_tahap3'),
(150, 37, '43323210', 1, '2024-12-15 09:51:05', '2024-12-15 09:51:05', NULL, NULL, 'ugu', 'acc_tahap3'),
(151, 38, '43323210', 1, '2024-12-16 00:47:15', '2024-12-16 00:47:15', NULL, NULL, NULL, 'pending_tahap1'),
(152, 38, '43323210', 1, '2024-12-16 00:48:36', '2024-12-16 00:48:36', '', NULL, NULL, 'acc_tahap1'),
(153, 38, '43323210', 1, '2024-12-16 00:48:36', '2024-12-16 00:48:36', '', NULL, NULL, 'acc_tahap1'),
(154, 38, '43323210', 1, '2024-12-16 00:49:45', '2024-12-16 00:49:45', NULL, NULL, NULL, 'pending_tahap2'),
(155, 38, '43323210', 1, '2024-12-16 00:50:15', '2024-12-16 00:50:15', NULL, '', NULL, 'acc_tahap2'),
(156, 38, '43323210', 1, '2024-12-16 00:50:16', '2024-12-16 00:50:16', NULL, '', NULL, 'acc_tahap2'),
(157, 38, '43323210', 1, '2024-12-16 00:50:40', '2024-12-16 00:50:40', NULL, NULL, NULL, 'pending_tahap3'),
(158, 38, '43323210', 1, '2024-12-16 00:50:57', '2024-12-16 00:50:57', NULL, NULL, '', 'acc_tahap3'),
(159, 38, '43323210', 1, '2024-12-16 00:50:57', '2024-12-16 00:50:57', NULL, NULL, '', 'acc_tahap3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `hierarki` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `hierarki`) VALUES
(1, 'Presiden Mahasiswa', 1),
(2, 'Wakil Presiden Mahasiswa', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan_divisi`
--

CREATE TABLE `jabatan_divisi` (
  `id_jabatan_divisi` int NOT NULL,
  `id_divisi` int NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `hierarki` int NOT NULL COMMENT 'Level 1 adalah jabatan tertinggi dalam divisi',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipe_jabatan` enum('inti','divisi') NOT NULL DEFAULT 'divisi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jabatan_divisi`
--

INSERT INTO `jabatan_divisi` (`id_jabatan_divisi`, `id_divisi`, `nama_jabatan`, `hierarki`, `created_at`, `updated_at`, `tipe_jabatan`) VALUES
(9, 1, 'Ketua Umum', 1, '2024-11-30 18:53:57', '2024-11-30 18:53:57', 'inti'),
(10, 1, 'Wakil Ketua 1', 2, '2024-11-30 18:53:57', '2024-11-30 18:53:57', 'inti'),
(11, 1, 'Wakil Ketua 2', 2, '2024-11-30 18:53:57', '2024-11-30 18:53:57', 'inti'),
(12, 1, 'Sekretaris 1', 3, '2024-11-30 18:53:57', '2024-11-30 18:53:57', 'inti'),
(13, 1, 'Sekretaris 2', 3, '2024-11-30 18:53:57', '2024-11-30 18:53:57', 'inti'),
(14, 1, 'Bendahara 1', 3, '2024-11-30 18:53:57', '2024-11-30 18:53:57', 'inti'),
(15, 1, 'Bendahara 2', 3, '2024-11-30 18:53:57', '2024-11-30 18:53:57', 'inti'),
(16, 2, 'Ketua Divisi', 1, '2024-11-30 18:54:03', '2024-11-30 18:54:03', 'divisi'),
(20, 2, 'Anggota', 2, '2024-11-30 18:54:03', '2024-12-02 16:31:19', 'divisi'),
(21, 6, 'Ketua', 1, '2024-12-02 00:54:24', '2024-12-02 00:54:24', 'divisi'),
(22, 3, 'Ketua', 1, '2024-12-02 00:57:33', '2024-12-02 00:57:33', 'divisi'),
(23, 3, 'staf', 2, '2024-12-02 00:58:10', '2024-12-02 00:58:10', 'divisi'),
(24, 2, 'anggota lagi', 3, '2024-12-02 01:16:03', '2024-12-02 16:31:26', 'divisi'),
(25, 2, 'lagi lagi', 4, '2024-12-09 00:30:46', '2024-12-09 00:30:46', 'divisi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan_panitia`
--

CREATE TABLE `jabatan_panitia` (
  `id_jabatan_panitia` int NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `level` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jabatan_panitia`
--

INSERT INTO `jabatan_panitia` (`id_jabatan_panitia`, `nama_jabatan`, `level`) VALUES
(1, 'Ketua Pelaksana', 1),
(2, 'Wakil Ketua', 2),
(3, 'Sekretaris', 3),
(4, 'Bendahara', 3),
(5, 'Koordinator', 4),
(6, 'Anggota', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_dokumen`
--

CREATE TABLE `jenis_dokumen` (
  `id_jenis_dokumen` int NOT NULL,
  `nama_jenis` varchar(50) NOT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jenis_dokumen`
--

INSERT INTO `jenis_dokumen` (`id_jenis_dokumen`, `nama_jenis`, `deskripsi`) VALUES
(1, 'Surat Izin Orang Tua', 'Surat pernyataan izin dari orang tua'),
(2, 'Sertifikat WaRna', 'Sertifikat kegiatan WaRna/Pesima/LDK'),
(3, 'Sertifikat LKMM', 'Sertifikat LKMM Dasar/Pendas/LKMM Madya'),
(4, 'Scan KTM', 'Scan Kartu Tanda Mahasiswa'),
(5, 'Scan KHS', 'Scan Kartu Hasil Studi'),
(6, 'CV', 'Curriculum Vitae'),
(7, 'Motivation Letter', 'Surat Motivasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keanggotaan_ukm`
--

CREATE TABLE `keanggotaan_ukm` (
  `id_keanggotaan` int NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `id_ukm` int DEFAULT NULL,
  `status` enum('anggota','pengurus') DEFAULT 'anggota',
  `id_periode` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `keanggotaan_ukm`
--

INSERT INTO `keanggotaan_ukm` (`id_keanggotaan`, `nim`, `id_ukm`, `status`, `id_periode`) VALUES
(6, '43323223', 9, 'pengurus', 1),
(7, '43323223', 5, 'anggota', 2),
(9, '43323223', 1, 'pengurus', 1),
(11, '43323204', 1, 'pengurus', 1),
(12, '43323205', 1, 'pengurus', 1),
(13, '43323212', 1, 'pengurus', 1),
(14, '43323211', 1, 'pengurus', 1),
(17, '43323202', 1, 'pengurus', 1),
(18, '43323217', 1, 'pengurus', 1),
(20, '43323209', 1, 'pengurus', 1),
(22, '43323210', 1, 'anggota', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `id_program_studi` int DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `alamat` text,
  `no_whatsapp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama_lengkap`, `id_program_studi`, `kelas`, `jenis_kelamin`, `alamat`, `no_whatsapp`, `email`, `foto_path`) VALUES
('43323202', 'Adjie raditya', 1, 'TI-2C', 'Laki-laki', 'durian', '081238902714', 'adjai@gmail.com', NULL),
('43323204', 'Aldo Ramadani', 1, 'TI-2C', 'Laki-laki', 'Gunpad', '0812873', 'aldo@gmail.com', NULL),
('43323205', 'Ammar Luqman Arifin', 1, 'TI-2C', 'Perempuan', 'gunpad hbjbjb', '0988412943', 'ammaraja@gmail.com', 'profile_43323205_1734272709.jpg'),
('43323209', 'Callista Risky', 1, 'TI-2C', 'Perempuan', 'wonton sobo', '008214744312', 'cally@123.com', NULL),
('43323210', 'Faiz Akmal Nurhakim', 1, 'TI-2c', 'Laki-laki', 'Jatingaleh', '089526861572', 'faiz@gmail.com', 'profile_43323210_1734273948.jpg'),
('43323211', 'Dirga Prayitno', 1, 'TI-2C', 'Laki-laki', 'Jatingaleh', '018230918', 'prayitno@gmail.com', NULL),
('43323212', 'Fathurafi Nadio Busono', 1, 'TI-2C', 'Laki-laki', 'fury anjasmara', '08822148142', 'fathur@yahoo.com', NULL),
('43323217', 'Muhammad Za\'im', 1, 'TI-2C', 'Laki-laki', 'bukit sari', '08821324124', 'zaimgantenk@gmail.com', 'profile_43323217_1734276642.jpg'),
('43323223', 'Prabaswara Shafa Azarioma', 1, 'TI-2C', 'Laki-laki', 'Jl. Jatingaleh', '089526861571', 'azshafa@gmail.com', 'profile_43323223_1734277719.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `panitia_proker`
--

CREATE TABLE `panitia_proker` (
  `id_panitia` int NOT NULL,
  `id_timeline` int NOT NULL,
  `nim` varchar(20) NOT NULL,
  `id_jabatan_panitia` int NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `panitia_proker`
--

INSERT INTO `panitia_proker` (`id_panitia`, `id_timeline`, `nim`, `id_jabatan_panitia`, `tanggal_mulai`, `tanggal_selesai`) VALUES
(1, 1, '43323223', 1, '2024-10-01', '2024-12-31'),
(2, 2, '43323223', 1, '2024-10-02', '2024-10-10'),
(3, 1, '43323210', 2, NULL, NULL),
(4, 4, '43323210', 1, NULL, NULL),
(5, 10, '43323204', 1, NULL, NULL),
(6, 10, '43323223', 3, NULL, NULL),
(7, 11, '43323204', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran_ukm`
--

CREATE TABLE `pendaftaran_ukm` (
  `id_pendaftaran` int NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `id_ukm` int DEFAULT NULL,
  `tahap_seleksi` enum('tahap1','tahap2','tahap3') DEFAULT 'tahap1',
  `motivasi` text,
  `id_divisi_pilihan_1` int DEFAULT NULL,
  `id_divisi_pilihan_2` int DEFAULT NULL,
  `cv` text,
  `motivation_letter` text,
  `tanggal_pendaftaran` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_periode_pendaftaran` int DEFAULT NULL,
  `waktu_submit_tahap1` datetime DEFAULT NULL,
  `waktu_submit_tahap2` datetime DEFAULT NULL,
  `waktu_submit_tahap3` datetime DEFAULT NULL,
  `catatan_reject_tahap1` text,
  `catatan_reject_tahap2` text,
  `catatan_reject_tahap3` text,
  `status` enum('belum_daftar','pending_tahap1','acc_tahap1','pending_tahap2','acc_tahap2','pending_tahap3','acc_tahap3','ditolak','selesai') NOT NULL DEFAULT 'belum_daftar',
  `catatan_tahap1` text,
  `catatan_tahap2` text,
  `catatan_tahap3` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pendaftaran_ukm`
--

INSERT INTO `pendaftaran_ukm` (`id_pendaftaran`, `nim`, `id_ukm`, `tahap_seleksi`, `motivasi`, `id_divisi_pilihan_1`, `id_divisi_pilihan_2`, `cv`, `motivation_letter`, `tanggal_pendaftaran`, `tanggal_update`, `id_periode_pendaftaran`, `waktu_submit_tahap1`, `waktu_submit_tahap2`, `waktu_submit_tahap3`, `catatan_reject_tahap1`, `catatan_reject_tahap2`, `catatan_reject_tahap3`, `status`, `catatan_tahap1`, `catatan_tahap2`, `catatan_tahap3`) VALUES
(7, '43323223', 7, 'tahap1', 'pengen makan', NULL, NULL, NULL, NULL, '2024-11-25 01:50:13', '2024-11-25 01:51:46', 1, '2024-11-25 08:50:13', NULL, NULL, NULL, NULL, NULL, 'belum_daftar', NULL, NULL, NULL),
(38, '43323210', 1, 'tahap3', 'pengen pemes', 1, 2, NULL, NULL, '2024-12-16 00:47:15', '2024-12-16 00:50:57', 5, '2024-12-16 07:47:15', '2024-12-16 07:49:45', '2024-12-16 07:50:40', NULL, NULL, NULL, 'acc_tahap3', '', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `periode_kepengurusan`
--

CREATE TABLE `periode_kepengurusan` (
  `id_periode` int NOT NULL,
  `tahun_mulai` year NOT NULL,
  `tahun_selesai` year NOT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `periode_kepengurusan`
--

INSERT INTO `periode_kepengurusan` (`id_periode`, `tahun_mulai`, `tahun_selesai`, `status`) VALUES
(1, '2024', '2025', 'aktif'),
(2, '2023', '2024', 'tidak aktif'),
(3, '2025', '2026', 'tidak aktif'),
(4, '2026', '2027', 'tidak aktif'),
(5, '2027', '2028', 'tidak aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `periode_pendaftaran_ukm`
--

CREATE TABLE `periode_pendaftaran_ukm` (
  `id_periode_pendaftaran` int NOT NULL,
  `id_ukm` int NOT NULL,
  `tanggal_buka` datetime NOT NULL,
  `tanggal_tutup` datetime NOT NULL,
  `batas_waktu_tahap1` int DEFAULT NULL COMMENT 'dalam hari',
  `batas_waktu_tahap2` int DEFAULT NULL COMMENT 'dalam hari',
  `batas_waktu_tahap3` int DEFAULT NULL COMMENT 'dalam hari',
  `tahap1_end` datetime DEFAULT NULL,
  `tahap2_start` datetime DEFAULT NULL,
  `tahap2_end` datetime DEFAULT NULL,
  `tahap3_start` datetime DEFAULT NULL,
  `tahap3_end` datetime DEFAULT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT 'tidak aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `periode_pendaftaran_ukm`
--

INSERT INTO `periode_pendaftaran_ukm` (`id_periode_pendaftaran`, `id_ukm`, `tanggal_buka`, `tanggal_tutup`, `batas_waktu_tahap1`, `batas_waktu_tahap2`, `batas_waktu_tahap3`, `tahap1_end`, `tahap2_start`, `tahap2_end`, `tahap3_start`, `tahap3_end`, `status`, `created_at`) VALUES
(1, 7, '2024-11-23 15:53:50', '2024-11-30 22:53:50', 10, 6, 3, NULL, NULL, NULL, NULL, NULL, 'aktif', '2024-11-17 15:54:45'),
(5, 1, '2024-12-09 07:17:00', '2024-12-18 07:17:00', 3, 3, 3, '2024-12-12 07:17:00', '2024-12-12 07:17:00', '2024-12-15 07:17:00', '2024-12-15 07:17:00', '2024-12-18 07:17:00', 'aktif', '2024-12-09 00:16:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_studi`
--

CREATE TABLE `program_studi` (
  `id_program_studi` int NOT NULL,
  `id_fakultas` int DEFAULT NULL,
  `nama_program_studi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `program_studi`
--

INSERT INTO `program_studi` (`id_program_studi`, `id_fakultas`, `nama_program_studi`) VALUES
(1, 1, 'Teknologi Rekayasa Komputer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rapat`
--

CREATE TABLE `rapat` (
  `id_rapat` int NOT NULL,
  `id_timeline` int DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `notulensi_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `rapat`
--

INSERT INTO `rapat` (`id_rapat`, `id_timeline`, `judul`, `tanggal`, `notulensi_path`) VALUES
(13, 4, 'asa', '2004-11-20', 'shafa-67405c6cc3c55.pdf'),
(15, 1, 'rapat 1', '2024-11-26', 'shafa-6743c989d6453.pdf'),
(16, 10, 'rapat pleno', '2024-11-27', 'shafa-6743d61fc5267.pdf'),
(17, 10, 'rapat besar', '2024-12-15', 'shafa-675f7a2abee4a.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `struktur_organisasi_ukm`
--

CREATE TABLE `struktur_organisasi_ukm` (
  `id_struktur` int NOT NULL,
  `id_ukm` int DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `id_divisi` int DEFAULT NULL,
  `id_jabatan_divisi` int DEFAULT NULL,
  `id_periode` int DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `struktur_organisasi_ukm`
--

INSERT INTO `struktur_organisasi_ukm` (`id_struktur`, `id_ukm`, `nim`, `id_divisi`, `id_jabatan_divisi`, `id_periode`, `tanggal_mulai`, `tanggal_berakhir`, `foto_path`) VALUES
(17, 1, '43323223', 1, 9, 1, NULL, NULL, 'foto-43323223.png'),
(18, 1, '43323204', 1, 11, 1, NULL, NULL, 'foto-43323204.png'),
(26, 1, '43323209', 1, 10, 1, NULL, NULL, 'foto-43323209.jpeg'),
(27, 1, '43323202', 1, 14, 1, NULL, NULL, 'foto-43323202.png'),
(28, 1, '43323205', 2, 16, 1, NULL, NULL, 'foto-43323205.png'),
(29, 1, '43323211', 6, 21, 1, NULL, NULL, 'foto-43323211.png'),
(30, 1, '43323212', 2, 20, 1, NULL, NULL, 'foto-43323212.jpg'),
(31, 1, '43323217', 1, 12, 3, NULL, NULL, 'foto-43323217.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `timeline_ukm`
--

CREATE TABLE `timeline_ukm` (
  `id_timeline` int NOT NULL,
  `id_ukm` int DEFAULT NULL,
  `judul_kegiatan` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `tanggal_kegiatan` date DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `jenis` enum('proker','agenda') NOT NULL DEFAULT 'proker'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `timeline_ukm`
--

INSERT INTO `timeline_ukm` (`id_timeline`, `id_ukm`, `judul_kegiatan`, `deskripsi`, `tanggal_kegiatan`, `waktu_mulai`, `waktu_selesai`, `image_path`, `status`, `jenis`) VALUES
(1, 1, 'UKM FESTIVAL', '🎉 Polinesian siap-siap diserbu UKM Festival paling gokil se-jagad raya! 🎉  Eksplor puluhan UKM kece,  hunting jajanan kekinian,  nonton penampilan keren,  dan ikutan lomba berhadiah fantastis! Catat tanggalnya: [Tanggal],  di [Lokasi].  Ajak semua temen kamu buat ngerasain vibes festival yang asik dan bikin momen kampus makin berkesan! Info lengkap cek [Akun Media Sosial Panitia/BEM]. #UKMFestival #[Nama Kampus] #FestivalMeriah', '2024-10-01', '12:42:00', '21:48:00', 'event-672760987b1f3.jpeg', 'active', 'proker'),
(2, 2, 'PCC CLASS', '🔥 PCC Class: [Tema Kelas] 🔥  UKM Polytechnic Computer Club ngadain meeting class nih! Kali ini kita bakal bahas tuntas tentang \"[Tema Kelas]\"  dijamin seru dan bermanfaat banget buat nambah skill IT kamu. Catat tanggalnya: [Tanggal],  pukul [Waktu] di [Lokasi].  Meeting class ini terbuka untuk semua mahasiswa Polines,  buruan daftar di [Link Formulir Pendaftaran Online]! Info lengkap cek  [Akun Media Sosial UKM PCC].  #PCCPolines #PCCClass #[Tema Kelas]', '2024-10-01', '21:17:00', '00:17:00', 'event-67275fa56d17c.png', 'active', 'proker'),
(3, 1, 'OPEN REQUITMENT', '🔥 OPEN RECRUITMENT BEM 2024/2025 🔥 BEM Polines membuka kesempatan bagi mahasiswa/i untuk bergabung dan berkontribusi dalam periode 2024/2025! Kami mencari individu berintegritas, berjiwa kepemimpinan, dan berdedikasi tinggi untuk mengisi posisi di [Daftar Kementerian].  Daftarkan dirimu sekarang melalui [Link Formulir Pendaftaran Online] dan jadilah agen perubahan! Timeline pendaftaran: [Tanggal Awal] - [Tanggal Akhir]. Info lengkap hubungi [Contact Person/Akun Media Sosial BEM]. #BEM[Nama Kampus] #OpenRecruitment #AgentOfChange', '2024-11-03', '12:35:00', '18:36:00', 'event-67275f58c9054.png', 'active', 'proker'),
(4, 1, 'Roar Polines', '🎶 Polines Bersuara : Konser Amal untuk Charity 🎶\r\n\r\n[Tanggal] di [Lokasi],  kita ramaikan konser musik penuh solidaritas!  Nikmati penampilan spesial dari [Nama Artis/Band],  sambil berbagi kebaikan untuk [Nama Penerima Manfaat].  Harga tiket [Harga Tiket] sudah termasuk donasi.  Yuk,  hadir dan dukung acara ini!  Info dan pembelian tiket hubungi [Contact Person/Akun Media Sosial]. #[Hashtag Event] #KonserAmal #BerbagiKebaikan', '2023-06-13', '20:27:00', '00:27:00', 'event-6743ce2f3d5eb.jpg', 'active', 'agenda'),
(5, 11, 'Pengajian Akbar', 'Pengajian Akbar --- Bersama Ustadz Maulana, kita tingkatkan keimanan dan raih keberkahan.', '2022-10-17', '19:39:00', '23:39:00', 'event-67276f1a46042.jpg', 'inactive', 'proker'),
(6, 6, 'WaRNA ', 'kegiatan yang bertujuan agar mahasiswa baru mengenal jurusan, tenaga pendidik, dan sarana prasarana', '2023-07-06', '07:44:00', '18:44:00', 'event-6727702570e20.jpg', 'inactive', 'proker'),
(7, 6, 'Makrab Elektro Muda', '⚡️ Makrab Elektro Muda: Mendekatkan diri kepada sesama ⚡️\r\n\r\nSiap-siap buat gebrakan seru bareng Elektro Muda Polines! ⚡️⚡️  [Tanggal] di [Lokasi] kita bakal ngadain Makrab kece dengan tema \"[Tema Makrab]\".  Acara ini penuh games seru,  sharing inspiratif,  dan momen bonding yang bikin kamu makin solid bareng temen-temen seangkatan.  Jangan sampai ketinggalan,  buruan daftar di [link pendaftaran] dan rasakan keseruan Makrab Elektro Muda!  Info lengkap hubungi [Contact Person/Akun Media Sosial]. #ElektroMuda #Polines #MakrabSeru ⚡️', '2024-06-01', '09:58:00', '23:58:00', 'event-6727738172e5c.jpg', 'inactive', 'proker'),
(10, 1, 'Bakti Sosial', 'kegiatan bertujuan menjujunjung kebaikan dan kesejahteraan masyarakat bersama', '2024-11-27', '10:59:00', '14:00:00', 'event-6743cc2141f9c.jpg', 'active', 'proker'),
(11, 1, 'Desak Anies', 'mendesak desuk pemikiran dari calon presiden Anies Basudara Bersaudara. ', '2024-12-27', '21:05:00', '22:05:00', 'event-6745b99605bda.jpg', 'active', 'proker');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ukm`
--

CREATE TABLE `ukm` (
  `id_ukm` int NOT NULL,
  `nama_ukm` varchar(100) NOT NULL,
  `deskripsi` text,
  `visi` text,
  `misi` text,
  `tanggal_berdiri` date DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `banner_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `ukm`
--

INSERT INTO `ukm` (`id_ukm`, `nama_ukm`, `deskripsi`, `visi`, `misi`, `tanggal_berdiri`, `logo_path`, `banner_path`) VALUES
(1, 'BEM', 'Badan Eksekutif Mahasiswa', '“Mewujudkan KBM polines yang solid dan dinamis demi terciptanya sinergisitas mahasiswa”', '1. Meningkatkan keimanan dan ketakwaan kepada Tuhan yang Maha Esa\r\n    Memperkuat hubungan dengan Tuhan\r\n    Menjaga keseimbangan spiritual dan dunia\r\n    Meningkatkan kesadaran sosial dan lingkungan\r\n    Meningkatkan toleransi dan kerukunan antar umat beragama\r\n    Menjadikan keimanan dan ketakwaan sebagai landasan moral dan etika dalam hidup\r\n2. Meningkatkan keimanan dan ketakwaan kepada Tuhan yang Maha Esa\r\n    Memperkuat hubungan dengan Tuhan\r\n    Menjaga keseimbangan spiritual dan dunia\r\n    Meningkatkan kesadaran sosial dan lingkungan\r\n    Meningkatkan toleransi dan kerukunan antar umat beragama\r\n    Menjadikan keimanan dan ketakwaan sebagai landasan moral dan etika dalam hidup', '2001-01-02', 'logo-bem.png', 'cover-bem.png'),
(2, 'PCC', 'Politecnik Computer Club', '\r\n“Mewujudkan KBM polines yang solid dan dinamis demi terciptanya sinergisitas mahasiswa”', '1. Meningkatkan keimanan dan ketakwaan kepada Tuhan yang Maha Esa\r\n    Memperkuat hubungan dengan Tuhan\r\n    Menjaga keseimbangan spiritual dan dunia\r\n    Meningkatkan kesadaran sosial dan lingkungan\r\n    Meningkatkan toleransi dan kerukunan antar umat beragama\r\n    Menjadikan keimanan dan ketakwaan sebagai landasan moral dan etika dalam hidup\r\n2. Meningkatkan keimanan dan ketakwaan kepada Tuhan yang Maha Esa\r\n    Memperkuat hubungan dengan Tuhan\r\n    Menjaga keseimbangan spiritual dan dunia\r\n    Meningkatkan kesadaran sosial dan lingkungan\r\n    Meningkatkan toleransi dan kerukunan antar umat beragama\r\n    Menjadikan keimanan dan ketakwaan sebagai landasan moral dan etika dalam hidup', '2024-10-01', 'logo-pcc.png', 'cover-pcc.jpg'),
(5, 'PECC', 'Polytechnic English Conversation Club', 'Polytechnic English Conversation Club', 'Melaksanakan pendidikan tinggi vokasi yang unggul, berkarakter, dan beretika\r\nMengembangkan penelitian terapan dan pengabdian kepada masyarakat\r\nMeningkatkan kualitas manajemen institusi\r\nMenghasilkan sumber daya manusia yang profesional, berkarakter, dan beretika\r\nMengembangkan kerja sama dengan pemangku kepentingan ', '2005-11-23', 'logo-pecc.png', 'cover-pecc.jpg'),
(6, 'HME', 'Himpunan Mahasiswa Teknik Elektro', 'Menjadikan HME sebagai fasilitator mahasiswa elektro yang berkualitas dan berbudi pekerti luhur serta sebagai himpunan mahasiswa jurusan terbaik sepanjang masa.', 'Mengadakan kegiatan yang bersifat kompetitif, edukatif, inovatif di antara mahasiswa elektro.\r\nPelaksana aspirasi masyarakat elektro sesuai dengan fungsi HMJ.', '2016-11-02', 'logo-hme.png', 'cover-hme.png'),
(7, 'HMA', 'Himpunan Mahasiswa Akutansi', 'Meningkatkan kualitas Mahasiswa Jurusan Teknik Mesin yang berkarakter, berkompeten dan berjiwa sosial serta menjunjung tinggi nilai solidaritas.', 'Mengembangkan potensi Mahasiswa Jurusan Teknik Mesin melalui pelayanan dan program kerja himpunan.\r\nMeningkatkan peran aktif Mahasiswa Jurusan Teknik Mesin dalam berorganisasi, menyalurkan bakat dan kepedulian terhadap masyarakat.\r\nMenumbuhkan semangat kebersamaan Mahasiswa Jurusan Teknik Mesin.', '2005-11-22', 'logo-hma.png', 'cover-hma.png'),
(8, 'HMM', 'Himpunan Mahasiswa Teknik Mesin', 'Meningkatkan kualitas Mahasiswa Jurusan Teknik Mesin yang berkarakter, berkompeten dan berjiwa sosial serta menjunjung tinggi nilai solidaritas.', 'Mengembangkan potensi Mahasiswa Jurusan Teknik Mesin melalui pelayanan dan program kerja himpunan.\r\nMeningkatkan peran aktif Mahasiswa Jurusan Teknik Mesin dalam berorganisasi, menyalurkan bakat dan kepedulian terhadap masyarakat.\r\nMenumbuhkan semangat kebersamaan Mahasiswa Jurusan Teknik Mesin.', '2005-11-25', 'logo-hmm.png', 'cover-hmm.jpg'),
(9, 'WAPALHI', 'Wahana Pencinta Lingkungan Hidup', 'Mewujudkan pendidikan tinggi vokasi yang unggul, berkarakter, dan beretika', 'Melaksanakan pendidikan tinggi vokasi yang unggul, berkarakter, dan beretika', '1999-11-16', 'logo-wapalhi.png', 'cover-wapalhi.jpg'),
(10, 'HMAB', 'Himpunan Mahasiswa Administrasi Bisnis', 'Mewujudkan pendidikan tinggi vokasi yang unggul, berkarakter, dan beretika', 'Melaksanakan pendidikan tinggi vokasi yang unggul, berkarakter, dan beretika', '1999-10-08', 'logo-hmab.png', 'cover-hmab.png'),
(11, 'KSEI Jazirah', 'Kelompok Studi Ekonomi Islam Jazirah ', 'Menjadi wadah pengembangan potensi mahasiswa Muslim yang berakhlak mulia, berwawasan luas, dan berkontribusi positif bagi kampus dan masyarakat.', 'Membina Keimanan dan Ketakwaan: Meningkatkan pemahaman dan pengamalan nilai-nilai Islam dalam kehidupan sehari-hari melalui kajian keagamaan, pelatihan ibadah, dan kegiatan kerohanian lainnya.\r\nMengembangkan Potensi Diri: Menfasilitasi pengembangan potensi mahasiswa di berbagai bidang, seperti keilmuan, kepemimpinan, dan kreativitas, dengan berlandaskan nilai-nilai Islam.\r\nMempererat Ukhuwah Islamiyah: Membangun solidaritas dan kebersamaan antar anggota UKM dan mahasiswa Muslim di kampus, serta menjalin silaturahmi dengan lembaga dakwah lainnya.\r\nMengabdi kepada Masyarakat: Menyelenggarakan kegiatan pengabdian masyarakat yang bermanfaat dan berdampak positif, serta menumbuhkan jiwa sosial dan kepedulian terhadap sesama.\r\nMenjadi Teladan yang Baik: Menjadi contoh dan inspirasi bagi mahasiswa Muslim di kampus dalam mengamalkan nilai-nilai Islam dan berkontribusi positif bagi lingkungan sekitar.', '2010-11-11', 'logo-ksei-jazirah.png', 'cover-ksei-jazirah.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_login`
--

CREATE TABLE `user_login` (
  `id_login` int NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','admin_ukm','super_admin') DEFAULT 'mahasiswa',
  `nim_reference` varchar(20) DEFAULT NULL,
  `id_ukm` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user_login`
--

INSERT INTO `user_login` (`id_login`, `username`, `password`, `role`, `nim_reference`, `id_ukm`) VALUES
(2, '43323223', '123', 'mahasiswa', '43323223', NULL),
(3, '43323210', '321', 'mahasiswa', '43323210', NULL),
(4, '43323205', '123', 'mahasiswa', '43323205', NULL),
(7, 'admin', 'admin', 'super_admin', NULL, NULL),
(8, 'bem', 'bem', 'admin_ukm', NULL, 1),
(9, '43323212', '123', 'mahasiswa', NULL, NULL),
(10, '43323211', '123', 'mahasiswa', NULL, NULL),
(11, '43323204', '123', 'mahasiswa', NULL, NULL),
(12, 'pcc', 'pcc', 'admin_ukm', NULL, 2),
(13, 'hme', 'hme', 'admin_ukm', NULL, 6),
(14, 'jazirah', 'jazirah', 'admin_ukm', NULL, 11),
(15, '43323217', '123', 'mahasiswa', NULL, NULL),
(16, '43323202', '123', 'mahasiswa', NULL, NULL),
(17, '43323209', '123', 'mahasiswa', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `divisi_ukm`
--
ALTER TABLE `divisi_ukm`
  ADD PRIMARY KEY (`id_divisi`),
  ADD KEY `id_ukm` (`id_ukm`);

--
-- Indeks untuk tabel `dokumentasi_kegiatan`
--
ALTER TABLE `dokumentasi_kegiatan`
  ADD PRIMARY KEY (`id_dokumentasi`),
  ADD KEY `id_timeline` (`id_timeline`),
  ADD KEY `idx_dokumentasi_judul` (`judul`);

--
-- Indeks untuk tabel `dokumentasi_rapat`
--
ALTER TABLE `dokumentasi_rapat`
  ADD PRIMARY KEY (`id_dokumentasi`),
  ADD KEY `id_rapat` (`id_rapat`);

--
-- Indeks untuk tabel `dokumen_pendaftaran`
--
ALTER TABLE `dokumen_pendaftaran`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_pendaftaran` (`id_pendaftaran`),
  ADD KEY `id_jenis_dokumen` (`id_jenis_dokumen`),
  ADD KEY `idx_pendaftaran_jenis` (`id_pendaftaran`,`id_jenis_dokumen`);

--
-- Indeks untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id_fakultas`);

--
-- Indeks untuk tabel `history_pendaftaran`
--
ALTER TABLE `history_pendaftaran`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `id_pendaftaran` (`id_pendaftaran`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_ukm` (`id_ukm`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indeks untuk tabel `jabatan_divisi`
--
ALTER TABLE `jabatan_divisi`
  ADD PRIMARY KEY (`id_jabatan_divisi`),
  ADD UNIQUE KEY `unique_jabatan_hierarki_tipe` (`id_divisi`,`hierarki`,`nama_jabatan`,`tipe_jabatan`),
  ADD KEY `idx_jabatan_hierarki` (`hierarki`,`tipe_jabatan`);

--
-- Indeks untuk tabel `jabatan_panitia`
--
ALTER TABLE `jabatan_panitia`
  ADD PRIMARY KEY (`id_jabatan_panitia`);

--
-- Indeks untuk tabel `jenis_dokumen`
--
ALTER TABLE `jenis_dokumen`
  ADD PRIMARY KEY (`id_jenis_dokumen`);

--
-- Indeks untuk tabel `keanggotaan_ukm`
--
ALTER TABLE `keanggotaan_ukm`
  ADD PRIMARY KEY (`id_keanggotaan`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_periode` (`id_periode`),
  ADD KEY `idx_keanggotaan_ukm` (`id_ukm`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `idx_mahasiswa_program_studi` (`id_program_studi`);

--
-- Indeks untuk tabel `panitia_proker`
--
ALTER TABLE `panitia_proker`
  ADD PRIMARY KEY (`id_panitia`),
  ADD KEY `id_timeline` (`id_timeline`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_jabatan_panitia` (`id_jabatan_panitia`);

--
-- Indeks untuk tabel `pendaftaran_ukm`
--
ALTER TABLE `pendaftaran_ukm`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_divisi_pilihan_1` (`id_divisi_pilihan_1`),
  ADD KEY `id_divisi_pilihan_2` (`id_divisi_pilihan_2`),
  ADD KEY `idx_pendaftaran_ukm` (`id_ukm`),
  ADD KEY `fk_pendaftaran_periode` (`id_periode_pendaftaran`);

--
-- Indeks untuk tabel `periode_kepengurusan`
--
ALTER TABLE `periode_kepengurusan`
  ADD PRIMARY KEY (`id_periode`);

--
-- Indeks untuk tabel `periode_pendaftaran_ukm`
--
ALTER TABLE `periode_pendaftaran_ukm`
  ADD PRIMARY KEY (`id_periode_pendaftaran`),
  ADD KEY `fk_periode_pendaftaran_ukm` (`id_ukm`);

--
-- Indeks untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id_program_studi`),
  ADD KEY `id_fakultas` (`id_fakultas`);

--
-- Indeks untuk tabel `rapat`
--
ALTER TABLE `rapat`
  ADD PRIMARY KEY (`id_rapat`),
  ADD KEY `id_timeline` (`id_timeline`);

--
-- Indeks untuk tabel `struktur_organisasi_ukm`
--
ALTER TABLE `struktur_organisasi_ukm`
  ADD PRIMARY KEY (`id_struktur`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_periode` (`id_periode`),
  ADD KEY `idx_struktur_organisasi_ukm` (`id_ukm`),
  ADD KEY `id_jabatan_divisi` (`id_jabatan_divisi`),
  ADD KEY `id_divisi` (`id_divisi`);

--
-- Indeks untuk tabel `timeline_ukm`
--
ALTER TABLE `timeline_ukm`
  ADD PRIMARY KEY (`id_timeline`),
  ADD KEY `idx_timeline_ukm` (`id_ukm`),
  ADD KEY `idx_timeline_status` (`status`);

--
-- Indeks untuk tabel `ukm`
--
ALTER TABLE `ukm`
  ADD PRIMARY KEY (`id_ukm`);

--
-- Indeks untuk tabel `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id_login`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_nim_mahasiswa` (`nim_reference`),
  ADD KEY `fk_user_login_ukm` (`id_ukm`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `divisi_ukm`
--
ALTER TABLE `divisi_ukm`
  MODIFY `id_divisi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `dokumentasi_kegiatan`
--
ALTER TABLE `dokumentasi_kegiatan`
  MODIFY `id_dokumentasi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dokumentasi_rapat`
--
ALTER TABLE `dokumentasi_rapat`
  MODIFY `id_dokumentasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `dokumen_pendaftaran`
--
ALTER TABLE `dokumen_pendaftaran`
  MODIFY `id_dokumen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id_fakultas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `history_pendaftaran`
--
ALTER TABLE `history_pendaftaran`
  MODIFY `id_history` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jabatan_divisi`
--
ALTER TABLE `jabatan_divisi`
  MODIFY `id_jabatan_divisi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `jabatan_panitia`
--
ALTER TABLE `jabatan_panitia`
  MODIFY `id_jabatan_panitia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jenis_dokumen`
--
ALTER TABLE `jenis_dokumen`
  MODIFY `id_jenis_dokumen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `keanggotaan_ukm`
--
ALTER TABLE `keanggotaan_ukm`
  MODIFY `id_keanggotaan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `panitia_proker`
--
ALTER TABLE `panitia_proker`
  MODIFY `id_panitia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran_ukm`
--
ALTER TABLE `pendaftaran_ukm`
  MODIFY `id_pendaftaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `periode_kepengurusan`
--
ALTER TABLE `periode_kepengurusan`
  MODIFY `id_periode` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `periode_pendaftaran_ukm`
--
ALTER TABLE `periode_pendaftaran_ukm`
  MODIFY `id_periode_pendaftaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id_program_studi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `rapat`
--
ALTER TABLE `rapat`
  MODIFY `id_rapat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `struktur_organisasi_ukm`
--
ALTER TABLE `struktur_organisasi_ukm`
  MODIFY `id_struktur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `timeline_ukm`
--
ALTER TABLE `timeline_ukm`
  MODIFY `id_timeline` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `ukm`
--
ALTER TABLE `ukm`
  MODIFY `id_ukm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id_login` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `divisi_ukm`
--
ALTER TABLE `divisi_ukm`
  ADD CONSTRAINT `divisi_ukm_ibfk_1` FOREIGN KEY (`id_ukm`) REFERENCES `ukm` (`id_ukm`);

--
-- Ketidakleluasaan untuk tabel `dokumentasi_kegiatan`
--
ALTER TABLE `dokumentasi_kegiatan`
  ADD CONSTRAINT `dokumentasi_kegiatan_ibfk_1` FOREIGN KEY (`id_timeline`) REFERENCES `timeline_ukm` (`id_timeline`);

--
-- Ketidakleluasaan untuk tabel `dokumentasi_rapat`
--
ALTER TABLE `dokumentasi_rapat`
  ADD CONSTRAINT `dokumentasi_rapat_ibfk_1` FOREIGN KEY (`id_rapat`) REFERENCES `rapat` (`id_rapat`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
