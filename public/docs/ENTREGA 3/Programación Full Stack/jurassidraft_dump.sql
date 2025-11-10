-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 25, 2025 at 04:28 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jurassidraft`
--

-- --------------------------------------------------------

--
-- Table structure for table `colocaciones`
--

CREATE TABLE `colocaciones` (
  `id` int NOT NULL,
  `partida_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `ronda` tinyint NOT NULL,
  `turno` tinyint NOT NULL,
  `recinto_id` int NOT NULL,
  `tipo_dino` int NOT NULL,
  `pts_obtenidos` int DEFAULT '0',
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colocaciones`
--

INSERT INTO `colocaciones` (`id`, `partida_id`, `usuario_id`, `ronda`, `turno`, `recinto_id`, `tipo_dino`, `pts_obtenidos`, `creado_en`) VALUES
(1, 5, 1, 1, 1, 1, 2, 2, '2025-10-25 00:35:32'),
(2, 5, 1, 1, 1, 1, 2, 1, '2025-10-25 01:07:52'),
(3, 5, 2, 1, 1, 1, 4, 1, '2025-10-25 01:40:38'),
(4, 5, 3, 1, 1, 1, 1, 1, '2025-10-25 01:40:48'),
(5, 5, 4, 1, 1, 1, 2, 1, '2025-10-25 01:40:57'),
(6, 5, 5, 1, 1, 2, 2, 1, '2025-10-25 01:41:09'),
(7, 6, 1, 1, 1, 2, 3, 1, '2025-10-25 01:42:04'),
(8, 6, 2, 1, 1, 5, 1, 1, '2025-10-25 01:42:16'),
(9, 6, 1, 1, 2, 2, 2, 1, '2025-10-25 01:43:14'),
(10, 6, 2, 1, 2, 1, 3, 1, '2025-10-25 01:43:20'),
(11, 6, 2, 1, 3, 1, 1, 1, '2025-10-25 01:44:47'),
(12, 6, 1, 1, 3, 2, 4, 1, '2025-10-25 01:44:54'),
(13, 6, 2, 1, 4, 3, 2, 1, '2025-10-25 01:45:38'),
(14, 6, 1, 1, 4, 1, 4, 1, '2025-10-25 01:48:54'),
(15, 6, 1, 1, 5, 3, 1, 7, '2025-10-25 02:33:23'),
(16, 6, 2, 1, 5, 5, 3, 1, '2025-10-25 02:34:09'),
(17, 6, 1, 1, 6, 1, 1, 2, '2025-10-25 03:15:48'),
(18, 6, 2, 1, 6, 2, 2, 1, '2025-10-25 03:16:14'),
(19, 6, 1, 2, 1, 7, 3, 1, '2025-10-25 03:19:02'),
(20, 6, 2, 2, 1, 3, 2, 0, '2025-10-25 03:19:47'),
(21, 7, 1, 1, 1, 2, 4, 1, '2025-10-25 03:47:53'),
(22, 7, 2, 1, 1, 1, 4, 2, '2025-10-25 03:48:06'),
(23, 7, 2, 1, 2, 5, 4, 0, '2025-10-25 03:48:22'),
(24, 7, 1, 1, 2, 4, 4, 0, '2025-10-25 03:48:50'),
(25, 7, 2, 1, 3, 6, 4, 7, '2025-10-25 11:53:30'),
(26, 7, 1, 1, 3, 2, 2, 3, '2025-10-25 11:54:04'),
(27, 7, 1, 1, 4, 2, 3, 6, '2025-10-25 12:31:42'),
(28, 8, 1, 1, 1, 2, 1, 1, '2025-10-25 13:12:31');

-- --------------------------------------------------------

--
-- Table structure for table `dinosaurios_catalogo`
--

CREATE TABLE `dinosaurios_catalogo` (
  `id` int NOT NULL,
  `nombre_corto` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `categoria` enum('herbivoro','carnivoro','especial') COLLATE utf8mb4_general_ci NOT NULL,
  `puntos_base` int DEFAULT '1',
  `nota_manual` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dinosaurios_catalogo`
--

