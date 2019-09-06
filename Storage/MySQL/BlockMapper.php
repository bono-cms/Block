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
use Block\Storage\BlockMapperInterface;

final class BlockMapper extends AbstractMapper implements BlockMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_block');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return BlockTranslationMapper::getTableName();
    }

    /**
     * Returns shared columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::column('id'),
            self::column('class'),
            self::column('name'),
            BlockTranslationMapper::column('lang_id'),
            BlockTranslationMapper::column('content')
        );
    }

    /**
     * Fetches block's content by its associated class name
     * 
     * @param string $class
     * @return string
     */
    public function fetchContentByClass($class)
    {
        return $this->createEntitySelect(array('content'))
                    ->whereEquals('class', $class)
                    ->andWhereEquals('lang_id', $this->getLangId())
                    ->queryScalar();
    }

    /**
     * Fetches block data by its associated class name
     * 
     * @param string $class Block's class name
     * @return array
     */
    public function fetchByClass($class)
    {
        return $this->createEntitySelect($this->getColumns())
                    ->whereEquals('class', $class)
                    ->andWhereEquals('lang_id', $this->getLangId())
                    ->query();
    }

    /**
     * Fetches all blocks
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->createEntitySelect($this->getColumns())
                    ->queryAll();
    }

    /**
     * Fetches all blocks filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @return array
     */
    public function fetchAllByPage($page, $itemsPerPage)
    {
        return $this->createEntitySelect($this->getColumns())
                    ->whereEquals(
                        BlockTranslationMapper::column('lang_id'), 
                        $this->getLangId()
                    )
                    ->orderBy(self::column('id'))
                    ->desc()
                    ->paginate($page, $itemsPerPage)
                    ->queryAll();
    }

    /**
     * Fetches block data by its associated id
     * 
     * @param string $id Block id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
    }
}
