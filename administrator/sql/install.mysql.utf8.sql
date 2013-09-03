CREATE TABLE IF NOT EXISTS `#__dztour_tours` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`access` INT(11)  NOT NULL ,
`language` VARCHAR(255)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified` DATETIME NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`title` VARCHAR(64)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`featured` VARCHAR(255)  NOT NULL ,
`on_offer` VARCHAR(255)  NOT NULL ,
`price` VARCHAR(255)  NOT NULL ,
`saleoff_price` VARCHAR(255)  NOT NULL ,
`duration` TEXT NOT NULL ,
`typeid` INT(11)  NOT NULL ,
`locationid` INT(11)  NOT NULL ,
`descriptions` TEXT NOT NULL ,
`images` TEXT NOT NULL ,
`metadesc` TEXT NOT NULL ,
`metakey` TEXT NOT NULL ,
`metadata` TEXT NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__dztour_orders` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`tourid` INT NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`created` DATETIME NOT NULL ,
`modified` DATETIME NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`phone` VARCHAR(255)  NOT NULL ,
`address` TEXT NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`adults` VARCHAR(255)  NOT NULL ,
`children` VARCHAR(255)  NOT NULL ,
`start_date` DATE NOT NULL ,
`end_date` DATE NOT NULL ,
`comment` TEXT NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`) VALUES (NULL, 'DZ Tour - Tour', 'com_dztour.tour', '{"special":{"dbtable":"#__dztour_tours","key":"id","type":"Tour","prefix":"DZPhotoTable","config":"array()"}}', '', '{"common":[{"core_content_item_id":"id","core_title":"title","core_state":"state","core_alias":"alias", "core_params":"params", "core_ordering":"ordering", "asset_id":"asset_id"}]}', 'DZTourHelperRoute::getTourRoute');