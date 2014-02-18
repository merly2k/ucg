-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.27 - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных graber
CREATE DATABASE IF NOT EXISTS `graber` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `graber`;


-- Дамп структуры для таблица graber.ghelp
CREATE TABLE IF NOT EXISTS `ghelp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `help_article` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.grabed
CREATE TABLE IF NOT EXISTS `grabed` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `razdel` varchar(50) NOT NULL,
  `site` varchar(250) NOT NULL,
  `full_URI` varchar(250) NOT NULL,
  `zagolovok` varchar(250) NOT NULL,
  `g_content` text NOT NULL,
  `published` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `full_URI` (`full_URI`),
  FULLTEXT KEY `g_content` (`g_content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `acl` int(10) NOT NULL DEFAULT '1',
  `menu_name` varchar(50) NOT NULL,
  `meny_link` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'ss_accept',
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.ppt
CREATE TABLE IF NOT EXISTS `ppt` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) DEFAULT NULL,
  `search_text` text,
  `repl_text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='задачи для постпроцессинга';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.publikate
CREATE TABLE IF NOT EXISTS `publikate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `razdel` varchar(50) NOT NULL,
  `site` varchar(50) NOT NULL,
  `full_URI` varchar(250) NOT NULL,
  `zagolovok` varchar(250) NOT NULL,
  `g_content` text NOT NULL,
  `published` varchar(50) NOT NULL,
  `to_pasword` varchar(50) NOT NULL,
  `to_site` varchar(50) DEFAULT NULL,
  `to_login` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `full_URI` (`full_URI`),
  FULLTEXT KEY `g_content` (`g_content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.rss_sites
CREATE TABLE IF NOT EXISTS `rss_sites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `razdel` varchar(255) DEFAULT NULL,
  `base_url` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `pravilo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.sites
CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `razdel` varchar(50) NOT NULL,
  `base_url` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `grab_mask` text NOT NULL,
  `start_url` varchar(50) DEFAULT NULL,
  `end_url` varchar(50) DEFAULT NULL,
  `pravilo` varchar(50) DEFAULT NULL,
  `start` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.translations
CREATE TABLE IF NOT EXISTS `translations` (
  `id` mediumint(6) unsigned NOT NULL,
  `language_id` tinyint(4) unsigned NOT NULL,
  `tag` text,
  `page_id` mediumint(6) unsigned NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  KEY `translations_idx` (`language_id`,`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Holds all tags (translated and untranslated) of a page ID.';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.translation_languages
CREATE TABLE IF NOT EXISTS `translation_languages` (
  `id` tinyint(4) unsigned NOT NULL,
  `code` char(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Holds all defined languages and link a user browser language';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.urllist_sites
CREATE TABLE IF NOT EXISTS `urllist_sites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `razdel` varchar(255) DEFAULT NULL,
  `base_url` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `grab_mask` varchar(255) DEFAULT NULL,
  `pravilo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `s_name` varchar(50) DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `mail` varchar(50) NOT NULL,
  `registred` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cod` int(10) DEFAULT NULL,
  `phone` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`,`mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица graber.user_accounts
CREATE TABLE IF NOT EXISTS `user_accounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) DEFAULT '0',
  `system` varchar(50) DEFAULT '0',
  `url` varchar(50) DEFAULT '0',
  `user` varchar(50) DEFAULT '0',
  `pasword` varchar(50) DEFAULT '0',
  `list_url` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
