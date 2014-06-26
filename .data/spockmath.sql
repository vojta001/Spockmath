-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `odpoved`;
CREATE TABLE `odpoved` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `data` varchar(256) NOT NULL COMMENT 'text, soubor nebo mathquill',
  `data2` decimal(16,6) NOT NULL COMMENT 'správná hodnota, pokud typu edit',
  `spravna` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`fid`),
  KEY `fk_table2_table1` (`fid`),
  CONSTRAINT `fk_table2_table1` FOREIGN KEY (`fid`) REFERENCES `otazka` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
(4,	2,	1,	'125',	0.000000,	0),
(4,	3,	3,	'\\frac{9}{16}',	0.000000,	1);

DROP TABLE IF EXISTS `otazka`;
CREATE TABLE `otazka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typ` int(11) NOT NULL COMMENT 'text, cesta k souboru, mathquill',
  `data` varchar(255) NOT NULL COMMENT 'text nebo obrazek',
  `data2` varchar(255) NOT NULL COMMENT 'text k obrázku nebo mathquill',
  `multi` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Jestli je to multiselect',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `otazka` (`id`, `typ`, `data`, `data2`, `multi`) VALUES
(1,	1,	'Kolik je 3 + 3?',	'',	0),
(2,	1,	'Jaký je obsah čtverce se stranou 7cm?',	'',	0),
(3,	3,	'\\frac{\\frac{3}{4}}{\\frac{4}{3}}',	'Jaká je hodnota tohoto výrazu?',	1),
(4,	1,	'Je 4 prvočíslo?',	'',	0),
(5,	2,	'kruh.png',	'Jaké vlastnosti splňuje tato množina bodů?',	1);

-- 2014-06-26 19:07:19
