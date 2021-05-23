-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2021 a las 12:23:56
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bassclef`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conversacion`
--

CREATE TABLE `conversacion` (
  `id` int(11) NOT NULL,
  `ultimo_mensaje_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210325113949', '2021-03-25 12:40:09', 583),
('DoctrineMigrations\\Version20210325114851', '2021-03-25 12:48:59', 166);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id` int(11) NOT NULL,
  `conversacion_id` int(11) DEFAULT NULL,
  `contenido` longtext NOT NULL,
  `creado_el` datetime(6) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante`
--

CREATE TABLE `participante` (
  `id` int(11) NOT NULL,
  `conversacion_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_interes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `email`, `roles`, `password`, `nombre`, `apellidos`, `foto`, `datos_interes`, `telefono`) VALUES
(1, 'd@d.com', '[\"ROLE_MUSICO\"]', '$argon2id$v=19$m=65536,t=4,p=1$WmpoM0F2ancudUJJTmRMYQ$TOX9xQ8K3KmcYLIxe7EP4+e0rKO/dukxg3N+FNUrpTc', 'David', 'Rebato Diana', '604caf7775bbd.jpg', 'fdghjfgkfghkfdgjdfgj\r\nHioolasdfjkasdfjkh', '625010025'),
(2, 'banda@b.com', '[\"ROLE_BANDA\"]', '$argon2id$v=19$m=65536,t=4,p=1$bnQ1SVFjN0dWVmx4V2p4Vg$Js3RxIRrd0Zc+uURsUcsJWaiyXK0jaL1wrpJNy0jeLs', 'Tres Caídas de Triana', NULL, '604cd6ab39929.jpg', NULL, NULL),
(3, 'a@o.com', '[\"ROLE_MUSICO\"]', '$argon2id$v=19$m=65536,t=4,p=1$NzE3enNpR0xpOG1USi5HTg$ZaWi3mCMj7wn7D8fIuiVXIWka6egIZQT/SDQUSC5+kc', 'Andrea', 'Ortega', 'pomerania.png', NULL, '623456789'),
(4, 'c@r.com', '[\"ROLE_MUSICO\"]', '$argon2id$v=19$m=65536,t=4,p=1$NXFSTG5LMGpYdHlFakYyeQ$t4+0xnrwI/i1iCIOykEnwjXRQQ3k13gwPPtkrvtDjKw', 'Cristino', 'Rebato Diana', '604dd56504a65.jpg', 'Soy el mejor músico dle mundo', '623456789'),
(5, 'a@d.com', '[\"ROLE_MUSICO\",\"ROLE_BANDA\"]', '$argon2id$v=19$m=65536,t=4,p=1$OS9lcHc3V0g5cGlVRE5mUA$52zoZkONrabUavhHHXZFDRUW0fNmt1h7W+BWFWW8TN0', 'Amalia', 'Diana Alaminos', '604de0f322f7d.png', 'Soy la mejor directora del mundo.', '623456789'),
(6, 'c@l.com', '[\"ROLE_MUSICO\"]', '$argon2id$v=19$m=65536,t=4,p=1$SkRNTFFNMjNrN25Gem9OZQ$p5DY3adRXoqM48SLvrMeF9AmtHjXQl0FnP4BCfe8ua0', 'Cristino', 'Rebato Lara', '604de371bb733.jpg', 'asdgasghdhrdsfhsdfhsdfhasgasg', '623456789'),
(7, 'd@ro.com', '[\"ROLE_MUSICO\"]', '1234', 'David', 'Rodriguez', 'user.jpg', NULL, '623456789'),
(8, 'agjh@jkgf.com', '{\"ROLE_MUSICO\":\"ROLE_MUSICO\"}', '1234', 'shdfjksd', 'hjgajkh', 'C:\\fakepath\\user.png', NULL, '623456789'),
(9, 'causdyh@gajk.com', '[\"ROLE_MUSICO\"]', '1234', 'jkgajkdhs', 'aasdg', 'C:\\fakepath\\pomerania.png', NULL, '623456789'),
(10, 'pepe@p.com', '[\"ROLE_MUSICO\"]', '1234', 'Pepe', 'Pepon', 'C:\\fakepath\\user.png', NULL, '623456789'),
(11, 'manolo@m.com', '[\"ROLE_MUSICO\"]', '1234', 'Manolo', 'Gafotas', 'C:\\fakepath\\pomerania.png', NULL, '623456789');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `codigo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `video`
--

INSERT INTO `video` (`id`, `usuario_id`, `codigo`) VALUES
(1, 1, '-CGKIBwe83c'),
(2, 1, 'o0DoTL54DXo'),
(6, 1, 'MeWGJWZ_jVo'),
(7, 4, 'PPkFMtrinZU'),
(8, 5, '09R8_2nJtjg'),
(9, 6, 'C8fRNNg5pgc');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `conversacion`
--
ALTER TABLE `conversacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_474049CF3EBCB904` (`ultimo_mensaje_id`),
  ADD KEY `ultimo_mensaje_id_index` (`ultimo_mensaje_id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9B631D01DB38439E` (`usuario_id`),
  ADD KEY `IDX_9B631D01ABD5A1D6` (`conversacion_id`),
  ADD KEY `creado_el_index` (`creado_el`);

--
-- Indices de la tabla `participante`
--
ALTER TABLE `participante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_85BDC5C3DB38439E` (`usuario_id`),
  ADD KEY `IDX_85BDC5C3ABD5A1D6` (`conversacion_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_2265B05DE7927C74` (`email`);

--
-- Indices de la tabla `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CC7DA2CDB38439E` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conversacion`
--
ALTER TABLE `conversacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `participante`
--
ALTER TABLE `participante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `conversacion`
--
ALTER TABLE `conversacion`
  ADD CONSTRAINT `FK_474049CF3EBCB904` FOREIGN KEY (`ultimo_mensaje_id`) REFERENCES `mensaje` (`id`);

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `FK_9B631D01ABD5A1D6` FOREIGN KEY (`conversacion_id`) REFERENCES `conversacion` (`id`),
  ADD CONSTRAINT `FK_9B631D01DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `participante`
--
ALTER TABLE `participante`
  ADD CONSTRAINT `FK_85BDC5C3ABD5A1D6` FOREIGN KEY (`conversacion_id`) REFERENCES `conversacion` (`id`),
  ADD CONSTRAINT `FK_85BDC5C3DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2CDB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
