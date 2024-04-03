-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 03 2024 г., 11:35
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_ids` text DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_ids`, `fullname`, `address`, `payment_method`, `total_amount`, `order_date`) VALUES
(19, 1, '2,9', '222222', 'Ursu32', '2834768234756', 4379.00, '2024-04-02 11:45:40'),
(20, 1, '2', '3333', 'Ursu32', '2834768234756', 140.00, '2024-04-02 13:20:08'),
(21, 1, '1', '5555', 'Ursu32', '2834768234756', 320.00, '2024-04-02 13:24:23'),
(23, 1, '6,7', '444', 'Ursu32', '2834768234756', 68600.00, '2024-04-02 16:20:16'),
(30, 1, '6', '1111', 'Ursu32', '2834768234756', 20400.00, '2024-04-03 09:09:36');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `brand`, `description`, `price`, `image`, `created_at`, `rating`) VALUES
(1, 'Nike Air Max Dn', 'Кроссовки', 'Nike', 'Say hello to the next generation of Air technology.', 160.00, '/ecommerce/img/air-max-dn-shoes-27XkSQ.png', '2024-04-01 18:07:57', 0.00),
(2, 'Nike Air 5', 'Кроссовки', 'Nike', 'Air technology.', 140.00, '/ecommerce/img/air-max-1-mens-shoes-2C5sX2.png', '2024-04-01 18:12:49', 0.00),
(3, 'Монитор 23.8\"Nitro VG240YM3bmiipx', 'Мониторы', 'Acer', 'Диагональ дисплея\r\n23.8\"\r\nМаксимальное разрешение дисплея\r\n1920x1080 (FullHD)\r\nВремя реакции матрицы\r\n1 мс (GtG) / 0.5 мс (GtG, Min.)\r\nЯркость дисплея\r\n250 кд/м²\r\nТип матрицы\r\nIPS\r\nКонтрастность дисплея\r\n1000:1\r\nОсобенности\r\n\"Безрамочный\" (Сinema screen)\r\nFlicker-Free', 5290.00, '\\ecommerce\\img\\366948863.webp', '2024-04-01 18:12:49', 0.00),
(4, 'Odyssey AG50 S27AG502NI', 'Мониторы', 'Samsung', 'Диагональ дисплея\r\n27\"\r\nМаксимальное разрешение дисплея\r\n2560x1440 (2K QHD)\r\nВремя реакции матрицы\r\n1 мс (GTG)\r\nЯркость дисплея\r\n350 кд/м2\r\nТип матрицы\r\nIPS\r\nКонтрастность дисплея\r\n1000:1 (Typ)\r\nОсобенности\r\n\"Безрамочный\" (Сinema screen)\r\nFlicker-Free\r\nПоворотный экран (Pivot)\r\nРегулировка по высоте', 11300.00, '\\ecommerce\\img\\248368726.webp', '2024-04-01 18:12:49', 0.00),
(5, 'Nitro VG271UM3bmiipx', 'Мониторы', 'Acer', 'Диагональ дисплея\r\n27\"\r\nМаксимальное разрешение дисплея\r\n2560x1440 (2K QHD)\r\nВремя реакции матрицы\r\n0.5 ms (VRB) / 1 ms (GTG)\r\nЯркость дисплея\r\n250 кд/м²\r\nТип матрицы\r\nIPS\r\nКонтрастность дисплея\r\n1000:1\r\nОсобенности\r\n\"Безрамочный\" (Сinema screen)\r\nFlicker-Free', 9999.00, '\\ecommerce\\img\\363113500.webp', '2024-04-01 18:12:49', 0.00),
(6, 'R6000-L2 5.5', 'Генераторы', 'Rato', 'Номинальная мощность\r\n5.5 кВт\r\nТип альтернатора\r\nСинхронный\r\nСистема пуска\r\nРучной стартер\r\nЭлектрический пуск\r\nКоличество фаз\r\nОднофазный\r\nМаксимальная мощность\r\n6 кВт', 20400.00, '\\ecommerce\\img\\317313759.webp', '2024-04-01 18:12:49', 0.00),
(7, 'Alimar ALM-D-7500TE', 'Генераторы', 'Alimar', 'Номинальная мощность\r\n4.8 кВт\r\nТип альтернатора\r\nСинхронный\r\nСистема пуска\r\nРучной стартер\r\nЭлектрический пуск\r\nКоличество фаз\r\nОднофазный + Трехфазный\r\nМаксимальная мощность\r\n5.6 кВт', 48200.00, '\\ecommerce\\img\\303608905.webp', '2024-04-01 18:12:49', 0.00),
(8, 'Rato R8500D-L2 Full Power', 'Генераторы', 'Rato', 'Номинальная мощность\r\n6.4 кВт\r\nТип альтернатора\r\nСинхронный\r\nСистема пуска\r\nРучной стартер\r\nЭлектрический пуск\r\nКоличество фаз\r\nОднофазный + Трехфазный\r\nМаксимальная мощность\r\n6.8 кВт', 29900.00, '\\ecommerce\\img\\317315598.webp', '2024-04-01 18:12:49', 0.00),
(9, 'Gore-Tex ASICS Gel-Sonoma 7 Gtx', 'Кроссовки', 'ASICS', 'Кроссовки ASICS Gel-Sonoma 7 Gtx предназначены для активного отдыха на свежем воздухе. Мембрана GORE-TEX помогает сохранить ваши ноги в тепле и сухости, пока вы бегаете при не самых благоприятных погодных условиях. ', 4239.00, '\\ecommerce\\img\\408712962.webp', '2024-04-01 18:12:49', 0.00),
(10, 'Jolt 4', 'Кроссовки', 'ASICS', 'Мужские кроссовки для бега', 4500.00, '\\ecommerce\\img\\350525152.webp', '2024-04-01 18:12:49', 0.00);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 1, 1, 4, 'Крутые кроссовки!', '2024-04-02 12:07:34'),
(3, 3, 1, 3, 'ну такое', '2024-04-02 12:12:00'),
(4, 2, 1, 1, 'очень крутые кросы', '2024-04-02 12:40:58'),
(6, 2, 1, 5, '', '2024-04-02 12:41:31'),
(12, 2, 1, 5, '', '2024-04-02 12:46:46'),
(13, 2, 1, 5, '', '2024-04-02 12:47:25'),
(14, 2, 1, 5, '', '2024-04-02 12:48:55'),
(17, 2, 1, 5, 'абажаю нике', '2024-04-02 12:55:34'),
(18, 10, 1, 5, 'Это просто вау', '2024-04-02 14:22:13');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_address` varchar(255) DEFAULT NULL,
  `payment_preferences` text DEFAULT NULL,
  `preferred_category` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `shipping_address`, `payment_preferences`, `preferred_category`, `is_admin`) VALUES
(1, 'Алеша', 'jenia.zspataru@gmail.com', '$2y$10$J3tmaTjD2sY9eqNbVOi7G.vgP1qP88ZKKoiYGH6meUHm4NzUbAH8G', '2024-04-01 17:55:53', 'Ursu32', '2834768234756', 'Генераторы', 1),
(3, 'aboba', 'spatira71@mail.ru', '$2y$10$hJG5ssmIg4EBjmjB32OySuHqWHLlqRuKgZr9xp3VEXTtw6b9Km.mS', '2024-04-01 19:15:38', 'Venus 3', '203476592807345', NULL, 0),
(4, 'pcelkфф', 'arabadjidimka@yandex.ru', '$2y$10$fBrl4v5ONEvcOobL7futT.WUPjdPM8SXNoNxa38RBiToHT2xDnsxW', '2024-04-02 07:16:59', 'Testimeteanu74', '11112423531425', 'Кроссовки', 0),
(5, 'Анатолий2004', 'Anatolii2004@gmail.com', '$2y$10$Dryk.4GlPZY1dCIfZdEoh.KQgYimYx9rjDxWubdv0mmdNzxEANkSm', '2024-04-02 14:11:31', 'Anatolievscaia 3', '888884374', 'Генераторы', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
