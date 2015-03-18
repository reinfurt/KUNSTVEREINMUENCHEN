-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.3
-- Generation Time: Nov 28, 2011 at 01:37 PM
-- Server version: 5.1.54
-- PHP Version: 4.4.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: 'db98668_43'
--

-- --------------------------------------------------------

--
-- Table structure for table 'editionitemimages'
--

CREATE TABLE editionitemimages (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  imagetype tinyint(4) NOT NULL,
  editionitem_id int(11) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'editionitems'
--

CREATE TABLE editionitems (
  id int(10) unsigned NOT NULL,
  artist_de varchar(255) NOT NULL,
  artist_en varchar(255) NOT NULL,
  title_de varchar(255) NOT NULL,
  title_en varchar(255) NOT NULL,
  text_de text NOT NULL,
  text_en text NOT NULL,
  editionitemimage_id int(10) unsigned DEFAULT NULL,
  longtext_de text,
  longtext_en text,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'elementfiles'
--

CREATE TABLE elementfiles (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  element_id int(10) unsigned NOT NULL,
  filename varchar(255) NOT NULL,
  mimetype varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'elements'
--

CREATE TABLE elements (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  page_id int(10) unsigned NOT NULL,
  `type` enum('textelement','imagegallery','vimeovideo','editionitem','logo','linkelement','flipbook') DEFAULT NULL,
  identifier varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'events'
--

CREATE TABLE `events` (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL,
  hl_de varchar(100) NOT NULL,
  hl_en varchar(100) NOT NULL,
  text_de text NOT NULL,
  text_en text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'files'
--

CREATE TABLE files (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  filename varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  mimetype varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'hlimages'
--

CREATE TABLE hlimages (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  imagetype tinyint(4) NOT NULL,
  page_id int(11) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'imagegalleries'
--

CREATE TABLE imagegalleries (
  id int(10) unsigned NOT NULL,
  width int(10) unsigned NOT NULL,
  height int(10) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'images'
--

CREATE TABLE images (
  id int(11) NOT NULL AUTO_INCREMENT,
  imagetype tinyint(4) NOT NULL,
  imagegallery_id int(11) NOT NULL,
  caption_de varchar(255) NOT NULL,
  caption_en varchar(255) NOT NULL,
  position int(10) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'keyvalue'
--

CREATE TABLE keyvalue (
  _key varchar(30) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (_key)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'logoimages'
--

CREATE TABLE logoimages (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  imagetype tinyint(4) NOT NULL,
  logo_id int(11) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'logos'
--

CREATE TABLE logos (
  id int(10) unsigned NOT NULL,
  text_de varchar(255) NOT NULL,
  text_en varchar(255) NOT NULL,
  url_de varchar(255) NOT NULL,
  url_en varchar(255) NOT NULL,
  logoimage_id int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'pagefiles'
--

CREATE TABLE pagefiles (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  page_id int(10) unsigned NOT NULL,
  filename varchar(255) NOT NULL,
  mimetype varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'pages'
--

CREATE TABLE pages (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  slug_de varchar(100) NOT NULL,
  slug_en varchar(100) NOT NULL,
  title_de varchar(255) NOT NULL,
  title_en varchar(255) NOT NULL,
  text_de text,
  text_en text,
  hlimage_id_de int(10) unsigned NOT NULL,
  hlimage_id_en int(10) unsigned NOT NULL,
  layout text NOT NULL,
  draft_de text NOT NULL,
  draft_en text NOT NULL,
  html_de text NOT NULL,
  html_en text NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  dont_show_hl tinyint(1) DEFAULT NULL,
  pagefile_id_de int(10) unsigned DEFAULT NULL,
  pagefile_id_en int(10) unsigned DEFAULT NULL,
  pagefile_description_de varchar(255) DEFAULT NULL,
  pagefile_description_en varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'textelements'
--

CREATE TABLE textelements (
  id int(10) unsigned NOT NULL,
  text_de text NOT NULL,
  text_en text NOT NULL,
  elementfile_id_de int(10) unsigned DEFAULT NULL,
  elementfile_id_en int(10) unsigned DEFAULT NULL,
  elementfile_description_de varchar(255) DEFAULT NULL,
  elementfile_description_en varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'users'
--

CREATE TABLE users (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  passwordhash char(40) NOT NULL,
  salt char(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  organization varchar(255) DEFAULT NULL,
  lang enum('de','en') NOT NULL,
  admin tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


// user admin:admin
INSERT INTO `kunstverein_muenchen`.`users` (`email`, `passwordhash`, `salt`, `name`, `organization`, `lang`, `admin`) VALUES ('admin', 'eabc56df73e376518235e753dbb2aa3e43da1f99', 'abc', 'Admin', 'Admin', 'de', '1');
-- --------------------------------------------------------
	
--
-- Table structure for table 'vimeovideos'
--

CREATE TABLE vimeovideos (
  id int(10) unsigned NOT NULL,
  vimeoid int(10) unsigned NOT NULL,
  width int(10) unsigned NOT NULL,
  height int(10) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `flipbooks` (
  `id` int(10) unsigned NOT NULL,
  `title_de` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `flipbookimages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagetype` tinyint(4) NOT NULL,
  `flipbook_id` int(11) NOT NULL,
  `position` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;