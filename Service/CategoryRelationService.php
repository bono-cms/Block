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

use Block\Storage\CategoryRelationMapperInterface;
use Cms\Service\AbstractManager;

final class CategoryRelationService extends AbstractManager
{
    /**
     * Category relation mapper
     * 
     * @var \Block\Storage\CategoryRelationMapperInterface
     */
    private $categoryRelationMapper;

    /**
     * State initialization
     * 
     * @param \Block\Storage\CategoryRelationMapperInterface $categoryRelationMapper
     * @return void
     */
    public function __construct(CategoryRelationMapperInterface $categoryRelationMapper)
    {
        $this->categoryRelationMapper = $categoryRelationMapper;
    }

    /**
     * Finds all records by page ID
     * 
     * @param int $id Page id
     * @return array
     */
    public function findAllByPageId($id)
    {
        return $this->categoryRelationMapper->findAllByPageId($id);
    }
}
