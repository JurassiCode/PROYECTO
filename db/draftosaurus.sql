-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2025 at 01:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `draftosaurus_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `colocaciones`
--

CREATE TABLE `colocaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partida_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `recinto_id` tinyint(3) UNSIGNED NOT NULL,
  `tipo_dino` smallint(5) UNSIGNED NOT NULL,
  `pts_obtenidos` int(11) NOT NULL DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dinosaurios_catalogo`
--

CREATE TABLE `dinosaurios_catalogo` (
  `id_dino` smallint(5) UNSIGNED NOT NULL,
  `nombre_corto` varchar(50) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lotes_turno`
--

CREATE TABLE `lotes_turno` (
  `id_lote` bigint(20) UNSIGNED NOT NULL,
  `partida_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `ronda_final` tinyint(3) UNSIGNED DEFAULT NULL,
  `turno_final` tinyint(3) UNSIGNED DEFAULT NULL,
  `dado_final` varchar(50) DEFAULT NULL,
  `estado_final` enum('config','en_curso','cerrada') DEFAULT NULL,
  `cantidad_moves` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partidas`
--

CREATE TABLE `partidas` (
  `id_partida` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `estado` enum('config','en_curso','cerrada') NOT NULL DEFAULT 'config',
  `creador_id` int(11) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partidas`
--

INSERT INTO `partidas` (`id_partida`, `nombre`, `estado`, `creador_id`, `creado_en`) VALUES
(1, 'Sala1', 'cerrada', 30, '2025-09-04 13:30:24'),
(2, 'sala1', 'config', 30, '2025-09-05 12:45:30'),
(3, '1', 'cerrada', 28, '2025-09-12 18:17:01'),
(4, '1', 'config', 28, '2025-09-12 18:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `partida_jugadores`
--

CREATE TABLE `partida_jugadores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partida_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `orden_mesa` tinyint(3) UNSIGNED NOT NULL,
  `puntos_totales` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partida_jugadores`
--

INSERT INTO `partida_jugadores` (`id`, `partida_id`, `usuario_id`, `orden_mesa`, `puntos_totales`) VALUES
(1, 1, 30, 1, 0),
(2, 1, 28, 2, 0),
(3, 1, 29, 3, 0),
(4, 1, 31, 4, 0),
(5, 1, 32, 5, 0),
(6, 1, 33, 6, 0),
(7, 2, 29, 1, 0),
(8, 2, 28, 2, 0),
(9, 2, 31, 3, 0),
(10, 2, 32, 4, 0),
(11, 2, 33, 5, 0),
(12, 2, 30, 6, 0),
(13, 3, 28, 1, 0),
(14, 4, 28, 1, 0),
(15, 4, 29, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `puntos_recinto`
--

CREATE TABLE `puntos_recinto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partida_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `recinto_id` tinyint(3) UNSIGNED NOT NULL,
  `puntos` int(11) NOT NULL DEFAULT 0,
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `puntos_recinto`
--
DELIMITER $$
CREATE TRIGGER `trg_pr_delete` AFTER DELETE ON `puntos_recinto` FOR EACH ROW UPDATE partida_jugadores
   SET puntos_totales = fn_sumar_puntos_jugador(OLD.partida_id, OLD.usuario_id)
 WHERE partida_id = OLD.partida_id AND usuario_id = OLD.usuario_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_pr_insert` AFTER INSERT ON `puntos_recinto` FOR EACH ROW UPDATE partida_jugadores
   SET puntos_totales = fn_sumar_puntos_jugador(NEW.partida_id, NEW.usuario_id)
 WHERE partida_id = NEW.partida_id AND usuario_id = NEW.usuario_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_pr_update` AFTER UPDATE ON `puntos_recinto` FOR EACH ROW UPDATE partida_jugadores
   SET puntos_totales = fn_sumar_puntos_jugador(NEW.partida_id, NEW.usuario_id)
 WHERE partida_id = NEW.partida_id AND usuario_id = NEW.usuario_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `recintos`
--

CREATE TABLE `recintos` (
  `id_recinto` tinyint(3) UNSIGNED NOT NULL,
  `clave` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recintos_tablero`
--

CREATE TABLE `recintos_tablero` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partida_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `recinto_id` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `rol` enum('jugador','admin') NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`rol`, `descripcion`) VALUES
('jugador', 'Usuario participante'),
('admin', 'Usuario administrador');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('jugador','admin') NOT NULL DEFAULT 'jugador',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `usuario`, `contrasena`, `rol`, `creado_en`, `deleted_at`) VALUES
(28, 'Ignacio Fianza', 'ifianza', '$2y$12$fbzxXyKSPu5tcPhn7hP/hOkRZn0kGOqcSzYri3DM0yekCWg9wZf4K', 'admin', '2025-08-22 17:13:07', NULL),
(29, 'Sebastián Benitez', 'seba', '$2y$12$SnD.BfNP3dXfEee.oIiVLuHpGVQGdc1EBse.foWM1gJcDFDIqoXLe', 'jugador', '2025-09-04 13:13:37', NULL),
(30, 'Usuario', 'usuario', '$2y$12$DnjTVsVkcxtkuAGBobiNYu9PeWXvUVV07s3lr5HStOKdJIC31ep9a', 'jugador', '2025-09-04 13:17:54', NULL),
(31, 'Admin', 'admin', '$2y$12$WA6l2dlKgoDWF1kTcbPGYuxy8JrZ9eIGQyrJTiji9RNOlGTMJhCmS', 'admin', '2025-09-04 13:18:08', NULL),
(32, 'Tomás Paz', 'tomi', '$2y$12$6hAVn65vo8nPMbsOf2zBNeM7uVvTvINfSPw3.rTpDd3.jah1VMQDe', 'jugador', '2025-09-04 13:24:31', NULL),
(33, 'Joaquín Fleitas', 'joaco', '$2y$12$qS5TatNtchAyUfSWFAYEWuqQ6J8/LEgUg4buzsJ5Td2S9TZmmYi9q', 'jugador', '2025-09-04 13:24:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `colocaciones`
--
ALTER TABLE `colocaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_col` (`partida_id`,`usuario_id`,`creado_en`),
  ADD KEY `fk_col_usuario` (`usuario_id`),
  ADD KEY `fk_col_dino` (`tipo_dino`),
  ADD KEY `fk_col_recinto` (`recinto_id`),
  ADD KEY `fk_col_tablero` (`partida_id`,`usuario_id`,`recinto_id`);

--
-- Indexes for table `dinosaurios_catalogo`
--
ALTER TABLE `dinosaurios_catalogo`
  ADD PRIMARY KEY (`id_dino`),
  ADD UNIQUE KEY `nombre_corto` (`nombre_corto`);

--
-- Indexes for table `lotes_turno`
--
ALTER TABLE `lotes_turno`
  ADD PRIMARY KEY (`id_lote`),
  ADD KEY `fk_lt_partida` (`partida_id`),
  ADD KEY `fk_lt_usuario` (`usuario_id`);

--
-- Indexes for table `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `fk_partidas_creador` (`creador_id`),
  ADD KEY `idx_partidas_estado` (`estado`);

--
-- Indexes for table `partida_jugadores`
--
ALTER TABLE `partida_jugadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pj_partida_usuario` (`partida_id`,`usuario_id`),
  ADD UNIQUE KEY `uq_pj_partida_orden` (`partida_id`,`orden_mesa`),
  ADD KEY `fk_pj_usuario` (`usuario_id`);

--
-- Indexes for table `puntos_recinto`
--
ALTER TABLE `puntos_recinto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pr` (`partida_id`,`usuario_id`,`recinto_id`);

--
-- Indexes for table `recintos`
--
ALTER TABLE `recintos`
  ADD PRIMARY KEY (`id_recinto`),
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
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `fk_usuarios_rol` (`rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `colocaciones`
--
ALTER TABLE `colocaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dinosaurios_catalogo`
--
ALTER TABLE `dinosaurios_catalogo`
  MODIFY `id_dino` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lotes_turno`
--
ALTER TABLE `lotes_turno`
  MODIFY `id_lote` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id_partida` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `partida_jugadores`
--
ALTER TABLE `partida_jugadores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `puntos_recinto`
--
ALTER TABLE `puntos_recinto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recintos`
--
ALTER TABLE `recintos`
  MODIFY `id_recinto` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recintos_tablero`
--
ALTER TABLE `recintos_tablero`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `colocaciones`
--
ALTER TABLE `colocaciones`
  ADD CONSTRAINT `fk_col_dino` FOREIGN KEY (`tipo_dino`) REFERENCES `dinosaurios_catalogo` (`id_dino`),
  ADD CONSTRAINT `fk_col_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id_partida`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_col_recinto` FOREIGN KEY (`recinto_id`) REFERENCES `recintos` (`id_recinto`),
  ADD CONSTRAINT `fk_col_tablero` FOREIGN KEY (`partida_id`,`usuario_id`,`recinto_id`) REFERENCES `recintos_tablero` (`partida_id`, `usuario_id`, `recinto_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_col_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `lotes_turno`
--
ALTER TABLE `lotes_turno`
  ADD CONSTRAINT `fk_lt_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id_partida`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lt_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `fk_partidas_creador` FOREIGN KEY (`creador_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `partida_jugadores`
--
ALTER TABLE `partida_jugadores`
  ADD CONSTRAINT `fk_pj_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id_partida`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pj_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `puntos_recinto`
--
ALTER TABLE `puntos_recinto`
  ADD CONSTRAINT `fk_pr` FOREIGN KEY (`partida_id`,`usuario_id`,`recinto_id`) REFERENCES `recintos_tablero` (`partida_id`, `usuario_id`, `recinto_id`) ON DELETE CASCADE;

--
-- Constraints for table `recintos_tablero`
--
ALTER TABLE `recintos_tablero`
  ADD CONSTRAINT `fk_rt_partida` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id_partida`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rt_recinto` FOREIGN KEY (`recinto_id`) REFERENCES `recintos` (`id_recinto`),
  ADD CONSTRAINT `fk_rt_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`rol`) REFERENCES `roles` (`rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
