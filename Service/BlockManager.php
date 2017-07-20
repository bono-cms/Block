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
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

final class BlockManager extends AbstractManager implements BlockManagerInterface
{
    /**
     * Any mapper which implements BlockMapperInterface
     * 
     * @var \Block\Storage\BlockMapperInterface
     */
    private $blockMapper;

    /**
     * History manager to keep track
     * 
     * @var \Cms\Service\HistoryManagerInterface
     */
    private $historyManager;

    /**
     * State initialization
     * 
     * @param \Block\Storage\BlockMapperInterface $blockMapper
     * @param \Cms\Service\HistoryManagerInterface $historyManager
     * @return void
     */
    public function __construct(BlockMapperInterface $blockMapper, HistoryManagerInterface $historyManager)
    {
        $this->blockMapper = $blockMapper;
        $this->historyManager = $historyManager;
    }

    /**
     * Tracks activity
     * 
     * @param string $message
     * @param string $placeholder
     * @return boolean
     */
    private function track($message, $placeholder)
    {
        return $this->historyManager->write('HTML Blocks', $message, $placeholder);
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $block)
    {
        $entity = new VirtualEntity();
        $entity->setId($block['id'], VirtualEntity::FILTER_INT)
            ->setLangId($block['lang_id'], VirtualEntity::FILTER_INT)
            ->setName($block['name'], VirtualEntity::FILTER_HTML)
            ->setClass($block['class'], VirtualEntity::FILTER_HTML)
            ->setContent($block['content'], VirtualEntity::FILTER_SAFE_TAGS);

        return $entity;
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
     * Returns prepared paginator instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->blockMapper->getPaginator();
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
     * Returns last block's id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->blockMapper->getLastId();
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
     * Adds a block
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        #$this->track('Added new block "%s"', $input['name']);
        return $this->blockMapper->saveEntity($input['block'], $input['translation']);
    }

    /**
     * Updates a block
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        #$this->track('Updated block "%s"', $input['name']);
        return $this->blockMapper->saveEntity($input['block'], $input['translation']);
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
     * Deletes a block by its associated id
     * 
     * @param string $id
     * @return boolean
     */
    public function deleteById($id)
    {
        // Grab block's name before we fetch it
        $name = Filter::escape($this->blockMapper->fetchNameById($id));

        if ($this->blockMapper->deleteById($id)) {
            $this->track('Removed "%s" block', $name);
            return true;

        } else {
            return false;
        }
    }

    /**
     * Delete blocks by their associated id
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteByIds(array $ids)
    {
        foreach ($ids as $id) {
            if (!$this->blockMapper->deleteById($id)) {
                return false;
            }
        }

        $this->track('Batch removal of %s blocks', count($ids));
        return true;
    }
}
