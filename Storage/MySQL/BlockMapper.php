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
        return self::getWithPrefix('bono_module_block_translations');
    }

    /**
     * Returns shared columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::getFullColumnName('id'),
            self::getFullColumnName('class'),
            self::getFullColumnName('lang_id', self::getTranslationTable()),
            self::getFullColumnName('name', self::getTranslationTable()),
            self::getFullColumnName('content', self::getTranslationTable())
        );
    }

    /**
     * Fetches block's name by its associated class name
     * 
     * @param string $name
     * @return string
     */
    public function fetchNameByClass($class)
    {
        return $this->fetchColumnByClass('name', $class);
    }

    /**
     * Fetches block's content by its associated class name
     * 
     * @param string $class
     * @return string
     */
    public function fetchContentByClass($class)
    {
        return $this->fetchColumnByClass('content', $class);
    }

    /**
     * Fetches column's value by associated class
     * 
     * @param string $column Column to be fetched
     * @param string $class Associated class name
     * @return string
     */
    private function fetchColumnByClass($column, $class)
    {
        return $this->db->select($column)
                        ->from(static::getTableName())
                        ->whereEquals('class', $class)
                        ->andWhereEquals('lang_id', $this->getLangId())
                        ->query($column);
    }

    /**
     * Fetches block name by its associated id
     * 
     * @param string $id
     * @return string
     */
    public function fetchNameById($id)
    {
        return $this->findColumnByPk($id, 'name');
    }

    /**
     * Fetches block data by its associated class name
     * 
     * @param string $class Block's class name
     * @return array
     */
    public function fetchByClass($class)
    {
        return $this->db->select('*')
                        ->from(static::getTableName())
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
                        self::getFullColumnName('lang_id', self::getTranslationTable()), 
                        $this->getLangId()
                    )
                    ->orderBy(self::getFullColumnName('id'))
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
