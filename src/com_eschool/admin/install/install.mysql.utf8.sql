
-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_classlevels`
--

DROP TABLE IF EXISTS `#__eschool_classlevels`;
CREATE TABLE IF NOT EXISTS `#__eschool_classlevels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_classrooms`
--

DROP TABLE IF EXISTS `#__eschool_classrooms`;
CREATE TABLE IF NOT EXISTS `#__eschool_classrooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `classlevel_id` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_coursegroups`
--

DROP TABLE IF EXISTS `#__eschool_coursegroups`;
CREATE TABLE IF NOT EXISTS `#__eschool_coursegroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `language` char(7) NOT NULL DEFAULT '*',
  `access` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_courses`
--

DROP TABLE IF EXISTS `#__eschool_courses`;
CREATE TABLE IF NOT EXISTS `#__eschool_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_type` int(11) NOT NULL DEFAULT '1',
  `credit` decimal(3,1) NOT NULL DEFAULT '1',
  `course_group_id` int(11) NOT NULL,
  `class_level_id` tinyint(4) DEFAULT NULL COMMENT 'ระดับชั้น เช่น 1=ม1',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `language` char(7) NOT NULL DEFAULT '*',
  `access` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_rawscores`
--

DROP TABLE IF EXISTS `#__eschool_rawscores`;
CREATE TABLE IF NOT EXISTS `#__eschool_rawscores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `semester_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `unit_id` tinyint(4) NOT NULL,
  `score` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_registrations`
--

DROP TABLE IF EXISTS `#__eschool_registrations`;
CREATE TABLE IF NOT EXISTS `#__eschool_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `semester_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '1',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_semesters`
--

DROP TABLE IF EXISTS `#__eschool_semesters`;
CREATE TABLE IF NOT EXISTS `#__eschool_semesters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `academic_year` int(11) NOT NULL,
  `academic_period` tinyint(3) NOT NULL,
  `class_level_id` tinyint(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `registration_on` datetime NOT NULL,
  `registration_off` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `language` char(7) NOT NULL DEFAULT '*',
  `access` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academic_year` (`academic_year`,`academic_period`,`class_level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_students`
--

DROP TABLE IF EXISTS `#__eschool_students`;
CREATE TABLE IF NOT EXISTS `#__eschool_students` (
  `id` int(11) NOT NULL COMMENT 'user joomla id, not auto-increment',
  `user_id` int(11) NOT NULL,
  `student_code` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` char(7) NOT NULL,
  `entry_date` datetime NOT NULL,
  `graduate_date` datetime NOT NULL,
  `classroom_id` int(11) NOT NULL,
  `classlevel_id` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_syllabuses`
--

DROP TABLE IF EXISTS `#__eschool_syllabuses`;
CREATE TABLE IF NOT EXISTS `#__eschool_syllabuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `credit` int(11) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '1',
  `language` varchar(20) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_syllabus_courses`
--

DROP TABLE IF EXISTS `#__eschool_syllabus_courses`;
CREATE TABLE IF NOT EXISTS `#__eschool_syllabus_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `syllabus_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `credit` decimal(3,1) NOT NULL DEFAULT '1',
  `hours` int(11) NOT NULL DEFAULT '3',
  `class_level_id` int(11) NOT NULL,
  `academic_term` tinyint(3) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;