<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 28/8/2010, 23:11
 */


if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_question`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answer`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat`";

$sql_create_module = $sql_drop_module;
//1. question ban nang cao bo sung them truong file
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_question` (
 `qid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, 
 `userid` mediumint(8) unsigned NOT NULL,
 `catid` mediumint(8) unsigned NOT NULL,
 `cus_name` varchar(255) NOT NULL ,
 `cus_email` varchar(255) NOT NULL,
 `title` varchar(255) NOT NULL ,
 `alias` varchar(255) NOT NULL COMMENT 'lien ket tinh',
 `question` mediumtext NOT NULL COMMENT 'cau hoi',
 `addtime` int(11) NOT NULL, 
 `most` smallint(4) NOT NULL COMMENT 'cau tra loi hay nhat',
 `sendmail` tinyint(0) NOT NULL COMMENT 'trang thai 0 ko gui, 1 gui',
 `showmail` tinyint(1) unsigned NOT NULL DEFAULT '1',
 `answer` tinyint(1) NOT NULL COMMENT 'trang thai 0 cau hoi chua tra loi, 1 cau hoi dc tra loi', 
 `number` smallint(4) unsigned NOT NULL DEFAULT '0',
 `status` tinyint(1) NOT NULL,
 PRIMARY KEY (`qid`),
 UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM";
$sql_create_module[] = "CREATE TABLE `".$db_config['prefix']."_".$lang."_".$module_data."_cat` (
`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 `alias` varchar(255) NOT NULL,
 `title` varchar(255) NOT NULL,
 `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 UNIQUE KEY `alias` (`alias`),
 KEY `weight` (`weight`)
) ENGINE=MyISAM;";
//3.answer ban nang cao bo sung them truong most
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answer` (
 `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 `qid` mediumint(8) unsigned NOT NULL,
 `userid` mediumint(8) unsigned NOT NULL, 
 `answer` mediumtext NOT NULL COMMENT 'tra loi cau hoi',
 `cus_name` varchar(255) NOT NULL COMMENT 'Há»� vÃ  tÃªn',
 `cus_email` varchar(255) NOT NULL COMMENT 'Ä‘á»‹a chá»‰ email',
 `addtime` int(11) NOT NULL,
 `file` varchar(255) NOT NULL COMMENT 'dinh kem file cua ban nang cao', 
 `status` tinyint(1) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `qid` (`qid`)
)ENGINE=MyISAM";



//6. Config
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (
 `config_name` varchar(30) NOT NULL,
 `config_value` varchar(255) NOT NULL,
 UNIQUE KEY `config_name` (`config_name`)
)ENGINE=MyISAM";

// ban nang cao
$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` VALUES
('is_email', 'mail@yahoo.com'),
('is_cus', '1'),
('is_admin', '1'),
('duyetan', '0'),
('who_an', '0'),
('who_view', '0')
";

?>