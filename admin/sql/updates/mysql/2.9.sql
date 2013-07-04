ALTER TABLE  `#__fieldsattach_images` ADD `catid` int(11) AFTER  `fieldsattachid` ; 
ALTER TABLE  `#__fieldsattach_groups` ADD `group_for` int(1)AFTER  `position` ; 

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
