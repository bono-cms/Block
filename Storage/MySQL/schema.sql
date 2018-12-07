
DROP TABLE IF EXISTS `bono_module_block`;
CREATE TABLE `bono_module_block` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`class` varchar(255) NOT NULL
) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_block_translations`;
CREATE TABLE `bono_module_block_translations` (

	`id` INT NOT NULL,
	`lang_id` INT NOT NULL,
	`name` varchar(255) NOT NULL,
	`content` LONGTEXT NOT NULL,

) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_block_categories`;
CREATE TABLE `bono_module_block_categories` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Category ID',
    `name` varchar(255) NOT NULL COMMENT 'Category name'
) DEFAULT CHARSET = UTF8;
