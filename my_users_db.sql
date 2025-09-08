-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: mysql
-- Χρόνος δημιουργίας: 07 Σεπ 2025 στις 10:17:31
-- Έκδοση διακομιστή: 8.0.43
-- Έκδοση PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `my_users_db`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `lists`
--

CREATE TABLE `lists` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `lists`
--

INSERT INTO `lists` (`id`, `title`, `created_at`, `user_id`) VALUES
(27, 'Python', '2025-08-06 16:02:03', 25),
(29, 'hello world', '2025-08-21 15:31:55', 25);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `list_items`
--

CREATE TABLE `list_items` (
  `id` int NOT NULL,
  `list_id` int NOT NULL,
  `user_id` int NOT NULL,
  `youtube_title` varchar(255) NOT NULL,
  `youtube_video_id` varchar(50) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `list_items`
--

INSERT INTO `list_items` (`id`, `list_id`, `user_id`, `youtube_title`, `youtube_video_id`, `added_at`) VALUES
(3, 27, 25, 'Learn Python OOP in under 20 Minutes', 'rLyYb7BFgQI', '2025-08-06 16:05:56'),
(4, 27, 25, 'Let\'s Build 10 Python Projects', 'SbpQJL49A_Y', '2025-08-06 16:07:17'),
(7, 27, 25, 'Python Functions: Visually Explained', 'KW6qncswzHw', '2025-08-06 16:17:50'),
(8, 27, 25, '👩‍💻 Python for Beginners Tutorial', 'b093aqAZiPU', '2025-08-06 16:19:16'),
(9, 27, 25, 'Python Beginner’s Tutorial in Just 10 Minutes', 'pyCJ3Ck-Vhg', '2025-08-06 16:19:57'),
(10, 27, 25, ' Python Classes in 1 Minute!', 'yYALsys-P_w', '2025-08-06 16:20:37'),
(11, 27, 25, 'Python OOP Tutorial 1: Classes and Instances', 'ZDa-Z5JzLYM', '2025-08-06 16:21:16'),
(12, 27, 25, 'Python OOP Tutorial 2: Class Variables', 'BJ-VvGyQxho', '2025-08-06 16:21:55'),
(13, 27, 25, 'Python OOP Tutorial 3: classmethods and staticmethods', 'rq8cL2XMM5M', '2025-08-06 16:22:31'),
(14, 27, 25, 'Python OOP Tutorial 4: Inheritance - Creating Subclasses', 'RSl87lqOXDE', '2025-08-06 16:22:59'),
(15, 27, 25, 'Python OOP Tutorial 5: Special (Magic/Dunder) Methods', '3ohzBxoFHAY', '2025-08-06 16:23:37');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`) VALUES
(25, 'ιορδανης', 'γεωργιαδης', 'jordangeor', 'jordan1@gmail.com', '$2y$10$kAjOAI2GCJd9gsbYjHJIfunUfOWjlMMNnQsukMIhnUQrfLyXZSiue'),
(26, 'kostas', 'spendas', 'kspendas', 'spen@gmail.com', '$2y$10$/w5RsaqvlX69zMzERngIX.BDQAIbd/VKCiMvpUf7IHHrGByQHg2nm');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `list_items`
--
ALTER TABLE `list_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_list_items_user_id` (`user_id`),
  ADD KEY `fk_list_items_list_id` (`list_id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`,`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `lists`
--
ALTER TABLE `lists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT για πίνακα `list_items`
--
ALTER TABLE `list_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `list_items`
--
ALTER TABLE `list_items`
  ADD CONSTRAINT `fk_list_items_list_id` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_list_items_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
