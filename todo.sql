-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 01 2019 г., 00:09
-- Версия сервера: 5.6.41
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `todo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `task`
--

INSERT INTO `task` (`id`, `user_id`, `assigned_user_id`, `description`, `is_done`, `date_added`) VALUES
(8, 1, 3, 'почистить зубы', 0, '2019-01-28 21:17:15'),
(11, 1, 4, 'посмотреть кино', 1, '2019-02-06 21:00:00'),
(12, 1, 1, 'приготовить борщ', 1, '2019-01-29 21:00:00'),
(13, 4, 1, 'пожарить шашлыки', 0, '2019-02-22 21:00:00'),
(16, 4, 4, 'сделать уроки', 0, '2019-02-08 21:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `password`) VALUES
(1, 'admin', 'pass'),
(3, 'sergey', '1234'),
(4, 'john', '12345');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
