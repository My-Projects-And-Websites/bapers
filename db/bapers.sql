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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO customer VALUES("1","ACC#1","Jimm","Richard","jimmrichard@gmail.com","32 Somewhere Street, London, United Kingdom, NE10 5YU","0","02012341234","","0");



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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO job VALUES("1","JOB#1","1","2021-04-01 18:32:11","Nice","Completed","2021-04-01 22:56:00","2021-04-01 21:35:42","2021-04-01 17:32:11","328","0","0","1");
INSERT INTO job VALUES("2","JOB#2","1","2021-04-01 23:37:04","make it precise","Completed","2021-04-01 23:39:00","2021-04-01 21:49:05","2021-04-01 22:37:04","303.5","","0","1");



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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO job_task VALUES("1","1","1","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("2","1","1","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("3","1","2","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("4","1","2","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("5","1","2","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("6","1","2","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("7","1","3","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("8","1","3","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("9","1","4","00:00:00","21:35:00","0000-00-00","Completed","1");
INSERT INTO job_task VALUES("10","2","1","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("11","2","1","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("12","2","2","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("13","2","2","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("14","2","2","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("15","2","2","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("16","2","2","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("17","2","3","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("18","2","3","21:38:00","21:49:00","2021-04-01","Completed","1");
INSERT INTO job_task VALUES("19","2","3","21:38:00","21:49:00","2021-04-01","Completed","1");



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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO payment VALUES("1","PAY#1","328","1","0","0","0","Late","1");
INSERT INTO payment VALUES("2","PAY#2","303.5","0","0","","0","Pending","1");



DROP TABLE staff;

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id_char` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `staff_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_sname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_department` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO staff VALUES("1","STAFF#1","Staff","Admin","Office Manager","Copy Room","office@test.com","office");



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



