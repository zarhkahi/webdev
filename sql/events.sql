-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2018 at 01:54 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `events`
--

-- --------------------------------------------------------

--
-- Table structure for table `actividades`
--

CREATE TABLE `actividades` (
  `id_actividad` int(10) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `actividades`
--

INSERT INTO `actividades` (`id_actividad`, `fecha`, `id_usuario`) VALUES
(1, '2018-04-29 18:42:30', 5),
(2, '2018-04-30 00:03:56', 4),
(3, '2018-04-30 00:04:30', 4),
(4, '2018-04-30 00:05:23', 6),
(5, '2018-04-30 00:06:03', 7),
(6, '2018-04-30 00:06:46', 8);

-- --------------------------------------------------------

--
-- Table structure for table `asistentes`
--

CREATE TABLE `asistentes` (
  `id_usuario` int(10) NOT NULL,
  `id_evento` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asistira`
--

CREATE TABLE `asistira` (
  `id_actividad` int(10) NOT NULL,
  `id_evento` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comenta`
--

CREATE TABLE `comenta` (
  `id_actividad` int(10) NOT NULL,
  `descripcion` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crea`
--

CREATE TABLE `crea` (
  `id_actividad` int(10) NOT NULL,
  `id_evento` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `crea`
--

INSERT INTO `crea` (`id_actividad`, `id_evento`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(10) NOT NULL,
  `nombre` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `lugar` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `precio` int(5) DEFAULT NULL,
  `descripcion` varchar(60) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_usuario` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`id_evento`, `nombre`, `fecha`, `lugar`, `precio`, `descripcion`, `id_usuario`) VALUES
(1, 'Imagen', '2018-04-30', 'A', 12, 'A', 5),
(2, 'BEvents', '2018-04-30', 'Ml', 50000, 'AA', 4),
(3, 'CasTTT', '2018-04-30', 'Ad', 122, 'As', 4),
(4, 'Handover', '2018-04-30', 'LA MONIKA', 300, 'FF', 6),
(5, 'One More', '2018-04-30', 'Duff', 123, 'SWE', 7),
(6, 'ANOTHER ONE', '2018-04-30', 'SSS', 12345, '123.', 8);

-- --------------------------------------------------------

--
-- Table structure for table `imagen`
--

CREATE TABLE `imagen` (
  `id_imagen` int(10) NOT NULL,
  `id_evento` int(10) NOT NULL,
  `tipo` char(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `imagen` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(10) NOT NULL,
  `nombre` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `seguidores`
--

CREATE TABLE `seguidores` (
  `id_usuario` int(10) NOT NULL,
  `id_siguiendo` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sigue`
--

CREATE TABLE `sigue` (
  `id_actividad` int(10) NOT NULL,
  `id_usuario` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) NOT NULL,
  `nombre` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellidos` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(70) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_rol` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellidos`, `password`, `fecha`, `email`, `id_rol`) VALUES
(1, 'User', 'U.', '$2y$10$0eR.KhfTH5ybn/jlB86hwe/1nQeCKXk2RcLEjBscJbpUaF504kSOi', '2018-04-10 00:00:00', 'user@example.org', 1),
(2, 'Admin', 'A.', '$2y$10$0eR.KhfTH5ybn/jlB86hwe/1nQeCKXk2RcLEjBscJbpUaF504kSOi', '2018-04-10 00:00:00', 'admin@example.org', 2),
(3, 'A', 'abc', '$2y$10$zmvIDu5j5SfTh4Hl05D.VuOY.6ZVpTGYrjZ.UBRJNgf6NizzbTTKm', '2018-04-25 17:23:46', 'a@a.com', 1),
(4, 'B', 'bcd', '$2y$10$GZAc44PUOy/SSFULVRnCFO586q1Amyn7G9qW4MQ4d3rtqm.zwlJyO', '2018-04-25 17:39:06', 'b@b.com', 1),
(5, 'abcd', 'abcd', '$2y$10$rQ6ztUyTwY6wFY07Qh813.OJxgse4DwhNCqFP2yX76iLPXvIz2zK.', '2018-04-25 17:43:59', 'c@c.com', 1),
(6, 'qwer', 'wert', '$2y$10$XAYDlnFqg2OBbacg97Tv4.cgszigmzDsc1RRj4BXnEohunZCyTiMy', '2018-04-25 17:44:18', 'd@d.com', 1),
(7, 'abcfg', 'fgh', '$2y$10$H.IMflNnvr9TZ84Brem41eZsFtEGeK4SVwz62LUkzrX1LlwFWHQtK', '2018-04-25 17:44:49', 'g@g.com', 1),
(8, 'abchj', 'asd', '$2y$10$N1849RexYzU2p867Z0YjH.75MMOeXi8UUtQofWEQA8CuhPsdze6.K', '2018-04-25 17:45:13', 'n@n.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `asistentes`
--
ALTER TABLE `asistentes`
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Indexes for table `asistira`
--
ALTER TABLE `asistira`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Indexes for table `comenta`
--
ALTER TABLE `comenta`
  ADD PRIMARY KEY (`id_actividad`);

--
-- Indexes for table `crea`
--
ALTER TABLE `crea`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `imagen`
--
ALTER TABLE `imagen`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indexes for table `seguidores`
--
ALTER TABLE `seguidores`
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_siguiendo` (`id_siguiendo`);

--
-- Indexes for table `sigue`
--
ALTER TABLE `sigue`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id_actividad` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `imagen`
--
ALTER TABLE `imagen`
  MODIFY `id_imagen` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `asistentes`
--
ALTER TABLE `asistentes`
  ADD CONSTRAINT `asistentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `asistentes_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`);

--
-- Constraints for table `asistira`
--
ALTER TABLE `asistira`
  ADD CONSTRAINT `asistira_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`),
  ADD CONSTRAINT `asistira_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`);

--
-- Constraints for table `comenta`
--
ALTER TABLE `comenta`
  ADD CONSTRAINT `comenta_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`);

--
-- Constraints for table `crea`
--
ALTER TABLE `crea`
  ADD CONSTRAINT `crea_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`),
  ADD CONSTRAINT `crea_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`);

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `imagen`
--
ALTER TABLE `imagen`
  ADD CONSTRAINT `imagen_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`);

--
-- Constraints for table `seguidores`
--
ALTER TABLE `seguidores`
  ADD CONSTRAINT `seguidores_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `seguidores_ibfk_2` FOREIGN KEY (`id_siguiendo`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `sigue`
--
ALTER TABLE `sigue`
  ADD CONSTRAINT `sigue_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`),
  ADD CONSTRAINT `sigue_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
