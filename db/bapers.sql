DROP TABLE cardpayment;

CREATE TABLE `cardpayment` (
  `card_num` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `cardholder_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_expiry` char(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Paymentpayment_id` int(10) NOT NULL,
  PRIMARY KEY (`card_num`,`Paymentpayment_id`),
  UNIQUE KEY `card_num` (`card_num`),
  KEY `Paymentpayment_id` (`Paymentpayment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE customer;

CREATE TABLE `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id_char` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cust_sname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cust_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cust_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cust_type` tinyint(1) NOT NULL,
  `cust_mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `discount_plan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`cust_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE job;

CREATE TABLE `job` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id_char` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_urgency` int(1) DEFAULT NULL,
  `job_deadline` datetime NOT NULL DEFAULT current_timestamp(),
  `special_instructions` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expected_finish` datetime DEFAULT current_timestamp(),
  `actual_finish` datetime DEFAULT current_timestamp(),
  `order_time` datetime DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `discount_amount` double DEFAULT NULL,
  `alert_flag` tinyint(4) NOT NULL DEFAULT 0,
  `Customercust_id` int(10) NOT NULL,
  PRIMARY KEY (`job_id`),
  KEY `Customercust_id` (`Customercust_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE job_task;

CREATE TABLE `job_task` (
  `JobTaskID` int(11) NOT NULL AUTO_INCREMENT,
  `Jobjob_id` int(10) NOT NULL,
  `Tasktask_id` int(10) NOT NULL,
  `start_time` time DEFAULT NULL,
  `finish_time` time DEFAULT NULL,
  `task_date` date DEFAULT NULL,
  `task_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Staffstaff_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`JobTaskID`),
  KEY `Jobjob_id` (`Jobjob_id`),
  KEY `Tasktask_id` (`Tasktask_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE message;

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `message_status` tinyint(1) NOT NULL,
  `send_time` datetime NOT NULL,
  `Staffstaff_id` int(10) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `Staffstaff_id` (`Staffstaff_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE payment;

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id_char` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_total` double NOT NULL,
  `payment_late` tinyint(1) NOT NULL DEFAULT 0,
  `payment_alert` tinyint(1) DEFAULT 0,
  `payment_discount` double DEFAULT NULL,
  `discount_rate` int(3) NOT NULL DEFAULT 0,
  `payment_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Customercust_id` int(10) NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `Customercust_id` (`Customercust_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE staff;

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id_char` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `staff_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_sname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_department` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_time` time NOT NULL,
  `username_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO staff VALUES("1","","Office","Manager","Office Manager"," NULL","00:00:00","office@test.com","office");



DROP TABLE task;

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `task_location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `task_price` double NOT NULL,
  `task_duration` int(11) NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO task VALUES("1","Use of large copy camera","Copy Room","19","120");
INSERT INTO task VALUES("2","Black and white film processing","Development Area","49.5","60");
INSERT INTO task VALUES("3","Bag up","Packing Departments","6","30");
INSERT INTO task VALUES("4","Colour film processing","Development Area","80","90");
INSERT INTO task VALUES("5","Colour Transparency processing","Development Area","110.3","180");
INSERT INTO task VALUES("6","Use of small copy camera","Copy Room","8.3","75");
INSERT INTO task VALUES("7","Mount Transparencies","Finishing Room","55.5","45");



