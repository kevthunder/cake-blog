SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `blog_posts`
--

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL auto_increment,
  `title_fre` varchar(255) collate utf8_unicode_ci default NULL,
  `title_eng` varchar(255) collate utf8_unicode_ci default NULL,
  `short_text_fre` text collate utf8_unicode_ci,
  `short_text_eng` text collate utf8_unicode_ci,
  `text_fre` text collate utf8_unicode_ci,
  `text_eng` text collate utf8_unicode_ci,
  `user_id` int(11) default NULL,
  `multimedia` text collate utf8_unicode_ci,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;




--
-- Structure de la table `blog_categories`
--

CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` int(11) NOT NULL auto_increment,
  `title_fre` varchar(255) collate utf8_unicode_ci default NULL,
  `title_eng` varchar(255) collate utf8_unicode_ci default NULL,
  `desc_fre` text collate utf8_unicode_ci,
  `desc_eng` text collate utf8_unicode_ci,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `blog_categories_blog_posts`
--

CREATE TABLE IF NOT EXISTS `blog_categories_blog_posts` (
  `id` int(11) NOT NULL auto_increment,
  `blog_category_id` int(11) default NULL,
  `blog_post_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;




INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES 
(NULL, 3, NULL, NULL, 'blogger', NULL, NULL);

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES 
(NULL, NULL, NULL, NULL, 'blog_post', NULL, NULL),
(NULL, NULL, NULL, NULL, 'blogger_edit', NULL, NULL);

INSERT INTO `aros_acos`(`aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) 
SELECT `aros`.`id`,`acos`.`id`,1,1,1,1 FROM `aros` LEFT JOIN `acos` ON `acos`.`alias` = 'blog_post' WHERE `aros`.`alias` = 'blogger';
INSERT INTO `aros_acos`(`aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) 
SELECT `aros`.`id`,`acos`.`id`,1,1,1,1 FROM `aros` LEFT JOIN `acos` ON `acos`.`alias` = 'blog_post' WHERE `aros`.`alias` = 'administrators';
INSERT INTO `aros_acos`(`aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) 
SELECT `aros`.`id`,`acos`.`id`,1,1,1,1 FROM `aros` LEFT JOIN `acos` ON `acos`.`alias` = 'blogger_edit' WHERE `aros`.`alias` = 'administrators';