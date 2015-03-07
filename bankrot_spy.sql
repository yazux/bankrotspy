-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 08 2015 г., 03:16
-- Версия сервера: 5.6.15-log
-- Версия PHP: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `bankrot_spy`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ds_chmail`
--

CREATE TABLE IF NOT EXISTS `ds_chmail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `mail` varchar(50) NOT NULL,
  `oldmail` varchar(50) NOT NULL,
  `key` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ds_comm`
--

CREATE TABLE IF NOT EXISTS `ds_comm` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL,
  `module` varchar(15) NOT NULL,
  `text` text NOT NULL,
  `username` varchar(25) NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `cache` blob NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `useredit` varchar(25) NOT NULL,
  `editid` int(11) unsigned NOT NULL,
  `lastedit` int(11) unsigned NOT NULL,
  `numedit` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ds_depr_emails`
--

CREATE TABLE IF NOT EXISTS `ds_depr_emails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ds_files`
--

CREATE TABLE IF NOT EXISTS `ds_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file` text NOT NULL,
  `filename` text NOT NULL,
  `module` varchar(30) NOT NULL,
  `mod_id` int(11) unsigned NOT NULL,
  `ref_mod` int(11) unsigned NOT NULL,
  `down` int(11) unsigned NOT NULL,
  `time` int(11) NOT NULL,
  `time_mod` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ds_forbidden_mails`
--

CREATE TABLE IF NOT EXISTS `ds_forbidden_mails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `domain` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `ds_forbidden_mails`
--

INSERT INTO `ds_forbidden_mails` (`id`, `domain`) VALUES
(2, 'annimon.com');

-- --------------------------------------------------------

--
-- Структура таблицы `ds_guests`
--

CREATE TABLE IF NOT EXISTS `ds_guests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `primid` varchar(32) NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `ua` text NOT NULL,
  `lastdate` int(11) unsigned NOT NULL,
  `module` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `primid` (`primid`),
  KEY `ip` (`ip`),
  KEY `module` (`module`),
  KEY `action` (`action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=380 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ds_menu`
--

CREATE TABLE IF NOT EXISTS `ds_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL,
  `name` text NOT NULL,
  `link` text NOT NULL,
  `one_counter` text NOT NULL,
  `two_counter` text NOT NULL,
  `three_counter` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Дамп данных таблицы `ds_menu`
--

