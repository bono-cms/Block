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

use Krystal\Stdlib\ArrayUtils;
use Krystal\Stdlib\VirtualEntity;
use Block\Storage\SharedFieldInterface;
use Block\Collection\FieldTypeCollection;

/**
 * Shared service for modules that use custom fields
 */
final class FieldService
{
    /**
     * An instance of mapper
     * 
     * @var \Block\Storage\SharedFieldInterface
     */
    private $fieldMapper;

    /**
     * State initialization
     * 
     * @param \Block\Storage\SharedFieldInterface $fieldMapper
     * @return void
     */
    public function __construct(SharedFieldInterface $fieldMapper)
    {
        $this->fieldMapper = $fieldMapper;
    }

    /**
     * Save field relation
     * 
     * @param int $pageId
     * @param array $relations
     * @return boolean
     */
    public function saveRelation($pageId, array $relations)
    {
        return $this->fieldMapper->saveRelation($pageId, $relations);
    }

    /**
     * Save fields
     * 
     * @param int $pageId
     * @param array $fields
     * @return boolean
     */
    public function saveFields($pageId, array $fields)
    {
        // Remove previous values
        $this->fieldMapper->deleteByColumn('page_id', $pageId);

        foreach ($fields as $id => $value) {
            $data = array(
                'page_id' => $pageId,
                'field_id' => $id,
                'value' => $value
            );

            $this->fieldMapper->persist($data);
        }

        return true;
    }

    /**
     * Append fields on page entity
     * 
     * @param \Krystal\Stdlib\VirtualEntity $page
     * @return void
     */
    public function appendFields(VirtualEntity $page)
    {
        $id = $page->getId();

        // If entity has id
        if ($id) {
            $rows = $this->fieldMapper->findFields($id);
            $output = array();

            // Start preparing data
            foreach($rows as $row){
                // Special case to convert to boolean
                if ($row['type'] == FieldTypeCollection::TYPE_BOOLEAN){
                    $row['value'] = boolval($row['value']);
                }

                // Turn a string into array, if required
                if ($row['type'] == FieldTypeCollection::TYPE_ARRAY) {
                    $row['value'] = explode(PHP_EOL, $row['value']);
                }

                $output[$row['id']] = $row['value'];
            }

            // Append prepared data
            $page->setFields($output);
        }
    }

    /**
     * Returns attached fields with their values
     * 
     * @param int $pageId
     * @return array
     */
    public function getFields($pageId)
    {
        // Grab raw rows first
        $rows = $this->fieldMapper->findFields($pageId);

        // Separate by translatable and non-translatable attributes
        $groups = ArrayUtils::arrayPartition($rows, 'translatable', false);

        // Give them meaningful key names now
        $groups['regular'] = $groups[0];
        $groups['translatable'] = $groups[1];

        // And unset numbers
        unset($groups[0], $groups[1]);

        // Now separate groups by categories. This simplifies rendering
        foreach ($groups as $name => $group) {
            $groups[$name] = ArrayUtils::arrayPartition($groups[$name], 'category', false);
        }

        return $groups;
    }

    /**
     * Get attached category ids
     * 
     * @param int $pageId
     * @param array $relations
     * @return array
     */
    public function getAttachedCategories($pageId)
    {
        return $this->fieldMapper->findAttachedSlaves($pageId);
    }
}
