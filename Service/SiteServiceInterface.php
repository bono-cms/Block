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

interface SiteServiceInterface
{
    /**
     * Renders a block
     * 
     * @param string $class Block's class name
     * @return string
     */
    public function render($class);

    /**
     * Renders a text exploding it into array
     * 
     * @param string $class
     * @param boolean $trim Whether to trim extra spaces
     * @return array
     */
    public function renderAsArray($class, $trim = true);
}
