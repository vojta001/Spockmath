-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `spockmath`;

DROP TABLE IF EXISTS `inst_odpoved`;
CREATE TABLE `inst_odpoved` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iqid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `data` decimal(16,6) NOT NULL COMMENT 'decimal odpoved editu',
  PRIMARY KEY (`id`),
  KEY `iqid` (`iqid`),
  KEY `aid` (`aid`),
  CONSTRAINT `inst_odpoved_ibfk_5` FOREIGN KEY (`aid`) REFERENCES `odpoved` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inst_odpoved_ibfk_6` FOREIGN KEY (`iqid`) REFERENCES `inst_otazka` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8;

INSERT INTO `inst_odpoved` (`id`, `iqid`, `aid`, `data`) VALUES
(14,	13,	1,	0.000000),
(15,	14,	4,	0.000000),
(16,	15,	2,	0.000000),
(17,	15,	3,	0.000000),
(18,	16,	3,	789.000000),
(32,	48,	2,	0.000000),
(33,	49,	4,	0.000000),
(34,	50,	1,	0.000000),
(35,	50,	3,	0.000000),
(36,	51,	2,	422.000000),
(37,	52,	2,	0.000000),
(38,	53,	3,	0.000000),
(39,	54,	3,	0.000000),
(40,	55,	3,	0.000000),
(41,	55,	4,	0.000000),
(42,	56,	2,	79.000000),
(43,	57,	2,	0.000000),
(44,	58,	1,	0.000000),
(45,	59,	1,	0.000000),
(46,	60,	1,	0.000000),
(47,	60,	2,	0.000000),
(48,	60,	3,	0.000000),
(49,	60,	4,	0.000000),
(50,	61,	1,	0.000000),
(51,	63,	2,	0.000000),
(52,	64,	2,	0.000000),
(53,	65,	1,	0.000000),
(54,	65,	2,	0.000000),
(55,	65,	3,	0.000000),
(56,	65,	4,	0.000000),
(57,	66,	2,	4566.000000),
(58,	67,	2,	0.000000),
(59,	68,	1,	0.000000),
(60,	69,	1,	0.000000),
(61,	70,	1,	0.000000),
(62,	70,	2,	0.000000),
(63,	70,	3,	0.000000),
(64,	70,	4,	0.000000),
(65,	71,	3,	47.000000),
(66,	72,	1,	0.000000),
(67,	72,	2,	0.000000),
(68,	73,	1,	0.000000),
(69,	74,	4,	0.000000),
(70,	75,	3,	0.000000),
(71,	75,	4,	0.000000),
(72,	76,	2,	39.000000),
(73,	77,	1,	0.000000),
(74,	81,	3,	78.000000),
(75,	83,	1,	0.000000),
(76,	84,	2,	0.000000),
(77,	85,	4,	0.000000),
(78,	86,	1,	0.000000),
(79,	87,	1,	0.000000),
(80,	87,	2,	0.000000),
(81,	88,	1,	0.000000),
(82,	91,	3,	354.000000),
(83,	96,	2,	39.000000),
(84,	101,	3,	46.000000),
(85,	106,	3,	13.000000),
(86,	108,	2,	0.000000),
(87,	109,	2,	0.000000),
(88,	110,	2,	0.000000),
(89,	110,	3,	0.000000),
(90,	111,	2,	38.450000),
(91,	112,	1,	0.000000),
(92,	112,	2,	0.000000),
(93,	118,	1,	0.000000),
(94,	119,	2,	0.000000),
(95,	120,	2,	0.000000),
(96,	120,	3,	0.000000),
(97,	121,	2,	33.000000),
(98,	122,	1,	0.000000),
(99,	127,	1,	0.000000),
(100,	128,	1,	0.000000),
(101,	128,	2,	0.000000),
(102,	129,	2,	38.000000),
(103,	130,	3,	0.000000),
(104,	135,	4,	0.000000),
(105,	135,	3,	0.000000),
(106,	136,	2,	0.000000),
(107,	137,	1,	0.000000),
(108,	138,	2,	0.000000),
(109,	139,	1,	0.000000),
(110,	140,	1,	0.000000),
(111,	141,	3,	0.000000),
(112,	142,	1,	0.000000),
(113,	143,	1,	0.000000),
(114,	144,	2,	0.000000),
(115,	145,	1,	0.000000),
(116,	146,	4,	0.000000),
(117,	147,	4,	0.000000),
(118,	147,	3,	0.000000),
(119,	148,	3,	0.000000),
(120,	149,	2,	38.000000),
(121,	150,	1,	0.000000),
(122,	151,	2,	13.000000),
(123,	152,	4,	0.000000),
(124,	152,	2,	0.000000),
(125,	153,	1,	0.000000),
(126,	154,	2,	0.000000),
(127,	155,	1,	0.000000),
(128,	156,	3,	4.000000),
(129,	157,	2,	0.000000),
(130,	158,	2,	0.000000),
(131,	159,	3,	0.000000),
(132,	160,	2,	0.000000),
(133,	161,	2,	0.000000),
(134,	162,	2,	0.000000),
(135,	164,	2,	0.000000),
(136,	165,	3,	2.000000),
(137,	166,	2,	0.000000),
(138,	167,	3,	0.000000),
(139,	168,	1,	0.000000),
(140,	169,	2,	0.000000),
(141,	169,	1,	0.000000),
(142,	170,	2,	33.000000),
(143,	171,	2,	0.000000),
(144,	172,	3,	0.000000),
(145,	172,	4,	0.000000),
(146,	173,	2,	0.000000),
(147,	174,	2,	0.000000),
(148,	174,	3,	0.000000),
(149,	175,	1,	0.000000),
(150,	176,	3,	0.000000),
(151,	176,	2,	0.000000),
(152,	177,	2,	0.000000),
(153,	177,	3,	0.000000),
(154,	178,	2,	0.000000),
(155,	182,	3,	0.000000),
(156,	183,	2,	1.000000),
(157,	184,	3,	0.000000),
(158,	185,	2,	0.000000),
(159,	185,	1,	0.000000),
(160,	186,	3,	0.000000),
(161,	186,	2,	0.000000),
(162,	187,	1,	0.000000),
(163,	188,	3,	0.000000),
(164,	188,	2,	0.000000),
(165,	189,	4,	0.000000),
(166,	190,	2,	0.000000),
(167,	191,	2,	0.000000),
(168,	192,	3,	0.000000),
(169,	192,	2,	0.000000),
(170,	193,	3,	0.000000),
(171,	194,	1,	0.000000),
(172,	195,	2,	0.000000),
(173,	196,	4,	0.000000),
(174,	197,	2,	0.000000),
(175,	198,	1,	0.000000),
(176,	199,	2,	0.000000),
(177,	200,	4,	0.000000),
(178,	201,	1,	0.000000),
(179,	201,	2,	0.000000),
(180,	202,	3,	0.000000),
(181,	203,	2,	0.000000),
(182,	205,	1,	0.000000),
(183,	206,	3,	0.000000),
(184,	206,	4,	0.000000),
(185,	207,	1,	0.000000),
(186,	209,	3,	0.000000),
(187,	210,	3,	0.000000),
(188,	211,	2,	0.000000),
(189,	212,	3,	13.000000),
(190,	213,	1,	0.000000),
(191,	214,	4,	0.000000),
(192,	215,	2,	0.000000),
(193,	216,	3,	0.000000),
(194,	216,	4,	0.000000),
(195,	218,	2,	0.000000),
(196,	219,	1,	0.000000),
(197,	220,	2,	4.000000),
(198,	221,	2,	0.000000),
(199,	222,	3,	0.000000),
(200,	222,	1,	0.000000),
(201,	222,	2,	0.000000),
(202,	223,	3,	0.000000),
(203,	224,	1,	0.000000),
(204,	225,	3,	7.000000),
(205,	226,	1,	0.000000),
(206,	227,	3,	0.000000),
(207,	227,	1,	0.000000),
(208,	227,	2,	0.000000),
(209,	229,	2,	0.000000),
(210,	230,	1,	0.000000),
(211,	230,	2,	0.000000),
(212,	231,	3,	45.000000),
(213,	232,	1,	0.000000),
(214,	232,	3,	0.000000),
(215,	233,	2,	0.000000),
(216,	234,	4,	0.000000),
(217,	234,	3,	0.000000),
(218,	235,	2,	0.000000),
(219,	236,	2,	0.000000),
(223,	239,	2,	0.000000),
(224,	240,	2,	0.000000),
(225,	240,	1,	0.000000),
(226,	241,	1,	0.000000),
(227,	242,	3,	0.000000),
(228,	243,	1,	0.000000),
(229,	243,	3,	0.000000),
(230,	244,	2,	55.000000),
(231,	245,	2,	0.000000),
(232,	246,	1,	0.000000),
(233,	247,	1,	0.000000),
(234,	248,	1,	0.000000),
(235,	248,	3,	0.000000),
(236,	250,	3,	12.000000);

DROP TABLE IF EXISTS `inst_otazka`;
CREATE TABLE `inst_otazka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`),
  KEY `oid` (`qid`),
  CONSTRAINT `inst_otazka_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `sada` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inst_otazka_ibfk_5` FOREIGN KEY (`qid`) REFERENCES `otazka` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8;

INSERT INTO `inst_otazka` (`id`, `qid`, `sid`) VALUES
(13,	1,	23),
(14,	2,	23),
(15,	3,	23),
(16,	4,	23),
(17,	5,	23),
(18,	1,	24),
(19,	2,	24),
(20,	3,	24),
(21,	4,	24),
(22,	5,	24),
(23,	1,	25),
(24,	2,	25),
(25,	3,	25),
(26,	4,	25),
(27,	5,	25),
(48,	1,	30),
(49,	2,	30),
(50,	3,	30),
(51,	4,	30),
(52,	5,	30),
(53,	1,	31),
(54,	2,	31),
(55,	3,	31),
(56,	4,	31),
(57,	5,	31),
(58,	1,	32),
(59,	2,	32),
(60,	3,	32),
(61,	4,	32),
(62,	5,	32),
(63,	1,	33),
(64,	2,	33),
(65,	3,	33),
(66,	4,	33),
(67,	5,	33),
(68,	1,	34),
(69,	2,	34),
(70,	3,	34),
(71,	4,	34),
(72,	5,	34),
(73,	1,	35),
(74,	2,	35),
(75,	3,	35),
(76,	4,	35),
(77,	5,	35),
(78,	1,	36),
(79,	2,	36),
(80,	3,	36),
(81,	4,	36),
(82,	5,	36),
(83,	1,	37),
(84,	2,	37),
(85,	3,	37),
(86,	4,	37),
(87,	5,	37),
(88,	1,	38),
(89,	2,	38),
(90,	3,	38),
(91,	4,	38),
(92,	5,	38),
(93,	1,	39),
(94,	2,	39),
(95,	3,	39),
(96,	4,	39),
(97,	5,	39),
(98,	1,	40),
(99,	2,	40),
(100,	3,	40),
(101,	4,	40),
(102,	5,	40),
(103,	1,	41),
(104,	2,	41),
(105,	3,	41),
(106,	4,	41),
(107,	5,	41),
(108,	1,	42),
(109,	2,	42),
(110,	3,	42),
(111,	4,	42),
(112,	5,	42),
(113,	1,	43),
(114,	2,	43),
(115,	3,	43),
(116,	4,	43),
(117,	5,	43),
(118,	1,	44),
(119,	2,	44),
(120,	3,	44),
(121,	4,	44),
(122,	5,	44),
(123,	5,	45),
(124,	5,	46),
(125,	5,	47),
(126,	5,	48),
(127,	5,	49),
(128,	5,	50),
(129,	4,	51),
(130,	1,	51),
(131,	2,	51),
(132,	1,	52),
(133,	2,	52),
(134,	4,	52),
(135,	3,	52),
(136,	3,	53),
(137,	4,	53),
(138,	2,	53),
(139,	1,	53),
(140,	4,	54),
(141,	2,	54),
(142,	2,	55),
(143,	5,	55),
(144,	2,	56),
(145,	5,	56),
(146,	2,	57),
(147,	3,	57),
(148,	2,	57),
(149,	4,	57),
(150,	1,	57),
(151,	4,	58),
(152,	3,	58),
(153,	5,	58),
(154,	2,	58),
(155,	1,	58),
(156,	4,	59),
(157,	5,	59),
(158,	2,	59),
(159,	1,	59),
(160,	2,	59),
(161,	5,	60),
(162,	2,	60),
(163,	5,	61),
(164,	2,	61),
(165,	4,	61),
(166,	2,	61),
(167,	3,	61),
(168,	1,	62),
(169,	5,	62),
(170,	4,	62),
(171,	2,	62),
(172,	3,	62),
(173,	7,	63),
(174,	6,	63),
(175,	7,	64),
(176,	6,	64),
(177,	6,	65),
(178,	7,	65),
(179,	6,	66),
(180,	7,	66),
(181,	7,	67),
(182,	6,	67),
(183,	4,	68),
(184,	1,	68),
(185,	6,	69),
(186,	3,	69),
(187,	5,	69),
(188,	6,	70),
(189,	2,	70),
(190,	7,	70),
(191,	2,	71),
(192,	3,	71),
(193,	1,	71),
(194,	5,	72),
(195,	2,	72),
(196,	2,	73),
(197,	2,	74),
(198,	1,	74),
(199,	2,	74),
(200,	3,	74),
(201,	5,	74),
(202,	1,	75),
(203,	2,	75),
(204,	4,	75),
(205,	5,	75),
(206,	3,	75),
(207,	4,	76),
(208,	3,	76),
(209,	2,	76),
(210,	2,	76),
(211,	1,	76),
(212,	4,	77),
(213,	1,	77),
(214,	2,	77),
(215,	5,	77),
(216,	3,	77),
(217,	5,	78),
(218,	2,	78),
(219,	7,	78),
(220,	4,	78),
(221,	1,	78),
(222,	6,	78),
(223,	3,	79),
(224,	1,	79),
(225,	4,	79),
(226,	2,	79),
(227,	6,	79),
(228,	5,	79),
(229,	7,	79),
(230,	5,	80),
(231,	4,	80),
(232,	6,	80),
(233,	1,	80),
(234,	3,	80),
(235,	7,	80),
(236,	2,	80),
(239,	5,	82),
(240,	5,	83),
(241,	1,	83),
(242,	2,	83),
(243,	3,	84),
(244,	4,	84),
(245,	2,	84),
(246,	5,	84),
(247,	1,	84),
(248,	3,	85),
(249,	1,	85),
(250,	4,	85),
(251,	5,	85),
(252,	2,	85);

DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr` varchar(255) NOT NULL COMMENT 'plaintext username',
  `passwd` varchar(255) NOT NULL COMMENT 'password hash, NO plaintext',
  `perm` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usr` (`usr`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `login` (`id`, `usr`, `passwd`) VALUES
(1,	'houba',	'$1$zZ4.Ap2.$DDrtydTDdsESSF43QIiLC0'),
(2,	'vojta001',	'$1$bt1.IE0.$aM1w3ikDRc27oixpGVeXp/');

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
(1,	5,	1,	'Trojúhelník',	0.000000,	0),
(1,	6,	1,	'Louskáček na ořechy',	0.000000,	1),
(1,	7,	1,	'Fyzikální veličina',	0.000000,	1),
(2,	1,	1,	'Nevim, 10?',	0.000000,	0),
(2,	2,	1,	'49 cm2',	0.000000,	1),
(2,	3,	3,	'\\sqrt{78}',	0.000000,	0),
(2,	4,	4,	'Jo a to přesně:',	42.000000,	1),
(2,	5,	1,	'Šestiúhelník',	0.000000,	0),
(2,	6,	1,	'Šroub',	0.000000,	0),
(2,	7,	1,	'Nesmysl',	0.000000,	0),
(3,	1,	1,	'Emm... PI?',	0.000000,	0),
(3,	2,	1,	'????',	0.000000,	0),
(3,	3,	1,	'správná',	0.000000,	1),
(3,	4,	4,	'Nebo třeba...',	0.000000,	0),
(3,	6,	1,	'Nůžky',	0.000000,	1),
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `otazka` (`id`, `typ`, `comment`, `data`, `data2`, `multi`) VALUES
(1,	1,	'Dej si pozor, je to pro pokročilé!',	'Kolik je 3 + 3?',	'',	0),
(2,	1,	'',	'Jaký je obsah čtverce se stranou 7cm?',	'',	0),
(3,	3,	'Už jste se učili zlomky?',	'\\frac{\\frac{3}{4}}{\\frac{4}{3}}',	'Jaká je hodnota tohoto výrazu?',	1),
(4,	1,	'Zkusíme trochu logiky.',	'Je 4 prvočíslo?',	'',	0),
(5,	2,	'Doufám, že nejsi discirculik.',	'kruh.png',	'Jaké vlastnosti splňuje tato množina bodů? Přemejšlej! Woe přemejšlej!!!',	1),
(6,	1,	'Páka. To zvládneš, ne?',	'Ve kterých jednoduchých strojích se vyskytuje páka?',	'',	1),
(7,	1,	'Pche. Tohle jsme se učili už ve školce',	'Teplota je:',	'',	0),
(8,	1,	'No Comment',	'Data',	'',	0);

DROP TABLE IF EXISTS `otazka_tema`;
CREATE TABLE `otazka_tema` (
  `otazka_id` int(11) NOT NULL,
  `tema_id` int(11) NOT NULL,
  PRIMARY KEY (`otazka_id`,`tema_id`),
  KEY `tema_id` (`tema_id`),
  CONSTRAINT `otazka_tema_ibfk_1` FOREIGN KEY (`otazka_id`) REFERENCES `otazka` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `otazka_tema_ibfk_2` FOREIGN KEY (`tema_id`) REFERENCES `tema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `otazka_tema` (`otazka_id`, `tema_id`) VALUES