INSERT INTO `dinosaurios_catalogo` (`id`, `nombre_corto`, `categoria`, `puntos_base`, `nota_manual`) VALUES
(1, 'T-Rex', 'carnivoro', 5, 'Solo puede colocarse en el recinto especial del T-Rex.'),
(2, 'Stegosaurus', 'herbivoro', 1, 'Funciona bien en recintos de bosque o pradera.'),
(3, 'Triceratops', 'herbivoro', 1, 'Ideal para recintos de variedad o parejas.'),
(4, 'Parasaurus', 'herbivoro', 1, 'Aporta variedad, común en pradera o bosque.'),
(5, 'Brachiosaurus', 'herbivoro', 2, 'Otorga puntos adicionales en recintos amplios o de altura.'),
(6, 'Spinosaurus', 'carnivoro', 2, 'Aumenta el valor en recintos de río o zonas acuáticas.');

-- --------------------------------------------------------

--
-- Table structure for table `partidas`
--

CREATE TABLE `partidas` (
  `id` int NOT NULL,
  `nombre` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `estado` enum('config','en_curso','cerrada') COLLATE utf8mb4_general_ci DEFAULT 'config',
  `ronda` tinyint NOT NULL DEFAULT '1',
  `turno` tinyint NOT NULL DEFAULT '1',
  `dado_restriccion` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creador_id` int NOT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partidas`
--

INSERT INTO `partidas` (`id`, `nombre`, `estado`, `ronda`, `turno`, `dado_restriccion`, `creador_id`, `creado_en`) VALUES
(4, '1', 'config', 1, 1, NULL, 6, '2025-10-25 00:21:22'),
(5, '2', 'en_curso', 1, 2, NULL, 6, '2025-10-25 00:29:48'),
(6, '1', 'en_curso', 2, 2, NULL, 6, '2025-10-25 01:41:56'),
(7, 'test', 'en_curso', 1, 4, 'Recinto Vacío', 6, '2025-10-25 03:46:47'),
(8, 'test', 'en_curso', 1, 1, 'El Bosque', 6, '2025-10-25 12:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `partida_jugadores`
--

CREATE TABLE `partida_jugadores` (
  `id` int NOT NULL,
  `partida_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `puntos_totales` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partida_jugadores`
--

INSERT INTO `partida_jugadores` (`id`, `partida_id`, `usuario_id`, `puntos_totales`) VALUES
(1, 4, 1, 0),
(2, 4, 2, 0),
(3, 4, 4, 0),
(4, 4, 5, 0),
(5, 4, 6, 0),
(6, 4, 7, 0),
(7, 5, 1, 3),
(8, 5, 2, 1),
(9, 5, 3, 1),
(10, 5, 4, 1),
(11, 5, 5, 1),
(12, 5, 6, 0),
(13, 6, 1, 14),
(14, 6, 2, 6),
(15, 7, 1, 10),
(16, 7, 2, 9),
(17, 8, 1, 1),
(18, 8, 2, 0),
(19, 8, 3, 0),
(20, 8, 4, 0),
(21, 8, 5, 0),
(22, 8, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `recintos_catalogo`
--

CREATE TABLE `recintos_catalogo` (
  `id` int NOT NULL,
  `clave` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_regla` enum('variedad','semejanza','parejas','exactos','solitario','comparativo','rio','especial') COLLATE utf8mb4_general_ci DEFAULT 'rio',
  `max_dinos` tinyint DEFAULT '6',
  `puntos_base` int DEFAULT '0',
  `puntos_condicional` text COLLATE utf8mb4_general_ci,
  `nota_manual` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recintos_catalogo`
--

INSERT INTO `recintos_catalogo` (`id`, `clave`, `descripcion`, `tipo_regla`, `max_dinos`, `puntos_base`, `puntos_condicional`, `nota_manual`) VALUES
(1, 'bosque_semejanza', 'El bosque de la semejanza', 'semejanza', 6, 0, 'puntos si dinosaurios iguales', 'Debe ocuparse siempre de izquierda a derecha sin dejar espacios intermedios.\nAl final de la partida, ganarás los puntos de victoria indicados según el número total de\ndinosaurios colocados en el recinto.'),
(2, 'prado_diferencia', 'El prado de las diferencias', 'variedad', 6, 0, 'puntos si dinosaurios distintos', 'Debe ocuparse siempre de izquierda a derecha sin dejar espacios intermedios. Al final\nde la partida, ganarás los puntos de victoria indicados según el número de dinosaurios colocados\nen el recinto.'),
(3, 'isla_solitario', 'La isla solitaria', 'solitario', 1, 0, 'solo 1 dino en todo el parque 7 puntos', 'Ganarás 7 puntos de victoria si ningún jugador o jugadora tiene en su parque más dinosaurios\nque tú de esta especie. En caso de empate recibes igualmente los 7 puntos.'),
(4, 'pradera_amor', 'La pradera del amor', 'parejas', 12, 0, 'por cada pareja 5 puntos', 'Conseguirás 5 puntos de victoria por cada pareja de dinosaurios\nde la misma especie que se encuentre en el recinto. Está permitido tener más de una pareja de\nla misma especie. Los dinosaurios que no formen pareja no suman puntos.'),
(5, 'trio_frondoso', 'El trío frondoso', 'exactos', 3, 0, 'si hay 3 dinos, 7 puntos', 'Ganarás 7 puntos de victoria si hay exactamente 3 dinosaurios\ndentro del recinto. Si al final de la partida el recinto no alberga exactamente 3 dinosaurios, no te\nllevas ningún punto.'),
(6, 'rey_selva', 'El rey de la selva', 'especial', 1, 0, 'si tenes mas que el resto 7 puntos', 'Ganarás 7 puntos de victoria si ningún jugador o jugadora tiene en su parque más dinosaurios\nque tú de esta especie. En caso de empate recibes igualmente los 7 puntos.'),
(7, 'rio', 'El río', 'rio', 12, 1, 'por cada dinosaurio que se tire al rio 1 punto.', 'Por cada dinosaurio que se encuentre en el Río valdrá 1 punto de victoria\nindependientemente de su especie.');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('admin','jugador') COLLATE utf8mb4_general_ci DEFAULT 'jugador',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `nickname`, `contrasena`, `rol`, `creado_en`, `deleted_at`) VALUES
(1, 'Admin Demo', 'admin_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-10-16 13:21:58', NULL),
(2, 'Seba', 'seba_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador', '2025-10-16 13:21:58', NULL),
(3, 'Tomi', 'tomi_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador', '2025-10-16 13:21:58', NULL),
(4, 'Nacho', 'nacho_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador', '2025-10-16 13:21:58', NULL),
(5, 'Joaco', 'joaco_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador', '2025-10-16 13:21:58', NULL),
(6, 'Ignacio Fianza', 'ifianza', '$2y$12$5pEGovcmpN6FIQjLJmqs0Ok5umEoRawwVp7Gj3.ypZgh68YTg.3gy', 'admin', '2025-10-16 16:28:23', NULL),
(7, 'Usuario', 'usuario', '$2y$12$T0vp5t6M1e6sC1M56.whHuriFG5uFroj/11LcDQvI6msZ86l8NjsC', 'jugador', '2025-10-21 02:05:57', NULL),
(8, 'test', 'test_deleted_8', '$2y$12$FfU/EMcqCbbBcRfLEc4R7OhP7HFL0ZaVfGbjmuHZqyHrnMtUfhuOO', 'jugador', '2025-10-25 03:05:47', '2025-10-25 06:05:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `colocaciones`
--
ALTER TABLE `colocaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_col_partida` (`partida_id`),
  ADD KEY `fk_col_usuario` (`usuario_id`),
  ADD KEY `fk_col_recinto` (`recinto_id`),
  ADD KEY `fk_col_dino` (`tipo_dino`);

--
-- Indexes for table `dinosaurios_catalogo`
--
ALTER TABLE `dinosaurios_catalogo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_corto` (`nombre_corto`);

--
-- Indexes for table `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_partidas_creador` (`creador_id`);

--
-- Indexes for table `partida_jugadores`
--
ALTER TABLE `partida_jugadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pj_partida` (`partida_id`),
  ADD KEY `fk_pj_usuario` (`usuario_id`);

--
-- Indexes for table `recintos_catalogo`
--
ALTER TABLE `recintos_catalogo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nickname` (`nickname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `colocaciones`
--
ALTER TABLE `colocaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `dinosaurios_catalogo`
--
ALTER TABLE `dinosaurios_catalogo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `partida_jugadores`
--
ALTER TABLE `partida_jugadores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `recintos_catalogo`
--
ALTER TABLE `recintos_catalogo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `colocaciones`
--
ALTER TABLE `colocaciones`
  ADD CONSTRAINT `fk_col_dino` FOREIGN KEY (`tipo_dino`) REFERENCES `dinosaurios_catalogo` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_col_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_col_recinto` FOREIGN KEY (`recinto_id`) REFERENCES `recintos_catalogo` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_col_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `fk_partidas_creador` FOREIGN KEY (`creador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `partida_jugadores`
--
ALTER TABLE `partida_jugadores`
  ADD CONSTRAINT `fk_pj_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pj_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
