
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_calcscores`
--

DROP TABLE IF EXISTS `#__eschool_calcscores`;
CREATE TABLE IF NOT EXISTS `#__eschool_calcscores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `syllabus_course_id` int(11) NOT NULL,
  `scoring_type_id` int(11) NOT NULL,
  `calc_score` decimal(6,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`,`semester_id`,`syllabus_course_id`,`scoring_type_id`),
  UNIQUE KEY `student_id_2` (`student_id`,`semester_id`,`syllabus_course_id`,`scoring_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `course_type` int(11) NOT NULL,
  `credit` decimal(3,1) NOT NULL DEFAULT '1.5',
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
-- Table structure for table `#__eschool_exams`
--

DROP TABLE IF EXISTS `#__eschool_exams`;
CREATE TABLE IF NOT EXISTS `#__eschool_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scoring_plan_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `full_score` int(11) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `published` tinyint(3) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '1',
  `language` varchar(20) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_gradings`
--

DROP TABLE IF EXISTS `#__eschool_gradings`;
CREATE TABLE IF NOT EXISTS `#__eschool_gradings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `pointing` decimal(4,2) NOT NULL,
  `grade` varchar(5) NOT NULL,
  `min_score` decimal(4,2) NOT NULL,
  `max_score` decimal(5,2) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` int(11) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '1',
  `language` varchar(5) NOT NULL DEFAULT '*',
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
  `semester_id` int(11) DEFAULT NULL,
  `syllabus_course_id` int(11) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `exam_id` int(4) NOT NULL,
  `score` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `language` varchar(10) NOT NULL DEFAULT '*',
  `access` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_registrations`
--

DROP TABLE IF EXISTS `#__eschool_registrations`;
CREATE TABLE IF NOT EXISTS `#__eschool_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_number` int(11) NOT NULL,
  `doc_number` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `class_level_id` int(11) NOT NULL,
  `syllabus_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '1',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `language` varchar(20) NOT NULL DEFAULT '*',
  `access` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_registration_records`
--

DROP TABLE IF EXISTS `#__eschool_registration_records`;
CREATE TABLE IF NOT EXISTS `#__eschool_registration_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `syllabus_course_id` int(11) NOT NULL,
  `scoring_progress` decimal(5,2) NOT NULL DEFAULT '0.00',
  `scoring_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `grading_id` int(11) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `language` varchar(20) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  KEY `registration_id` (`registration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_scoring_plans`
--

DROP TABLE IF EXISTS `#__eschool_scoring_plans`;
CREATE TABLE IF NOT EXISTS `#__eschool_scoring_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `semester_id` int(11) NOT NULL,
  `syllabus_course_id` int(11) NOT NULL,
  `scoring_type_id` int(11) NOT NULL,
  `weight` float(5,2) NOT NULL DEFAULT '100.00',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` varchar(20) NOT NULL DEFAULT '*',
  `ordering` int(11) NOT NULL DEFAULT '1',
  `note` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_scoring_types`
--

DROP TABLE IF EXISTS `#__eschool_scoring_types`;
CREATE TABLE IF NOT EXISTS `#__eschool_scoring_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `scoring` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'scoring=1 คิดคะแนน 0=ผ่านหรือไม่ผ่าน',
  `published` tinyint(4) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '1',
  `language` varchar(20) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  UNIQUE KEY `academic_year` (`academic_year`,`academic_period`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__eschool_students`
--

DROP TABLE IF EXISTS `#__eschool_students`;
CREATE TABLE IF NOT EXISTS `#__eschool_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `student_code` varchar(50) NOT NULL,
  `title` varchar(10) NOT NULL DEFAULT 'ด.ช.',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` char(7) NOT NULL,
  `syllabus_id` int(11) NOT NULL,
  `entry_date` datetime NOT NULL,
  `graduate_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'graduated date (if completed syllabus)',
  `classlevel_id` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=studying,2=graduated,3=resigned,4=terminated',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `credit` decimal(3,1) NOT NULL DEFAULT '1.5',
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
