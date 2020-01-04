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
            self::column('name'),
            self::column('translatable'),
            self::column('value'),
            BlockTranslationMapper::column('lang_id'),
            BlockTranslationMapper::column('content')
        );
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
        $extraCondition = array(
            BlockTranslationMapper::column('lang_id') => $this->getLangId()
        );

        $db = $this->createEntitySelect($this->getColumns(), null, $extraCondition)
                    ->orderBy(self::column('id'))
                    ->desc();

        if ($page !== null && $itemsPerPage !== null){
            $db->paginate($page, $itemsPerPage);
        }

        return $db->queryAll();
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
