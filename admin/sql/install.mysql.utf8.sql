-- $Id: install.mysql.utf8.sql 24 2011-01-11 11:56:31Z   $
DROP TABLE IF EXISTS `#__fieldsattach`;
CREATE TABLE IF NOT EXISTS `#__fieldsattach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `extras` text NOT NULL,
  `showtitle` tinyint(1) NOT NULL,
  `type` varchar(20) NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `articlesid` varchar(255) NULL,
  `language` varchar(20) NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

ALTER TABLE  `#__fieldsattach` ADD `searchable` tinyint(1) AFTER  `published` ; 
ALTER TABLE  `#__fieldsattach` ADD `required` tinyint(1) AFTER  `published` ; 
ALTER TABLE  `#__fieldsattach` ADD `positionarticle` tinyint(1) DEFAULT 0 AFTER  `showtitle`  ; 



DROP TABLE IF EXISTS `#__fieldsattach_groups`;
CREATE TABLE IF NOT EXISTS `#__fieldsattach_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `showtitle` tinyint(1) NOT NULL,
  `catid` varchar(100) NOT NULL,
  `recursive` tinyint(1) NOT NULL,
  `language` varchar(7) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;

ALTER TABLE `#__fieldsattach_groups` ADD `articlesid` varchar(255) AFTER  `catid` ; 
ALTER TABLE  `#__fieldsattach_groups` ADD `description` text AFTER  `title` ; 
ALTER TABLE  `#__fieldsattach_groups` ADD `position` varchar(255) AFTER  `description` ; 
ALTER TABLE  `#__fieldsattach_groups` ADD `group_for` int(1) AFTER  `position` ; 
ALTER TABLE  `#__fieldsattach_groups` ADD `note` varchar(150) AFTER  `title` ; 
ALTER TABLE  `#__fieldsattach_groups` ADD `access` int(11) AFTER  `note` ; 






DROP TABLE IF EXISTS `#__fieldsattach_values`;

CREATE TABLE IF NOT EXISTS `#__fieldsattach_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL,
  `fieldsid` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

/*-- --------------------------------------------------------

--
-- Table structure for table `jos_fieldsattach_images`
--*/

CREATE TABLE IF NOT EXISTS `#__fieldsattach_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL,
  `fieldsattachid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;

ALTER TABLE  `#__fieldsattach_images` ADD `catid` int(11) AFTER  `fieldsattachid` ; 

/*-- --------------------------------------------------------

--
-- Table structure for table `jos_categorie fields`
--*/

CREATE TABLE IF NOT EXISTS `#__fieldsattach_categories_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `fieldsid` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 





