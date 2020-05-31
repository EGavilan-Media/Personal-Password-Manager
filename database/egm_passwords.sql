CREATE DATABASE egm_passwords;

USE egm_passwords;

CREATE TABLE `tbl_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_status` enum('Active','Inactive') NOT NULL,
  `category_created_date` datetime NOT NULL,
  PRIMARY KEY(category_id)
);

CREATE TABLE `tbl_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY(id)
);

CREATE TABLE `tbl_sites` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `site_url` varchar(100) NOT NULL,
  `site_name` varchar(50) NOT NULL,
  `site_username` varchar(50) NOT NULL,
  `site_password` varchar(100) NOT NULL,
  `site_note` varchar(500) DEFAULT NULL,
  `site_created_date` datetime NOT NULL,
  PRIMARY KEY(site_id)
);

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_first_name` varchar(50) NOT NULL,
  `user_last_name` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(150) NOT NULL,
  `user_created_date` datetime NOT NULL,
  PRIMARY KEY(user_id)
);