-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-10-2018 a las 23:12:45
-- Versión del servidor: 10.1.34-MariaDB
-- Versión de PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdcarritocompras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--
-- comenzamos cargando 5-10 notebooks y dejamos una carpeta con imagenes
CREATE TABLE `producto` (
  `idproducto` bigint(20) NOT NULL,
  `pronombre` varchar(100) NOT NULL,
  `prodetalle` varchar(512) NOT NULL,
  `procantstock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `idcompra` bigint(20) NOT NULL,
  `cofecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idusuario` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraestado`
--

CREATE TABLE `compraestado` (
  `idcompraestado` bigint(20) UNSIGNED NOT NULL,
  `idcompra` bigint(11) NOT NULL,
  `idcompraestadotipo` int(11) NOT NULL,
  `cefechaini` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cefechafin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraestadotipo`
--

CREATE TABLE `compraestadotipo` (
  `idcompraestadotipo` int(11) NOT NULL,
  `cetdescripcion` varchar(50) NOT NULL,
  `cetdetalle` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `compraestadotipo`
--

INSERT INTO `compraestadotipo` (`idcompraestadotipo`, `cetdescripcion`, `cetdetalle`) VALUES
(1, 'iniciada', 'cuando el usuario : cliente inicia la compra de uno o mas productos del carrito'), --cliente le da a aceptar compra
(2, 'aceptada', 'cuando el usuario administrador da ingreso a uno de las compras en estado = 1 '), --deposito indica que tiene la orden lista
(3, 'enviada', 'cuando el usuario administrador envia a uno de las compras en estado =2 '), --deposito
(4, 'cancelada', 'un usuario administrador podra cancelar una compra en cualquier estado y un usuario cliente solo en estado=1 '); --deposito

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraitem`
--

CREATE TABLE `compraitem` (
  `idcompraitem` bigint(20) UNSIGNED NOT NULL,
  `idproducto` bigint(20) NOT NULL,
  `idcompra` bigint(20) NOT NULL,
  `cicantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `idmenu` bigint(20) NOT NULL,
  `menombre` varchar(50) NOT NULL COMMENT 'Nombre del item del menu',
  `medescripcion` varchar(124) NOT NULL COMMENT 'Descripcion mas detallada del item del menu',
  `idpadre` bigint(20) DEFAULT NULL COMMENT 'Referencia al id del menu que es subitem',
  `medeshabilitado` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha en la que el menu fue deshabilitado por ultima vez'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `menu` subi solo las opciones para el cliente
-- esta serian en un principio para el cliente
--

INSERT INTO `menu` (`idmenu`, `menombre`, `medescripcion`, `idpadre`, `medeshabilitado`) VALUES
(1, 'Home', 'home.php', NULL, NULL),
(2, 'Productos', 'productos.php', NULL, NULL),
(3, 'Contacto', 'contacto.php', NULL, NULL),
(4, 'Carrito', 'carrito.php', NULL, NULL),
(5, 'Mi Cuenta', 'cuenta.php', NULL, NULL),

(6, 'Stock', 'stock.php', NULL, NULL),
(7, 'Ordenes', 'ordenes.php', NULL, NULL),
(8, 'Shipping', 'shipping.php', NULL, NULL),

(9, 'Usuarios', 'usuarios.php', NULL, NULL),
(10, 'Configuraciones', 'configuraciones.php', NULL, NULL),
(11, 'Asignar Roles', 'asignarRoles.php', NULL, NULL),

(12, 'Cerrar Sesión', '../Action/logout.php', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menurol`
--

CREATE TABLE `menurol` (
  `idmenu` bigint(20) NOT NULL,
  `idrol` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Volcado de datos para la tabla `menurol` en este caso para las opciones de cliente id 3 es igual a rol cliente
-- primero le hacemos el insert del menu ya que este insert busca el menu segun el nombre
--


INSERT INTO `menurol` (`idmenu`, `idrol`) VALUES
((SELECT idmenu FROM menu WHERE menombre = 'Home'), 3),
((SELECT idmenu FROM menu WHERE menombre = 'Productos'), 3),
((SELECT idmenu FROM menu WHERE menombre = 'Productos'), 1),
((SELECT idmenu FROM menu WHERE menombre = 'Contacto'), 3),
((SELECT idmenu FROM menu WHERE menombre = 'Carrito'), 3),
((SELECT idmenu FROM menu WHERE menombre = 'Mi Cuenta'), 3),
((SELECT idmenu FROM menu WHERE menombre = 'Stock'), 2),
((SELECT idmenu FROM menu WHERE menombre = 'Ordenes'), 2),
((SELECT idmenu FROM menu WHERE menombre = 'Ordenes'), 1),
((SELECT idmenu FROM menu WHERE menombre = 'Shipping'), 2),
((SELECT idmenu FROM menu WHERE menombre = 'Usuarios'), 1),
((SELECT idmenu FROM menu WHERE menombre = 'Configuraciones'), 1),
((SELECT idmenu FROM menu WHERE menombre = 'Asignar Roles'), 1),
((SELECT idmenu FROM menu WHERE menombre = 'Cerrar Sesión'), 1),
((SELECT idmenu FROM menu WHERE menombre = 'Cerrar Sesión'), 2),
((SELECT idmenu FROM menu WHERE menombre = 'Cerrar Sesión'), 3);



-- --------------------------------------------------------


--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` bigint(20) NOT NULL,
  `rodescripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `rol` (`idrol`, `rodescripcion`) 
VALUES
(1, 'Administrador'),
(2, 'Deposito'),
(3, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` bigint(20) NOT NULL,
  `usnombre` varchar(50) NOT NULL,
  `uspass` varchar(200) NOT NULL,
  `usmail` varchar(50) NOT NULL,
  `usdeshabilitado` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariorol`
--

CREATE TABLE `usuariorol` (
  `idusuario` bigint(20) NOT NULL,
  `idrol` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`idcompra`),
  ADD UNIQUE KEY `idcompra` (`idcompra`),
  ADD KEY `fkcompra_1` (`idusuario`);

--
-- Indices de la tabla `compraestado`
--
ALTER TABLE `compraestado`
  ADD PRIMARY KEY (`idcompraestado`),
  ADD UNIQUE KEY `idcompraestado` (`idcompraestado`),
  ADD KEY `fkcompraestado_1` (`idcompra`),
  ADD KEY `fkcompraestado_2` (`idcompraestadotipo`);

--
-- Indices de la tabla `compraestadotipo`
--
ALTER TABLE `compraestadotipo`
  ADD PRIMARY KEY (`idcompraestadotipo`);

--
-- Indices de la tabla `compraitem`
--
ALTER TABLE `compraitem`
  ADD PRIMARY KEY (`idcompraitem`),
  ADD UNIQUE KEY `idcompraitem` (`idcompraitem`),
  ADD KEY `fkcompraitem_1` (`idcompra`),
  ADD KEY `fkcompraitem_2` (`idproducto`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idmenu`),
  ADD UNIQUE KEY `idmenu` (`idmenu`),
  ADD KEY `fkmenu_1` (`idpadre`);

--
-- Indices de la tabla `menurol`
--
ALTER TABLE `menurol`
  ADD PRIMARY KEY (`idmenu`,`idrol`),
  ADD KEY `fkmenurol_2` (`idrol`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idproducto`),
  ADD UNIQUE KEY `idproducto` (`idproducto`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`),
  ADD UNIQUE KEY `idrol` (`idrol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD PRIMARY KEY (`idusuario`,`idrol`),
  ADD KEY `idusuario` (`idusuario`),
  ADD KEY `idrol` (`idrol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `idcompra` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compraestado`
--
ALTER TABLE `compraestado`
  MODIFY `idcompraestado` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compraitem`
--
ALTER TABLE `compraitem`
  MODIFY `idcompraitem` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `idmenu` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idproducto` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `fkcompra_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `compraestado`
--
ALTER TABLE `compraestado`
  ADD CONSTRAINT `fkcompraestado_1` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fkcompraestado_2` FOREIGN KEY (`idcompraestadotipo`) REFERENCES `compraestadotipo` (`idcompraestadotipo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `compraitem`
--
ALTER TABLE `compraitem`
  ADD CONSTRAINT `fkcompraitem_1` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fkcompraitem_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fkmenu_1` FOREIGN KEY (`idpadre`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `menurol`
--
ALTER TABLE `menurol`
  ADD CONSTRAINT `fkmenurol_1` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fkmenurol_2` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD CONSTRAINT `fkmovimiento_1` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuariorol_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


INSERT INTO `producto` (`idproducto`,`pronombre`,`prodetalle`,`procantstock`) VALUES
(1,'Dell XPS 13','Pantalla de 13.4", Intel Core i7, 16GB RAM, 512GB SSD, Windows 10',12),
(2,'HP Spectre x360','13.5 Intel Core i5, 8GB RAM, 256GB SSD, convertible 2-en-1, Windows 11',22),
(3,'Apple MacBook Air M1','Chip M1, 8GB RAM, 256GB SSD, macOS, Retina Display de 13.3',7),
(4,'Lenovo ThinkPad X1 Carbon','Pantalla de 14", Intel Core i7, 16GB RAM, 512GB SSD, teclado retroiluminado',15),
(5,'Asus ROG Zephyrus G14','Pantalla de 14", AMD Ryzen 9, 16GB RAM, 1TB SSD, GPU NVIDIA RTX 3060',31),
(6,'Acer Aspire 5','15.6", Intel Core i5, 8GB RAM, 512GB SSD, Windows 10',42),
(7,'Microsoft Surface Laptop 4','13.5", Intel Core i5, 8GB RAM, 512GB SSD, pantalla táctil',13),
(8,'Razer Blade 15','Pantalla de 15.6", Intel Core i7, 16GB RAM, 1TB SSD, GPU NVIDIA RTX 3070',55),
(9,'Lenovo IdeaPad 3','Pantalla de 15.6", AMD Ryzen 5, 8GB RAM, 256GB SSD, Windows 10',42),
(10,'HP Pavilion 15','15.6", Intel Core i7, 16GB RAM, 512GB SSD, Windows 11',47),
(11,'Acer Swift 3','Pantalla de 14", AMD Ryzen 7, 8GB RAM, 512GB SSD, Windows 10',44),
(12,'Dell Inspiron 15 3000','Pantalla de 15.6", Intel Core i5, 8GB RAM, 256GB SSD, Windows 11',32),
(13,'Asus ZenBook 14','Pantalla de 14", Intel Core i7, 16GB RAM, 512GB SSD, Windows 10, ultraligero',26),
(14,'HP Envy 13','Pantalla de 13.3", Intel Core i7, 8GB RAM, 256GB SSD, pantalla táctil',25),
(15,'Lenovo Yoga 7i','Pantalla de 14", Intel Core i5, 8GB RAM, 512GB SSD, convertible 2-en-1, Windows 10',21),
(16,'Apple MacBook Pro 16"','Chip M1 Pro, 16GB RAM, 512GB SSD, macOS, pantalla Liquid Retina XDR',19),
(17,'Samsung Galaxy Book Pro','Pantalla AMOLED de 15.6", Intel Core i7, 16GB RAM, 512GB SSD, diseño ultradelgado',33),
(18,'MSI GF63 Thin','Pantalla de 15.6", Intel Core i5, 8GB RAM, 256GB SSD, GPU NVIDIA GTX 1650',32),
(19,'Acer Nitro 5','Pantalla de 15.6", Intel Core i7, 16GB RAM, 1TB HDD + 256GB SSD, GPU NVIDIA RTX 3050',30),
(20,'LG Gram 17','Pantalla de 17", Intel Core i7, 16GB RAM, 1TB SSD, ultraligero, batería de larga duración',28),