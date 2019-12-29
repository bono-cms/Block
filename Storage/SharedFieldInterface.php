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
     * Fetch active translation by field ids
     * 
     * @param array $fieldIds Attached field ids
     * @param int $entityId Current entity id
     * @return array
     */
    public function findActiveTranslations(array $fieldIds, $entityId);

    /**
     * Find field translation by associated entity id
     * 
     * @param int $id Entity id
     * @return array
     */
    public function findTranslationsByEntityId($id);

    /**
     * Find attached fields by entity id
     * 
     * @param int $id
     * @return array
     */
    public function findFields($id);

    /**
     * Find attached category ids
     * 
     * @param int $id Target entity id
     * @return array
     */
    public function findAttachedSlaves($id);

    /**
     * Save junction relation
     * 
     * @param int $id Target entity id
     * @param array $slaveIds
     * @return boolean
     */
    public function saveRelation($id, array $slaveIds);
}
