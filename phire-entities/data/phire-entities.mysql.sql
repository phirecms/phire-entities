--
-- Entities Module MySQL Database for Phire CMS 2.0
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------

--
-- Table structure for table `entities`
--

CREATE TABLE IF NOT EXISTS `[{prefix}]entities` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18001 ;

-- --------------------------------------------------------

--
-- Table structure for table `entity_types`
--

CREATE TABLE IF NOT EXISTS `[{prefix}]entity_types` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19001 ;

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;
