-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2026 a las 17:20:35
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `csv_db 7`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas_de_empleo`
--

CREATE TABLE `ofertas_de_empleo` (
  `Título` varchar(110) DEFAULT NULL,
  `Provincia` varchar(10) DEFAULT NULL,
  `Fecha publicación` varchar(10) DEFAULT NULL,
  `Descripción` varchar(8767) DEFAULT NULL,
  `ProvinciaAlternativa` varchar(15) DEFAULT NULL,
  `FuenteContenido` varchar(17) DEFAULT NULL,
  `ID Localidad` varchar(13) DEFAULT NULL,
  `Localidad` varchar(43) DEFAULT NULL,
  `Latitud` varchar(12) DEFAULT NULL,
  `Longitud` varchar(13) DEFAULT NULL,
  `Código localidad` varchar(2) DEFAULT NULL,
  `Identificador` bigint(13) DEFAULT NULL,
  `actualizacion de metadatos` varchar(10) DEFAULT NULL,
  `Enlace al contenido` varchar(110) DEFAULT NULL,
  `Posicion` varchar(29) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
