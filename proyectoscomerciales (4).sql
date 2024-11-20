-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2024 a las 12:40:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectoscomerciales`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etapas`
--

CREATE TABLE `etapas` (
  `id` int(11) NOT NULL,
  `proyecto_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `propuesta` text DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'pendiente',
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `etapas`
--

INSERT INTO `etapas` (`id`, `proyecto_id`, `nombre`, `propuesta`, `monto`, `fecha`, `estado`, `archivo`) VALUES
(2, 12, 'empezar', 'nbnb', 54545.00, '7777-04-04', 'pendiente', NULL),
(3, 10, 'resto', 'bbb', 7657.00, '7777-07-07', 'pendiente', NULL),
(4, 18, 'etpanumerox', 'bvbv', 989.00, '1111-11-11', 'pendiente', NULL),
(5, 18, 'kkkkk', 'llll', 55656.00, '1111-11-11', 'pendiente', 'Proyecto C_detalles.pdf'),
(6, 18, 'jkjk', 'klk', -0.03, '6666-06-06', 'pendiente', 'Inicio_etapa.pdf'),
(7, 18, 'jkjk', 'klk', -0.03, '6666-06-06', 'pendiente', 'proyecto comerciales.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `contrato` varchar(255) DEFAULT NULL,
  `propuesta` text DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `fecha_de_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_de_aprobacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `estado`, `contrato`, `propuesta`, `monto`, `fecha_creacion`, `fecha_aprobacion`, `fecha_de_creacion`, `fecha_de_aprobacion`) VALUES
(5, 'Proyecto E', 'Pendiente', 'Contrato E', 'Propuesta E', 30000.00, '2024-10-30 11:54:25', NULL, '2024-10-30 12:36:04', NULL),
(6, 'daniel', 'pendiente', 'rappi', 'exclavitud', 12.00, '2024-10-30 13:02:17', NULL, '2024-10-30 13:02:17', NULL),
(10, 'papu lince', 'pendiente', 'skibidi', 'toilet', 69.00, '2024-11-05 11:52:33', NULL, '2024-11-05 11:52:33', NULL),
(11, 'Proyecto X', 'pendiente', 'X', 'X', 10.00, '2024-11-05 11:58:03', NULL, '2024-11-05 11:58:03', NULL),
(12, 'proyectonuevo', 'pendiente', 'paulolondra', 'estafa piramidal', 1.00, '2024-11-13 11:36:25', NULL, '2024-11-13 11:36:25', NULL),
(13, 'jijjosdas', 'aprobado', 'asdsadg', 'rrt', 1233214.00, '2024-11-13 11:57:26', NULL, '2024-11-13 11:57:26', NULL),
(14, 'yio', 'aprobado', 'oiy', 'iuo', 777.00, '2024-11-13 11:58:44', NULL, '2024-11-13 11:58:44', NULL),
(15, 'hghg', 'pendiente', 'mmm', 'mmmm', 666.00, '2024-11-13 12:15:25', NULL, '2024-11-13 12:15:25', NULL),
(16, 'jkjkjkkjkjkjkj', 'pendiente', 'zzzzzzzzzzzzzzzzzzzzzzzzzzz', 'pum', 909.00, '2024-11-13 12:19:50', NULL, '2024-11-13 12:19:50', NULL),
(17, 'santi', 'Pendiente', 'kjkh', 'kjhkhk', 1256.00, '2024-11-13 13:18:50', NULL, '2024-11-13 13:18:50', NULL),
(18, 'xxl', 'Pendiente', 'bnbn', 'ghgh', 546.00, '2024-11-13 13:23:19', NULL, '2024-11-13 13:23:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos_asignados`
--

CREATE TABLE `proyectos_asignados` (
  `id` int(11) NOT NULL,
  `proyecto_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos_asignados`
--

INSERT INTO `proyectos_asignados` (`id`, `proyecto_id`, `usuario_id`) VALUES
(6, 6, 2),
(7, 10, 5),
(8, 11, 6),
(9, 5, 5),
(10, 12, 6),
(11, 15, 6),
(13, 14, 5),
(14, 13, 6),
(15, 17, 6),
(16, 18, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'xd', 'admin'),
(2, 'usuario', 'nashe', 'user'),
(5, 'usuario2', 'xd2', 'user'),
(6, 'usuario3', 'xd3', 'user');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `etapas`
--
ALTER TABLE `etapas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proyecto_id` (`proyecto_id`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyectos_asignados`
--
ALTER TABLE `proyectos_asignados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proyecto_id` (`proyecto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `etapas`
--
ALTER TABLE `etapas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `proyectos_asignados`
--
ALTER TABLE `proyectos_asignados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `etapas`
--
ALTER TABLE `etapas`
  ADD CONSTRAINT `etapas_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `proyectos_asignados`
--
ALTER TABLE `proyectos_asignados`
  ADD CONSTRAINT `proyectos_asignados_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`),
  ADD CONSTRAINT `proyectos_asignados_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
