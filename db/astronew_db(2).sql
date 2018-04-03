-- Adminer 4.0.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+00:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `another_persons`;
CREATE TABLE `another_persons` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `added_by` bigint(20) NOT NULL COMMENT 'shows which user has been added this person',
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` tinyint(2) NOT NULL,
  `dob` date NOT NULL,
  `day` varchar(20) NOT NULL,
  `time` varchar(10) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `language_id` tinyint(2) NOT NULL DEFAULT '1',
  `zone` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `created` varchar(255) NOT NULL,
  `modified` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `sun_sign_code` varchar(10) NOT NULL,
  `answer_code` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `answer_code` (`answer_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `asked_questions`;
CREATE TABLE `asked_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(50) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_ids` varchar(128) NOT NULL,
  `answers` varchar(128) NOT NULL,
  `sun_sign_code` varchar(10) NOT NULL,
  `answered` tinyint(2) NOT NULL COMMENT '0 = Not answered, 1 = Answered',
  `credits` varchar(50) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `answers` (`answers`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `astrologers`;
CREATE TABLE `astrologers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `gender` varchar(4) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country_id` int(10) unsigned NOT NULL,
  `biography` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `languages` text NOT NULL,
  `timezone_id` int(10) unsigned NOT NULL,
  `on_vacation` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1=no, 2=yes',
  `mode` varchar(50) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `birthdata`;
CREATE TABLE `birthdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` tinyint(4) NOT NULL DEFAULT '0',
  `month` tinyint(4) NOT NULL DEFAULT '0',
  `year` int(11) NOT NULL DEFAULT '0',
  `untimed` char(1) NOT NULL DEFAULT '0',
  `hour` varchar(5) NOT NULL DEFAULT '0',
  `minute` varchar(5) NOT NULL DEFAULT '0',
  `gmt` char(1) NOT NULL DEFAULT '',
  `zoneref` decimal(11,2) NOT NULL DEFAULT '0.00',
  `summerref` decimal(11,2) NOT NULL DEFAULT '0.00',
  `place` varchar(100) NOT NULL DEFAULT '0',
  `state` varchar(100) NOT NULL DEFAULT '0' COMMENT 'Country Abbreviation',
  `longitude` float NOT NULL DEFAULT '0',
  `latitude` float NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `age` int(11) DEFAULT '0' COMMENT 'age for essential year report',
  `name_on_report` varchar(255) NOT NULL,
  `duration` int(11) DEFAULT '2',
  `start_date` date DEFAULT '0000-00-00',
  `gender` char(3) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orderid` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `birth_details`;
CREATE TABLE `birth_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `country_id` int(10) unsigned NOT NULL COMMENT 'this is birth country',
  `city_id` int(10) unsigned NOT NULL COMMENT 'this is brith city - this is atlast id, used as city id in registration process',
  `date` date NOT NULL,
  `day` varchar(10) DEFAULT NULL,
  `time` varchar(10) DEFAULT NULL,
  `sun_sign_id` int(10) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `zone` decimal(11,2) NOT NULL,
  `type` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `city_id` (`city_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `bookdk`;
CREATE TABLE `bookdk` (
  `bookdkid` int(11) NOT NULL AUTO_INCREMENT,
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`bookdkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bookdu`;
CREATE TABLE `bookdu` (
  `bookduid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bookge`;
CREATE TABLE `bookge` (
  `bookgeid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bookgr`;
CREATE TABLE `bookgr` (
  `bookgrid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bookno`;
CREATE TABLE `bookno` (
  `booknoid` int(11) NOT NULL AUTO_INCREMENT,
  `book` char(2) DEFAULT NULL,
  `chapter` char(2) DEFAULT NULL,
  `code3` char(2) DEFAULT NULL,
  `code4` char(2) DEFAULT NULL,
  `attitude` varchar(80) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`booknoid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `discount_text` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL,
  `button_text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `booksp`;
CREATE TABLE `booksp` (
  `bookspid` int(11) NOT NULL AUTO_INCREMENT,
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`bookspid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `booksp-old`;
CREATE TABLE `booksp-old` (
  `bookspid` int(11) NOT NULL AUTO_INCREMENT,
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`bookspid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `booksw`;
CREATE TABLE `booksw` (
  `bookswid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bookuk`;
CREATE TABLE `bookuk` (
  `bookukid` int(11) NOT NULL AUTO_INCREMENT,
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`bookukid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `slug` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `city_index` int(11) NOT NULL,
  `country_id` bigint(11) NOT NULL COMMENT 'country_index ACS country id',
  `city` varchar(255) NOT NULL,
  `county` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `latitude` int(11) NOT NULL,
  `longitude` int(11) NOT NULL,
  `typetable` int(11) NOT NULL,
  `zonetable` int(11) NOT NULL,
  `countydup` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `city` (`city`),
  KEY `county` (`county`),
  KEY `country` (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cities_old`;
CREATE TABLE `cities_old` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `city_index` int(11) NOT NULL,
  `country_id` bigint(11) NOT NULL COMMENT 'country_index ACS country id',
  `city` varchar(255) NOT NULL,
  `county` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `latitude` int(11) NOT NULL,
  `longitude` int(11) NOT NULL,
  `typetable` int(11) NOT NULL,
  `zonetable` int(11) NOT NULL,
  `countydup` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `city` (`city`),
  KEY `county` (`county`),
  KEY `country` (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `comment_files`;
CREATE TABLE `comment_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(200) DEFAULT NULL,
  `support_ticket_id` int(10) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_ticket_id` (`support_ticket_id`),
  CONSTRAINT `comment_files_ibfk_1` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `copy_xmls`;
CREATE TABLE `copy_xmls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `language` varchar(10) NOT NULL,
  `day` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `untimed` tinyint(2) NOT NULL DEFAULT '0',
  `hour` int(11) NOT NULL,
  `minute` int(11) NOT NULL,
  `gmt` char(1) NOT NULL,
  `zoneref` decimal(11,2) NOT NULL,
  `summerref` decimal(11,2) NOT NULL,
  `place` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `gender` char(3) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `abbr` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cname` (`name`),
  KEY `abbr` (`abbr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `symbol` varchar(5) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `deleted_asked_questions`;
CREATE TABLE `deleted_asked_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asked_question_id` int(11) NOT NULL,
  `ip_address` varbinary(50) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_ids` varchar(128) NOT NULL,
  `answers` varchar(128) NOT NULL,
  `sun_sign_code` varchar(10) NOT NULL,
  `answered` tinyint(2) NOT NULL COMMENT '0 = Not answered, 1 = Answered',
  `credits` varchar(50) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asked_question_id` (`asked_question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `delivery_options`;
CREATE TABLE `delivery_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `elite_members`;
CREATE TABLE `elite_members` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `cover_page` varchar(255) NOT NULL,
  `footer` varchar(255) NOT NULL,
  `currency` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `membership_upgrade` tinyint(2) NOT NULL DEFAULT '0',
  `created` varchar(255) NOT NULL,
  `modified` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `variables` text NOT NULL,
  `short_code` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `guest_user_product_details`;
CREATE TABLE `guest_user_product_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `language_id` int(11) NOT NULL,
  `portal_id` int(11) NOT NULL,
  `payment_status` tinyint(2) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `i18n`;
CREATE TABLE `i18n` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(6) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(10) NOT NULL,
  `field` varchar(255) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `I18N_FIELD` (`model`,`foreign_key`,`field`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `import_birthdata`;
CREATE TABLE `import_birthdata` (
  `importid` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `gender` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'M',
  `isunknown` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `birthdateday` tinyint(4) NOT NULL,
  `birthdatemonth` tinyint(4) NOT NULL,
  `birthdateyear` int(11) NOT NULL,
  `birthdatehour` tinyint(4) NOT NULL,
  `birthdateminute` tinyint(4) NOT NULL,
  `birthplace` varchar(100) NOT NULL,
  `atlastid` int(11) DEFAULT NULL,
  `birthcountry` varchar(50) NOT NULL,
  `birthcountryabbrev` varchar(10) CHARACTER SET latin1 NOT NULL,
  `birthcountryid` int(11) DEFAULT NULL,
  `clatitude` varchar(20) CHARACTER SET latin1 NOT NULL,
  `clongitude` varchar(20) CHARACTER SET latin1 NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `summerref` varchar(20) NOT NULL,
  `zoneref` varchar(20) NOT NULL,
  `isfavorite` tinyint(1) NOT NULL DEFAULT '0',
  `notes` varchar(2500) DEFAULT NULL,
  `identity_name` varchar(100) DEFAULT NULL,
  `importeddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`importid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stored the exported data from software';


DROP TABLE IF EXISTS `import_tokens`;
CREATE TABLE `import_tokens` (
  `tokenid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `createdtime` datetime NOT NULL,
  `expiredtime` datetime DEFAULT NULL,
  PRIMARY KEY (`tokenid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ip_addresses`;
CREATE TABLE `ip_addresses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `log_status` tinyint(2) unsigned NOT NULL COMMENT '0 = for signup user from sunsign page popup, 1 = if user loggedin within 30days',
  `created` varchar(200) NOT NULL,
  `end_date` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `ip_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `language_category` int(11) NOT NULL DEFAULT '0' COMMENT '0 = website, 1 = product, 2 = both website and product language',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `lovers_report_data`;
CREATE TABLE `lovers_report_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_name` varchar(200) NOT NULL,
  `gender` char(3) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `birth_data_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `page_id` int(10) unsigned NOT NULL DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `menu_type` varchar(10) NOT NULL DEFAULT 'top',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `mini_blogs`;
CREATE TABLE `mini_blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(200) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `sort_order` tinyint(2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_acsatlas`;
CREATE TABLE `npm_acsatlas` (
  `acsatlasid` int(11) NOT NULL AUTO_INCREMENT,
  `lkey` varchar(255) NOT NULL DEFAULT '',
  `placename` varchar(255) NOT NULL DEFAULT '',
  `region` varchar(255) NOT NULL DEFAULT '',
  `latitude` int(11) NOT NULL DEFAULT '0',
  `longitude` int(11) NOT NULL DEFAULT '0',
  `zone` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acsatlasid`),
  KEY `ix_acsatlas_lkey` (`lkey`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_affiliate`;
CREATE TABLE `npm_affiliate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `enabled` enum('enabled','disabled') NOT NULL DEFAULT 'disabled',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_associate`;
CREATE TABLE `npm_associate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `enabled` enum('enabled','disabled') NOT NULL DEFAULT 'disabled',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_birthdata`;
CREATE TABLE `npm_birthdata` (
  `birthdataid` int(11) NOT NULL AUTO_INCREMENT,
  `day` tinyint(4) NOT NULL DEFAULT '0',
  `month` tinyint(4) NOT NULL DEFAULT '0',
  `year` int(11) NOT NULL DEFAULT '0',
  `untimed` char(1) NOT NULL DEFAULT '',
  `hour` tinyint(4) NOT NULL DEFAULT '0',
  `minute` tinyint(4) NOT NULL DEFAULT '0',
  `gender` int(11) DEFAULT '0' COMMENT 'This was not there before 19-Apr-2014, Added By Amit Parmar',
  `gmt` char(1) NOT NULL DEFAULT '',
  `zoneref` int(11) NOT NULL DEFAULT '0',
  `summerref` int(11) NOT NULL DEFAULT '0',
  `place` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `longitude` float NOT NULL DEFAULT '0',
  `latitude` float NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`birthdataid`),
  KEY `orderid` (`orderid`),
  KEY `orderid_2` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_bookdk`;
CREATE TABLE `npm_bookdk` (
  `bookdkid` int(11) NOT NULL AUTO_INCREMENT,
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`bookdkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_bookdu`;
CREATE TABLE `npm_bookdu` (
  `bookduid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_bookge`;
CREATE TABLE `npm_bookge` (
  `bookgeid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_bookgr`;
CREATE TABLE `npm_bookgr` (
  `bookgrid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_bookno`;
CREATE TABLE `npm_bookno` (
  `booknoid` int(11) NOT NULL AUTO_INCREMENT,
  `book` char(2) DEFAULT NULL,
  `chapter` char(2) DEFAULT NULL,
  `code3` char(2) DEFAULT NULL,
  `code4` char(2) DEFAULT NULL,
  `attitude` varchar(80) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`booknoid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_bookno_safe`;
CREATE TABLE `npm_bookno_safe` (
  `book` char(2) DEFAULT NULL,
  `chapter` char(2) DEFAULT NULL,
  `code3` char(2) DEFAULT NULL,
  `code4` char(2) DEFAULT NULL,
  `attitude` varchar(80) DEFAULT NULL,
  `text` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_bookpt`;
CREATE TABLE `npm_bookpt` (
  `book` char(2) DEFAULT NULL,
  `chapter` char(2) DEFAULT NULL,
  `code3` char(2) DEFAULT NULL,
  `code4` char(2) DEFAULT NULL,
  `attitude` varchar(80) DEFAULT NULL,
  `text` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_booksp`;
CREATE TABLE `npm_booksp` (
  `bookspid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_booksw`;
CREATE TABLE `npm_booksw` (
  `bookswid` int(11) NOT NULL DEFAULT '0',
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_bookuk`;
CREATE TABLE `npm_bookuk` (
  `bookukid` int(11) NOT NULL AUTO_INCREMENT,
  `book` varchar(255) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `code3` varchar(255) DEFAULT NULL,
  `code4` varchar(255) DEFAULT NULL,
  `attitude` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`bookukid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_book_no_NO`;
CREATE TABLE `npm_book_no_NO` (
  `book` char(2) DEFAULT NULL,
  `chapter` char(2) DEFAULT NULL,
  `code3` char(2) DEFAULT NULL,
  `code4` char(2) DEFAULT NULL,
  `attitude` varchar(80) DEFAULT NULL,
  `text` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_currency`;
CREATE TABLE `npm_currency` (
  `currencyid` int(11) NOT NULL AUTO_INCREMENT,
  `abbrev` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`currencyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_deliveryaddress`;
CREATE TABLE `npm_deliveryaddress` (
  `deliveryaddressid` int(11) NOT NULL AUTO_INCREMENT,
  `line1` varchar(255) NOT NULL DEFAULT '',
  `line2` varchar(255) NOT NULL DEFAULT '',
  `town` varchar(255) NOT NULL DEFAULT '',
  `postcode` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(255) NOT NULL DEFAULT '',
  `orderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`deliveryaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_deliveryoption`;
CREATE TABLE `npm_deliveryoption` (
  `deliveryoptionid` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`deliveryoptionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_emailaddress`;
CREATE TABLE `npm_emailaddress` (
  `emailaddressid` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`emailaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_emailsubscriber`;
CREATE TABLE `npm_emailsubscriber` (
  `emailsubscriberid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `emailaddress` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `softwarename` varchar(255) DEFAULT NULL,
  `download_date` datetime DEFAULT NULL,
  `email_no` int(11) DEFAULT '1',
  `language` varchar(255) DEFAULT NULL,
  `hear_about_us` varchar(255) DEFAULT NULL,
  `portalid` bigint(255) DEFAULT NULL,
  `download_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`emailsubscriberid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_gash`;
CREATE TABLE `npm_gash` (
  `reportoptionid` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `duration` tinyint(4) NOT NULL DEFAULT '0',
  `language` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `paper_size` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `emailaddressid` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_hi_places_uk`;
CREATE TABLE `npm_hi_places_uk` (
  `stateid` char(4) CHARACTER SET utf8 DEFAULT NULL,
  `placename` char(40) CHARACTER SET utf8 DEFAULT NULL,
  `latitude` int(11) DEFAULT NULL,
  `longitude` int(11) DEFAULT NULL,
  `zoneref` char(4) CHARACTER SET utf8 DEFAULT NULL,
  `summerref` char(4) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_horarydata`;
CREATE TABLE `npm_horarydata` (
  `horarydataid` int(11) NOT NULL AUTO_INCREMENT,
  `day` tinyint(4) NOT NULL DEFAULT '0',
  `month` tinyint(4) NOT NULL DEFAULT '0',
  `year` int(11) NOT NULL DEFAULT '0',
  `hour` tinyint(4) NOT NULL DEFAULT '0',
  `minute` tinyint(4) NOT NULL DEFAULT '0',
  `gmt` char(20) NOT NULL,
  `zoneref` int(11) NOT NULL DEFAULT '0',
  `summerref` int(11) NOT NULL DEFAULT '0',
  `place` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `longitude` float NOT NULL DEFAULT '0',
  `latitude` float NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`horarydataid`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_hourlytrend`;
CREATE TABLE `npm_hourlytrend` (
  `hourlytrendid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text,
  PRIMARY KEY (`hourlytrendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_hourlytrends`;
CREATE TABLE `npm_hourlytrends` (
  `trendid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text,
  PRIMARY KEY (`trendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_hourlytrends_dk`;
CREATE TABLE `npm_hourlytrends_dk` (
  `trendid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text,
  PRIMARY KEY (`trendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_hourlytrend_dk`;
CREATE TABLE `npm_hourlytrend_dk` (
  `hourlytrend_dkid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text,
  PRIMARY KEY (`hourlytrend_dkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_lovematch`;
CREATE TABLE `npm_lovematch` (
  `p1` tinyint(4) NOT NULL DEFAULT '0',
  `p2` tinyint(4) NOT NULL DEFAULT '0',
  `analysis` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_lovematch_safe`;
CREATE TABLE `npm_lovematch_safe` (
  `p1` tinyint(4) NOT NULL DEFAULT '0',
  `p2` tinyint(4) NOT NULL DEFAULT '0',
  `analysis` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_order`;
CREATE TABLE `npm_order` (
  `orderid` int(11) NOT NULL AUTO_INCREMENT,
  `portalid` int(11) NOT NULL DEFAULT '0',
  `received_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `productid` int(11) NOT NULL DEFAULT '0',
  `delivery_option` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `currency` int(11) NOT NULL DEFAULT '0',
  `value` double NOT NULL DEFAULT '0',
  `payment_method` int(11) DEFAULT '1' COMMENT ' PayPal Standard Checkout | 2 = NETS Credit Card Checkout',
  PRIMARY KEY (`orderid`),
  KEY `portalid` (`portalid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_orm_acsatlas`;
CREATE TABLE `npm_orm_acsatlas` (
  `acsatlasid` int(11) NOT NULL DEFAULT '0',
  `lkey` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `placename` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `region` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `latitude` int(11) NOT NULL DEFAULT '0',
  `longitude` int(11) NOT NULL DEFAULT '0',
  `zone` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acsatlasid`),
  KEY `acsatlasid` (`acsatlasid`),
  KEY `lkey` (`lkey`),
  KEY `placename` (`placename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_paymentgateway`;
CREATE TABLE `npm_paymentgateway` (
  `paymentgatewayid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`paymentgatewayid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_paypaltransaction`;
CREATE TABLE `npm_paypaltransaction` (
  `paypaltransactionid` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0',
  `payment_date` date NOT NULL DEFAULT '0000-00-00',
  `payment_status` varchar(32) NOT NULL DEFAULT '',
  `payment_type` varchar(32) NOT NULL DEFAULT '',
  `first_name` varchar(32) NOT NULL DEFAULT '',
  `last_name` varchar(32) NOT NULL DEFAULT '',
  `payer_email` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(32) DEFAULT NULL,
  `parent_transaction_id` varchar(32) DEFAULT NULL,
  `mc_currency` varchar(8) NOT NULL DEFAULT '',
  `mc_gross` float NOT NULL DEFAULT '0',
  `mc_fee` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`paypaltransactionid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_portal`;
CREATE TABLE `npm_portal` (
  `portalid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`portalid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_pricing`;
CREATE TABLE `npm_pricing` (
  `pricingid` int(11) NOT NULL AUTO_INCREMENT,
  `portalid` int(11) NOT NULL DEFAULT '0',
  `productid` int(11) NOT NULL DEFAULT '0',
  `currencyid` int(11) NOT NULL DEFAULT '0',
  `deliveryoptionid` int(11) NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT '0',
  `handling` double DEFAULT '0',
  `tax` double DEFAULT '0',
  PRIMARY KEY (`pricingid`),
  KEY `portalid` (`portalid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_product`;
CREATE TABLE `npm_product` (
  `productid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_refer`;
CREATE TABLE `npm_refer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `arid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `arid` (`arid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_reportoption`;
CREATE TABLE `npm_reportoption` (
  `reportoptionid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `duration` tinyint(4) NOT NULL DEFAULT '0',
  `language` varchar(2) NOT NULL DEFAULT '',
  `paper_size` varchar(2) NOT NULL DEFAULT '',
  `emailaddressid` int(11) NOT NULL DEFAULT '0',
  `format` varchar(5) NOT NULL DEFAULT 'pdf',
  PRIMARY KEY (`reportoptionid`),
  KEY `orderid` (`orderid`,`emailaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_report_pages_log`;
CREATE TABLE `npm_report_pages_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `total_pages` int(11) NOT NULL,
  `new_pages` bigint(20) NOT NULL DEFAULT '0',
  `log_date` date NOT NULL,
  `is_processed` int(11) NOT NULL DEFAULT '0' COMMENT 'If 0 need to reprocess and 1 then stop',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_shorttermtrend`;
CREATE TABLE `npm_shorttermtrend` (
  `shorttermtrendid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text,
  `txtques1` text,
  `txtques2` text,
  `txtques3` text,
  `txtans1` text,
  `txtans2` text,
  `txtans3` text,
  PRIMARY KEY (`shorttermtrendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_shorttermtrend_dk`;
CREATE TABLE `npm_shorttermtrend_dk` (
  `shorttermtrend_dkid` int(11) NOT NULL DEFAULT '0',
  `trendtext` text CHARACTER SET utf8,
  `txtques1` text CHARACTER SET utf8,
  `txtques2` text CHARACTER SET utf8,
  `txtques3` text CHARACTER SET utf8,
  `txtans1` text CHARACTER SET utf8,
  `txtans2` text CHARACTER SET utf8,
  `txtans3` text CHARACTER SET utf8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_shorttermtrend_dk_original`;
CREATE TABLE `npm_shorttermtrend_dk_original` (
  `shorttermtrend_dkid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text,
  `txtques1` text,
  `txtques2` text,
  `txtques3` text,
  `txtans1` text,
  `txtans2` text,
  `txtans3` text,
  PRIMARY KEY (`shorttermtrend_dkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_shorttermtrend_se`;
CREATE TABLE `npm_shorttermtrend_se` (
  `shorttermtrend_seid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text NOT NULL,
  `txtques1` text NOT NULL,
  `txtques2` text NOT NULL,
  `txtques3` text NOT NULL,
  `txtans1` text NOT NULL,
  `txtans2` text NOT NULL,
  `txtans3` text NOT NULL,
  PRIMARY KEY (`shorttermtrend_seid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_state`;
CREATE TABLE `npm_state` (
  `stateid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`stateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_sunsign`;
CREATE TABLE `npm_sunsign` (
  `sunsignid` int(11) NOT NULL AUTO_INCREMENT,
  `scope` char(1) NOT NULL DEFAULT '',
  `language` char(2) NOT NULL DEFAULT '',
  `schedule_date` date NOT NULL DEFAULT '0000-00-00',
  `sign` tinyint(4) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`sunsignid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_synastryorder`;
CREATE TABLE `npm_synastryorder` (
  `synastryorderid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `name1` varchar(32) NOT NULL DEFAULT '',
  `birthdata1` int(11) NOT NULL DEFAULT '0',
  `name2` varchar(32) NOT NULL DEFAULT '',
  `birthdata2` int(11) NOT NULL DEFAULT '0',
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `auxgender` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`synastryorderid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_testplaces`;
CREATE TABLE `npm_testplaces` (
  `stateid` char(4) DEFAULT NULL,
  `placename` char(40) DEFAULT NULL,
  `latitude` int(11) DEFAULT NULL,
  `longitude` int(11) DEFAULT NULL,
  `zoneref` char(4) DEFAULT NULL,
  `summerref` char(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_testplaces2`;
CREATE TABLE `npm_testplaces2` (
  `placeid` int(11) NOT NULL AUTO_INCREMENT,
  `stateid` char(4) DEFAULT NULL,
  `placename` char(40) DEFAULT NULL,
  `latitude` int(11) DEFAULT NULL,
  `longitude` int(11) DEFAULT NULL,
  `zoneref` char(4) DEFAULT NULL,
  `summerref` char(4) DEFAULT NULL,
  PRIMARY KEY (`placeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_teststates`;
CREATE TABLE `npm_teststates` (
  `statename` char(40) DEFAULT NULL,
  `stateid` char(4) DEFAULT NULL,
  `maxlatitude` int(11) DEFAULT NULL,
  `minlatitude` int(11) DEFAULT NULL,
  `maxlongitude` int(11) DEFAULT NULL,
  `minlongitude` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_testsummer`;
CREATE TABLE `npm_testsummer` (
  `summerid` char(4) DEFAULT NULL,
  `fromdate` int(11) DEFAULT NULL,
  `fromtime` int(11) DEFAULT NULL,
  `timediff` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_testzone`;
CREATE TABLE `npm_testzone` (
  `zoneid` char(4) DEFAULT NULL,
  `startdate` int(11) DEFAULT NULL,
  `timedelta` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_transaction`;
CREATE TABLE `npm_transaction` (
  `transactionid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`transactionid`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_trend`;
CREATE TABLE `npm_trend` (
  `trendid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text,
  `txtques1` text,
  `txtques2` text,
  `txtques3` text,
  `txtans1` text,
  `txtans2` text,
  `txtans3` text,
  PRIMARY KEY (`trendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `npm_user`;
CREATE TABLE `npm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL DEFAULT '',
  `emailaddress` varchar(80) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT 'DEADBEEFDEADBEEFDEADBEEFDEADBEEF',
  `contacttype` enum('admin','primary','user') NOT NULL DEFAULT 'user',
  `state` enum('enabled','disabled','suspended') NOT NULL DEFAULT 'suspended',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_yearbook_downloaders`;
CREATE TABLE `npm_yearbook_downloaders` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `emailid` varchar(200) NOT NULL,
  `regdate` datetime NOT NULL,
  `language` varchar(20) NOT NULL,
  `portalid` int(11) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_year_book_dk`;
CREATE TABLE `npm_year_book_dk` (
  `year_book_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint(20) DEFAULT NULL,
  `planet_code1` varchar(20) DEFAULT NULL,
  `aspect_id` varchar(10) DEFAULT NULL,
  `planet_code12` varchar(20) DEFAULT NULL,
  `aspect_strength` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `planet_direction` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `aspect_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8,
  `title` mediumtext CHARACTER SET utf8,
  `short_text` mediumtext CHARACTER SET utf8,
  `tester_text` mediumtext CHARACTER SET utf8,
  `chapter_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `find_param` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`year_book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_year_book_dk_agetext`;
CREATE TABLE `npm_year_book_dk_agetext` (
  `age_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `age_no` bigint(20) DEFAULT NULL,
  `age_text` mediumtext CHARACTER SET utf8,
  `find_param` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`age_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_year_book_en`;
CREATE TABLE `npm_year_book_en` (
  `year_book_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint(20) DEFAULT NULL,
  `planet_code1` varchar(20) DEFAULT NULL,
  `aspect_id` varchar(10) DEFAULT NULL,
  `planet_code12` varchar(20) DEFAULT NULL,
  `aspect_strength` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `planet_direction` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `aspect_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8,
  `title` mediumtext CHARACTER SET utf8,
  `short_text` mediumtext CHARACTER SET utf8,
  `tester_text` mediumtext CHARACTER SET utf8,
  `chapter_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `find_param` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`year_book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `npm_year_book_en_agetext`;
CREATE TABLE `npm_year_book_en_agetext` (
  `age_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `age_no` bigint(20) DEFAULT NULL,
  `age_text` mediumtext CHARACTER SET utf8,
  `find_param` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`age_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(100) DEFAULT NULL,
  `price` decimal(11,2) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `another_person` tinyint(2) DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `payer_order_id` varchar(100) NOT NULL,
  `delivery_option` int(4) DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `confirm_payment_date` datetime DEFAULT NULL,
  `product_type` varchar(10) DEFAULT '0',
  `chk_for_register` tinyint(1) DEFAULT '0',
  `currency_id` varchar(10) DEFAULT NULL,
  `shipping_charge` decimal(11,2) DEFAULT '0.00',
  `language_id` varchar(10) NOT NULL,
  `portal_id` int(3) NOT NULL DEFAULT '2',
  `payment_method` int(11) DEFAULT '1' COMMENT '1 = PayPal         Standard Checkout | 2 = NETS Credit Card Checkout, 3 = Astocoins ',
  `order_by` tinyint(2) DEFAULT '0' COMMENT '0 = Using web portal (Full Report - paid), 1 = Using AstroClock App (Mini Report), 2 = Using web portal (Full Report - free), 3 => Using Admin Panel(Mini Report)',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `order_shippings`;
CREATE TABLE `order_shippings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `address_1` varchar(200) DEFAULT NULL,
  `address_2` varchar(200) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `order_transactions`;
CREATE TABLE `order_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_status` varchar(50) NOT NULL,
  `payment_date` datetime NOT NULL,
  `order_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `payer_email` varchar(255) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `payer_order_id` varchar(255) DEFAULT NULL,
  `transaction_no` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payer_order_id` (`payer_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `seo_url` varchar(255) NOT NULL,
  `meta_title` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_url` (`seo_url`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `portals`;
CREATE TABLE `portals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `preview_reports`;
CREATE TABLE `preview_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `pdf` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` varchar(20) NOT NULL,
  `pr_number` smallint(3) unsigned zerofill NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_detail` varchar(255) NOT NULL,
  `pages` smallint(5) unsigned NOT NULL,
  `parent_id` varchar(20) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `seo_url` varchar(255) NOT NULL,
  `meta_title` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_url` (`seo_url`),
  UNIQUE KEY `pr_number` (`pr_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `product_languages`;
CREATE TABLE `product_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `product_prices`;
CREATE TABLE `product_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `currency_id` int(10) unsigned NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `vat` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `discount_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `discount_vat` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `discount_total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_type_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `product_types`;
CREATE TABLE `product_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` varchar(4) NOT NULL DEFAULT 'NULL',
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `country_id` int(10) unsigned NOT NULL,
  `city_id` int(10) unsigned NOT NULL COMMENT 'This is atlast id, used as city id in registration process',
  `zip` varchar(10) NOT NULL,
  `language_id` int(10) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `sun_sign_code` varchar(10) NOT NULL,
  `question_number` varchar(10) NOT NULL,
  `question_code` varchar(10) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_code` (`question_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `quiz_credits`;
CREATE TABLE `quiz_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `credits` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field_key` varchar(255) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `shorttermtrend`;
CREATE TABLE `shorttermtrend` (
  `shorttermtrendid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text,
  `txtques1` text,
  `txtques2` text,
  `txtques3` text,
  `txtans1` text,
  `txtans2` text,
  `txtans3` text,
  PRIMARY KEY (`shorttermtrendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shorttermtrend_dk`;
CREATE TABLE `shorttermtrend_dk` (
  `shorttermtrend_dkid` int(11) NOT NULL DEFAULT '0',
  `trendtext` text CHARACTER SET utf8,
  `txtques1` text CHARACTER SET utf8,
  `txtques2` text CHARACTER SET utf8,
  `txtques3` text CHARACTER SET utf8,
  `txtans1` text CHARACTER SET utf8,
  `txtans2` text CHARACTER SET utf8,
  `txtans3` text CHARACTER SET utf8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `shorttermtrend_se`;
CREATE TABLE `shorttermtrend_se` (
  `shorttermtrend_seid` int(11) NOT NULL AUTO_INCREMENT,
  `trendtext` text NOT NULL,
  `txtques1` text NOT NULL,
  `txtques2` text NOT NULL,
  `txtques3` text NOT NULL,
  `txtans1` text NOT NULL,
  `txtans2` text NOT NULL,
  `txtans3` text NOT NULL,
  PRIMARY KEY (`shorttermtrend_seid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `social_app_keys`;
CREATE TABLE `social_app_keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `app_key` varchar(255) DEFAULT NULL,
  `app_secret` varchar(255) DEFAULT NULL,
  `oauth_token` varchar(255) DEFAULT NULL,
  `oauth_secret` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) DEFAULT '0',
  `status` tinyint(2) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `subscribes`;
CREATE TABLE `subscribes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `amount` varchar(20) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `created` varchar(255) NOT NULL,
  `modified` varchar(255) NOT NULL,
  `previous_db_id` int(11) unsigned NOT NULL,
  `previous_subscription_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `daily_sun_sign` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `weekly_sun_sign` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `daily_personal_horoscope` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `astrology_articles` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `special_offers` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sun_signs`;
CREATE TABLE `sun_signs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `date` varchar(120) DEFAULT NULL,
  `icon` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `characteristics` text NOT NULL,
  `celebrity` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sun_sign_predictions`;
CREATE TABLE `sun_sign_predictions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sun_sign_id` int(11) NOT NULL,
  `scope` int(2) NOT NULL,
  `prediction` text CHARACTER SET utf8 NOT NULL,
  `schedule_date` date DEFAULT NULL,
  `language` varchar(20) DEFAULT 'en',
  PRIMARY KEY (`id`),
  KEY `FK_sunsignprediction` (`sun_sign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `support_tickets`;
CREATE TABLE `support_tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `commented_by` tinyint(2) NOT NULL COMMENT '1 = Admin, 2 = User',
  `status` tinyint(2) DEFAULT '1' COMMENT '1 = open, 2 = closed',
  `handled_by` tinyint(2) DEFAULT NULL COMMENT '1 = Nethues, 2= Adrian, 3 = Other',
  `approved` tinyint(2) DEFAULT '0' COMMENT '0 = Rejected, 1 = Approved, 2=Pending',
  `approved_on` datetime DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `admin_message_read` tinyint(2) DEFAULT '0' COMMENT '0 = unread, 1 = read',
  `user_message_read` tinyint(2) DEFAULT '0' COMMENT '0 = unread, 1 = read',
  `mail_sent` tinyint(2) DEFAULT '0' COMMENT '0=msg sent, 1=need to send',
  `locale` tinyint(2) DEFAULT '1' COMMENT '1=en, 2=dk',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `temporary_lovers_report_data`;
CREATE TABLE `temporary_lovers_report_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temporary_order_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `name_on_report` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(4) NOT NULL,
  `birth_date` date NOT NULL,
  `birth_time` time DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `temporary_orders`;
CREATE TABLE `temporary_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(100) DEFAULT NULL,
  `price` decimal(11,2) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `name_on_report` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(4) NOT NULL,
  `birth_date` date NOT NULL,
  `birth_time` time DEFAULT NULL,
  `age` int(11) DEFAULT '0',
  `city_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `delivery_option` int(4) DEFAULT NULL,
  `product_type` int(4) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `currency_id` varchar(10) DEFAULT NULL,
  `language_id` varchar(10) NOT NULL,
  `lovers_report` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `temp_order_shippings`;
CREATE TABLE `temp_order_shippings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `address_1` varchar(200) DEFAULT NULL,
  `address_2` varchar(200) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `temp_subscribes`;
CREATE TABLE `temp_subscribes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `amount` varchar(20) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `created` varchar(255) NOT NULL,
  `modified` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `profile` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `thankyou_mail_other_actions`;
CREATE TABLE `thankyou_mail_other_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `send_to` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `action` varchar(255) NOT NULL,
  `mail_status` tinyint(2) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table is used to send mails on any action perform except product purchase product';


DROP TABLE IF EXISTS `timezones`;
CREATE TABLE `timezones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GMT` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `state_id` int(11) NOT NULL DEFAULT '0',
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user' COMMENT '2 roles - user & admin & elite',
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `step` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1=>step1, 2=> step2 - use to determine the step user has completed while first registration',
  `is_guest` tinyint(2) NOT NULL DEFAULT '0',
  `self_signup` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1=>yes, 2=>no, this is to determine whether user registers himself or admin has registered',
  `portal_id` int(10) unsigned NOT NULL DEFAULT '2',
  `reset_password_token` varchar(255) DEFAULT NULL,
  `token_created_at` datetime DEFAULT NULL,
  `preview_report` varchar(100) NOT NULL,
  `is_delete` tinyint(2) NOT NULL DEFAULT '0',
  `previous_db_id` int(10) unsigned NOT NULL DEFAULT '0',
  `old_password` varchar(255) DEFAULT NULL,
  `flag` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0 = New user, 1 = existing user, Used to migrate data password a/c to cakephp',
  `birthday_report_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `popup_user` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0=normal user, 1=popup user, 2=popup user loggedin',
  `mobile_app_user` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0=User registration using desktop, 1=User registration using mobile App',
  `profile_updated` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0=not updated, 1=user profile updated',
  `astro_coins` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `unique_token` (`reset_password_token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `user_testimonials`;
CREATE TABLE `user_testimonials` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `user_profile` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `user_thankyou_mails`;
CREATE TABLE `user_thankyou_mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_type` int(11) NOT NULL,
  `mail_status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_type` (`product_type`),
  CONSTRAINT `user_thankyou_mails_ibfk_1` FOREIGN KEY (`product_type`) REFERENCES `product_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table is used to send mails on any product purchase';


DROP TABLE IF EXISTS `user_year_report_transit`;
CREATE TABLE `user_year_report_transit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `HittingDate` date NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `year_book_id` bigint(20) NOT NULL,
  `AspectType` varchar(10) NOT NULL,
  `Aspect` varchar(10) NOT NULL,
  `PRTOPR_Date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `wow_acsatlas`;
CREATE TABLE `wow_acsatlas` (
  `acsatlasid` int(11) NOT NULL AUTO_INCREMENT,
  `lkey` varchar(255) NOT NULL DEFAULT '',
  `placename` varchar(255) NOT NULL DEFAULT '',
  `region` varchar(255) NOT NULL DEFAULT '',
  `latitude` int(11) NOT NULL DEFAULT '0',
  `longitude` int(11) NOT NULL DEFAULT '0',
  `zone` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acsatlasid`),
  KEY `ix_acsatlas_lkey` (`lkey`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `wow_birthdata`;
CREATE TABLE `wow_birthdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` tinyint(4) NOT NULL DEFAULT '0',
  `month` tinyint(4) NOT NULL DEFAULT '0',
  `year` int(11) NOT NULL DEFAULT '0',
  `untimed` char(1) NOT NULL DEFAULT '0',
  `hour` varchar(5) NOT NULL DEFAULT '0',
  `minute` varchar(5) NOT NULL DEFAULT '0',
  `gmt` char(1) NOT NULL DEFAULT '',
  `zoneref` decimal(11,2) NOT NULL DEFAULT '0.00',
  `summerref` decimal(11,2) NOT NULL DEFAULT '0.00',
  `place` varchar(100) NOT NULL DEFAULT '0',
  `state` varchar(100) NOT NULL DEFAULT '0' COMMENT 'Country Abbreviation',
  `longitude` float NOT NULL DEFAULT '0',
  `latitude` float NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `age` int(11) DEFAULT '0' COMMENT 'age for essential year report',
  `name_on_report` varchar(255) NOT NULL,
  `duration` int(11) DEFAULT '2',
  `start_date` date DEFAULT '0000-00-00',
  `gender` char(3) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orderid` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wow_deliveryaddress`;
CREATE TABLE `wow_deliveryaddress` (
  `deliveryaddressid` int(11) NOT NULL AUTO_INCREMENT,
  `line1` varchar(255) NOT NULL DEFAULT '',
  `line2` varchar(255) NOT NULL DEFAULT '',
  `town` varchar(255) NOT NULL DEFAULT '',
  `postcode` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(255) NOT NULL DEFAULT '',
  `orderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`deliveryaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wow_orders`;
CREATE TABLE `wow_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(100) DEFAULT NULL,
  `price` decimal(11,2) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `another_person` tinyint(2) DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `payer_order_id` varchar(100) NOT NULL,
  `delivery_option` int(4) DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `confirm_payment_date` datetime DEFAULT NULL,
  `product_type` varchar(10) DEFAULT '0',
  `chk_for_register` tinyint(1) DEFAULT '0',
  `currency_id` varchar(10) DEFAULT NULL,
  `shipping_charge` decimal(11,2) DEFAULT '0.00',
  `language_id` varchar(10) NOT NULL,
  `portal_id` int(3) NOT NULL DEFAULT '2',
  `payment_method` int(11) DEFAULT '1' COMMENT '1 = PayPal         Standard Checkout | 2 = NETS Credit Card Checkout',
  `format` varchar(5) DEFAULT 'pdf',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `wow_portal`;
CREATE TABLE `wow_portal` (
  `portalid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`portalid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wow_pricing`;
CREATE TABLE `wow_pricing` (
  `pricingid` int(11) NOT NULL AUTO_INCREMENT,
  `portalid` int(11) NOT NULL DEFAULT '0',
  `productid` int(11) NOT NULL DEFAULT '0',
  `currencyid` int(11) NOT NULL DEFAULT '0',
  `deliveryoptionid` int(11) NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT '0',
  `handling` double DEFAULT '0',
  `tax` double DEFAULT '0',
  PRIMARY KEY (`pricingid`),
  KEY `portalid` (`portalid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wow_product`;
CREATE TABLE `wow_product` (
  `productid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wow_products`;
CREATE TABLE `wow_products` (
  `productid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `wp_acsatlas`;
CREATE TABLE `wp_acsatlas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `city_index` int(11) NOT NULL,
  `country_id` bigint(11) NOT NULL COMMENT 'country_index ACS country id',
  `city` varchar(255) NOT NULL,
  `county` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `latitude` int(11) NOT NULL,
  `longitude` int(11) NOT NULL,
  `typetable` int(11) NOT NULL,
  `zonetable` int(11) NOT NULL,
  `countydup` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `city` (`city`),
  KEY `county` (`county`),
  KEY `country` (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wp_commentmeta`;
CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_comments`;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8_unicode_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_countries`;
CREATE TABLE `wp_countries` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cname` varchar(200) CHARACTER SET utf8 NOT NULL,
  `abbr` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cname` (`cname`),
  KEY `abbr` (`abbr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_currency`;
CREATE TABLE `wp_currency` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(30) CHARACTER SET latin1 NOT NULL,
  `currency_symbol` varchar(10) CHARACTER SET latin1 NOT NULL,
  `currency_code` varchar(10) CHARACTER SET latin1 NOT NULL,
  `currency_status` enum('Active','Deactive') CHARACTER SET latin1 NOT NULL DEFAULT 'Active',
  `iso_code` int(5) NOT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Helps to manage currency for orders';


DROP TABLE IF EXISTS `wp_icl_content_status`;
CREATE TABLE `wp_icl_content_status` (
  `rid` bigint(20) NOT NULL,
  `nid` bigint(20) NOT NULL,
  `timestamp` datetime NOT NULL,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_core_status`;
CREATE TABLE `wp_icl_core_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) NOT NULL,
  `module` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_flags`;
CREATE TABLE `wp_icl_flags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_template` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_code` (`lang_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_languages`;
CREATE TABLE `wp_icl_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `english_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `major` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL,
  `default_locale` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `encode_url` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `english_name` (`english_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_languages_translations`;
CREATE TABLE `wp_icl_languages_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_language_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_code` (`language_code`,`display_language_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_locale_map`;
CREATE TABLE `wp_icl_locale_map` (
  `code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `code` (`code`,`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_message_status`;
CREATE TABLE `wp_icl_message_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) unsigned NOT NULL,
  `object_id` bigint(20) unsigned NOT NULL,
  `from_language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid` (`rid`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_node`;
CREATE TABLE `wp_icl_node` (
  `nid` bigint(20) NOT NULL,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `links_fixed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_reminders`;
CREATE TABLE `wp_icl_reminders` (
  `id` bigint(20) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_delete` tinyint(4) NOT NULL,
  `show` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_strings`;
CREATE TABLE `wp_icl_strings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` varchar(160) CHARACTER SET utf8 NOT NULL,
  `name` varchar(160) CHARACTER SET utf8 NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `string_package_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LINE',
  `title` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `gettext_context` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain_name_context_md5` varchar(32) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_domain_name_context_md5` (`domain_name_context_md5`),
  KEY `language_context` (`language`,`context`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_string_packages`;
CREATE TABLE `wp_icl_string_packages` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kind_slug` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kind` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edit_link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `view_link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_string_pages`;
CREATE TABLE `wp_icl_string_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `string_id` bigint(20) NOT NULL,
  `url_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `string_to_url_id` (`url_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_string_positions`;
CREATE TABLE `wp_icl_string_positions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `string_id` bigint(20) NOT NULL,
  `kind` tinyint(4) DEFAULT NULL,
  `position_in_page` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `string_id` (`string_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_string_status`;
CREATE TABLE `wp_icl_string_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) NOT NULL,
  `string_translation_id` bigint(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `string_translation_id` (`string_translation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_string_translations`;
CREATE TABLE `wp_icl_string_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `string_id` bigint(20) unsigned NOT NULL,
  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `translator_id` bigint(20) unsigned DEFAULT NULL,
  `translation_service` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `batch_id` int(11) NOT NULL DEFAULT '0',
  `translation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `string_language` (`string_id`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_string_urls`;
CREATE TABLE `wp_icl_string_urls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `string_string_lang_url` (`language`,`url`(191))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_translate`;
CREATE TABLE `wp_icl_translate` (
  `tid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` bigint(20) unsigned NOT NULL,
  `content_id` bigint(20) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `field_type` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_format` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_translate` tinyint(4) NOT NULL,
  `field_data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_data_translated` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_finished` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `job_id` (`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_translate_job`;
CREATE TABLE `wp_icl_translate_job` (
  `job_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) unsigned NOT NULL,
  `translator_id` int(10) unsigned NOT NULL,
  `translated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `manager_id` int(10) unsigned NOT NULL,
  `revision` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`job_id`),
  KEY `rid` (`rid`,`translator_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_translations`;
CREATE TABLE `wp_icl_translations` (
  `translation_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `element_type` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post_post',
  `element_id` bigint(20) DEFAULT NULL,
  `trid` bigint(20) NOT NULL,
  `language_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_language_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`translation_id`),
  UNIQUE KEY `trid_lang` (`trid`,`language_code`),
  UNIQUE KEY `el_type_id` (`element_type`,`element_id`),
  KEY `trid` (`trid`),
  KEY `id_type_language` (`element_id`,`element_type`,`language_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_translation_batches`;
CREATE TABLE `wp_icl_translation_batches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tp_id` int(11) DEFAULT NULL,
  `ts_url` text COLLATE utf8mb4_unicode_ci,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_icl_translation_status`;
CREATE TABLE `wp_icl_translation_status` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `translation_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `translator_id` bigint(20) NOT NULL,
  `needs_update` tinyint(4) NOT NULL,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `translation_service` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` int(11) NOT NULL DEFAULT '0',
  `translation_package` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `links_fixed` tinyint(4) NOT NULL DEFAULT '0',
  `_prevstate` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `translation_id` (`translation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_languages`;
CREATE TABLE `wp_languages` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `language_code` varchar(10) CHARACTER SET latin1 NOT NULL,
  `language_local` varchar(15) CHARACTER SET latin1 NOT NULL,
  `language_status` enum('Active','Deactive') CHARACTER SET latin1 NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_links`;
CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_new_aiowps_events`;
CREATE TABLE `wp_new_aiowps_events` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `username` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `event_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip_or_host` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `referer_info` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `country_code` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `event_data` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `wp_new_aiowps_failed_logins`;
CREATE TABLE `wp_new_aiowps_failed_logins` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_login` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `failed_login_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `login_attempt_ip` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `wp_new_aiowps_global_meta`;
CREATE TABLE `wp_new_aiowps_global_meta` (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `meta_key1` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_key2` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_key3` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_key4` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_key5` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_value1` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_value2` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_value3` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_value4` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_value5` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `wp_new_aiowps_login_activity`;
CREATE TABLE `wp_new_aiowps_login_activity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_login` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `login_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `logout_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `login_ip` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `login_country` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `browser_type` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `wp_new_aiowps_login_lockdown`;
CREATE TABLE `wp_new_aiowps_login_lockdown` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_login` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `lockdown_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `release_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `failed_login_ip` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `lock_reason` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `unlock_key` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `wp_new_aiowps_permanent_block`;
CREATE TABLE `wp_new_aiowps_permanent_block` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `blocked_ip` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `block_reason` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `country_origin` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `blocked_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `unblock` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `wp_new_commentmeta`;
CREATE TABLE `wp_new_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_comments`;
CREATE TABLE `wp_new_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10)),
  KEY `woo_idx_comment_type` (`comment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_content_status`;
CREATE TABLE `wp_new_icl_content_status` (
  `rid` bigint(20) NOT NULL,
  `nid` bigint(20) NOT NULL,
  `timestamp` datetime NOT NULL,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `nid` (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_core_status`;
CREATE TABLE `wp_new_icl_core_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) NOT NULL,
  `module` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_flags`;
CREATE TABLE `wp_new_icl_flags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_template` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_code` (`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_languages`;
CREATE TABLE `wp_new_icl_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `english_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `major` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL,
  `default_locale` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `encode_url` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `english_name` (`english_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_languages_translations`;
CREATE TABLE `wp_new_icl_languages_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_language_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_code` (`language_code`,`display_language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_locale_map`;
CREATE TABLE `wp_new_icl_locale_map` (
  `code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `code` (`code`,`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_message_status`;
CREATE TABLE `wp_new_icl_message_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) unsigned NOT NULL,
  `object_id` bigint(20) unsigned NOT NULL,
  `from_language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid` (`rid`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_node`;
CREATE TABLE `wp_new_icl_node` (
  `nid` bigint(20) NOT NULL,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `links_fixed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_reminders`;
CREATE TABLE `wp_new_icl_reminders` (
  `id` bigint(20) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_delete` tinyint(4) NOT NULL,
  `show` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_strings`;
CREATE TABLE `wp_new_icl_strings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `context_name` (`context`,`name`),
  KEY `language_context` (`language`,`context`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_string_positions`;
CREATE TABLE `wp_new_icl_string_positions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `string_id` bigint(20) NOT NULL,
  `kind` tinyint(4) DEFAULT NULL,
  `position_in_page` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `string_id` (`string_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_string_status`;
CREATE TABLE `wp_new_icl_string_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) NOT NULL,
  `string_translation_id` bigint(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `string_translation_id` (`string_translation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_string_translations`;
CREATE TABLE `wp_new_icl_string_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `string_id` bigint(20) unsigned NOT NULL,
  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `translator_id` bigint(20) unsigned DEFAULT NULL,
  `translation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `string_language` (`string_id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_translate`;
CREATE TABLE `wp_new_icl_translate` (
  `tid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` bigint(20) unsigned NOT NULL,
  `content_id` bigint(20) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `field_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_format` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_translate` tinyint(4) NOT NULL,
  `field_data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_data_translated` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_finished` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `job_id` (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_translate_job`;
CREATE TABLE `wp_new_icl_translate_job` (
  `job_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) unsigned NOT NULL,
  `translator_id` int(10) unsigned NOT NULL,
  `translated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `manager_id` int(10) unsigned NOT NULL,
  `revision` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`job_id`),
  KEY `rid` (`rid`,`translator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_translations`;
CREATE TABLE `wp_new_icl_translations` (
  `translation_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `element_type` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post_post',
  `element_id` bigint(20) DEFAULT NULL,
  `trid` bigint(20) NOT NULL,
  `language_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_language_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`translation_id`),
  UNIQUE KEY `trid_lang` (`trid`,`language_code`),
  UNIQUE KEY `el_type_id` (`element_type`,`element_id`),
  KEY `trid` (`trid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_icl_translation_status`;
CREATE TABLE `wp_new_icl_translation_status` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `translation_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `translator_id` bigint(20) NOT NULL,
  `needs_update` tinyint(4) NOT NULL,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `translation_service` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `translation_package` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `links_fixed` tinyint(4) NOT NULL DEFAULT '0',
  `_prevstate` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `translation_id` (`translation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_links`;
CREATE TABLE `wp_new_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_options`;
CREATE TABLE `wp_new_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_postmeta`;
CREATE TABLE `wp_new_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_posts`;
CREATE TABLE `wp_new_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_termmeta`;
CREATE TABLE `wp_new_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_terms`;
CREATE TABLE `wp_new_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_term_relationships`;
CREATE TABLE `wp_new_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_term_taxonomy`;
CREATE TABLE `wp_new_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_usermeta`;
CREATE TABLE `wp_new_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_users`;
CREATE TABLE `wp_new_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_api_keys`;
CREATE TABLE `wp_new_woocommerce_api_keys` (
  `key_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consumer_key` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consumer_secret` char(43) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nonces` longtext COLLATE utf8mb4_unicode_ci,
  `truncated_key` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`key_id`),
  KEY `consumer_key` (`consumer_key`),
  KEY `consumer_secret` (`consumer_secret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_attribute_taxonomies`;
CREATE TABLE `wp_new_woocommerce_attribute_taxonomies` (
  `attribute_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_label` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_orderby` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_public` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`attribute_id`),
  KEY `attribute_name` (`attribute_name`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_downloadable_product_permissions`;
CREATE TABLE `wp_new_woocommerce_downloadable_product_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `download_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `order_key` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `downloads_remaining` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_granted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access_expires` datetime DEFAULT NULL,
  `download_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`permission_id`),
  KEY `download_order_key_product` (`product_id`,`order_id`,`order_key`(16),`download_id`),
  KEY `download_order_product` (`download_id`,`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_log`;
CREATE TABLE `wp_new_woocommerce_log` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `level` smallint(4) NOT NULL,
  `source` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`log_id`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_order_itemmeta`;
CREATE TABLE `wp_new_woocommerce_order_itemmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `meta_key` (`meta_key`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_order_items`;
CREATE TABLE `wp_new_woocommerce_order_items` (
  `order_item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_item_type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `order_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_payment_tokenmeta`;
CREATE TABLE `wp_new_woocommerce_payment_tokenmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_token_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `payment_token_id` (`payment_token_id`),
  KEY `meta_key` (`meta_key`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_payment_tokens`;
CREATE TABLE `wp_new_woocommerce_payment_tokens` (
  `token_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gateway_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_sessions`;
CREATE TABLE `wp_new_woocommerce_sessions` (
  `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_key` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_expiry` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`session_key`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_shipping_zones`;
CREATE TABLE `wp_new_woocommerce_shipping_zones` (
  `zone_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone_order` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_shipping_zone_locations`;
CREATE TABLE `wp_new_woocommerce_shipping_zone_locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` bigint(20) unsigned NOT NULL,
  `location_code` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `location_id` (`location_id`),
  KEY `location_type_code` (`location_type`(10),`location_code`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_shipping_zone_methods`;
CREATE TABLE `wp_new_woocommerce_shipping_zone_methods` (
  `zone_id` bigint(20) unsigned NOT NULL,
  `instance_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `method_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_order` bigint(20) unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`instance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_tax_rates`;
CREATE TABLE `wp_new_woocommerce_tax_rates` (
  `tax_rate_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tax_rate_country` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tax_rate_state` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tax_rate` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tax_rate_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tax_rate_priority` bigint(20) unsigned NOT NULL,
  `tax_rate_compound` int(1) NOT NULL DEFAULT '0',
  `tax_rate_shipping` int(1) NOT NULL DEFAULT '1',
  `tax_rate_order` bigint(20) unsigned NOT NULL,
  `tax_rate_class` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_rate_id`),
  KEY `tax_rate_country` (`tax_rate_country`),
  KEY `tax_rate_state` (`tax_rate_state`(2)),
  KEY `tax_rate_class` (`tax_rate_class`(10)),
  KEY `tax_rate_priority` (`tax_rate_priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_new_woocommerce_tax_rate_locations`;
CREATE TABLE `wp_new_woocommerce_tax_rate_locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location_code` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_rate_id` bigint(20) unsigned NOT NULL,
  `location_type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `location_type_code` (`location_type`(10),`location_code`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wp_options`;
CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_options_280817`;
CREATE TABLE `wp_options_280817` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_orders`;
CREATE TABLE `wp_orders` (
  `order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_type` enum('Free','Paid','Subscription') CHARACTER SET latin1 NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email_id` varchar(150) CHARACTER SET latin1 NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `order_price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `order_discount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `shipping_charges` decimal(18,2) DEFAULT '0.00',
  `delivery_option` enum('Email','Post','Download') CHARACTER SET latin1 NOT NULL DEFAULT 'Email',
  `language_id` int(11) NOT NULL,
  `portal_id` int(11) NOT NULL,
  `payment_method_type` int(11) NOT NULL COMMENT 'PayPal - 1 | NEST - 2 | ePay = 3',
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirm_payment_date` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `product_id` (`product_id`,`product_type`,`user_id`,`email_id`,`currency_id`,`language_id`,`portal_id`,`payment_method_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Handles all types of order';


DROP TABLE IF EXISTS `wp_order_birthdata`;
CREATE TABLE `wp_order_birthdata` (
  `birth_data_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `birth_day` int(11) NOT NULL,
  `birth_month` int(11) NOT NULL,
  `birth_year` int(11) NOT NULL,
  `untimed` char(1) NOT NULL DEFAULT 'n',
  `birth_hour` int(11) NOT NULL,
  `birth_minute` int(11) NOT NULL,
  `birth_city` int(11) DEFAULT NULL,
  `birth_country` int(11) DEFAULT NULL,
  `zoneref` decimal(11,2) DEFAULT NULL,
  `summerref` decimal(11,2) DEFAULT NULL,
  `report_duration` int(11) DEFAULT '3',
  `report_start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`birth_data_id`),
  KEY `order_id` (`order_id`,`birth_day`,`birth_month`,`birth_year`,`birth_city`,`birth_country`,`report_duration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wp_order_shipping`;
CREATE TABLE `wp_order_shipping` (
  `order_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address_line_1` varchar(300) NOT NULL,
  `address_line_2` varchar(300) DEFAULT NULL,
  `shipping_city` varchar(50) NOT NULL,
  `shipping_state` varchar(50) NOT NULL,
  `shipping_country` varchar(50) NOT NULL,
  `shipping_zipcode` varchar(6) NOT NULL,
  `customer_phone` varchar(15) NOT NULL,
  PRIMARY KEY (`order_shipping_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wp_order_status`;
CREATE TABLE `wp_order_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(30) CHARACTER SET latin1 NOT NULL,
  `status_code` varchar(20) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_order_subscription`;
CREATE TABLE `wp_order_subscription` (
  `subscription_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('Pending','Running','Complated') CHARACTER SET latin1 NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Handle User subscription for calender';


DROP TABLE IF EXISTS `wp_order_tokens`;
CREATE TABLE `wp_order_tokens` (
  `order_token_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_token` varchar(40) CHARACTER SET latin1 NOT NULL,
  `download_count` int(11) DEFAULT '0',
  PRIMARY KEY (`order_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_order_transaction`;
CREATE TABLE `wp_order_transaction` (
  `transaction_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `txnid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `order_amount` decimal(18,2) DEFAULT '0.00',
  `currency_code` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `payer_full_name` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `transaction_status` varchar(20) CHARACTER SET latin1 NOT NULL,
  `transaction_date` varchar(15) CHARACTER SET latin1 NOT NULL,
  `transaction_time` varchar(10) CHARACTER SET latin1 NOT NULL,
  `transaction_fee` decimal(18,1) DEFAULT '0.0',
  `payment_type` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `card_no_paypal_reference` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `order_hash_key` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='It handles order transaction reference. Holds payment status';


DROP TABLE IF EXISTS `wp_postmeta`;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_posts`;
CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8_unicode_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_products`;
CREATE TABLE `wp_products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_type` enum('Report','Shareware','Software CD','Bundle Product','Consulting','Year Pass','eLiteModule') CHARACTER SET latin1 NOT NULL,
  `product_code` enum('YearRPT','SeasonalRPT','PersonalRPT','MiniYearRPT','MiniSeasonalRPT','MiniPersonalRPT','RelationshipRPT','YearPass','HICD','AFLCD','ACCD','HISW','AFLSW','ACSW','Consultation','eLiteCustomer') CHARACTER SET latin1 NOT NULL COMMENT 'This is for programming.',
  `product_title` varchar(300) CHARACTER SET utf8 NOT NULL,
  `short_description` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `product_description` varchar(3000) CHARACTER SET utf8 DEFAULT NULL,
  `product_status` enum('Active','Deactive') CHARACTER SET latin1 NOT NULL DEFAULT 'Active',
  `product_display_order` int(11) NOT NULL DEFAULT '1',
  `product_delivery_option` enum('Email','Post','Both Email & Post') CHARACTER SET latin1 NOT NULL DEFAULT 'Both Email & Post',
  `language_id` int(11) NOT NULL,
  `product_slug` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_slug` (`product_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_product_files`;
CREATE TABLE `wp_product_files` (
  `product_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `file_path` varchar(300) CHARACTER SET latin1 NOT NULL,
  `file_type` enum('Small','Thumbnail','Detail Page Image','Preview PDF','Download Exe') CHARACTER SET latin1 NOT NULL,
  `file_status` enum('Active','Deactive') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`product_file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_product_languages`;
CREATE TABLE `wp_product_languages` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(50) NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `language_status` enum('Active','Deactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `wp_product_language_relation`;
CREATE TABLE `wp_product_language_relation` (
  `product_lanuage_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_lanuage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `wp_product_prices`;
CREATE TABLE `wp_product_prices` (
  `product_price_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `email_price` decimal(18,2) NOT NULL,
  `email_vat_price` decimal(18,2) DEFAULT '0.00',
  `post_price` decimal(10,2) NOT NULL,
  `shipping_charges` decimal(10,2) DEFAULT '0.00',
  `post_vat_price` decimal(18,2) DEFAULT '0.00',
  `elite_customer_price` decimal(10,2) NOT NULL,
  `price_status` enum('Active','Deactive') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`product_price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_termmeta`;
CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_terms`;
CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_term_relationships`;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_term_taxonomy`;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_userbirthdetail`;
CREATE TABLE `wp_userbirthdetail` (
  `userbirthdetailId` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `day` int(4) DEFAULT NULL,
  `month` int(4) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `hours` int(4) DEFAULT NULL,
  `minutes` int(4) DEFAULT NULL,
  `seconds` int(4) DEFAULT NULL,
  `untimed` char(1) CHARACTER SET latin1 DEFAULT 'N',
  `GMT` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `zoneref` decimal(11,2) DEFAULT NULL,
  `summertimezoneref` decimal(11,2) DEFAULT NULL,
  `longitute` double DEFAULT NULL,
  `lagitute` double DEFAULT NULL,
  `country` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `state` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `city` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `country_name` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `sunsign` int(2) NOT NULL,
  `createddate` datetime DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  PRIMARY KEY (`userbirthdetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_usermeta`;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_userprofile`;
CREATE TABLE `wp_userprofile` (
  `wp_userprofileid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'fk',
  `firstname` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `lastname` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `gender` char(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `address` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `city` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `state` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `country` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `zip` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `createddate` datetime DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  PRIMARY KEY (`wp_userprofileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_users`;
CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_user_imported_data`;
CREATE TABLE `wp_user_imported_data` (
  `imported_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `day` int(4) DEFAULT NULL,
  `month` int(4) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `hours` int(4) DEFAULT NULL,
  `minutes` int(4) DEFAULT NULL,
  `seconds` int(4) DEFAULT NULL,
  `untimed` char(1) CHARACTER SET latin1 DEFAULT 'N',
  `GMT` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `zoneref` decimal(11,2) DEFAULT NULL,
  `summertimezoneref` decimal(11,2) DEFAULT NULL,
  `longitute` double DEFAULT NULL,
  `lagitute` double DEFAULT NULL,
  `country` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `state` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `city` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `country_name` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `sunsign` int(2) NOT NULL,
  `createddate` datetime DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  PRIMARY KEY (`imported_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_woocommerce_api_keys`;
CREATE TABLE `wp_woocommerce_api_keys` (
  `key_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `permissions` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `consumer_key` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `consumer_secret` char(43) COLLATE utf8_unicode_ci NOT NULL,
  `nonces` longtext COLLATE utf8_unicode_ci,
  `truncated_key` char(7) COLLATE utf8_unicode_ci NOT NULL,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`key_id`),
  KEY `consumer_key` (`consumer_key`),
  KEY `consumer_secret` (`consumer_secret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_woocommerce_attribute_taxonomies`;
CREATE TABLE `wp_woocommerce_attribute_taxonomies` (
  `attribute_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_label` longtext COLLATE utf8_unicode_ci,
  `attribute_type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_orderby` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_public` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`attribute_id`),
  KEY `attribute_name` (`attribute_name`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_woocommerce_downloadable_product_permissions`;
CREATE TABLE `wp_woocommerce_downloadable_product_permissions` (
  `permission_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `download_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL DEFAULT '0',
  `order_key` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `downloads_remaining` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `access_granted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access_expires` datetime DEFAULT NULL,
  `download_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`permission_id`),
  KEY `download_order_key_product` (`product_id`,`order_id`,`order_key`(191),`download_id`),
  KEY `download_order_product` (`download_id`,`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_woocommerce_order_itemmeta`;
CREATE TABLE `wp_woocommerce_order_itemmeta` (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint(20) NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_woocommerce_order_items`;
CREATE TABLE `wp_woocommerce_order_items` (
  `order_item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_item_name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `order_item_type` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `order_id` bigint(20) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_woocommerce_tax_rates`;
CREATE TABLE `wp_woocommerce_tax_rates` (
  `tax_rate_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tax_rate_country` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_rate_state` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_rate` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_rate_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_rate_priority` bigint(20) NOT NULL,
  `tax_rate_compound` int(1) NOT NULL DEFAULT '0',
  `tax_rate_shipping` int(1) NOT NULL DEFAULT '1',
  `tax_rate_order` bigint(20) NOT NULL,
  `tax_rate_class` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_rate_id`),
  KEY `tax_rate_country` (`tax_rate_country`(191)),
  KEY `tax_rate_state` (`tax_rate_state`(191)),
  KEY `tax_rate_class` (`tax_rate_class`(191)),
  KEY `tax_rate_priority` (`tax_rate_priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_woocommerce_tax_rate_locations`;
CREATE TABLE `wp_woocommerce_tax_rate_locations` (
  `location_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `location_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_rate_id` bigint(20) NOT NULL,
  `location_type` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `location_type` (`location_type`),
  KEY `location_type_code` (`location_type`,`location_code`(90))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `wp_woocommerce_termmeta`;
CREATE TABLE `wp_woocommerce_termmeta` (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `woocommerce_term_id` bigint(20) NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `woocommerce_term_id` (`woocommerce_term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `xmls`;
CREATE TABLE `xmls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `language_id` tinyint(2) NOT NULL DEFAULT '1',
  `day` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `untimed` tinyint(2) NOT NULL DEFAULT '0',
  `hour` int(11) NOT NULL,
  `minute` int(11) NOT NULL,
  `gmt` char(1) NOT NULL,
  `zoneref` decimal(11,2) NOT NULL,
  `summerref` decimal(11,2) NOT NULL,
  `place` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `gender` char(3) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `xmls1`;
CREATE TABLE `xmls1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `language` varchar(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `year_book_dk`;
CREATE TABLE `year_book_dk` (
  `year_book_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint(20) DEFAULT NULL,
  `planet_code1` varchar(20) DEFAULT NULL,
  `aspect_id` varchar(10) DEFAULT NULL,
  `planet_code12` varchar(20) DEFAULT NULL,
  `aspect_strength` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `planet_direction` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `aspect_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8,
  `title` mediumtext CHARACTER SET utf8,
  `short_text` mediumtext CHARACTER SET utf8,
  `tester_text` mediumtext CHARACTER SET utf8,
  `chapter_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `find_param` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`year_book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `year_book_dk_agetext`;
CREATE TABLE `year_book_dk_agetext` (
  `age_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `age_no` bigint(20) DEFAULT NULL,
  `age_text` mediumtext CHARACTER SET utf8,
  `find_param` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`age_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `year_book_en`;
CREATE TABLE `year_book_en` (
  `year_book_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint(20) DEFAULT NULL,
  `planet_code1` varchar(20) DEFAULT NULL,
  `aspect_id` varchar(10) DEFAULT NULL,
  `planet_code12` varchar(20) DEFAULT NULL,
  `aspect_strength` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `planet_direction` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `aspect_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8,
  `title` mediumtext CHARACTER SET utf8,
  `short_text` mediumtext CHARACTER SET utf8,
  `tester_text` mediumtext CHARACTER SET utf8,
  `chapter_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `find_param` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`year_book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `year_book_en_agetext`;
CREATE TABLE `year_book_en_agetext` (
  `age_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `age_no` bigint(20) DEFAULT NULL,
  `age_text` mediumtext CHARACTER SET utf8,
  `find_param` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`age_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2018-04-03 09:22:59
