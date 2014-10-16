-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `inst_odpoved`;
CREATE TABLE `inst_odpoved` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iqid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `data` decimal(16,6) NOT NULL COMMENT 'decimal odpoved editu',
  PRIMARY KEY (`id`),
  KEY `iqid` (`iqid`),
  KEY `aid` (`aid`),
  CONSTRAINT `inst_odpoved_ibfk_6` FOREIGN KEY (`iqid`) REFERENCES `inst_otazka` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inst_odpoved_ibfk_5` FOREIGN KEY (`aid`) REFERENCES `odpoved` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `inst_otazka`;
CREATE TABLE `inst_otazka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`),
  KEY `oid` (`qid`),
  CONSTRAINT `inst_otazka_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `sada` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inst_otazka_ibfk_5` FOREIGN KEY (`qid`) REFERENCES `otazka` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `odpoved`;
CREATE TABLE `odpoved` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `data` varchar(256) NOT NULL COMMENT 'text, soubor nebo mathquill',
  `data2` decimal(16,6) NOT NULL COMMENT 'správná hodnota, pokud typu edit',
  `spravna` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`fid`),
  KEY `fid` (`fid`),
  CONSTRAINT `odpoved_ibfk_1` FOREIGN KEY (`fid`) REFERENCES `otazka` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `odpoved` (`id`, `fid`, `typ`, `data`, `data2`, `spravna`) VALUES
(1,	1,	1,	'6 nebo tak',	0.000000,	1),
(1,	2,	1,	'1540 km3',	0.000000,	0),
(1,	3,	1,	'3',	0.000000,	0),
(1,	4,	2,	'troll.jpg',	0.000000,	0),
(2,	1,	1,	'Nevim, 10?',	0.000000,	0),
(2,	2,	1,	'49 cm2',	0.000000,	1),
(2,	3,	3,	'\\sqrt{78}',	0.000000,	0),
(2,	4,	4,	'Jo a to přesně:',	42.000000,	1),
(3,	1,	1,	'Emm... PI?',	0.000000,	0),
(3,	2,	1,	'????',	0.000000,	0),
(3,	3,	1,	'správná',	0.000000,	1),
(3,	4,	4,	'Nebo třeba...',	0.000000,	0),
(4,	2,	1,	'125',	0.000000,	0),
(4,	3,	3,	'\\frac{9}{16}',	0.000000,	1);

DROP TABLE IF EXISTS `otazka`;
CREATE TABLE `otazka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typ` int(11) NOT NULL COMMENT 'text, cesta k souboru, mathquill',
  `comment` varchar(511) NOT NULL COMMENT 'Spockův komentář k otázce',
  `data` varchar(511) NOT NULL COMMENT 'text nebo obrazek',
  `data2` varchar(511) NOT NULL COMMENT 'text k obrázku nebo mathquill',
  `multi` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Jestli je to multiselect',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `otazka` (`id`, `typ`, `comment`, `data`, `data2`, `multi`) VALUES
(1,	1,	'Dej si pozor, je to pro pokročilé!',	'Kolik je 3 + 3?',	'',	0),
(2,	1,	'',	'Jaký je obsah čtverce se stranou 7cm?',	'',	0),
(3,	3,	'Už jste se učili zlomky?',	'\\frac{\\frac{3}{4}}{\\frac{4}{3}}',	'Jaká je hodnota tohoto výrazu?',	1),
(4,	1,	'Zkusíme trochu logiky.',	'Je 4 prvočíslo?',	'',	0),
(5,	2,	'Doufám, že nejsi discirculik.',	'kruh.png',	'Jaké vlastnosti splňuje tato množina bodů?',	1);

DROP TABLE IF EXISTS `sada`;
CREATE TABLE `sada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jmeno` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2014-10-16 19:56:35
