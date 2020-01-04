<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block;

use Cms\AbstractCmsModule;
use Block\Service\BlockManager;
use Block\Service\SiteService;
use Block\Service\CategoryService;
use Block\Service\CategoryFieldService;
use Block\Service\CategoryRelationService;

final class Module extends AbstractCmsModule
{
    /**
     * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        $blockMapper = $this->getMapper('/Block/Storage/MySQL/BlockMapper');
        $blockManager = new BlockManager($blockMapper);

        return array(
            'siteService' => new SiteService($blockManager),
            'blockManager' => $blockManager,
            'categoryService' => new CategoryService($this->getMapper('/Block/Storage/MySQL/CategoryMapper')),
            'categoryFieldService' => new CategoryFieldService($this->getMapper('/Block/Storage/MySQL/CategoryFieldMapper')),
            'categoryRelationService' => new CategoryRelationService($this->getMapper('/Block/Storage/MySQL/CategoryRelationMapper'))
        );
    }
}
