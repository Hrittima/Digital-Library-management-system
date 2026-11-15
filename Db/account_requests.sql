CREATE TABLE IF NOT EXISTS `iL_AccountRequests` (
  `ar_id` int(20) NOT NULL AUTO_INCREMENT,
  `ar_name` varchar(200) NOT NULL,
  `ar_email` varchar(200) NOT NULL,
  `ar_phone` varchar(200) NOT NULL,
  `ar_password` varchar(255) NOT NULL,
  `ar_role` varchar(20) NOT NULL,
  `ar_gender` varchar(20) NOT NULL DEFAULT '',
  `ar_address` varchar(255) NOT NULL DEFAULT '',
  `ar_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ar_id`),
  KEY `ar_status_created_at` (`ar_status`, `created_at`),
  KEY `ar_email` (`ar_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
