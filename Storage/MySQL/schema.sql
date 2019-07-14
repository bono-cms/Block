
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

/* Category fields */
DROP TABLE IF EXISTS `bono_module_block_category_fields`;
CREATE TABLE `bono_module_block_category_fields` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Field ID',
    `category_id` INT NOT NULL COMMENT 'Category ID',
    `name` varchar(255) NOT NULL COMMENT 'Field name',
    `type` SMALLINT NOT NULL COMMENT 'Field type constant',
    `translatable` BOOLEAN NOT NULL COMMENT 'Whether this field can have translations',

    FOREIGN KEY (category_id) REFERENCES bono_module_block_categories(id) ON DELETE CASCADE
) DEFAULT CHARSET = UTF8;

/* Category relation */
DROP TABLE IF EXISTS `bono_module_block_categories_relation`;
CREATE TABLE `bono_module_block_categories_relation` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Relation ID',
    `field_id` INT NOT NULL COMMENT 'Related Field ID',
    `page_id` INT NOT NULL COMMENT 'Page ID',

    FOREIGN KEY (field_id) REFERENCES bono_module_block_category_fields(id) ON DELETE CASCADE

) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_block_categories_relation_translations`;
CREATE TABLE `bono_module_block_categories_relation_translations` (
    `id` INT NOT NULL COMMENT 'Relation ID',
    `lang_id` INT NOT NULL COMMENT 'Attached language ID',
    `value` TEXT NOT NULL COMMENT 'Value itself',

    FOREIGN KEY (id) REFERENCES bono_module_block_categories_relation(id) ON DELETE CASCADE

) DEFAULT CHARSET = UTF8;
