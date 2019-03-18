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
use Block\Storage\CategoryRelationMapperInterface;

final class CategoryRelationMapper extends AbstractMapper implements CategoryRelationMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_block_categories_relation');
    }

    /**
     * Finds all fields by page ID
     * 
     * @param int $id Page id
     * @return array
     */
    public function findAllByPageId($id)
    {
        // Columns to be selected
        $columns = array(
            CategoryFieldMapper::column('id') => 'field_id',
            CategoryRelationTranslationMapper::column('lang_id'),
            CategoryFieldMapper::column('name'),
            CategoryFieldMapper::column('type'),
            CategoryRelationTranslationMapper::column('value'),
        );

        $db = $this->db->select($columns)
                       ->from(CategoryFieldMapper::getTableName())
                       ->join('LEFT', self::getTableName(), array(
                            CategoryFieldMapper::column('id') => self::getRawColumn('field_id'),
                            self::column('page_id') => (int) $id
                       ))
                       // Translation relation
                       ->leftJoin(CategoryRelationTranslationMapper::getTableName(), array(
                            CategoryRelationTranslationMapper::column('id') => CategoryFieldMapper::getRawColumn('id')
                       ))
                       // Sort by latest
                       ->orderBy(self::column('id'))
                       ->desc();

        return $db->queryAll();
    }
}
