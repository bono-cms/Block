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

use Block\Storage\CategoryFieldMapperInterface;
use Cms\Service\AbstractManager;
use Krystal\Stdlib\VirtualEntity;

final class CategoryFieldService extends AbstractManager
{
    /**
     * Any compliant category field mapper
     * 
     * @var \Block\Storage\CategoryFieldMapperInterface
     */
    private $categoryFieldMapper;

    /**
     * State initialization
     * 
     * @param \Block\Storage\CategoryFieldMapperInterface $categoryFieldMapper
     * @return void
     */
    public function __construct(CategoryFieldMapperInterface $categoryFieldMapper)
    {
        $this->categoryFieldMapper = $categoryFieldMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'], VirtualEntity::FILTER_INT)
               ->setCategoryId($row['category_id'], VirtualEntity::FILTER_INT)
               ->setName($row['name'], VirtualEntity::FILTER_TAGS)
               ->setType($row['type'], VirtualEntity::FILTER_INT);

        return $entity;
    }

    /**
     * Fetch all fields by attached category ID
     * 
     * @param int $categoryId
     * @return array
     */
    public function fetchAll($categoryId)
    {
        return $this->prepareResults($this->categoryFieldMapper->fetchAll($categoryId));
    }
}
