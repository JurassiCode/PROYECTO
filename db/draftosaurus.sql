-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 20, 2025 at 08:22 PM
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
-- Database: `draftosaurus`
--

-- --------------------------------------------------------

--
-- Table structure for table `colocaciones`
--

CREATE TABLE `colocaciones` (
  `id` bigint UNSIGNED NOT NULL,
  `partida_id` bigint UNSIGNED NOT NULL,
  `usuario_id` int NOT NULL,
  `ronda` tinyint UNSIGNED NOT NULL,
  `turno` tinyint UNSIGNED NOT NULL,
  `recinto_id` tinyint UNSIGNED NOT NULL,
  `tipo_dino` smallint UNSIGNED NOT NULL,
  `valido` tinyint(1) DEFAULT '1',
  `motivo_invalidez` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colocaciones`
--

INSERT INTO `colocaciones` (`id`, `partida_id`, `usuario_id`, `ronda`, `turno`, `recinto_id`, `tipo_dino`, `valido`, `motivo_invalidez`, `creado_en`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, NULL, '2025-10-16 13:21:58'),
(2, 1, 3, 1, 1, 2, 2, 1, NULL, '2025-10-16 13:21:58'),
(3, 1, 4, 1, 1, 3, 3, 1, NULL, '2025-10-16 13:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `dinosaurios_catalogo`
--

CREATE TABLE `dinosaurios_catalogo` (
  `id` smallint UNSIGNED NOT NULL,
  `nombre_corto` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dinosaurios_catalogo`
--

INSERT INTO `dinosaurios_catalogo` (`id`, `nombre_corto`, `categoria`) VALUES
(1, 't_rex', 'base'),
(2, 'triceratops', 'base'),
(3, 'stegosaurus', 'base'),
(4, 'brachiosaurus', 'base'),
(5, 'parasaurolophus', 'base'),
(6, 'velociraptor', 'base');

-- --------------------------------------------------------

--
-- Table structure for table `partidas`
--

CREATE TABLE `partidas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `estado` enum('config','en_curso','cerrada') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'config',
  `ronda` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `turno` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `dado_restriccion` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creador_id` int NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partidas`
--

INSERT INTO `partidas` (`id`, `nombre`, `estado`, `ronda`, `turno`, `dado_restriccion`, `creador_id`, `creado_en`) VALUES
(1, 'Sala de prueba', 'en_curso', 1, 1, NULL, 2, '2025-10-16 13:21:58'),
(2, 'sala1', 'cerrada', 1, 1, NULL, 6, '2025-10-16 16:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `partida_jugadores`
--

CREATE TABLE `partida_jugadores` (
  `id` bigint UNSIGNED NOT NULL,
  `partida_id` bigint UNSIGNED NOT NULL,
  `usuario_id` int NOT NULL,
  `orden_mesa` tinyint UNSIGNED NOT NULL,
  `puntos_totales` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partida_jugadores`
--

INSERT INTO `partida_jugadores` (`id`, `partida_id`, `usuario_id`, `orden_mesa`, `puntos_totales`) VALUES
(1, 1, 1, 1, 0),
(2, 1, 3, 2, 0),
(3, 1, 4, 3, 0),
(4, 2, 2, 1, 0),
(5, 2, 6, 2, 0),
(6, 2, 7, 3, 0),
(7, 2, 1, 4, 0),
(8, 2, 4, 5, 0),
(9, 2, 5, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `puntos_recinto`
--

CREATE TABLE `puntos_recinto` (
  `id` bigint UNSIGNED NOT NULL,
  `partida_id` bigint UNSIGNED NOT NULL,
  `usuario_id` int NOT NULL,
  `recinto_id` tinyint UNSIGNED NOT NULL,
  `puntos` int DEFAULT '0',
  `actualizado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `puntos_recinto`
--

INSERT INTO `puntos_recinto` (`id`, `partida_id`, `usuario_id`, `recinto_id`, `puntos`, `actualizado_en`) VALUES
(1, 1, 1, 1, 5, '2025-10-16 13:21:58'),
(2, 1, 3, 2, 4, '2025-10-16 13:21:58'),
(3, 1, 4, 3, 6, '2025-10-16 13:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `recintos_catalogo`
--

CREATE TABLE `recintos_catalogo` (
  `id` tinyint UNSIGNED NOT NULL,
  `clave` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recintos_catalogo`
--

INSERT INTO `recintos_catalogo` (`id`, `clave`, `descripcion`) VALUES
(1, 'bosque', 'Recinto de vegetación frondosa. Favorece variedad.'),
(2, 'llanura', 'Área abierta para grupos de la misma especie.'),
(3, 'montana', 'Zona rocosa; suele favorecer solitarios.'),
(4, 'rio_izquierda', 'Zona del río (margen izquierda) usada por restricciones.'),
(5, 'rio_derecha', 'Zona del río (margen derecha) usada por restricciones.'),
(6, 'pradera', 'Pradera para formar parejas del mismo tipo.'),
(7, 'comedor', 'Área central usada en algunas variantes/bonos.');

-- --------------------------------------------------------

--
-- Table structure for table `recintos_tablero`
--

CREATE TABLE `recintos_tablero` (
  `id` bigint UNSIGNED NOT NULL,
  `partida_id` bigint UNSIGNED NOT NULL,
  `usuario_id` int NOT NULL,
  `recinto_id` tinyint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recintos_tablero`
--

INSERT INTO `recintos_tablero` (`id`, `partida_id`, `usuario_id`, `recinto_id`) VALUES
(1, 1, 1, 1),
(2, 1, 3, 2),
(3, 1, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `rol` enum('jugador','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`rol`, `descripcion`) VALUES
('jugador', 'Usuario que participa de las partidas'),
('admin', 'Usuario con permisos de administración');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('jugador','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'jugador',
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `nickname`, `contrasena`, `rol`, `creado_en`, `deleted_at`) VALUES
(1, 'Admin Demo', 'admin_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-10-16 13:21:58', NULL),
(2, 'Seba', 'seba_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador', '2025-10-16 13:21:58', NULL),
(3, 'Tomi', 'tomi_demo_deleted_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador', '2025-10-16 13:21:58', '2025-10-16 19:17:36'),
(4, 'Nacho', 'nacho_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador', '2025-10-16 13:21:58', NULL),
(5, 'Joaco', 'joaco_demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador', '2025-10-16 13:21:58', NULL),
(6, 'Ignacio Fianza', 'ifianza', '$2y$12$5pEGovcmpN6FIQjLJmqs0Ok5umEoRawwVp7Gj3.ypZgh68YTg.3gy', 'admin', '2025-10-16 16:28:23', NULL),
(7, 'Carlos', 'carlos_deleted_7', '$2y$12$dLmflyvEa9CmN7O1AZTdN.LpT9mZ84BHP6xQLwknHf37Ah2EhVcrS', 'jugador', '2025-10-16 16:33:32', '2025-10-18 01:50:06');

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
  ADD UNIQUE KEY `uq_partida_usuario` (`partida_id`,`usuario_id`),
  ADD UNIQUE KEY `uq_partida_orden` (`partida_id`,`orden_mesa`),
  ADD KEY `fk_pj_usuario` (`usuario_id`);

--
-- Indexes for table `puntos_recinto`
--
ALTER TABLE `puntos_recinto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pr` (`partida_id`,`usuario_id`,`recinto_id`);

--
-- Indexes for table `recintos_catalogo`
--
ALTER TABLE `recintos_catalogo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indexes for table `recintos_tablero`
--
ALTER TABLE `recintos_tablero`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rt` (`partida_id`,`usuario_id`,`recinto_id`),
  ADD KEY `fk_rt_usuario` (`usuario_id`),
  ADD KEY `fk_rt_recinto` (`recinto_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD KEY `fk_usuarios_rol` (`rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `colocaciones`
--
ALTER TABLE `colocaciones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dinosaurios_catalogo`
--
ALTER TABLE `dinosaurios_catalogo`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `partida_jugadores`
--
ALTER TABLE `partida_jugadores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `puntos_recinto`
--
ALTER TABLE `puntos_recinto`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recintos_catalogo`
--
ALTER TABLE `recintos_catalogo`
  MODIFY `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `recintos_tablero`
--
ALTER TABLE `recintos_tablero`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `colocaciones`
--
ALTER TABLE `colocaciones`
  ADD CONSTRAINT `fk_col_dino` FOREIGN KEY (`tipo_dino`) REFERENCES `dinosaurios_catalogo` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_col_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_col_recinto` FOREIGN KEY (`recinto_id`) REFERENCES `recintos_catalogo` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_col_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `fk_partidas_creador` FOREIGN KEY (`creador_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `partida_jugadores`
--
ALTER TABLE `partida_jugadores`
  ADD CONSTRAINT `fk_pj_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pj_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `puntos_recinto`
--
ALTER TABLE `puntos_recinto`
  ADD CONSTRAINT `fk_pr_tablero` FOREIGN KEY (`partida_id`,`usuario_id`,`recinto_id`) REFERENCES `recintos_tablero` (`partida_id`, `usuario_id`, `recinto_id`) ON DELETE CASCADE;

--
-- Constraints for table `recintos_tablero`
--
ALTER TABLE `recintos_tablero`
  ADD CONSTRAINT `fk_rt_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rt_recinto` FOREIGN KEY (`recinto_id`) REFERENCES `recintos_catalogo` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rt_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`rol`) REFERENCES `roles` (`rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
