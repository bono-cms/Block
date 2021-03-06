<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Storage;

interface BlockMapperInterface
{
    /**
     * Fetches all blocks filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $perPageCount Per page count
     * @return array
     */
    public function fetchAllByPage($page, $perPageCount);

    /**
     * Fetches block data by its associated id
     * 
     * @param string $id Block id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations);
}
