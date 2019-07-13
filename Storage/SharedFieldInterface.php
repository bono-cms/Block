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

interface SharedFieldInterface
{
    /**
     * Find attached fields by page id
     * 
     * @param int $id
     * @return array
     */
    public function findFields($id);

    /**
     * Find attached category ids
     * 
     * @param int $pageId Target page id
     * @return array
     */
    public function findAttachedSlaves($pageId);

    /**
     * Save junction relation
     * 
     * @param int $pageId Target page id
     * @param array $slaveIds
     * @return boolean
     */
    public function saveRelation($pageId, array $slaveIds);
}
