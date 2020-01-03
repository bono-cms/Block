<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Block\Storage\BlockMapperInterface;

final class BlockManager extends AbstractManager
{
    /**
     * Any mapper which implements BlockMapperInterface
     * 
     * @var \Block\Storage\BlockMapperInterface
     */
    private $blockMapper;

    /**
     * State initialization
     * 
     * @param \Block\Storage\BlockMapperInterface $blockMapper
     * @return void
     */
    public function __construct(BlockMapperInterface $blockMapper)
    {
        $this->blockMapper = $blockMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $block)
    {
        $entity = new BlockEntity();
        $entity->setId($block['id'], BlockEntity::FILTER_INT)
               ->setLangId($block['lang_id'], BlockEntity::FILTER_INT)
               ->setName($block['name'], BlockEntity::FILTER_HTML)
               ->setClass($block['class'], BlockEntity::FILTER_HTML)
               ->setContent($block['content'], BlockEntity::FILTER_SAFE_TAGS)
               ->setValue($block['value'], BlockEntity::FILTER_HTML)
               ->setTranslatable($block['translatable'], BlockEntity::FILTER_BOOL);

        return $entity;
    }

    /**
     * Returns prepared paginator instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->blockMapper->getPaginator();
    }

    /**
     * Fetches a block by its associated class name
     * 
     * @param string $class
     * @return \Krystal\Stdlib\VirtualEntity
     */
    public function fetchByClass($class)
    {
        return $this->blockMapper->fetchByClass($class);
    }

    /**
     * Fetches all block entities filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage $itemsPerPage
     * @return array
     */
    public function fetchAllByPage($page, $itemsPerPage)
    {
        return $this->prepareResults($this->blockMapper->fetchAllByPage($page, $itemsPerPage));
    }

    /**
     * Fetches all block entities
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->blockMapper->fetchAll());
    }

    /**
     * Fetches block's entity by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return \Krystal\Stdlib\VirtualEntity|boolean
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->blockMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->blockMapper->fetchById($id, false));
        }
    }

    /**
     * Returns last block's id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->blockMapper->getLastId();
    }

    /**
     * Updates a block
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function save(array $input)
    {
        $translation = isset($input['translation']) ? $input['translation'] : array();

        return $this->blockMapper->saveEntity($input['block'], $translation);
    }

    /**
     * Deletes a block by its associated id
     * 
     * @param string|array $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->blockMapper->deleteEntity($id);
    }
}
