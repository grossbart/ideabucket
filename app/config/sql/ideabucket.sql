-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 15. Oktober 2008 um 23:42
-- Server Version: 5.0.41
-- PHP-Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Datenbank: `ideabucket`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ideas`
-- 

CREATE TABLE `ideas` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `user_id` int(10) NOT NULL,
  `image` varchar(255) collate utf8_unicode_ci NOT NULL,
  `excerpt` text collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `people` tinyint(3) NOT NULL,
  `weather` tinyint(2) NOT NULL,
  `costs` tinyint(2) NOT NULL,
  `season` tinyint(2) NOT NULL,
  `duration` tinyint(2) NOT NULL,
  `daytime` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`,`excerpt`,`content`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- 
-- Daten für Tabelle `ideas`
-- 

INSERT INTO `ideas` VALUES (1, 'Pilze sammeln', '2008-10-15 08:17:12', '2008-10-15 08:17:12', 0, '', '', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure d.', 2, 1, 0, 3, 3, 1);
INSERT INTO `ideas` VALUES (12, 'Typografie studieren', '2008-10-15 22:17:09', '2008-10-15 22:17:09', 0, 'image/png', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure d.', '', 1, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ideas_tags`
-- 

CREATE TABLE `ideas_tags` (
  `idea_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Daten für Tabelle `ideas_tags`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ideas_users`
-- 

CREATE TABLE `ideas_users` (
  `idea_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Daten für Tabelle `ideas_users`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `tags`
-- 

CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `tags`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `users`
-- 

CREATE TABLE `users` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `password` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `users`
-- 

