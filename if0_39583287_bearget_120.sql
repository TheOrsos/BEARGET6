-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql306.infinityfree.com
-- Creato il: Set 08, 2025 alle 18:19
-- Versione del server: 11.4.7-MariaDB
-- Versione PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39583287_bearget`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `initial_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `accounts`
--

INSERT INTO `accounts` (`id`, `user_id`, `name`, `initial_balance`, `created_at`) VALUES
(7, 2, 'newda', '500.00', '2025-08-29 10:45:08'),
(17, 12, 'Conto Principale', '0.00', '2025-09-02 01:00:13'),
(20, 14, 'Conto Principale', '99999999.99', '2025-09-02 14:44:36'),
(21, 1, 'Revoluta', '4600.00', '2025-09-02 16:04:20'),
(22, 2, 'revolutttt', '600.00', '2025-09-02 17:39:43'),
(23, 1, 'Contanti', '300.00', '2025-09-02 23:30:29'),
(24, 1, 'intesa', '166.00', '2025-09-05 23:30:46'),
(25, 15, 'Conto Principale', '0.00', '2025-09-08 02:01:46'),
(26, 1, 'Uoso', '5.00', '2025-09-08 13:09:09'),
(27, 16, 'Conto Principale', '0.00', '2025-09-08 19:38:39');

-- --------------------------------------------------------

--
-- Struttura della tabella `app_settings`
--

CREATE TABLE `app_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `app_settings`
--

INSERT INTO `app_settings` (`setting_key`, `setting_value`, `updated_at`) VALUES
('maintenance_mode', 'off', '2025-09-08 11:40:22'),
('maintenance_message', '', '2025-09-08 10:40:12');

-- --------------------------------------------------------

--
-- Struttura della tabella `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashed_validator` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `auth_tokens`
--

INSERT INTO `auth_tokens` (`id`, `selector`, `hashed_validator`, `user_id`, `expires`) VALUES
(20, '66d7edd0fe0a697e3022532c', '$2y$10$UOBnHkcoR1MFQBnkcBSDk.So6yOkkgZUvfpaxgkyIknRwU3pI6L9O', 2, '2025-09-26 09:37:52'),
(17, 'b7e16d0cd88f18497bb9bf0b', '$2y$10$wSSNRrFm5Q7UjupoFB5lWutU4P/lcdoPJTrHrpWeh3ntr7NrHFuVa', 2, '2025-09-17 11:57:27'),
(3, 'a4882e13e80976bb995640b6', '$2y$10$mEUHmNJUp9WQSpKcl6oW3.lODGSb8BJpxFfBW0y3NFYOZ0OH0l.2q', 1, '2025-09-16 08:38:19'),
(5, 'af8bb1156d1b7414143d0886', '$2y$10$6t7K0PkAMQMur71ZHEdAu.1S0eBe/zVbwfch.Atp0hREli9nz8iei', 1, '2025-09-16 08:51:21'),
(6, 'aac21a4a87c6ee70ab7d1883', '$2y$10$LFByeC.6Xq3K8LZrhiKj2eqiC0C2yd7B1ii8onY2T4F.Le6PcEIlO', 1, '2025-09-16 08:51:58'),
(7, 'd94122891990acc2bf86d1cc', '$2y$10$XhfUSJ270Cxlsuuy/Yah6.nZ8R3WazGSthm1VgoGmq4tf3J4nb/cu', 1, '2025-09-16 09:02:16'),
(8, '4250352f3d7b4d779421e406', '$2y$10$HSKAaymAx6rSghvJYktb7eteQJVt/Nx93ojoy/OezqaZKRPBsgEeS', 1, '2025-09-16 09:02:30'),
(9, '54f57230755f3e4bd3db5149', '$2y$10$e5EIDppjwERxrFbKlJ7T6.5Jl135p7SQvNu3UNKaD7mBD44K/U0Ju', 1, '2025-09-16 09:05:45'),
(10, 'd279e1de9206048b4b81109a', '$2y$10$9M90kf4pSo5MXWrpS7IAMel0fPKjfFt6tJ22GkwDyEk.hVoqTYxDu', 1, '2025-09-16 09:20:58'),
(11, '23ab401171a8c526c49fb3f5', '$2y$10$rkaWRTWecu4LGJ9lDN2Q9ugToG4MGmznScerbGgkmXw047ApdrsN.', 1, '2025-09-16 09:37:41'),
(25, 'ef0eaed38fdaccf26c6b906b', '$2y$10$Pp697ZTphuv6kmFEbnh8mOTPrH1DK26IQr5pXQr4JwlpGlMFTxlOa', 14, '2025-10-02 10:34:21'),
(39, 'e10e72050917e57e00cb5b1e', '$2y$10$ejUfeA8EB8Q5QOvcI9yun.iwe30rrfja1jZK4k5wpFFo2PuxoJLLy', 16, '2025-10-08 21:39:33'),
(28, '7d008f56f97e2970065df482', '$2y$10$cyeHUuWGk8Cd0CmoaexyZ.nsAhIeeiXp8CE78DEFoDKscoQ6fPhVO', 1, '2025-10-04 17:10:08'),
(38, '54c1ef0da85ce087088d0447', '$2y$10$fB3qLPhnVChqdxo49EzJp.cuBFr3hOVinlGvS9hzFtV/YzVctSs7G', 1, '2025-10-08 21:38:32'),
(40, '2e98de96854fabc71aaaea19', '$2y$10$.RxtfUpcpmorlZQmwuQdi.aGYoC5xpBkYP6fTVrAWt5Cd0myUuevW', 16, '2025-10-08 21:40:35');

-- --------------------------------------------------------

--
-- Struttura della tabella `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `budgets`
--

INSERT INTO `budgets` (`id`, `user_id`, `category_id`, `amount`, `start_date`, `created_at`) VALUES
(4, 2, 16, '48.00', NULL, '2025-08-29 00:39:49'),
(7, 1, 3, '507.00', NULL, '2025-09-08 13:13:23');

-- --------------------------------------------------------

--
-- Struttura della tabella `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'expense',
  `icon` varchar(10) DEFAULT NULL,
  `category_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `type`, `icon`, `category_order`) VALUES
