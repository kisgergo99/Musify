-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2021. Dec 09. 11:44
-- Kiszolgáló verziója: 10.4.6-MariaDB
-- PHP verzió: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `musify`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `albums`
--

CREATE TABLE `albums` (
  `album_id` int(11) NOT NULL,
  `album_artist_name` varchar(120) COLLATE utf8_hungarian_ci NOT NULL,
  `album_name` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `album_artwork_path` text COLLATE utf8_hungarian_ci NOT NULL,
  `album_release_date` date NOT NULL,
  `album_distributed_by` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `album_distributed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `distributors`
--

CREATE TABLE `distributors` (
  `distributor_id` int(11) NOT NULL,
  `distributor_name` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `distributor_publish_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `music`
--

CREATE TABLE `music` (
  `music_id` int(11) NOT NULL COMMENT 'Zene ID',
  `music_artist_name` varchar(120) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Eloado neve',
  `music_track_name` text COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Zenei mu cime',
  `music_path` text COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Az eleresi utvonal',
  `music_artwork_path` text COLLATE utf8_hungarian_ci NOT NULL DEFAULT '\'/musify/images/defaultart.png\'' COMMENT 'Zenei Cover eleresi utvonala',
  `music_status` tinyint(1) NOT NULL COMMENT 'Public, private',
  `music_updated` date NOT NULL DEFAULT current_timestamp() COMMENT 'Mikor lett feltoltve',
  `album_id` int(11) NOT NULL COMMENT 'Melyik albumbol van ID',
  `music_distributed_by` text COLLATE utf8_hungarian_ci NOT NULL DEFAULT 'SZE - Musify' COMMENT 'Ki a disztributor (kiado)',
  `music_distributed_id` int(11) NOT NULL DEFAULT 1 COMMENT 'disztributor ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `user_password` text COLLATE utf8_hungarian_ci NOT NULL,
  `user_email` varchar(120) COLLATE utf8_hungarian_ci NOT NULL,
  `user_firstname` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `user_lastname` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `user_subscription_status` tinyint(1) NOT NULL,
  `user_subscription_expiredate` date DEFAULT NULL,
  `user_type` tinyint(4) NOT NULL COMMENT '0 - user, 1 - admin, 2 - disztributor',
  `user_distributor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`),
  ADD KEY `album_distributed_id` (`album_distributed_id`),
  ADD KEY `album_id` (`album_id`);

--
-- A tábla indexei `distributors`
--
ALTER TABLE `distributors`
  ADD PRIMARY KEY (`distributor_id`);

--
-- A tábla indexei `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`music_id`),
  ADD KEY `music_album_id` (`album_id`),
  ADD KEY `music_distributed_id` (`music_distributed_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `useremail` (`user_email`),
  ADD KEY `user_distributor_id` (`user_distributor_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `distributors`
--
ALTER TABLE `distributors`
  MODIFY `distributor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `music`
--
ALTER TABLE `music`
  MODIFY `music_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Zene ID';

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `music_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`),
  ADD CONSTRAINT `music_ibfk_2` FOREIGN KEY (`music_distributed_id`) REFERENCES `distributors` (`distributor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
