-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 19 feb 2024 om 23:19
-- Serverversie: 10.11.2-MariaDB
-- PHP-versie: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbforrockers`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dfr_blogs`
--

CREATE TABLE `dfr_blogs` (
  `blogId` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `file` varchar(250) NOT NULL,
  `userAccountsId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `dfr_blogs`
--

INSERT INTO `dfr_blogs` (`blogId`, `title`, `file`, `userAccountsId`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
(1, 'Hello World1', 'helloworldblog-20240219115501', 1, '2024-02-01 11:57:15', NULL, NULL),
(2, 'It&#039;s alive', 'itsalive-20240219115501', 14, '2024-02-01 11:57:15', NULL, NULL),
(3, 'Iets nieuws', 'blog_20240219091740_jlTNi', 14, '2024-02-19 21:17:40', NULL, NULL),
(4, 'De wereld is groen', 'blog_20240219094525_b0iUK', 14, '2024-02-19 21:45:25', NULL, NULL),
(5, 'Groente eten is slecht', 'blog_20240219105911_dGHDq', 15, '2024-02-19 22:59:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dfr_permissions`
--

CREATE TABLE `dfr_permissions` (
  `id` int(11) NOT NULL,
  `rolesId` int(11) NOT NULL COMMENT 'Dit veld bevat het id nummer van de datatable dfr_roles.',
  `rulesId` int(11) NOT NULL COMMENT 'Dit veld bevat het id nummer van de datatable dfr_rules',
  `level` int(11) NOT NULL COMMENT '1=read; 2=read and edit; 3 = read, edit, delete'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `dfr_permissions`
--

INSERT INTO `dfr_permissions` (`id`, `rolesId`, `rulesId`, `level`) VALUES
(1, 1, 1, 3),
(3, 1, 2, 3),
(4, 2, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dfr_roles`
--

CREATE TABLE `dfr_roles` (
  `RolesId` int(11) NOT NULL,
  `RolesName` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `dfr_roles`
--

INSERT INTO `dfr_roles` (`RolesId`, `RolesName`) VALUES
(1, 'admin'),
(2, 'gebruiker');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dfr_rules`
--

CREATE TABLE `dfr_rules` (
  `RulesId` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `explanation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `dfr_rules`
--

INSERT INTO `dfr_rules` (`RulesId`, `name`, `alias`, `explanation`) VALUES
(1, 'Roles', 'roles', ''),
(2, 'Users', 'users', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dfr_userAccounts`
--

CREATE TABLE `dfr_userAccounts` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `RolesId` tinyint(1) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `dfr_blogs`
--
ALTER TABLE `dfr_blogs`
  ADD PRIMARY KEY (`blogId`);

--
-- Indexen voor tabel `dfr_permissions`
--
ALTER TABLE `dfr_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `dfr_roles`
--
ALTER TABLE `dfr_roles`
  ADD PRIMARY KEY (`RolesId`);

--
-- Indexen voor tabel `dfr_rules`
--
ALTER TABLE `dfr_rules`
  ADD PRIMARY KEY (`RulesId`);

--
-- Indexen voor tabel `dfr_userAccounts`
--
ALTER TABLE `dfr_userAccounts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `dfr_blogs`
--
ALTER TABLE `dfr_blogs`
  MODIFY `blogId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `dfr_permissions`
--
ALTER TABLE `dfr_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `dfr_roles`
--
ALTER TABLE `dfr_roles`
  MODIFY `RolesId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `dfr_rules`
--
ALTER TABLE `dfr_rules`
  MODIFY `RulesId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `dfr_userAccounts`
--
ALTER TABLE `dfr_userAccounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
