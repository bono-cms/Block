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

use Block\Storage\BlockMapperInterface;

final class SiteService implements SiteServiceInterface
{
    /**
     * Any compliant block mapper
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
     * Renders a block
     * 
     * @param string $class Block's class name
     * @return string
     */
    public function render($class)
    {
        return $this->blockMapper->fetchContentByClass($class);
    }

    /**
     * Renders a text exploding it into array
     * 
     * @param string $class
     * @param boolean $trim Whether to trim extra spaces
     * @return array
     */
    public function renderAsArray($class, $trim = true)
    {
        $collection = explode("\r", $this->render($class));

        foreach ($collection as &$item) {
            if ($trim === true) {
                $item = rtrim($item);
                $item = ltrim($item);
            }
        }

        return $collection;
    }
}
