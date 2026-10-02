-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql209.infinityfree.com
-- Tiempo de generación: 02-10-2026 a las 06:03:23
-- Versión del servidor: 11.4.13-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_42999121_bookify`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Ficción'),
(2, 'Fantasía'),
(3, 'Ciencia'),
(4, 'Historia'),
(5, 'Arte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `autor` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor`, `descripcion`, `anio`, `imagen`, `id_categoria`) VALUES
(1, '1984', 'George Orwell', 'Una novela distópica sobre una sociedad sometida a una vigilancia constante.', 1949, '1984.jpg', 1),
(2, 'Orgullo y prejuicio', 'Jane Austen', 'Una novela sobre las relaciones, las diferencias sociales y las expectativas de la sociedad.', 1813, 'orgullo-prejuicio.jpg', 1),
(3, 'El Hobbit', 'J. R. R. Tolkien', 'Bilbo Bolsón emprende una aventura inesperada junto a un grupo de enanos.', 1937, 'hobbit.jpg', 2),
(4, 'Harry Potter y la piedra filosofal', 'J. K. Rowling', 'El comienzo de la historia de Harry Potter en el mundo mágico.', 1997, 'harry-potter.jpg', 2),
(5, 'Breves respuestas a las grandes preguntas', 'Stephen Hawking', 'Reflexiones sobre el universo, la ciencia y algunas de las grandes preguntas de la humanidad.', 2018, 'breves-respuestas.jpg', 3),
(6, 'Una breve historia del tiempo', 'Stephen Hawking', 'Una introducción a algunas de las grandes cuestiones de la física y del universo.', 1988, 'historia-tiempo.jpg', 3),
(7, 'Sapiens', 'Yuval Noah Harari', 'Un recorrido por la historia de la humanidad desde sus orígenes.', 2011, 'sapiens.jpg', 4),
(8, 'El arte de la guerra', 'Sun Tzu', 'Tratado clásico sobre estrategia y pensamiento militar.', -500, 'arte-guerra.jpg', 4),
(9, 'Historia del arte', 'E. H. Gombrich', 'Recorrido por la evolución de las principales manifestaciones artísticas.', 1950, 'historia-arte.jpg', 5),
(11, 'El amor en los tiempos del cólera', 'Gabriel García Márquez', 'Una historia de amor que atraviesa décadas de espera, encuentros y desencuentros.', 1985, 'el-amor-en-los-tiempos-del-colera.jpg', 1),
(12, 'La casa de los espíritus', 'Isabel Allende', 'Una saga familiar marcada por el amor, los conflictos políticos y los acontecimientos sobrenaturales.', 1982, 'la-casa-de-los-espiritus.jpg', 1),
(13, '1984', 'George Orwell', 'Una novela distópica sobre una sociedad sometida a una vigilancia y un control constantes.', 1949, '1984.jpg', 1),
(14, 'El jardín de las palabras', 'Makoto Shinkai', 'Una historia sobre un encuentro inesperado y la conexión entre dos personas durante los días de lluvia.', 2013, 'el-jardin-de-las-palabras.jpg', 2),
(15, 'Dune', 'Frank Herbert', 'Una aventura de ciencia ficción ambientada en un planeta desértico cuyo recurso más valioso está en el centro de una lucha de poder.', 1965, 'dune.jpg', 2),
(16, 'El universo en una cáscara de nuez', 'Stephen Hawking', 'Una introducción divulgativa a algunas de las grandes ideas de la física y la cosmología moderna.', 2001, 'el-universo-en-una-cascara-de-nuez.jpg', 3),
(17, 'Homo Deus', 'Yuval Noah Harari', 'Una reflexión sobre el futuro de la humanidad, la tecnología y los cambios que pueden transformar nuestra sociedad.', 2015, 'homo-deus.jpg', 3),
(18, 'Los pilares de la Tierra', 'Ken Follett', 'Una novela histórica ambientada en la Inglaterra del siglo XII alrededor de la construcción de una catedral.', 1989, 'los-pilares-de-la-tierra.jpg', 4),
(19, 'La joven de la perla', 'Tracy Chevalier', 'Novela inspirada en el famoso cuadro de Johannes Vermeer y ambientada en la Holanda del siglo XVII.', 1999, 'la-joven-de-la-perla.jpg', 5),
(20, 'El retrato de Dorian Gray', 'Oscar Wilde', 'Una novela sobre la belleza, la juventud y las consecuencias de una vida entregada al placer.', 1890, 'el-retrato-de-dorian-gray.jpg', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion` date NOT NULL,
  `fuera_de_plazo` tinyint(1) NOT NULL DEFAULT 0,
  `estado` varchar(20) NOT NULL DEFAULT 'activo'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `id_usuario`, `id_libro`, `fecha_prestamo`, `fecha_devolucion`, `fuera_de_plazo`, `estado`) VALUES
(1, 1, 7, '2026-09-29', '2026-10-14', 0, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'usuario'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena`, `rol`) VALUES
(1, 'laura', 'laura@gmail.com', '$2y$12$OzR/vWzT8LEreOXOpQdcu.CTdfnqM5y2X8920eNut.xfFrKnwW6I6', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_libros_categoria` (`id_categoria`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prestamos_usuario` (`id_usuario`),
  ADD KEY `fk_prestamos_libro` (`id_libro`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
