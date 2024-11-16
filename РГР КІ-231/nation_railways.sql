-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Лис 16 2024 р., 10:29
-- Версія сервера: 10.4.32-MariaDB
-- Версія PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `nation_railways`
--

-- --------------------------------------------------------

--
-- Структура таблиці `cashier`
--

CREATE TABLE `cashier` (
  `cashier_id` int(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `cashier`
--

INSERT INTO `cashier` (`cashier_id`, `name`, `date_of_birth`, `phone_number`) VALUES
(1, 'Іван Максименко', '1999-01-23', '0638729410'),
(2, 'Марія Кочубей', '1991-05-01', '0689632851'),
(3, 'Олег Шевченко', '1980-12-17', '0738502855'),
(4, 'Владислав Бабак', '2001-06-15', '0638529427'),
(5, 'Юлія Авраменко', '1990-01-22', '0508724891'),
(6, 'Володимир Баєвський', '1989-11-30', '0681869849'),
(7, 'Наталія Гордієнко', '1978-05-29', '0509727129'),
(8, 'Анатолій Піпа', '1973-02-01', '0638491920'),
(9, 'Вікторія Кравченко', '1999-11-17', '0681926442'),
(10, 'Надія Бондарчук', '1980-09-02', '0504738561');

-- --------------------------------------------------------

--
-- Структура таблиці `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `date_of_birth`, `phone_number`) VALUES
(1, 'Юлія Васильєва', '1991-05-20', '0689833120'),
(2, 'Олексій Сидоров', '1989-01-12', '0639512899'),
(3, 'Анна Нечай', '1998-04-02', '0738263788'),
(4, 'Ігор Кук', '1984-09-10', '0503847129'),
(5, 'Світлана Маск', '2001-12-25', '0738462811'),
(6, 'Єлизавета Коваль', '2003-11-21', '0634789420'),
(7, 'Сергій Савченко', '1974-05-17', '0504793490'),
(8, 'Максим Поліщук', '1999-07-02', '0687453118'),
(9, 'Тетяна Кухар', '1992-12-10', '0507364251'),
(10, 'Дмитро Юрченко', '2000-04-29', '0639866421');

-- --------------------------------------------------------

--
-- Структура таблиці `dispatcher`
--

CREATE TABLE `dispatcher` (
  `dispatcher_id` int(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `salary` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `dispatcher`
--

INSERT INTO `dispatcher` (`dispatcher_id`, `name`, `salary`) VALUES
(1, 'Володимир Іванов', 12000),
(2, 'Ольга Дорошенко', 15000),
(3, 'Євген Павленко', 9000),
(4, 'Юрій Руденок', 12000),
(5, 'Андрій Герасименко', 11500),
(6, 'Богдан Червоноокий', 13000),
(7, 'Віталій Абраменко', 12850),
(8, 'Іванна Добридень', 10759),
(9, 'Лілія Бандера', 13456),
(10, 'Роман Ткаченко', 15000);

-- --------------------------------------------------------

--
-- Структура таблиці `driver`
--

CREATE TABLE `driver` (
  `driver_id` int(30) NOT NULL,
  `work_experience` int(30) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `driver`
--

INSERT INTO `driver` (`driver_id`, `work_experience`, `name`) VALUES
(1, 11, 'Андрій Патлань'),
(2, 3, 'Віталій Ротару'),
(3, 15, 'Ірина Шевченко'),
(4, 20, 'Ілля Василенко'),
(5, 9, 'Ярослава Олійник'),
(6, 16, 'Варвара Марченко'),
(7, 1, 'Борис Левченко'),
(8, 6, 'Герман Романюк'),
(9, 15, 'Костянтин Приходько'),
(10, 8, 'Денис Федоренко');

-- --------------------------------------------------------

--
-- Структура таблиці `driver_disp`
--

CREATE TABLE `driver_disp` (
  `id` int(30) NOT NULL,
  `dispatcher_id` int(30) NOT NULL,
  `driver_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `driver_disp`
--

INSERT INTO `driver_disp` (`id`, `dispatcher_id`, `driver_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 5, 6),
(5, 4, 8),
(6, 10, 7),
(7, 8, 9),
(8, 9, 4),
(9, 6, 5),
(10, 7, 10);

-- --------------------------------------------------------

--
-- Структура таблиці `driver_train`
--

CREATE TABLE `driver_train` (
  `id` int(30) NOT NULL,
  `train_id` int(30) NOT NULL,
  `driver_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `driver_train`
--

INSERT INTO `driver_train` (`id`, `train_id`, `driver_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 10),
(5, 2, 6),
(6, 1, 9),
(7, 3, 8),
(8, 3, 7),
(9, 4, 5),
(10, 2, 2),
(11, 1, 1),
(12, 4, 3),
(13, 2, 4);

-- --------------------------------------------------------

--
-- Структура таблиці `ticket`
--

CREATE TABLE `ticket` (
  `ticket_id` int(30) NOT NULL,
  `customer_id` int(30) NOT NULL,
  `train_id` int(30) NOT NULL,
  `ticket_type` varchar(50) NOT NULL,
  `cashier_id` int(30) NOT NULL,
  `price` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `ticket`
--

INSERT INTO `ticket` (`ticket_id`, `customer_id`, `train_id`, `ticket_type`, `cashier_id`, `price`) VALUES
(1, 1, 1, 'Звичайний', 1, 499),
(2, 2, 2, 'Люкс', 2, 999),
(3, 3, 3, 'Пільговий', 3, 299),
(4, 4, 2, 'Люкс', 2, 999),
(5, 5, 1, 'Пільговий', 1, 299),
(6, 7, 3, 'Звичайний', 4, 499),
(7, 9, 2, 'Люкс', 9, 999),
(8, 10, 1, 'Звичайний', 6, 499),
(9, 6, 2, 'Звичайний', 8, 499),
(10, 8, 3, 'Пільговий', 7, 299),
(11, 5, 3, 'Люкс', 10, 999),
(12, 7, 2, 'Люкс', 10, 999),
(13, 9, 1, 'Звичаний', 8, 499),
(14, 1, 1, 'Пільговий', 7, 299),
(15, 4, 2, 'Люкс', 5, 999),
(16, 2, 4, 'Люкс', 7, 299),
(17, 1, 4, 'Люкс', 1, 499);

-- --------------------------------------------------------

--
-- Структура таблиці `train`
--

CREATE TABLE `train` (
  `train_id` int(30) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `train`
--

INSERT INTO `train` (`train_id`, `type`) VALUES
(1, 'Пасажирський Плацкартний'),
(2, 'Пасажирський Купейний'),
(3, 'Інтерсіті'),
(4, 'Інтерсіті +');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `cashier`
--
ALTER TABLE `cashier`
  ADD PRIMARY KEY (`cashier_id`);

--
-- Індекси таблиці `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Індекси таблиці `dispatcher`
--
ALTER TABLE `dispatcher`
  ADD PRIMARY KEY (`dispatcher_id`);

--
-- Індекси таблиці `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`driver_id`);

--
-- Індекси таблиці `driver_disp`
--
ALTER TABLE `driver_disp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispatcher_id` (`dispatcher_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Індекси таблиці `driver_train`
--
ALTER TABLE `driver_train`
  ADD PRIMARY KEY (`id`),
  ADD KEY `train_id` (`train_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Індекси таблиці `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `train_id` (`train_id`),
  ADD KEY `cashier_id` (`cashier_id`);

--
-- Індекси таблиці `train`
--
ALTER TABLE `train`
  ADD PRIMARY KEY (`train_id`);

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `driver_disp`
--
ALTER TABLE `driver_disp`
  ADD CONSTRAINT `driver_disp_ibfk_1` FOREIGN KEY (`dispatcher_id`) REFERENCES `dispatcher` (`dispatcher_id`),
  ADD CONSTRAINT `driver_disp_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`);

--
-- Обмеження зовнішнього ключа таблиці `driver_train`
--
ALTER TABLE `driver_train`
  ADD CONSTRAINT `driver_train_ibfk_1` FOREIGN KEY (`train_id`) REFERENCES `train` (`train_id`),
  ADD CONSTRAINT `driver_train_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`);

--
-- Обмеження зовнішнього ключа таблиці `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`train_id`) REFERENCES `train` (`train_id`),
  ADD CONSTRAINT `ticket_ibfk_3` FOREIGN KEY (`cashier_id`) REFERENCES `cashier` (`cashier_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
