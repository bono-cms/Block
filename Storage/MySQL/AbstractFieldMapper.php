<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Block\Storage\MySQL\CategoryFieldMapper;
use Block\Storage\MySQL\CategoryMapper;
use Block\Storage\SharedFieldInterface;

abstract class AbstractFieldMapper extends AbstractMapper implements SharedFieldInterface
{
    /**
     * Returns junction table name that connects entities with block categories
     * 
     * @return string
     */
    abstract public static function getRelationTable();

    /**
     * Find attached category ids
     * 
     * @param int $pageId Target page id
     * @return array
     */
    final public function findAttachedSlaves($pageId)
    {
        return $this->getSlaveIdsFromJunction(static::getRelationTable(), $pageId);
    }

    /**
     * Save junction relation
     * 
     * @param int $pageId Target page id
     * @param array $slaveIds
     * @return boolean
     */
    final public function saveRelation($pageId, array $slaveIds)
    {
        return $this->syncWithJunction(static::getRelationTable(), $pageId, $slaveIds);
    }

    /**
     * Find attached fields by entity id
     * 
     * @param int $id
     * @return array
     */
    final public function findFields($id)
    {
        // To be selected
        $columns = array(
            CategoryFieldMapper::column('id'),
            CategoryFieldMapper::column('name'),
            CategoryFieldMapper::column('type'),
            CategoryFieldMapper::column('translatable'),
            CategoryMapper::column('name') => 'category',
            static::column('value') // Non-translatable value
        );

        $db = $this->db->select($columns, true)
                       ->from(CategoryFieldMapper::getTableName())
                       ->leftJoin(CategoryMapper::getTableName(), array(
                            CategoryMapper::column('id') => CategoryFieldMapper::getRawColumn('category_id')
                       ))
                       // Block relation
                       ->leftJoin(static::getRelationTable(), array(
                            static::column('slave_id', static::getRelationTable()) => CategoryFieldMapper::getRawColumn('category_id')
                       ))
                       // Field value mapper
                       ->leftJoin(static::getTableName(), array(
                            static::column('entity_id') => static::getRawColumn('master_id', static::getRelationTable()),
                            static::column('field_id') => CategoryFieldMapper::getRawColumn('id'),
                       ))
                       ->whereEquals(static::column('master_id', static::getRelationTable()), $id);

        return $db->queryAll();
    }

    /**
     * Find field translation by associated entity id
     * 
     * @param int $id Entity id
     * @return array
     */
    final public function findTranslationsByEntityId($id)
    {
        // Columns to be selected
        $columns = array(
            static::column('field_id'),
            static::column('lang_id', static::getTranslationTable()),
            static::column('value', static::getTranslationTable())
        );

        $db = $this->db->select($columns)
                       ->from(static::getTableName())
                       // Translation relation
                       ->leftJoin(static::getTranslationTable(), array(
                            static::column('id', static::getTranslationTable()) => static::getRawColumn('id')
                       ))
                       ->whereEquals(static::column('entity_id'), $id);

        return $db->queryAll();
    }

    /**
     * Fetch active translation by field ids
     * 
     * @param array $fieldIds
     * @return array
     */
    final public function findActiveTranslations(array $fieldIds)
    {
        // Columns to be selected
        $columns = array(
            static::column('field_id'),
            static::column('value', static::getTranslationTable())
        );

        $db = $this->db->select($columns)
                       ->from(static::getTableName())
                       // Translation relation
                       ->leftJoin(static::getTranslationTable(), array(
                            static::column('id', static::getTranslationTable()) => static::getRawColumn('id')
                       ))
                       ->whereIn(static::column('field_id'), $fieldIds)
                       ->andWhereEquals(static::column('lang_id', static::getTranslationTable()), $this->getLangId());

        return $db->queryAll();
    }
}
