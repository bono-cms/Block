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

final class SiteService implements SiteServiceInterface
{
    /**
     * Any compliant block mapper
     * 
     * @var \Block\Service\BlockManager $blockManager
     */
    private $blockManager;

    /**
     * State initialization
     * 
     * @param \Block\Service\BlockManager $blockManager
     * @return void
     */
    public function __construct(BlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    /**
     * Renders a block
     * 
     * @param int $id Block id
     * @return string
     */
    public function render($id)
    {
        $block = $this->blockManager->fetchById($id, false);

        if ($block) {
            return $block->getTranslatable() ? $block->getContent() : $block->getValue();
        } else {
            return null;
        }
    }

    /**
     * Renders a text exploding it into array
     * 
     * @param int $id Block id
     * @param boolean $trim Whether to trim extra spaces
     * @return array|boolean
     */
    public function renderAsArray($id, $trim = true)
    {
        $collection = explode("\r", $this->render($id));

        if ($collection !== null) {
            foreach ($collection as &$item) {
                if ($trim === true) {
                    $item = rtrim($item);
                    $item = ltrim($item);
                }
            }

            return $collection;
        } else {
            return false;
        }
    }
}
