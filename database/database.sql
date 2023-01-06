--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idParent` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(100) NOT NULL,
  `content` longblob NOT NULL,
  `ownername` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
);


--
-- Table structure for table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE `folders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idParentFolders` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ownername` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
);


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(150) NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `is_verified` int NOT NULL DEFAULT '0',
  `otp_secret` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
);