(1, 1, 'Stipendio', 'income', '💼', 13),
(2, 1, 'Altre Entrate', 'income', '💰', 14),
(3, 1, 'Spesa', 'expense', '🛒', 1),
(4, 1, 'Trasporti', 'expense', '⛽️', 11),
(5, 1, 'Casa', 'expense', '🏠', 9),
(6, 1, 'Bollettes', 'expense', '🧾', 8),
(7, 1, 'Svago', 'expense', '🎉', 5),
(8, 1, 'Ristoranti', 'expense', '🍔', 7),
(9, 1, 'Salute', 'expense', '❤️‍🩹', 6),
(11, 1, 'Risparmi', 'expense', '💾', 3),
(12, 1, 'Fondi Comuni', 'expense', '👥', 2),
(13, 1, 'Trasferimento', 'expense', '🔄', 10),
(14, 2, 'Stipendio', 'income', '💼', 13),
(15, 2, 'Altre Entrate', 'income', '💰', 14),
(16, 2, 'Spesa', 'expense', '🛒', 1),
(17, 2, 'Trasporti', 'expense', '⛽️', 11),
(18, 2, 'Casa', 'expense', '🏠', 10),
(19, 2, 'Bollette', 'expense', '🧾', 9),
(20, 2, 'Svago', 'expense', '🎉', 8),
(21, 2, 'Ristoranti', 'expense', '🍔', 7),
(22, 2, 'Salute', 'expense', '❤️‍🩹', 5),
(23, 2, 'Regali', 'expense', '🎁', 6),
(24, 2, 'Risparmi', 'expense', '💾', 4),
(25, 2, 'Fondi Comuni', 'expense', '👥', 3),
(26, 2, 'Trasferimento', 'expense', '🔄', 2),
(27, 2, 'Regolamento Fondo', 'expense', '⚖️', 12),
(56, 2, 'Regolamento Fondo', 'income', '⚖️', 15),
(175, 12, 'Stipendio', 'income', '💼', 0),
(176, 12, 'Altre Entrate', 'income', '💰', 0),
(177, 12, 'Spesa', 'expense', '🛒', 0),
(178, 12, 'Trasporti', 'expense', '⛽️', 0),
(179, 12, 'Casa', 'expense', '🏠', 0),
(180, 12, 'Bollette', 'expense', '🧾', 0),
(181, 12, 'Svago', 'expense', '🎉', 0),
(182, 12, 'Ristoranti', 'expense', '🍔', 0),
(183, 12, 'Salute', 'expense', '❤️‍🩹', 0),
(184, 12, 'Regali', 'expense', '🎁', 0),
(185, 12, 'Risparmi', 'expense', '💾', 0),
(186, 12, 'Fondi Comuni', 'expense', '👥', 0),
(187, 12, 'Trasferimento', 'expense', '🔄', 0),
(201, 14, 'Stipendio', 'income', '💼', 12),
(202, 14, 'Altre Entrate', 'income', '💰', 13),
(203, 14, 'Spesa', 'expense', '🛒', 1),
(204, 14, 'Trasporti', 'expense', '⛽️', 10),
(205, 14, 'Casa', 'expense', '🏠', 9),
(206, 14, 'Bollette', 'expense', '🧾', 8),
(207, 14, 'Svago', 'expense', '🎉', 7),
(208, 14, 'Ristoranti', 'expense', '🍔', 6),
(209, 14, 'Salute', 'expense', '❤️‍🩹', 5),
(210, 14, 'Regali', 'expense', '🎁', 4),
(211, 14, 'Risparmi', 'expense', '💾', 3),
(212, 14, 'Fondi Comuni', 'expense', '👥', 2),
(213, 14, 'Trasferimento', 'expense', '🔄', 11),
(214, 1, 'siumme', 'expense', '', 12),
(215, 1, 'Risparmio: Moto', 'expense', 'piggy-bank', 0),
(216, 15, 'Stipendio', 'income', '💼', 0),
(217, 15, 'Altre Entrate', 'income', '💰', 0),
(218, 15, 'Spesa', 'expense', '🛒', 0),
(219, 15, 'Trasporti', 'expense', '⛽️', 0),
(220, 15, 'Casa', 'expense', '🏠', 0),
(221, 15, 'Bollette', 'expense', '🧾', 0),
(222, 15, 'Svago', 'expense', '🎉', 0),
(223, 15, 'Ristoranti', 'expense', '🍔', 0),
(224, 15, 'Salute', 'expense', '❤️‍🩹', 0),
(225, 15, 'Regali', 'expense', '🎁', 0),
(226, 15, 'Risparmi', 'expense', '💾', 0),
(227, 15, 'Fondi Comuni', 'expense', '👥', 0),
(228, 15, 'Trasferimento', 'expense', '🔄', 0),
(229, 16, 'Stipendio', 'income', '💼', 12),
(230, 16, 'Altre Entrate', 'income', '💰', 13),
(231, 16, 'Spesa', 'expense', '🛒', 1),
(232, 16, 'Trasporti', 'expense', '⛽️', 10),
(233, 16, 'Casa', 'expense', '🏠', 9),
(234, 16, 'Bollette', 'expense', '🧾', 8),
(235, 16, 'Svago', 'expense', '🎉', 7),
(236, 16, 'Ristoranti', 'expense', '🍔', 5),
(237, 16, 'Salute', 'expense', '❤️‍🩹', 6),
(238, 16, 'Regali', 'expense', '🎁', 4),
(239, 16, 'Risparmi', 'expense', '💾', 3),
(240, 16, 'Fondi Comuni', 'expense', '👥', 2),
(241, 16, 'Trasferimento', 'expense', '🔄', 11);

-- --------------------------------------------------------

--
-- Struttura della tabella `changelog_updates`
--

