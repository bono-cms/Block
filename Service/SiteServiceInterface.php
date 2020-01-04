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
     * @param int $id Block id
     * @return string
     */
    public function render($id);

    /**
     * Renders a text exploding it into array
     * 
     * @param int $id Block id
     * @param boolean $trim Whether to trim extra spaces
     * @return array|boolean
     */
    public function renderAsArray($id, $trim = true);
}
