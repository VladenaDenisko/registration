-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 13 2023 г., 07:07
-- Версия сервера: 5.6.38
-- Версия PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `my_database`
--
CREATE DATABASE IF NOT EXISTS `my_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `my_database`;

-- --------------------------------------------------------

--
-- Структура таблицы `my_user_table`
--

CREATE TABLE `my_user_table` (
  `my_id` int(11) NOT NULL,
  `my_login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_fio` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_phone` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_address` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_birthday` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `my_user_table`
--

INSERT INTO `my_user_table` (`my_id`, `my_login`, `my_password`, `my_fio`, `my_email`, `my_phone`, `my_address`, `my_birthday`) VALUES
(1, 'koko', '$2y$10$A9KBVoLu0I7dg8BDlRP/fuLcpK3mOSzdAOYAaVvUNexPcSVgs.LDO', 'koko koko', 'asd@asd', '334455', 'sdfsfasfasfaf', '2023-06-06'),
(2, 'kokoko', '$2y$10$yWSv.omY983VakFob5bh6.hhMPFgihGRykK26Qvl1nTvvXqexlfgy', 'koko koko', 'asdko@asd', '334455', '', '0000-00-00'),
(3, 'kokokok', '$2y$10$BHwvlMPYwdupxQcbgaGVXeyAuacKgjCfOUgk8As7VCWx4MLYkirey', 'koko koko', 'asdkok@asd', '334455', '', '0000-00-00'),
(4, 'user1', '$2y$10$KfLo.Y3PMWQrkhfDDvVLn.Tp4gSDMwL4hrKafdgxP23O22q3jbYCK', 'name surname', 'user@user', '234234234', 'sadsf sdfsdfsdf', '2023-06-12');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `my_user_table`
--
ALTER TABLE `my_user_table`
  ADD PRIMARY KEY (`my_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `my_user_table`
--
ALTER TABLE `my_user_table`
  MODIFY `my_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
