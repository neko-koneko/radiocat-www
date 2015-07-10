SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `login` varchar(35) NOT NULL,
  `hash` blob NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `cron_jobs`;
CREATE TABLE IF NOT EXISTS `cron_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `done` set('Y','N') NOT NULL DEFAULT 'N',
  `result` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `repeat_weekly` set('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE KEY `time` (`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` text NOT NULL,
  `date` datetime NOT NULL,
  `add_date` datetime NOT NULL,
  `size` int(255) NOT NULL,
  `rating` int(1) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `year` int(4) NOT NULL,
  `length` int(6) NOT NULL COMMENT 'in seconds',
  `bpm` int(4) NOT NULL,
  `camelot_ton` varchar(4) NOT NULL,
  `context` int(20) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `count` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `filename` (`filename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `files_log`;
CREATE TABLE IF NOT EXISTS `files_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `playlists`;
CREATE TABLE IF NOT EXISTS `playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `static` set('Y','N') NOT NULL DEFAULT 'Y',
  `rules` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `playlist_status`;
CREATE TABLE IF NOT EXISTS `playlist_status` (
  `id` int(11) NOT NULL,
  `current_playlist_id` int(11) NOT NULL,
  `current_track_number` int(11) NOT NULL,
  `change_playlist` set('Y','N') NOT NULL DEFAULT 'N',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tracks`;
CREATE TABLE IF NOT EXISTS `tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `track_number` int(11) NOT NULL,
  `file_id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `playlist_status` (`id`, `current_playlist_id`, `current_track_number`, `change_playlist`) VALUES (1, 1, 1, 'N');
INSERT INTO `admin_users` VALUES (1,'admin','13XQc51/kzJ9nxEfmscnut2ycvqVPLl4:MDIyNmNmNmIwZjNhNjAzNGE1ZDBkOTE0ZDE3NWU0YmQzZGUxOGY0Zjk4M2Y0NDZhYmI1ZDQ1MWZkMDM2ZDI2NjFmZmIwOTY3ZGUxMGQyOWJmMWMxMDM3NzA4MTQzNDIz','Neko Koneko');
