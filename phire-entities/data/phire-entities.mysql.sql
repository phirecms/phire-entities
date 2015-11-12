--
-- Entities Module MySQL Database for Phire CMS 2.0
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------

--
-- Table structure for table `entity_types`
--

CREATE TABLE IF NOT EXISTS `[{prefix}]entity_types` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `field_num` int(16) NOT NULL,
  `order` int(16),
  PRIMARY KEY (`id`),
  INDEX `entity_type_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50001 ;

-- --------------------------------------------------------

--
-- Table structure for table `entities`
--

CREATE TABLE IF NOT EXISTS `[{prefix}]entities` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `type_id` int(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `entity_type_id` (`type_id`),
  INDEX `entity_name` (`name`),
  CONSTRAINT `fk_entity_type` FOREIGN KEY (`type_id`) REFERENCES `[{prefix}]entity_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51001 ;

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;