INSERT INTO `ds_menu` (`id`, `sort`, `name`, `link`, `one_counter`, `two_counter`, `three_counter`) VALUES
(16, 1, 'Новости', '<<home_link>>/news', '', '', ''),
(17, 2, 'Техподдержка', '<<home_link>>/support', '', '', ''),
(18, 3, 'Помощь', '<<home_link>>/help', '', '', ''),
(19, 4, 'Аукционый робот', '<<home_link>>/', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ds_post_files`
--

CREATE TABLE IF NOT EXISTS `ds_post_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attach` int(11) DEFAULT '0',
  `atmod` int(11) NOT NULL,
  `module` varchar(20) NOT NULL,
  `post` int(11) DEFAULT '0',
  `time` int(11) NOT NULL,
  `name` text NOT NULL,
  `userload` int(11) NOT NULL,
  `count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `module` (`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ds_rights`
--

CREATE TABLE IF NOT EXISTS `ds_rights` (
  `id` int(11) NOT NULL,
  `rights` text NOT NULL,
  `descr` varchar(15) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ds_rights`
--

INSERT INTO `ds_rights` (`id`, `rights`, `descr`) VALUES
(0, 'a:9:{s:16:"frm_create_topic";i:1;s:15:"frm_create_mess";i:1;s:20:"frm_delete_self_mess";i:1;s:18:"frm_edit_self_mess";i:1;s:14:"comm_self_edit";i:1;s:16:"comm_self_delete";i:1;s:11:"create_comm";i:1;s:17:"stats_cr_draftmod";i:1;s:18:"add_stats_moderate";i:1;}', 'user'),
(100, 'a:38:{s:9:"edit_user";i:1;s:10:"edit_group";i:1;s:8:"del_user";i:1;s:11:"edit_smiles";i:1;s:10:"edit_album";i:1;s:12:"delete_album";i:1;s:10:"edit_photo";i:1;s:12:"delete_photo";i:1;s:12:"stats_create";i:1;s:10:"stats_edit";i:1;s:12:"stats_delete";i:1;s:9:"comm_edit";i:1;s:11:"comm_delete";i:1;s:18:"frm_create_section";i:1;s:16:"frm_edit_section";i:1;s:18:"frm_delete_section";i:1;s:16:"frm_create_topic";i:1;s:15:"frm_create_mess";i:1;s:13:"frm_edit_mess";i:1;s:15:"frm_delete_mess";i:1;s:9:"ban_users";i:1;s:20:"frm_delete_self_mess";i:1;s:18:"frm_edit_self_mess";i:1;s:14:"comm_self_edit";i:1;s:16:"comm_self_delete";i:1;s:11:"create_comm";i:1;s:14:"stats_moderate";i:1;s:14:"frm_del_topics";i:1;s:18:"add_stats_moderate";i:1;s:16:"frm_rename_topic";i:1;s:21:"frm_write_close_topic";i:1;s:19:"frm_can_close_topic";i:1;s:18:"frm_can_open_topic";i:1;s:13:"frm_fix_topic";i:1;s:15:"frm_unfix_topic";i:1;s:17:"frm_move_to_trash";i:1;s:19:"frm_move_from_trash";i:1;s:14:"frm_view_trash";i:1;}', 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `ds_settings`
--

CREATE TABLE IF NOT EXISTS `ds_settings` (
  `key` tinytext NOT NULL,
  `val` text NOT NULL,
  PRIMARY KEY (`key`(30))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ds_settings`
--

INSERT INTO `ds_settings` (`key`, `val`) VALUES
('theme', 'a:3:{s:3:"pda";s:7:"default";s:3:"wap";s:7:"default";s:3:"web";s:7:"default";}'),
('all_langs', 'a:1:{i:0;s:2:"ru";}'),
('lang', 'ru'),
('module', 'index'),
('action', 'index'),
('reg', '1'),
('last_clean', '1425769634'),
('counters', '<a href="http://mobtop.ru/in/668"><img src="http://mobtop.ru/668.gif" border="0" alt="MobTop - top mobile rating" /></a>'),
('antiflood', 'a:5:{s:4:"mode";i:4;s:3:"day";i:10;s:5:"night";i:30;s:7:"dayfrom";i:10;s:5:"dayto";i:22;}'),
('onlinetime', '300'),
('mail_max_sub', '50'),
('max_av_size', '400'),
('site_name', 'BC.com'),
('site_name_main', 'Банкротный шпион'),
('max_post_file', '1000'),
('keywords', ''),
('description', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ds_smiles`
--

CREATE TABLE IF NOT EXISTS `ds_smiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refid` int(11) NOT NULL,
  `type` varchar(2) NOT NULL,
  `pattern` text NOT NULL,
  `image` text NOT NULL,
  `adm` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=322 ;

--
-- Дамп данных таблицы `ds_smiles`
--

INSERT INTO `ds_smiles` (`id`, `refid`, `type`, `pattern`, `image`, `adm`) VALUES
(1, 0, 'ct', 'Эмоции', '', 0),
(2, 0, 'ct', 'Спорт', '', 0),
(3, 0, 'ct', 'Техника', '', 0),
(116, 115, 'sm', ':офигеть: :ofiget:', '116.png', 0),
(163, 1, 'sm', ':facepalm: :фейспалм:', '162.gif', 0),
(7, 0, 'ct', 'Животные', '', 0),
(10, 0, 'ct', 'Жесты', '', 0),
(11, 0, 'ct', 'Праздники', '', 0),
(12, 0, 'ct', 'Разное', '', 0),
(13, 0, 'ct', 'Для администрации', '', 1),
(14, 1, 'sm', ':ah: :ах:', '14.gif', 0),
(15, 1, 'sm', ':bis: :бис:', '15.gif', 0),
(16, 1, 'sm', ':blabla: :блабла:', '16.gif', 0),
(17, 1, 'sm', ':bratok: :браток:', '17.gif', 0),
(18, 1, 'sm', ':cry: :цры:', '18.gif', 0),
(19, 1, 'sm', ':dovolen: :доволен:', '19.gif', 0),
(20, 115, 'sm', ':emo: :емо: :эмо:', '20.gif', 0),
(21, 1, 'sm', ':fuu: :фуу:', '21.gif', 0),
(22, 1, 'sm', ':gg: :гг:', '22.gif', 0),
(23, 1, 'sm', ':gy: :гы:', '23.gif', 0),
(24, 1, 'sm', ':gyy: :гыы:', '24.gif', 0),
(25, 1, 'sm', ':ha: :ха:', '25.gif', 0),
(26, 1, 'sm', ':hack: :хацк:', '26.gif', 0),
(27, 1, 'sm', ':haha: :хаха:', '27.gif', 0),
(28, 1, 'sm', ':helpme: :хелпме:', '28.gif', 0),
(29, 1, 'sm', ':hm: :хм:', '29.gif', 0),
(30, 1, 'sm', ':hrap: :храп:', '30.gif', 0),
(31, 1, 'sm', ':isterika: :истерика:', '31.gif', 0),
(32, 115, 'sm', ':killseva: :киллсева:', '32.gif', 0),
(33, 1, 'sm', ':kraken: :кракен:', '33.gif', 0),
(34, 1, 'sm', ':krasn: :красн:', '34.gif', 0),
(35, 1, 'sm', ':kub: :куб:', '35.gif', 0),
(36, 1, 'sm', ':lol: :лол:', '36.gif', 0),
(37, 1, 'sm', ':plak: :плак:', '37.gif', 0),
(38, 1, 'sm', ':preved: :превед:', '38.gif', 0),
(39, 1, 'sm', ':rofl: :рофл:', '39.gif', 0),
(40, 1, 'sm', ':rzhu: :ржу:', '40.gif', 0),
(41, 115, 'sm', ':seva: :сева:', '41.gif', 0),
(42, 1, 'sm', ':shok: :щок:', '42.gif', 0),
(43, 1, 'sm', ':sljuni: :слйуни:', '43.gif', 0),
(44, 1, 'sm', ':sorry: :сорры:', '44.gif', 0),
(45, 1, 'sm', ':stena: :стена:', '45.gif', 0),
(46, 1, 'sm', ':swoon: :сшоон: :vosadok: восадок:', '46.gif', 0),
(47, 1, 'sm', ':yahoo: :яхоо:', '47.gif', 0),
(48, 1, 'sm', ':zloj: :злой:', '48.gif', 0),
(49, 2, 'sm', ':beg: :бег:', '49.gif', 0),
(50, 2, 'sm', ':best: :бест:', '50.gif', 0),
(51, 2, 'sm', ':boks: :бокс:', '51.gif', 0),
(52, 2, 'sm', ':mjach: :мйач:', '52.gif', 0),
(53, 3, 'sm', ':nokia: :нокиа:', '53.gif', 0),
(54, 3, 'sm', ':samolet :самолет:', '54.gif', 0),
(55, 3, 'sm', ':skuter: :скутер:', '55.gif', 0),
(56, 3, 'sm', ':velo: :вело:', '56.gif', 0),
(57, 13, 'sm', ':adm: :адм:', '57.gif', 1),
(58, 13, 'sm', ':ban: :бан:', '58.gif', 1),
(59, 13, 'sm', ':ban2: :бан2:', '59.gif', 1),
(60, 13, 'sm', ':ban3: :бан3:', '60.gif', 1),
(61, 13, 'sm', ':closed: :цлосед:', '61.gif', 1),
(62, 13, 'sm', ':devil: :девил:', '62.gif', 1),
(63, 13, 'sm', ':dont: :донт:', '63.gif', 1),
(64, 13, 'sm', ':flood: :флоод:', '64.gif', 1),
(65, 13, 'sm', ':huligan: :хулиган:', '65.gif', 1),
(66, 13, 'sm', ':moder: :модер:', '66.gif', 1),
(67, 13, 'sm', ':moder2: :модер2:', '67.gif', 1),
(68, 13, 'sm', ':offtop: :оффтоп:', '68.gif', 1),
(69, 13, 'sm', ':pravila: :правила:', '69.gif', 1),
(70, 120, 'sm', ':selov: :селов:', '70.gif', 0),
(71, 12, 'sm', ':russia: :россия:', '71.gif', 0),
(72, 12, 'sm', ':anm: :анм:', '72.gif', 0),
(73, 12, 'sm', ':anm2: :анм2:', '73.gif', 0),
(74, 12, 'sm', ':banan: :банан:', '74.gif', 0),
(75, 12, 'sm', ':belarus: :беларус: :беларусь:', '75.gif', 0),
(76, 12, 'sm', ':demrule: :демруле:', '76.gif', 0),
(77, 12, 'sm', ':fire: :фире:', '77.gif', 0),
(78, 12, 'sm', ':kopilka: :копилка:', '78.gif', 0),
(79, 12, 'sm', ':mail: :маил:', '79.gif', 0),
(80, 120, 'sm', ':php: :пхп: :пых:', '80.gif', 0),
(81, 12, 'sm', ':pylesos: :пылесос:', '81.gif', 0),
(82, 12, 'sm', ':teleskop: :телескоп:', '82.gif', 0),
(83, 12, 'sm', ':vampir: :вампир:', '83.gif', 0),
(84, 12, 'sm', ':vizard: :визард:', '84.gif', 0),
(85, 12, 'sm', ':yad: :яд:', '85.gif', 0),
(86, 11, 'sm', ':ded_moroz: :дед_мороз:', '86.gif', 0),
(87, 11, 'sm', ':ded_snegur2: :дед_снегур2:', '87.gif', 0),
(88, 11, 'sm', ':snegur: :снегур:', '88.gif', 0),
(89, 10, 'sm', ':apl: :апл:', '89.gif', 0),
(90, 10, 'sm', ':clapping: :цлаппинг:', '90.gif', 0),
(91, 10, 'sm', ':druj: :друй:', '91.gif', 0),
(92, 10, 'sm', ':druzhba: :дружба:', '92.gif', 0),
(93, 10, 'sm', ':kul2: :кул2:', '93.gif', 0),
(94, 10, 'sm', ':neznayu: :незнаю: :neznaet:', '94.gif', 0),
(95, 10, 'sm', ':nono: :ноно:', '95.gif', 0),
(96, 10, 'sm', ':sarkastik: :саркастик:', '96.gif', 0),
(97, 105, 'sm', ':-D :D', '97.gif', 0),
(98, 105, 'sm', ':-( :(', '98.gif', 0),
(99, 105, 'sm', ':)', '99.gif', 0),
(100, 105, 'sm', ':-)', '100.gif', 0),
(101, 105, 'sm', ';P ;-P', '101.gif', 0),
(115, 0, 'ct', 'Персонажи', '', 0),
(103, 105, 'sm', ':?', '103.gif', 0),
(104, 105, 'sm', ':@', '104.gif', 0),
(105, 0, 'ct', 'Быстрые смайлы', '', 0),
(106, 105, 'sm', ';-)', '106.gif', 0),
(107, 105, 'sm', ';)', '107.gif', 0),
(108, 105, 'sm', '8-)', '108.gif', 0),
(109, 105, 'sm', ':*', '109.gif', 0),
(110, 12, 'sm', ':sho: :шо:', '110.gif', 0),
(111, 12, 'sm', ':yy: :ыы:', '111.gif', 0),
(112, 115, 'sm', ':ktulhu: :ктулху:', '112.gif', 0),
(113, 12, 'sm', ':patstulom: :подстулом:', '113.gif', 0),
(114, 105, 'sm', ':!', '114.gif', 0),
(117, 12, 'sm', ':зона: :zona:', '117.gif', 0),
(118, 115, 'sm', ':дудл: :doodle:', '118.png', 0),
(119, 2, 'sm', ':basket: :баскет:', '119.gif', 0),
(120, 0, 'ct', 'Значки', '', 0),
(121, 120, 'sm', ':torrent: :торрент:', '121.png', 0),
(122, 120, 'sm', ':бт: :bt:', '122.png', 0),
(123, 120, 'sm', ':linux: :линукс:', '123.png', 0),
(294, 120, 'sm', ':opera: :опера:', '294.png', 0),
(293, 120, 'sm', ':windows: :винда:', '293.png', 0),
(296, 120, 'sm', ':java: :ява:', '296.png', 0),
(127, 120, 'sm', ':se: :SE:', '127.gif', 0),
(128, 120, 'sm', ':walkman:', '128.gif', 0),
(129, 120, 'sm', ':java2: :ява2:', '129.gif', 0),
(130, 120, 'sm', ':strela: :стрела: :туда:', '130.gif', 0),
(131, 120, 'sm', ':ahtung: :ахтунг:', '131.gif', 0),
(132, 120, 'sm', ':cpp: :c++:', '132.png', 0),
(133, 115, 'sm', ':princess: :принцесса:', '133.gif', 0),
(134, 115, 'sm', ':princess2: :принцесса2:', '134.gif', 0),
(135, 115, 'sm', ':gotess: :готесса:', '135.gif', 0),
(292, 120, 'sm', ':vk: :вк:', '292.png', 0),
(144, 1, 'sm', ':biggrin:', '144.gif', 0),
(145, 115, 'sm', ':bart:', '145.gif', 0),
(147, 115, 'sm', ':webdemon:', '146.gif', 0),
(148, 115, 'sm', ':scream:', '148.gif', 0),
(149, 120, 'sm', ':666:', '149.gif', 0),
(150, 3, 'sm', ':helloworld:', '150.gif', 0),
(151, 1, 'sm', ':dum: :дум:', '151.gif', 0),
(152, 1, 'sm', ':fu: :фу:', '152.gif', 0),
(153, 120, 'sm', ':miha: :миха:', '153.png', 0),
(154, 115, 'sm', ':bender: :бендер:', '154.gif', 0),
(155, 7, 'sm', ':kot: :kote: :кот: :котэ:', '155.png', 0),
(156, 7, 'sm', ':mudr: :мудр:', '156.png', 0),
(282, 12, 'sm', ':zub2: :зуб2:', '267.gif', 0),
(158, 120, 'sm', ':oblivion: :обливион:', '158.png', 0),
(159, 115, 'sm', ':cap: :кэп:', '159.png', 0),
(160, 12, 'sm', ':idea:', '160.gif', 0),
(161, 12, 'sm', ':von: :вонь:', '161.png', 0),
(164, 1, 'sm', ':aaa: :ааа:', '164.gif', 0),
(165, 1, 'sm', ':aj: :ай:', '165.gif', 0),
(166, 1, 'sm', ':facepalm2: :фейспалм2:', '166.gif', 0),
(167, 1, 'sm', ':gordo: :гордо:', '167.gif', 0),
(168, 1, 'sm', ':ispug: :испуг:', '168.gif', 0),
(169, 10, 'sm', ':kul3: :кул3:', '169.gif', 0),
(170, 1, 'sm', ':plushit: :плющит:', '170.gif', 0),
(171, 1, 'sm', ':zharko: :жарко:', '171.gif', 0),
(172, 1, 'sm', ':nus: :нус:', '172.gif', 0),
(173, 1, 'sm', ':umora: :умора:', '173.gif', 0),
(174, 10, 'sm', ':nopanic: :нопаник:', '174.gif', 0),
(175, 10, 'sm', ':kul: :cool: :кул:', '175.gif', 0),
(176, 10, 'sm', ':es: :ес:', '176.gif', 0),
(177, 10, 'sm', ':molchu: :молчу:', '177.gif', 0),
(178, 10, 'sm', ':victory: :победа:', '178.gif', 0),
(179, 10, 'sm', ':ploho: :плохо:', '179.gif', 0),
(180, 10, 'sm', ':superski: :суперски:', '180.gif', 0),
(181, 105, 'sm', ':-/', '181.gif', 0),
(182, 105, 'sm', ':-*', '182.gif', 0),
(183, 2, 'sm', ':plavaet: :плавает:', '183.gif', 0),
(184, 2, 'sm', ':rybalka: :рыбалка:', '184.gif', 0),
(185, 2, 'sm', ':tennis: :теннис:', '185.gif', 0),
(186, 2, 'sm', ':tennis2: :теннис2:', '186.gif', 0),
(191, 3, 'sm', ':tank: :танк:', '191.gif', 0),
(192, 3, 'sm', ':bolid: :болид:', '192.gif', 0),
(193, 7, 'sm', ':akula: :акула:', '193.gif', 0),
(194, 7, 'sm', ':babochka: :бабочка:', '194.gif', 0),
(195, 115, 'sm', ':ghost: :гост:', '195.gif', 0),
(196, 7, 'sm', ':kitti: :китти:', '196.gif', 0),
(197, 7, 'sm', ':martywka: :мартышка:', '197.gif', 0),
(198, 10, 'sm', ':fuckoff: :факофф:', '198.gif', 0),
(200, 7, 'sm', ':pingvin: :пингвин:', '200.gif', 0),
(206, 2, 'sm', ':hokkej: :хоккей:', '206.gif', 0),
(207, 2, 'sm', ':wtanga: :штанга:', '207.gif', 0),
(213, 2, 'sm', ':fan: :фан:', '213.gif', 0),
(214, 2, 'sm', ':fanaty: :фанаты:', '214.gif', 0),
(215, 12, 'sm', ':2druga: :2друга:', '215.gif', 0),
(216, 12, 'sm', ':benzopila: :бензопила:', '216.gif', 0),
(217, 12, 'sm', ':derg: :дерг:', '217.gif', 0),
(218, 3, 'sm', ':lamer: :ламер: :ламер2:', '218.gif', 0),
(219, 115, 'sm', ':puh: :пух:', '219.gif', 0),
(221, 12, 'sm', ':draka: :драка:', '221.gif', 0),
(222, 12, 'sm', ':palach: :палач:', '222.gif', 0),
(223, 12, 'sm', ':puwki: :пушки:', '223.gif', 0),
(224, 12, 'sm', ':starwar: :старвар:', '224.gif', 0),
(225, 105, 'sm', ':P :Р', '225.gif', 0),
(226, 12, 'sm', ':vglaz: :вглаз:', '226.gif', 0),
(227, 11, 'sm', ':dr: :др:', '227.gif', 0),
(228, 11, 'sm', ':tost: :тост:', '228.gif', 0),
(291, 120, 'sm', ':chrome: :хром:', '291.png', 0),
(230, 11, 'sm', ':pivanet: :пиванет:', '230.gif', 0),
(232, 1, 'sm', ':or: :ор:', '232.gif', 0),
(233, 105, 'sm', ':O :О', '233.gif', 0),
(234, 120, 'sm', ':ps: :пс:', '234.gif', 0),
(235, 11, 'sm', ':kruzhki: :кружки:', '235.gif', 0),
(236, 11, 'sm', ':alkawi: :алкаши:', '236.gif', 0),
(237, 11, 'sm', ':pivo2: :пиво2:', '237.gif', 0),
(238, 11, 'sm', ':pivo3: :пиво3:', '238.gif', 0),
(239, 115, 'sm', ':holms: :холмс:', '239.gif', 0),
(240, 12, 'sm', ':chit: :чит:', '240.gif', 0),
(241, 12, 'sm', ':dozhd: :rain: :дождь:', '241.gif', 0),
(242, 12, 'sm', ':kur: :кур:', '242.gif', 0),
(243, 12, 'sm', ':night: :найт:', '243.gif', 0),
(244, 12, 'sm', ':sortir: :сортир:', '244.gif', 0),
(245, 12, 'sm', ':zvezdy: :звезды:', '245.gif', 0),
(246, 115, 'sm', ':angel: :ангел:', '246.gif', 0),
(247, 115, 'sm', ':cherep: :череп:', '247.gif', 0),
(248, 115, 'sm', ':mertvec: :мертвец:', '248.gif', 0),
(249, 115, 'sm', ':skosoj: :скосой:', '249.gif', 0),
(250, 115, 'sm', ':zlodej: :злодей:', '250.gif', 0),
(251, 120, 'sm', ':bomba: :бомба:', '251.gif', 0),
(252, 115, 'sm', ':killer: :киллер:', '252.gif', 0),
(253, 10, 'sm', ':figa: :фига:', '253.gif', 0),
(254, 10, 'sm', ':sumas: :сумас:', '254.gif', 0),
(255, 10, 'sm', ':sumas2: :сумас2:', '255.gif', 0),
(256, 1, 'sm', ':wow: :вов:', '256.gif', 0),
(257, 12, 'sm', ':hackcat: :хаккот:', '257.gif', 0),
(259, 120, 'sm', ':ppm:', '259.png', 0),
(260, 11, 'sm', ':salut: :салют:', '260.gif', 0),
(261, 11, 'sm', ':salut2: :салют2:', '261.gif', 0),
(262, 11, 'sm', ':salut3: :салют3:', '262.gif', 0),
(263, 7, 'sm', ':angrycat: :zlojkot:', '263.png', 0),
(265, 7, 'sm', ':nya:', '264.png', 0),
(266, 12, 'sm', ':желудь: :zhelud:', '266.gif', 0),
(283, 1, 'sm', ':wow2: :вов2:', '283.png', 0),
(284, 12, 'sm', ':owibochka: :ошибочка:', '284.gif', 0),
(285, 12, 'sm', ':vsempr: :всемпр:', '285.gif', 0),
(286, 12, 'sm', ':google: :гугл:', '286.gif', 0),
(287, 13, 'sm', ':flood2: :флоод2:', '287.gif', 1),
(288, 115, 'sm', ':girl: :герл:', '288.gif', 0),
(289, 12, 'sm', ':ogurec: :огурец:', '289.png', 0),
(290, 12, 'sm', ':ogurec2: :огурец2:', '290.png', 0),
(295, 120, 'sm', ':android: :андроид:', '295.png', 0),
(297, 120, 'sm', ':csharp: :сишарп:', '297.png', 0),
(298, 120, 'sm', ':python: :питон:', '298.png', 0),
(299, 12, 'sm', ':siski: :сиски: :сиськи:', '299.gif', 0),
(300, 12, 'sm', ':oops: :упс:', '300.gif', 0),
(301, 10, 'sm', ':nevozm: :невозм:', '301.gif', 0),
(302, 1, 'sm', ':mat: :мат:', '302.gif', 0),
(303, 10, 'sm', ':stranno: :странно:', '303.gif', 0),
(304, 7, 'sm', ':lowadka: :лошадка:', '304.gif', 0),
(305, 12, 'sm', ':ogurjum: :огурюм:', '305.gif', 0),
(306, 7, 'sm', ':horseshe: :хорсеще:', '306.gif', 0),
(307, 7, 'sm', ':nasobakah: :насобаках:', '307.gif', 0),
(308, 12, 'sm', ':tomat: :томат:', '308.gif', 0),
(309, 115, 'sm', ':lenin: :ленин:', '309.png', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `ds_users`
--

CREATE TABLE IF NOT EXISTS `ds_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `sex` varchar(2) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `rights` int(11) NOT NULL,
  `info` text NOT NULL,
  `settings` text NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `lastvisit` int(11) unsigned NOT NULL,
  `comm_plus` int(11) unsigned NOT NULL DEFAULT '0',
  `comm_minus` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` int(10) unsigned NOT NULL,
  `ua` text NOT NULL,
  `lang` varchar(3) NOT NULL,
  `module` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `lastpost` int(11) unsigned NOT NULL,
  `avtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lastvisit` (`lastvisit`),
  KEY `module` (`module`),
  KEY `action` (`action`),
  KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `ds_users`
--

INSERT INTO `ds_users` (`id`, `login`, `password`, `sex`, `mail`, `rights`, `info`, `settings`, `time`, `lastvisit`, `comm_plus`, `comm_minus`, `ip`, `ua`, `lang`, `module`, `action`, `lastpost`, `avtime`) VALUES
(1, 'web_demon', '55d92f2a38b79d70096a35f4bb91a726', 'm', 'web_demon@annimon.com', 100, 'a:8:{s:4:"name";s:14:"Алексей";s:4:"from";s:16:"Беларусь";s:5:"phone";s:5:"с902";s:4:"site";s:20:"http://wikimobile.su";s:3:"icq";s:9:"578263699";s:9:"interests";s:36:"Программирование PHP";s:5:"about";a:2:{i:0;s:304:"[b]Добрый и пуФФыстый[/b], нет, это не про меня. Я маньяк, убийца, злобная тварь, ненавижу всех, и готовлю план по захвату Земли. ХА-хА-Ха!!!\r\n[youtube]http://www.youtube.com/watch?v=8SbUC-UaAxE[/youtube]";i:1;s:0:"";}s:3:"age";s:9:"5.12.1991";}', 'a:1:{s:10:"themecolor";s:6:"0BA1B8";}', 1336254925, 1425769646, 0, 0, 2130706433, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:37.0) Gecko/20100101 Firefox/37.0', '', 'user', 'profile', 1422550153, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `ds_users_inactive`
--

CREATE TABLE IF NOT EXISTS `ds_users_inactive` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `key` varchar(32) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `sex` varchar(2) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `ua` text NOT NULL,
  `lang` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
