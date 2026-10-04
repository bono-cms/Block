<?php

/**
 * This file is part of the Bono CMS
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
     * Find attached category IDs
     * 
     * @param int $id Target entity ID
     * @return array
     */
    final public function findAttachedSlaves($id)
    {
        return $this->getSlaveIdsFromJunction(static::getRelationTable(), $id);
    }

    /**
     * Save junction relation
     * 
     * @param int $id Target entity ID
     * @param array $slaveIds
     * @return boolean
     */
    final public function saveRelation($id, array $slaveIds)
    {
        return $this->syncWithJunction(static::getRelationTable(), $id, $slaveIds);
    }

    /**
     * Find attached fields by entity ID
     * 
     * @param int $id
     * @return array
     */
    final public function findFields($id)
    {
        // To be selected
        $columns = [
            CategoryFieldMapper::column('id'),
            CategoryFieldMapper::column('name'),
            CategoryFieldMapper::column('type'),
            CategoryFieldMapper::column('translatable'),
            CategoryMapper::column('name') => 'category',
            static::column('value') // Non-translatable value
        ];

        $db = $this->db->select($columns, true)
                       ->from(CategoryFieldMapper::getTableName())
                       ->leftJoin(CategoryMapper::getTableName(), [
                            CategoryMapper::column('id') => CategoryFieldMapper::getRawColumn('category_id')
                       ])
                       // Block relation
                       ->leftJoin(static::getRelationTable(), [
                            static::column('slave_id', static::getRelationTable()) => CategoryFieldMapper::getRawColumn('category_id')
                       ])
                       // Field value mapper
                       ->leftJoin(static::getTableName(), [
                            static::column('entity_id') => static::getRawColumn('master_id', static::getRelationTable()),
                            static::column('field_id') => CategoryFieldMapper::getRawColumn('id'),
                       ])
                       ->whereEquals(static::column('master_id', static::getRelationTable()), $id);

        return $db->queryAll();
    }

    /**
     * Find field translation by associated entity ID
     * 
     * @param int $id Entity ID
     * @return array
     */
    final public function findTranslationsByEntityId($id)
    {
        // Columns to be selected
        $columns = [
            static::column('field_id'),
            static::column('lang_id', static::getTranslationTable()),
            static::column('value', static::getTranslationTable())
        ];

        $db = $this->db->select($columns)
                       ->from(static::getTableName())
                       // Translation relation
                       ->leftJoin(static::getTranslationTable(), [
                            static::column('id', static::getTranslationTable()) => static::getRawColumn('id')
                       ])
                       ->whereEquals(static::column('entity_id'), $id);

        return $db->queryAll();
    }

    /**
     * Fetch active translation by field IDs
     * 
     * @param array $fieldIds Attached field IDs
     * @param int $entityId Current entity ID
     * @return array
     */
    final public function findActiveTranslations(array $fieldIds, $entityId)
    {
        // Columns to be selected
        $columns = [
            static::column('field_id'),
            static::column('value', static::getTranslationTable())
        ];

        $db = $this->db->select($columns)
                       ->from(static::getTableName())
                       // Translation relation
                       ->leftJoin(static::getTranslationTable(), [
                            static::column('id', static::getTranslationTable()) => static::getRawColumn('id')
                       ])
                       ->whereIn(static::column('field_id'), $fieldIds)
                       ->andWhereEquals(static::column('entity_id'), $entityId)
                       ->andWhereEquals(static::column('lang_id', static::getTranslationTable()), $this->getLangId());

        return $db->queryAll();
    }
}