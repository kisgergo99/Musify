-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2021. Nov 08. 17:18
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
  `album_distributed_id` int(11) NOT NULL,
  `album_tracks` text COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `albums`
--

INSERT INTO `albums` (`album_id`, `album_artist_name`, `album_name`, `album_artwork_path`, `album_release_date`, `album_distributed_by`, `album_distributed_id`, `album_tracks`) VALUES
(1, 'ID', 'The First One', '/musify/images/defaultart.png', '2021-11-04', 'SZE - Musify', 1, '1,2,3'),
(2, 'ID', 'Second One', '/musify/images/defaultart.png', '2021-11-04', 'SZE - Musify', 1, ''),
(3, 'ID', 'Third One', '/musify/images/defaultart.png', '2021-10-05', 'Sony', 2, ''),
(4, 'ME', 'Fourth One', '/musify/images/defaultart.png', '2020-08-11', 'Sony', 2, ''),
(5, 'DJ Béla', 'Fifth one', '/musify/images/defaultart.png', '2021-11-01', 'SZE - Musify', 1, ''),
(6, 'ID', 'Sixth One', '/musify/images/defaultart.png', '2021-06-07', 'Sony', 2, '');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `distributors`
--

CREATE TABLE `distributors` (
  `distributor_id` int(11) NOT NULL,
  `distributor_name` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `distributor_publish_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `distributors`
--

INSERT INTO `distributors` (`distributor_id`, `distributor_name`, `distributor_publish_status`) VALUES
(1, 'SZE - Musify', 1);

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

--
-- A tábla adatainak kiíratása `music`
--

INSERT INTO `music` (`music_id`, `music_artist_name`, `music_track_name`, `music_path`, `music_artwork_path`, `music_status`, `music_updated`, `album_id`, `music_distributed_by`, `music_distributed_id`) VALUES
(1, 'ID', 'Drift', '/musify/audio/drift.mp3', '/musify/images/defaultart.png', 1, '2021-10-25', 1, 'Gradden\'s Production & Recordings Ltd.', 1),
(2, 'Johnny Hallyday', 'Le Pénitencier', '/musify/audio/johnny.mp3', '/musify/images/defaultart.png', 1, '2021-10-30', 1, '\'SZE - Musify\'', 1),
(3, 'Adrian Lux, Axwell', 'Teenage Crime (Axwell Remix)', '/musify/audio/axwell.mp3', '/musify/images/defaultart.png', 1, '2021-10-30', 1, '\'SZE - Musify\'', 1);

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
  `user_type` tinyint(4) NOT NULL COMMENT '0 - user, 1 - admin, 2 - disztributor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_password`, `user_email`, `user_firstname`, `user_lastname`, `user_subscription_status`, `user_subscription_expiredate`, `user_type`) VALUES
(1, 'gradden', '$2y$10$EIyjY2sYZOdyIa1SC4pZh.7xyfBzgKI/iDQbt9TXSREvI0pT2ykwO', 'teszt@mail.com', 'teszt', 'bela', 1, NULL, 2),
(26, 'Tóth', '$2y$10$wUV1h5hk1sC7jsPnHAVKAuzr2CbtE/lfno37Hqm8Y2JnC5ySpjR8G', 'tgery3@gmail.com', 'Tóth', 'Gergő', 1, '2021-11-25', 0),
(30, 'Teszt', '$2y$10$fYm9C.LBqHCXu0w7rh8bA.r1Ro6yVKagoFsSw1my/xWW052MXdP/.', 'tesztacc@gmail.com', 'Teszt', 'Account', 1, '2021-12-06', 0);

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
  ADD UNIQUE KEY `useremail` (`user_email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `distributors`
--
ALTER TABLE `distributors`
  MODIFY `distributor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `music`
--
ALTER TABLE `music`
  MODIFY `music_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Zene ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