CREATE TABLE `changelog_updates` (
  `id` int(11) NOT NULL,
  `version` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `email_sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `changelog_updates`
--

INSERT INTO `changelog_updates` (`id`, `version`, `title`, `description`, `image_url`, `content`, `is_published`, `email_sent`, `created_at`) VALUES
(3, 'BETA', 'Versione di prova', 'questa è una descrizione di prova', '', '<p><strong>Questo &egrave;</strong> <em>un testo</em> di <span style=\"text-decoration: underline;\">prova.</span></p>\n<p><span style=\"text-decoration: underline;\">La versione attuale &egrave; la beta, di prova&nbsp;</span></p>', 1, 1, '2025-09-03 15:24:56');

-- --------------------------------------------------------

--
-- Struttura della tabella `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`) VALUES
(12, 1, 14, 'gaaaaaaayyyy', 1, '2025-09-02 14:42:22'),
(13, 14, 1, 'oooooooooooo', 1, '2025-09-02 14:42:42'),
(14, 1, 14, '00', 1, '2025-09-08 13:16:16'),
(28, 1, 2, 'asda', 1, '2025-09-08 19:14:18'),
(29, 1, 16, 'sono bellissimo', 1, '2025-09-08 19:47:54'),
(30, 16, 1, 'SEI FIGHISSIMO ALTRO CHE', 1, '2025-09-08 19:52:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `expense_splits`
--

CREATE TABLE `expense_splits` (
  `id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount_owed` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `expense_splits`
--

INSERT INTO `expense_splits` (`id`, `expense_id`, `user_id`, `amount_owed`) VALUES
(3, 2, 1, '90.00'),
(4, 2, 2, '90.00'),
(5, 3, 1, '54.11'),
(6, 3, 2, '54.11'),
(7, 4, 1, '10.00'),
(8, 4, 2, '20.00'),
(9, 5, 1, '5.00'),
(10, 5, 2, '25.00'),
(11, 6, 1, '172.50'),
(12, 6, 2, '172.50'),
(25, 13, 1, '20.00'),
(26, 13, 2, '20.00'),
(29, 15, 1, '4.00'),
(30, 15, 2, '4.00'),
(31, 16, 1, '2.00'),
(32, 16, 2, '2.00'),
(33, 17, 1, '1.50'),
(34, 17, 2, '1.50'),
(35, 18, 1, '8888.00'),
(36, 19, 1, '10.00'),
(37, 19, 2, '10.00'),
(38, 19, 14, '10.00');

-- --------------------------------------------------------

--
-- Struttura della tabella `friendships`
--

CREATE TABLE `friendships` (
  `id` int(11) NOT NULL,
  `user_id_1` int(11) NOT NULL COMMENT 'ID of the user who sent the request',
  `user_id_2` int(11) NOT NULL COMMENT 'ID of the user who received the request',
  `status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `friendships`
--

INSERT INTO `friendships` (`id`, `user_id_1`, `user_id_2`, `status`, `created_at`, `updated_at`) VALUES
(2, 14, 1, 'accepted', '2025-09-02 14:41:47', '2025-09-02 14:41:55'),
(3, 1, 2, 'accepted', '2025-09-08 18:51:30', '2025-09-08 18:51:39'),
(4, 1, 16, 'accepted', '2025-09-08 19:46:35', '2025-09-08 19:46:59');

-- --------------------------------------------------------

--
-- Struttura della tabella `fund_level_expenses`
--

CREATE TABLE `fund_level_expenses` (
  `id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `recorded_by_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `group_expenses`
--

CREATE TABLE `group_expenses` (
  `id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL,
  `paid_by_user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `note_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `group_expenses`
--

INSERT INTO `group_expenses` (`id`, `fund_id`, `paid_by_user_id`, `description`, `amount`, `expense_date`, `created_at`, `category_id`, `note_id`) VALUES
(2, 3, 2, 'Pranzo 1', '180.00', '2025-08-14', '2025-08-15 02:36:52', 16, NULL),
(3, 4, 2, 'SPESA GIORNO 1', '108.22', '2025-08-14', '2025-08-15 03:07:56', 16, NULL),
(4, 7, 2, 'SPESA GIORNO 2', '30.00', '2025-08-15', '2025-08-15 04:05:09', NULL, NULL),
(5, 7, 2, 't5', '30.00', '2025-08-15', '2025-08-15 04:06:11', NULL, NULL),
(6, 8, 2, '34', '345.00', '2025-08-15', '2025-08-15 04:28:26', NULL, NULL),
(13, 9, 2, 'prova', '40.00', '2025-08-15', '2025-08-15 06:57:19', NULL, NULL),
(15, 9, 2, 'c', '8.00', '2025-08-18', '2025-08-18 16:35:00', NULL, NULL),
(16, 9, 2, 'g', '4.00', '2025-08-18', '2025-08-18 17:13:27', NULL, NULL),
(17, 9, 2, 'fg', '3.00', '2025-08-18', '2025-08-18 17:26:58', NULL, NULL),
(18, 10, 1, 'Cena', '8888.00', '2025-09-02', '2025-09-02 14:41:05', NULL, NULL),
(19, 10, 2, 'bb', '30.00', '2025-09-02', '2025-09-02 17:39:51', NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `loan_requests`
--

CREATE TABLE `loan_requests` (
  `id` int(11) NOT NULL,
  `requester_id` int(11) NOT NULL,
  `lender_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','accepted','rejected','paid_back') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL,
  `requester_account_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `loan_requests`
--

INSERT INTO `loan_requests` (`id`, `requester_id`, `lender_id`, `amount`, `status`, `created_at`, `resolved_at`, `requester_account_id`) VALUES
(1, 1, 2, '20.00', 'accepted', '2025-09-08 21:29:18', '2025-09-08 21:30:36', 21),
(2, 1, 2, '90.00', 'accepted', '2025-09-08 22:08:30', '2025-09-08 22:08:45', 23);

-- --------------------------------------------------------

--
-- Struttura della tabella `money_transfers`
--

CREATE TABLE `money_transfers` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `from_account_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `money_transfers`
--

INSERT INTO `money_transfers` (`id`, `sender_id`, `receiver_id`, `from_account_id`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 7, '10.00', 'accepted', '2025-09-01 17:57:07', '2025-09-01 18:07:25'),
(3, 2, 1, 7, '20.00', 'declined', '2025-09-01 18:07:48', '2025-09-01 18:44:04'),
(4, 2, 1, 7, '4.00', 'declined', '2025-09-01 18:42:07', '2025-09-01 18:44:05'),
(6, 2, 1, 7, '1.00', 'accepted', '2025-09-01 18:57:47', '2025-09-01 18:59:38'),
(7, 1, 2, 21, '9.00', 'accepted', '2025-09-08 13:16:26', '2025-09-08 13:56:47'),
(8, 2, 1, 7, '9.00', 'declined', '2025-09-08 15:09:31', '2025-09-08 21:30:54'),
(9, 1, 2, 21, '33.00', 'declined', '2025-09-08 19:14:23', '2025-09-08 21:30:16'),
(10, 1, 14, 21, '4.00', 'pending', '2025-09-08 19:18:48', '2025-09-08 19:18:48'),
(11, 1, 2, 21, '8.00', 'declined', '2025-09-08 20:28:37', '2025-09-08 21:30:15');

-- --------------------------------------------------------

--
-- Struttura della tabella `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `todolist_content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'personal' COMMENT 'e.g., personal, group_expense'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `creator_id`, `title`, `content`, `todolist_content`, `created_at`, `updated_at`, `transaction_id`, `type`) VALUES
(3, 2, 2, 'Nota per spesa di gruppo', 'Medicine', NULL, '2025-08-14 04:27:57', '2025-08-14 04:27:57', NULL, 'personal'),
(8, 2, 2, 'funzionassss', 'dfdsf', '[{\"task\":\"eee\",\"completed\":true}]', '2025-08-17 03:14:48', '2025-08-17 03:15:04', NULL, 'personal'),
(13, 2, 2, 'Nota per contribution #7', 'cx', NULL, '2025-08-17 21:14:34', '2025-08-17 21:14:34', NULL, 'personal'),
(16, 2, 2, 'Nuova Nota', '', '[]', '2025-08-20 21:50:49', '2025-08-20 21:50:49', NULL, 'personal'),
(17, 2, 2, 'Nuova Nota', '', '[]', '2025-08-25 20:02:48', '2025-08-25 20:02:48', NULL, 'personal'),
(18, 2, 2, 'Nuova Nota', '', '[]', '2025-08-25 20:02:49', '2025-08-25 20:02:49', NULL, 'personal'),
(19, 2, 2, 'Nuova Nota', '', '[]', '2025-08-25 20:02:50', '2025-08-25 20:02:50', NULL, 'personal'),
(20, 2, 2, 'Nuova Nota', '', '[]', '2025-08-25 20:02:51', '2025-08-25 20:02:51', NULL, 'personal'),
(21, 2, 2, 'Nuova Nota', '', '[]', '2025-08-26 12:32:31', '2025-08-26 12:32:31', NULL, 'personal'),
(22, 2, 2, 'Nuova Nota', '', '[]', '2025-08-26 12:32:32', '2025-08-26 12:32:32', NULL, 'personal'),
(24, 2, 2, 'Nuova Nota', '', '[]', '2025-08-26 17:41:11', '2025-08-26 17:41:11', NULL, 'personal'),
(25, 2, 2, 'Nuova Nota', 'Ciao, vorrei aggiungere nuovi temi e stili personalizzati al mio progetto:\r\n\r\nrendere la scrollbar invisibile nella sidebar.php perché è fastidiosa e brutta.\r\ncreare un tema dark dorato elegante compreso il cambio di font che è disponibile solo per la versione PRO del mio programma (e non quella free, in quel caso mostrare il lucchetto se si dispone della versione gratuita).\r\ncreare un tema super moderno dark, con colori gradienti, elementi trasparenti e blur, e colore speciale viola/lilla.', '[]', '2025-09-01 00:14:37', '2025-09-01 00:14:40', NULL, 'personal'),
(27, 1, 1, 'Problemi da sistemare', '', '[{\"task\":\"Fondi comuni (conto delle persone)\",\"completed\":true},{\"task\":\"Esportazione file bancari\",\"completed\":true},{\"task\":\"filtro etichetta/categoria\",\"completed\":true},{\"task\":\"select delle etichette non presente nei moviemnti\",\"completed\":true},{\"task\":\"amici come select nelle cose condifvise\",\"completed\":true},{\"task\":\"specificare 30gg cancellazione\",\"completed\":false},{\"task\":\"Email per sospensione e riattivazione\",\"completed\":false}]', '2025-09-02 15:12:18', '2025-09-05 12:47:05', NULL, 'personal');

-- --------------------------------------------------------

--
-- Struttura della tabella `note_shares`
--

CREATE TABLE `note_shares` (
  `id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission` varchar(10) NOT NULL DEFAULT 'edit' COMMENT 'Può essere ''view'' o ''edit''',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `message`, `related_id`, `is_read`, `created_at`) VALUES
(1, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'Jesolo\'.', 1, 1, '2025-08-14 03:20:57'),
(2, 2, 'fund_invite', 'Christian Orso ti ha invitato a partecipare al fondo \'Tokyo\'.', 2, 1, '2025-08-14 05:24:06'),
(3, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'Jesolo 2\'.', 3, 1, '2025-08-15 02:35:43'),
(4, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'Jesolo 3\'.', 4, 1, '2025-08-15 03:03:19'),
(5, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'Jesolo 3\'.', 4, 1, '2025-08-15 03:04:01'),
(6, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'Jesolo 4\'.', 5, 1, '2025-08-15 03:31:25'),
(7, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'Jesolo 5\'.', 6, 1, '2025-08-15 03:33:06'),
(8, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'jesolo 6\'.', 7, 1, '2025-08-15 03:52:28'),
(9, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'Jesolo 7\'.', 8, 1, '2025-08-15 04:27:51'),
(10, 1, 'fund_invite', '6brtb ti ha invitato a partecipare al fondo \'Jesolo 8\'.', 9, 1, '2025-08-15 06:23:14'),
(11, 2, 'friend_request', 'You have received a friend request from Christian Orso.', 1, 1, '2025-08-19 14:01:31'),
(12, 1, 'friend_request_accepted', '6brtb has accepted your friend request.', 1, 1, '2025-08-19 14:01:54'),
(13, 1, 'money_transfer_request', '6brtb wants to send you €80,00.', 1, 1, '2025-08-19 14:02:33'),
(14, 2, 'money_transfer_accepted', 'Christian Orso accepted your transfer of €80,00.', 1, 1, '2025-08-19 14:02:45'),
(15, 2, 'budget_exceeded', 'Hai superato il budget per la categoria \'Spesa\' questo mese!', 4, 1, '2025-08-29 00:39:53'),
(16, 1, 'money_transfer_request', '6brtb wants to send you €10,00.', 2, 1, '2025-09-01 17:57:07'),
(17, 2, 'money_transfer_accepted', 'Christian Orso accepted your transfer of €10,00.', 2, 1, '2025-09-01 18:07:25'),
(18, 1, 'money_transfer_request', '6brtb wants to send you €20,00.', 3, 1, '2025-09-01 18:07:48'),
(19, 1, 'money_transfer_request', '6brtb wants to send you €4,00.', 4, 1, '2025-09-01 18:42:07'),
(20, 2, 'money_transfer_declined', 'Christian Orso declined your money transfer.', 3, 1, '2025-09-01 18:44:04'),
(21, 2, 'money_transfer_declined', 'Christian Orso declined your money transfer.', 4, 1, '2025-09-01 18:44:05'),
(22, 2, 'money_transfer_request', 'Christian Orso wants to send you €3,00.', 5, 1, '2025-09-01 18:44:39'),
(23, 1, 'money_transfer_accepted', '6brtb accepted your transfer of €3,00.', 5, 1, '2025-09-01 18:44:54'),
(24, 1, 'money_transfer_request', '6brtb wants to send you €1,00.', 6, 1, '2025-09-01 18:57:47'),
(25, 2, 'money_transfer_accepted', 'Christian Orso accepted your transfer of €1,00.', 6, 1, '2025-09-01 18:59:38'),
(26, 1, 'fund_invite', 'dennis.parolin ti ha invitato a partecipare al fondo \'Vacanza Sardegna\'.', 10, 1, '2025-09-02 14:40:10'),
(27, 1, 'friend_request', 'You have received a friend request from dennis.parolin.', 2, 1, '2025-09-02 14:41:47'),
(28, 14, 'friend_request_accepted', 'Christian Orso has accepted your friend request.', 2, 1, '2025-09-02 14:41:55'),
(29, 2, 'fund_invite', 'Christian Orso ti ha invitato a partecipare al fondo \'Vacanza Sardegna\'.', 10, 1, '2025-09-02 17:22:04'),
(31, 1, 'budget_exceeded', 'Hai superato il budget per la categoria \'Risparmio: Moto\' questo mese!', 6, 1, '2025-09-06 23:10:02'),
(32, 14, 'fund_invite', 'Christian Orso ti ha invitato a partecipare al fondo \'Vacanza Sardegna\'.', 10, 1, '2025-09-07 21:41:38'),
(33, 2, 'money_transfer_request', 'Christian Orso wants to send you €9,00.', 7, 1, '2025-09-08 13:16:26'),
(34, 2, 'fund_invite', 'Christian Orso ti ha invitato a partecipare al fondo \'abbandono\'.', 11, 1, '2025-09-08 13:56:35'),
(35, 1, 'money_transfer_accepted', '6brtb accepted your transfer of €9,00.', 7, 1, '2025-09-08 13:56:47'),
(36, 1, 'money_transfer_request', '6brtb wants to send you €9,00.', 8, 1, '2025-09-08 15:09:31'),
(37, 1, 'chat_message', 'Hai un nuovo messaggio da 6brtb.', 2, 1, '2025-09-08 16:13:24'),
(38, 2, 'friend_request', 'You have received a friend request from Christian Orso.', 3, 1, '2025-09-08 18:51:30'),
(39, 1, 'friend_request_accepted', '6brtb has accepted your friend request.', 3, 1, '2025-09-08 18:51:39'),
(40, 2, 'chat_message', 'Hai un nuovo messaggio da Christian Orso.', 1, 1, '2025-09-08 19:14:18'),
(41, 2, 'money_transfer_request', 'Christian Orso wants to send you €33,00.', 9, 1, '2025-09-08 19:14:23'),
(42, 14, 'money_transfer_request', 'Christian Orso wants to send you €4,00.', 10, 0, '2025-09-08 19:18:48'),
(43, 16, 'friend_request', 'You have received a friend request from Christian Orso.', 4, 1, '2025-09-08 19:46:35'),
(44, 1, 'friend_request_accepted', '(⁠｡⁠•́⁠︿⁠•̀⁠｡⁠) has accepted your friend request.', 4, 1, '2025-09-08 19:46:59'),
(45, 16, 'chat_message', 'Hai un nuovo messaggio da Christian Orso.', 1, 1, '2025-09-08 19:47:54'),
(46, 1, 'chat_message', 'Hai un nuovo messaggio da (⁠｡⁠•́⁠︿⁠•̀⁠｡⁠).', 16, 1, '2025-09-08 19:52:00'),
(47, 2, 'money_transfer_request', 'Christian Orso wants to send you €8,00.', 11, 1, '2025-09-08 20:28:37'),
(48, 2, 'loan_request', 'Christian Orso ti ha chiesto un prestito di €20,00.', 1, 1, '2025-09-08 21:29:18'),
(49, 1, 'money_transfer_declined', '6brtb declined your money transfer.', 11, 1, '2025-09-08 21:30:15'),
(50, 1, 'money_transfer_declined', '6brtb declined your money transfer.', 9, 1, '2025-09-08 21:30:16'),
(51, 1, 'loan_accepted', '6brtb ha accettato la tua richiesta di prestito di €20,00', 1, 1, '2025-09-08 21:30:36'),
(52, 2, 'money_transfer_declined', 'Christian Orso declined your money transfer.', 8, 1, '2025-09-08 21:30:54'),
(53, 2, 'loan_request', 'Christian Orso ti ha chiesto un prestito di €90,00.', 2, 1, '2025-09-08 22:08:30'),
(54, 1, 'loan_accepted', '6brtb ha accettato la tua richiesta di prestito di €90,00', 2, 1, '2025-09-08 22:08:45');

-- --------------------------------------------------------

--
-- Struttura della tabella `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'christian.orso.oc@gmail.com', 'd62be52bd213ac94f3cae34eb398da99b03200254a3350161cfca17bb91e9e74', '2025-09-01 16:24:01', '2025-09-01 19:24:01'),
(5, 'koreankpop04@gmail.com', '45a58134b20b0bd4c53e68e04a5d3bffbae3aaa44f3899147b6cd97afac6952b', '2025-09-02 01:21:30', '2025-09-02 04:21:30'),
(6, 'koreankpop04@gmail.com', '6b69fec60befbed16b96b2a74985ced0d3e79829d251d21c6ef3f00183fbc087', '2025-09-02 10:31:32', '2025-09-02 13:31:32'),
(7, 'microhardtolax4@gmail.com', '376b371c60714ba0a93ad19bfea61c12b7a0012e46a8e44452ef3cdb4d5f3e04', '2025-09-02 10:31:40', '2025-09-02 13:31:40'),
(8, 'dennis.par20@gmail.com', '17a9f125000e8aedb7f60ebbcf2f3ce9f87c97534078f9549ccc38ffacd547e0', '2025-09-02 11:54:48', '2025-09-02 14:54:48'),
(9, 'microhardtolax3@gmail.com', 'acd7e210e34a7eb87aa6241a484d17cb496f049757a2e64b17d10afd08951db5', '2025-09-05 06:55:36', '2025-09-05 09:55:36'),
(10, 'microhardtolax3@gmail.com', '4616c5ecb3afb2d4bd5c40013d8cd449d8a2405105e17d8995cc4e6e940ccf92', '2025-09-08 05:06:10', '2025-09-08 02:06:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `recurring_transactions`
--

CREATE TABLE `recurring_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(10) NOT NULL,
  `category_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `frequency` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `next_due_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `recurring_transactions`
--

INSERT INTO `recurring_transactions` (`id`, `user_id`, `description`, `amount`, `type`, `category_id`, `account_id`, `frequency`, `start_date`, `next_due_date`, `created_at`) VALUES
(3, 14, 'OnlyFans per Orso', '20.00', 'expense', 207, 20, 'monthly', '2025-09-02', '2025-10-02', '2025-09-02 14:49:45'),
(4, 1, 'Amazon Primeo', '2.48', 'expense', 7, 24, 'bimonthly', '2025-09-08', '2025-11-08', '2025-09-08 13:14:38');

-- --------------------------------------------------------

--
-- Struttura della tabella `saving_goals`
--

CREATE TABLE `saving_goals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `current_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `target_date` date DEFAULT NULL,
  `monthly_contribution` decimal(10,2) NOT NULL DEFAULT 0.00,
  `linked_category_id` int(11) DEFAULT NULL,
  `created_by_planner` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `saving_goals`
--

INSERT INTO `saving_goals` (`id`, `user_id`, `name`, `target_amount`, `current_amount`, `target_date`, `monthly_contribution`, `linked_category_id`, `created_by_planner`, `created_at`) VALUES
(1, 2, 'prova obia', '3000.00', '611.00', NULL, '0.00', NULL, 0, '2025-08-16 17:15:12'),
(3, 2, 'Prova logica obb', '444.00', '0.00', NULL, '0.00', NULL, 0, '2025-08-29 00:21:09'),
(4, 1, 'Moto', '4000.00', '150.00', '2027-09-17', '160.00', 215, 1, '2025-09-06 22:23:50');

-- --------------------------------------------------------

--
-- Struttura della tabella `settlement_payments`
--

CREATE TABLE `settlement_payments` (
  `id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `from_account_id` int(11) DEFAULT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT 'e.g., pending, payer_confirmed, completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payer_confirmed_at` timestamp NULL DEFAULT NULL,
  `payee_confirmed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `settlement_payments`
--

INSERT INTO `settlement_payments` (`id`, `fund_id`, `from_user_id`, `to_user_id`, `amount`, `from_account_id`, `to_account_id`, `status`, `created_at`, `payer_confirmed_at`, `payee_confirmed_at`) VALUES
(2, 4, 1, 2, '54.11', NULL, NULL, 'pending', '2025-08-15 03:09:03', NULL, NULL),
(3, 4, 2, 2, '408.22', NULL, NULL, 'pending', '2025-08-15 03:09:03', NULL, NULL),
(4, 7, 1, 2, '15.00', NULL, NULL, 'pending', '2025-08-15 04:07:18', NULL, NULL),
(5, 7, 2, 2, '130.00', NULL, NULL, 'pending', '2025-08-15 04:07:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `shared_funds`
--

CREATE TABLE `shared_funds` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `target_amount` decimal(10,2) DEFAULT NULL,
  `creator_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'active' COMMENT 'e.g., active, settling, archived'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `shared_funds`
--

INSERT INTO `shared_funds` (`id`, `name`, `description`, `target_amount`, `creator_id`, `created_at`, `status`) VALUES
(1, 'Jesolo', NULL, '300.00', 2, '2025-08-14 03:20:06', 'archived'),
(3, 'Jesolo 2', NULL, '500.00', 2, '2025-08-15 02:32:39', 'archived'),
(4, 'Jesolo 3', NULL, '500.00', 2, '2025-08-15 03:03:03', 'settling_auto'),
(5, 'Jesolo 4', NULL, '600.00', 2, '2025-08-15 03:29:04', 'archived'),
(6, 'Jesolo 5', NULL, '6.00', 2, '2025-08-15 03:32:20', 'settling'),
(7, 'jesolo 6', NULL, '567.00', 2, '2025-08-15 03:52:19', 'settling_auto'),
(8, 'Jesolo 7', NULL, '500.00', 2, '2025-08-15 04:25:05', 'archived'),
(9, 'Jesolo 8', NULL, '333.00', 2, '2025-08-15 04:59:46', 'active'),
(10, 'Vacanza Sardegna', NULL, '1.00', 14, '2025-09-02 14:39:34', 'active'),
(13, 'Risparmio generale', NULL, '32.00', 1, '2025-09-08 14:26:43', 'settling'),
(14, 'Revolut', NULL, '6.00', 1, '2025-09-08 14:32:17', 'active');

-- --------------------------------------------------------

--
-- Struttura della tabella `shared_fund_contributions`
--

CREATE TABLE `shared_fund_contributions` (
  `id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `contribution_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_id` int(11) DEFAULT NULL,
  `note_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `shared_fund_contributions`
--

INSERT INTO `shared_fund_contributions` (`id`, `fund_id`, `user_id`, `amount`, `contribution_date`, `created_at`, `transaction_id`, `note_id`) VALUES
(1, 1, 2, '100.00', '2025-08-14', '2025-08-14 04:27:05', NULL, NULL),
(2, 1, 2, '-10.00', '2025-08-14', '2025-08-14 04:27:57', NULL, NULL),
(3, 3, 2, '200.00', '2025-08-14', '2025-08-15 02:38:06', NULL, NULL),
(4, 4, 2, '300.00', '2025-08-14', '2025-08-15 03:04:52', NULL, NULL),
(5, 7, 2, '100.00', '2025-08-14', '2025-08-15 03:53:39', NULL, NULL),
(8, 9, 2, '200.00', '2025-08-18', '2025-08-18 17:27:07', NULL, NULL),
(9, 10, 14, '1.10', '2025-09-02', '2025-09-02 14:45:28', NULL, NULL),
(10, 10, 1, '10.00', '2025-09-07', '2025-09-07 21:41:25', 81, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `shared_fund_members`
--

CREATE TABLE `shared_fund_members` (
  `id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `shared_fund_members`
--

INSERT INTO `shared_fund_members` (`id`, `fund_id`, `user_id`, `joined_at`) VALUES
(1, 1, 2, '2025-08-14 03:20:06'),
(2, 1, 1, '2025-08-14 03:21:05'),
(5, 3, 2, '2025-08-15 02:32:39'),
(6, 3, 1, '2025-08-15 02:35:54'),
(7, 4, 2, '2025-08-15 03:03:03'),
(8, 4, 1, '2025-08-15 03:03:31'),
(9, 5, 2, '2025-08-15 03:29:04'),
(10, 6, 2, '2025-08-15 03:32:20'),
(11, 6, 1, '2025-08-15 03:33:15'),
(12, 5, 1, '2025-08-15 03:34:16'),
(13, 7, 2, '2025-08-15 03:52:19'),
(14, 7, 1, '2025-08-15 03:52:38'),
(15, 8, 2, '2025-08-15 04:25:05'),
(17, 9, 2, '2025-08-15 04:59:46'),
(18, 9, 1, '2025-08-15 06:23:19'),
(19, 10, 14, '2025-09-02 14:39:34'),
(20, 10, 1, '2025-09-02 14:40:21'),
(21, 10, 2, '2025-09-02 17:24:49'),
(25, 13, 1, '2025-09-08 14:26:43'),
(26, 14, 1, '2025-09-08 14:32:17');

-- --------------------------------------------------------

--
-- Struttura della tabella `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tags`
--

INSERT INTO `tags` (`id`, `user_id`, `name`) VALUES
(8, 1, 'newda'),
(4, 1, 'vacanzes'),
(2, 14, 'Cazzi'),
(1, 14, 'Vacanza');

-- --------------------------------------------------------

--
-- Struttura della tabella `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `transfer_group_id` varchar(36) DEFAULT NULL,
  `invoice_path` varchar(255) DEFAULT NULL,
  `goal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `account_id`, `category_id`, `amount`, `type`, `description`, `transaction_date`, `created_at`, `transfer_group_id`, `invoice_path`, `goal_id`) VALUES
(59, 2, 7, 20, '-2.00', 'expense', 'Amazon Prime', '2025-09-01', '2025-09-01 15:14:17', NULL, NULL, NULL),
(60, 2, 7, NULL, '-10.00', 'expense', 'Money sent to Christian Orso', '2025-09-01', '2025-09-01 18:07:25', NULL, NULL, NULL),
(63, 2, 7, NULL, '3.00', 'income', 'Money received from Christian Orso', '2025-09-01', '2025-09-01 18:44:54', NULL, NULL, NULL),
(64, 2, 7, NULL, '-1.00', 'expense', 'Money sent to Christian Orso', '2025-09-01', '2025-09-01 18:59:38', NULL, NULL, NULL),
(69, 14, 20, 207, '-20.00', 'expense', 'OnlyFans per Orso', '2025-09-02', '2025-09-02 14:49:58', NULL, NULL, NULL),
(72, 2, 22, NULL, '-30.00', 'expense', 'Spesa di gruppo \'Vacanza Sardegna\': bb', '2025-09-02', '2025-09-02 17:39:51', NULL, NULL, NULL),
(76, 1, 23, 12, '-66.00', 'expense', 'Kebab', '2025-09-02', '2025-09-03 00:36:08', NULL, NULL, NULL),
(77, 1, 23, 3, '-54.00', 'expense', 'Kebab', '2025-09-02', '2025-09-03 00:36:08', NULL, NULL, NULL),
(78, 1, 21, 12, '-66.00', 'expense', 'Kebab', '2025-09-02', '2025-09-03 00:36:08', NULL, NULL, NULL),
(79, 1, 21, 3, '-54.00', 'expense', 'Kebab', '2025-09-02', '2025-09-03 00:36:08', NULL, NULL, NULL),
(80, 1, 21, 12, '-160.00', 'expense', 'Contributo a: Moto', '2025-09-07', '2025-09-06 22:24:52', NULL, NULL, 4),
(81, 1, 21, 12, '-10.00', 'expense', 'Contributo a fondo: Vacanza Sardegna', '2025-09-07', '2025-09-07 21:41:25', NULL, NULL, NULL),
(82, 1, 21, 3, '-111.00', 'expense', 'weee', '2025-09-08', '2025-09-08 13:07:45', NULL, NULL, NULL),
(83, 1, 24, 7, '-2.48', 'expense', 'Amazon Primeo', '2025-09-08', '2025-09-08 13:29:15', NULL, NULL, NULL),
(84, 1, 21, NULL, '-9.00', 'expense', 'Money sent to 6brtb', '2025-09-08', '2025-09-08 13:56:47', NULL, NULL, NULL),
(85, 2, 7, NULL, '9.00', 'income', 'Money received from Christian Orso', '2025-09-08', '2025-09-08 13:56:47', NULL, NULL, NULL),
(86, 2, 22, NULL, '-20.00', 'expense', 'Prestito concesso a Christian Orso', '2025-09-08', '2025-09-08 21:30:36', NULL, NULL, NULL),
(87, 1, 21, NULL, '20.00', 'income', 'Prestito ricevuto da 6brtb', '2025-09-08', '2025-09-08 21:30:36', NULL, NULL, NULL),
(88, 2, 7, NULL, '-90.00', 'expense', 'Prestito concesso a Christian Orso', '2025-09-08', '2025-09-08 22:08:45', NULL, NULL, NULL),
(89, 1, 23, NULL, '90.00', 'income', 'Prestito ricevuto da 6brtb', '2025-09-08', '2025-09-08 22:08:45', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `transaction_tags`
--

CREATE TABLE `transaction_tags` (
  `transaction_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `receives_emails` tinyint(1) NOT NULL DEFAULT 1,
  `profile_picture_path` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `theme` varchar(25) NOT NULL DEFAULT 'dark-indigo',
  `subscription_status` varchar(20) NOT NULL DEFAULT 'free',
  `account_status` varchar(20) NOT NULL DEFAULT 'active',
  `suspended_until` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `stripe_customer_id` varchar(255) DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `subscription_end_date` timestamp NULL DEFAULT NULL,
  `subscription_start_date` timestamp NULL DEFAULT NULL,
  `friend_code` varchar(8) DEFAULT NULL,
  `desktop_nav_enabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `receives_emails`, `profile_picture_path`, `password`, `verification_token`, `is_verified`, `created_at`, `theme`, `subscription_status`, `account_status`, `suspended_until`, `last_login_at`, `stripe_customer_id`, `stripe_subscription_id`, `subscription_end_date`, `subscription_start_date`, `friend_code`, `desktop_nav_enabled`) VALUES
(1, 'Christian Orso', 'christian.orso.oc@gmail.com', 1, 'uploads/avatars/user_1_1755778306.jpg', '$2y$10$yc0CsaVkdYXTn5Hwcp6G.O.nqsonmDXNnOtHphRprkd71ZZgreQ2q', NULL, 1, '2025-08-12 14:26:56', 'violet-night', 'lifetime', 'active', NULL, '2025-09-08 10:32:19', NULL, NULL, NULL, NULL, 'TZ5CGYD8', 0),
(2, '6brtb', 'microhardtolax3@gmail.com', 1, 'uploads/avatars/user_2_1755725464.jpeg', '$2y$10$m.Q0cq9v.qSU7zyGDKTZO.mSdC01E/VQjZ1VbTLeoktAD6xHqnZ1q', NULL, 1, '2025-08-12 14:43:18', 'lunar-rays', 'active', 'active', NULL, '2025-09-08 03:32:31', 'cus_T14w9ZcpAJDfFs', 'sub_1S52n2GvLwuAyACzhjgDSpfL', '2025-09-08 19:59:19', '2025-09-08 19:59:14', 'FQNTR9XO', 1),
(12, 'comp1', 'microhardtolax4@gmail.com', 0, NULL, '$2y$10$NL5ymjxppPKygk/42prcwOUZUVEs9TiHH3DQsXVBn8EGAzzVvSrW2', NULL, 1, '2025-09-02 01:00:13', 'dark-indigo', 'lifetime', 'active', NULL, '2025-09-01 20:52:12', NULL, NULL, NULL, NULL, '6G1VTO2K', 0),
(14, 'dennis.parolin', 'dennis.par20@gmail.com', 0, NULL, '$2y$10$4KKSE1QW3uxHeo5VLAnnzeKCntvvlbYJa1f6JgzxpxSuwF6gVCN5C', NULL, 1, '2025-09-02 14:32:43', 'crimson-white', 'lifetime', 'active', NULL, '2025-09-02 07:34:21', 'cus_Syt6EuCvTnrQjP', 'sub_1S2vKJGvLwuAyACzUFnrRjBj', '2025-09-02 17:36:53', '2025-09-02 17:36:49', 'BVOA1LH3', 0),
(15, 'prova2', 'si@gmail.com', 1, NULL, '$2y$10$hr9UofJ0snKY5qP2R3E4.Oh0wS989J0Bz2QAXiROGXxxObUMxULQ2', 'f306f8c82dc2cb3c0118139273140e3926332f392caf6691f712092250f60492', 0, '2025-09-08 02:01:46', 'dark-indigo', 'free', 'active', NULL, NULL, NULL, NULL, NULL, NULL, 'NHB9Q5K7', 0),
(16, '(⁠｡⁠•́⁠︿⁠•̀⁠｡⁠)', 'champa.aly@gmail.com', 1, 'uploads/avatars/user_16_1757360575.jpg', '$2y$10$urObMvHJKv8ewxGYM3VJEuzuLe/UX54Pj2FOTFPRcWXI0BwrJYr76', NULL, 1, '2025-09-08 19:38:39', 'violet-night', 'lifetime', 'active', NULL, '2025-09-08 12:40:35', NULL, NULL, NULL, NULL, '7I4LRBEF', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `user_blocks`
--

CREATE TABLE `user_blocks` (
  `id` int(11) NOT NULL,
  `blocker_id` int(11) NOT NULL,
  `blocked_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indici per le tabelle `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_auth_tokens_user_id` (`user_id`),
  ADD KEY `idx_selector` (`selector`);

--
-- Indici per le tabelle `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_category_unique` (`user_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indici per le tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `changelog_updates`
--
ALTER TABLE `changelog_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sender_receiver` (`sender_id`,`receiver_id`),
  ADD KEY `idx_receiver_sender` (`receiver_id`,`sender_id`);

--
-- Indici per le tabelle `expense_splits`
--
ALTER TABLE `expense_splits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_user_unique` (`expense_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_friendship` (`user_id_1`,`user_id_2`),
  ADD KEY `user_id_1` (`user_id_1`),
  ADD KEY `user_id_2` (`user_id_2`);

--
-- Indici per le tabelle `fund_level_expenses`
--
ALTER TABLE `fund_level_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_id` (`fund_id`),
  ADD KEY `fk_fund_level_expenses_category` (`category_id`),
  ADD KEY `fk_fund_level_expenses_user` (`recorded_by_user_id`);

--
-- Indici per le tabelle `group_expenses`
--
ALTER TABLE `group_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_id` (`fund_id`),
  ADD KEY `paid_by_user_id` (`paid_by_user_id`),
  ADD KEY `fk_group_expenses_category_restart` (`category_id`),
  ADD KEY `fk_group_expenses_note_restart` (`note_id`);

--
-- Indici per le tabelle `loan_requests`
--
ALTER TABLE `loan_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requester_id` (`requester_id`),
  ADD KEY `lender_id` (`lender_id`);

--
-- Indici per le tabelle `money_transfers`
--
ALTER TABLE `money_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `from_account_id` (`from_account_id`);

--
-- Indici per le tabelle `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_note_transaction` (`transaction_id`),
  ADD KEY `fk_notes_creator` (`creator_id`);

--
-- Indici per le tabelle `note_shares`
--
ALTER TABLE `note_shares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_share` (`note_id`,`user_id`),
  ADD KEY `fk_note_shares_user` (`user_id`);

--
-- Indici per le tabelle `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indici per le tabelle `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indici per le tabelle `saving_goals`
--
ALTER TABLE `saving_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_saving_goals_linked_category` (`linked_category_id`);

--
-- Indici per le tabelle `settlement_payments`
--
ALTER TABLE `settlement_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_id` (`fund_id`),
  ADD KEY `fk_settlement_from_user_restart` (`from_user_id`),
  ADD KEY `fk_settlement_to_user_restart` (`to_user_id`),
  ADD KEY `fk_settlement_from_account` (`from_account_id`),
  ADD KEY `fk_settlement_to_account` (`to_account_id`);

--
-- Indici per le tabelle `shared_funds`
--
ALTER TABLE `shared_funds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Indici per le tabelle `shared_fund_contributions`
--
ALTER TABLE `shared_fund_contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_id` (`fund_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_contribution_transaction` (`transaction_id`);

--
-- Indici per le tabelle `shared_fund_members`
--
ALTER TABLE `shared_fund_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fund_user_unique` (`fund_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_tag_unique` (`user_id`,`name`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_transaction_goal` (`goal_id`);

--
-- Indici per le tabelle `transaction_tags`
--
ALTER TABLE `transaction_tags`
  ADD PRIMARY KEY (`transaction_id`,`tag_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `friend_code` (`friend_code`);

--
-- Indici per le tabelle `user_blocks`
--
ALTER TABLE `user_blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_block` (`blocker_id`,`blocked_id`),
  ADD KEY `blocked_id` (`blocked_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT per la tabella `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT per la tabella `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT per la tabella `changelog_updates`
--
ALTER TABLE `changelog_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `expense_splits`
--
ALTER TABLE `expense_splits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT per la tabella `friendships`
--
ALTER TABLE `friendships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `fund_level_expenses`
--
ALTER TABLE `fund_level_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `group_expenses`
--
ALTER TABLE `group_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT per la tabella `loan_requests`
--
ALTER TABLE `loan_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `money_transfers`
--
ALTER TABLE `money_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `note_shares`
--
ALTER TABLE `note_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT per la tabella `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT per la tabella `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `saving_goals`
--
ALTER TABLE `saving_goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `settlement_payments`
--
ALTER TABLE `settlement_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `shared_funds`
--
ALTER TABLE `shared_funds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `shared_fund_contributions`
--
ALTER TABLE `shared_fund_contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `shared_fund_members`
--
ALTER TABLE `shared_fund_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT per la tabella `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `user_blocks`
--
ALTER TABLE `user_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budgets_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `fk_chat_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `expense_splits`
--
ALTER TABLE `expense_splits`
  ADD CONSTRAINT `expense_splits_ibfk_1` FOREIGN KEY (`expense_id`) REFERENCES `group_expenses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_splits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `friendships`
--
ALTER TABLE `friendships`
  ADD CONSTRAINT `fk_friendships_user1` FOREIGN KEY (`user_id_1`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_friendships_user2` FOREIGN KEY (`user_id_2`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `fund_level_expenses`
--
ALTER TABLE `fund_level_expenses`
  ADD CONSTRAINT `fk_fund_level_expenses_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fund_level_expenses_fund` FOREIGN KEY (`fund_id`) REFERENCES `shared_funds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fund_level_expenses_user` FOREIGN KEY (`recorded_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `group_expenses`
--
ALTER TABLE `group_expenses`
  ADD CONSTRAINT `fk_group_expenses_category_restart` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_group_expenses_note_restart` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `group_expenses_ibfk_1` FOREIGN KEY (`fund_id`) REFERENCES `shared_funds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_expenses_ibfk_2` FOREIGN KEY (`paid_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `money_transfers`
--
ALTER TABLE `money_transfers`
  ADD CONSTRAINT `fk_transfers_account` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transfers_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transfers_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_note_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notes_creator` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `note_shares`
--
ALTER TABLE `note_shares`
  ADD CONSTRAINT `fk_note_shares_note` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_note_shares_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  ADD CONSTRAINT `recurring_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recurring_transactions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recurring_transactions_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `saving_goals`
--
ALTER TABLE `saving_goals`
  ADD CONSTRAINT `fk_saving_goals_linked_category` FOREIGN KEY (`linked_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `saving_goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `settlement_payments`
--
ALTER TABLE `settlement_payments`
  ADD CONSTRAINT `fk_settlement_from_account` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_settlement_from_user_restart` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_settlement_fund_restart` FOREIGN KEY (`fund_id`) REFERENCES `shared_funds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_settlement_to_account` FOREIGN KEY (`to_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_settlement_to_user_restart` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `shared_funds`
--
ALTER TABLE `shared_funds`
  ADD CONSTRAINT `shared_funds_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `shared_fund_contributions`
--
ALTER TABLE `shared_fund_contributions`
  ADD CONSTRAINT `fk_contribution_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `shared_fund_contributions_ibfk_1` FOREIGN KEY (`fund_id`) REFERENCES `shared_funds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_fund_contributions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `shared_fund_members`
--
ALTER TABLE `shared_fund_members`
  ADD CONSTRAINT `shared_fund_members_ibfk_1` FOREIGN KEY (`fund_id`) REFERENCES `shared_funds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_fund_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transaction_goal` FOREIGN KEY (`goal_id`) REFERENCES `saving_goals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `transaction_tags`
--
ALTER TABLE `transaction_tags`
  ADD CONSTRAINT `transaction_tags_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
