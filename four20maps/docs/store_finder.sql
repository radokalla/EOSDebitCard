
CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `address` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `mobile` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `website` varchar(255) NOT NULL DEFAULT '',
  `description` text character set utf8 collate utf8_bin NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `latitude` float NOT NULL DEFAULT '0',
  `longitude` float NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `cat_id` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

/* Users table */
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL, 
  `firstname` varchar(255) NOT NULL, 
  `lastname` varchar(255) NOT NULL, 
  `facebook_id` varchar(255) NOT NULL, 
  `address` varchar(255) NOT NULL, 
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

/* Store categories table */
CREATE TABLE `categories` (
`id` int(11) NOT NULL auto_increment,
`cat_name` varchar(100) character set utf8 collate utf8_bin default NULL,
`cat_icon` varchar(255) default NULL,
`cat_parent_id` int(11) default NULL,
`cat_free_flag` int(1) default NULL,
PRIMARY KEY (id)
);

/* insert admin user */
/* Username: admin, Password: password */
insert  into `users`(`username`,`password`) values ('admin','e64a4f78be2256a38de080744dd5b117');