(1,	1),
(2,	1),
(3,	1),
(4,	1),
(2,	2),
(5,	2),
(6,	3),
(7,	3);

DROP TABLE IF EXISTS `sada`;
CREATE TABLE `sada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jmeno` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;

INSERT INTO `sada` (`id`, `datum`, `jmeno`) VALUES
(23,	'2014-10-16 19:25:07',	'Příliš \'); DROP TABLE sada; -- žluťoučký kůň úpěl ďábelské ódy.'),
(24,	'2014-10-16 19:31:38',	'Příliš \'); DROP TABLE sada; -- žluťoučký kůň úpěl ďábelské ódy.'),
(25,	'2014-10-16 19:31:47',	'Příliš \'); DROP TABLE sada; -- žluťoučký kůň úpěl ďábelské ódy.'),
(30,	'2014-10-27 23:21:51',	'Příliš \'); DROP TABLE sada; -- žluťoučký kůň úpěl ďábelské ódy.'),
(31,	'2014-10-29 09:16:08',	'Příliš \'); DROP TABLE sada; -- žluťoučký kůň úpěl ďábelské ódy.'),
(32,	'2014-10-29 09:32:49',	'Příliš \'); DROP TABLE sada; -- žluťoučký kůň úpěl ďábelské ódy.'),
(33,	'2014-10-30 17:07:01',	''),
(34,	'2014-10-30 17:12:36',	''),
(35,	'2014-10-30 18:14:39',	'vojta001'),
(36,	'2014-10-30 18:51:50',	''),
(37,	'2014-10-30 20:49:13',	'Kare'),
(38,	'2014-10-30 21:11:53',	''),
(39,	'2014-10-30 21:19:26',	''),
(40,	'2014-10-30 21:28:14',	''),
(41,	'2014-10-30 21:29:02',	''),
(42,	'2014-11-06 18:02:54',	'vojta001'),
(43,	'2014-11-06 18:28:48',	''),
(44,	'2014-11-06 18:51:33',	'45'),
(45,	'2014-11-20 17:43:06',	''),
(46,	'2014-11-20 17:44:21',	''),
(47,	'2014-11-20 17:45:11',	''),
(48,	'2014-11-20 17:47:09',	''),
(49,	'2014-11-20 17:49:25',	''),
(50,	'2014-11-20 17:51:27',	'blemst'),
(51,	'2014-11-20 18:02:54',	''),
(52,	'2014-11-20 18:03:30',	'fglrx'),
(53,	'2014-11-20 18:11:37',	'Karel I.'),
(54,	'2014-11-27 16:15:20',	''),
(55,	'2014-11-27 16:17:05',	''),
(56,	'2014-11-27 17:03:32',	''),
(57,	'2014-11-27 17:06:19',	''),
(58,	'2014-11-27 18:59:34',	'vojta'),
(59,	'2014-11-27 19:12:31',	'123456789'),
(60,	'2014-12-01 18:13:24',	'tadeas'),
(61,	'2014-12-01 18:14:59',	'tad4+e87867643a5486'),
(62,	'2014-12-02 20:47:33',	'\'); DROP DATABASE `spockmath` --'),
(63,	'2014-12-04 17:57:44',	'vojt'),
(64,	'2014-12-04 18:33:07',	'132'),
(65,	'2014-12-04 18:45:32',	'976544*/*/'),
(66,	'2014-12-04 18:45:59',	'6'),
(67,	'2014-12-04 18:46:43',	'klů'),
(68,	'2014-12-04 18:50:20',	'Koza'),
(69,	'2014-12-19 14:51:28',	'\'blemst--'),
(70,	'2014-12-19 16:37:38',	'13'),
(71,	'2014-12-19 16:39:06',	'\') DROP DATABASE *; --'),
(72,	'2015-01-08 19:42:49',	'\')'),
(73,	'2015-01-08 19:43:41',	'123'),
(74,	'2015-01-09 19:17:28',	'vakoještěr'),
(75,	'2015-01-09 19:27:54',	'\') blemst'),
(76,	'2015-01-15 18:51:46',	'***'),
(77,	'2015-01-15 19:01:39',	'\')*/-+!@#$%^&*('),
(78,	'2015-02-06 18:57:48',	'<script> alert(\"4654654\") </script>'),
(79,	'2015-02-09 17:26:45',	'erbeg'),
(80,	'2015-03-13 11:11:45',	'/*kejda*/'),
(82,	'2015-03-13 11:27:50',	'fň'),
(83,	'2015-03-13 13:44:04',	'já'),
(84,	'2015-03-13 16:09:33',	'kejn'),
(85,	'2015-03-13 16:12:13',	'huh');

CREATE TABLE `tema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT 'parentId',
  `jmeno` varchar(255) NOT NULL,
  `komentar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  CONSTRAINT `tema_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `tema` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `tema` (`id`, `pid`, `jmeno`, `komentar`) VALUES
(1,	5,	'Zlomky',	'Zlomky pro sekundu'),
(2,	5,	'Geometrie',	'Geometrie z první třídy'),
(3,	4,	'Jednoduché stroje a veličiny',	'Fyzika pro 1. třídu'),
(4,	NULL,	'Fyzika',	'Aplikovaná matematika'),
(5,	NULL,	'Matematika',	''),
(6,	NULL,	'Informatika',	'');

-- 2015-06-25 20:00:35
