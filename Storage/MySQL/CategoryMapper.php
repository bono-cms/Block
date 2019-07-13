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
use Block\Storage\CategoryMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_block_categories');
    }

    /**
     * Fetch all categories with field count
     * 
     * @return array
     */
    public function fetchAll()
    {
        // To be selected
        $columns = array(
            self::column('id'),
            self::column('name')
        );

        $db = $this->db->select($columns)
                       ->count(CategoryFieldMapper::column('id'), 'field_count')
                       ->from(self::getTableName())
                       // Category field relation
                       ->leftJoin(CategoryFieldMapper::getTableName(), array(
                            CategoryFieldMapper::column('category_id') => self::getRawColumn('id')
                       ))
                       ->groupBy($columns)
                       ->orderBy('id')
                       ->desc();

        return $db->queryAll();
    }
}
