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
use Krystal\Http\FileTransfer\FileUploader;
use Krystal\Filesystem\FileManager;
use Block\Storage\SharedFieldInterface;
use Block\Collection\FieldTypeCollection;

/**
 * Shared service for modules that use custom fields
 */
final class FieldService
{
    /* Shared constants */
    const PARAM_UPLOAD_PATH = '/data/uploads/module/block';

    /**
     * An instance of mapper
     * 
     * @var \Block\Storage\SharedFieldInterface
     */
    private $fieldMapper;

    /**
     * Path to document root
     * 
     * @var string
     */
    private static $rootDir;

    /**
     * State initialization
     * 
     * @param \Block\Storage\SharedFieldInterface $fieldMapper
     * @param string $rootDir
     * @return void
     */
    public function __construct(SharedFieldInterface $fieldMapper, $rootDir)
    {
        $this->fieldMapper = $fieldMapper;
        self::$rootDir = $rootDir;
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
     * Parse input that contains translatable fields
     * 
     * @param int $id Entity id
     * @param array $translatable
     * @return array
     */
    private static function parseLocalizedInput($id, array $translations)
    {
        $rows = $options = array();

        foreach ($translations as $langId => $data) {
            foreach($data as $fieldId => $value) {
                $rows[] = array(
                    'field_id' => $fieldId,
                    'lang_id' => $langId,
                    'value' => $value
                );
            }
        }

        $localizations = ArrayUtils::arrayPartition($rows, 'field_id', false);

        foreach ($localizations as $fieldId => $inner) {
            $options[] = [
                'entity_id' => $id,
                'field_id' => $fieldId
            ];
        }

        return array(
            'options' => $options,
            'translations' => $localizations
        );
    }

    /**
     * Uploads a file
     * 
     * @param string $destination Target directory file will be uploaded to
     * @param mixed $file File bag instance
     * @return boolean Depending on success
     */
    private static function uploadFile($destination, $file)
    {
        // Remove all previous files inside current directory, if any
        if (is_dir($destination) && !FileManager::cleanDir($destination)) {
            // If failed, then we don't have enough rights
            return false;
        }

        // Upload current file
        $uploader = new FileUploader();

        // Attempt to upload
        return $uploader->upload($destination, array($file));
    }

    /**
     * Creates destination path for uploading
     * 
     * @param int $id Entity id
     * @param int $fieldId Field id
     * @return string
     */
    private static function createDestinationPath($id, $fieldId)
    {
        return self::$rootDir . self::PARAM_UPLOAD_PATH . '/' . $id . '/' . $fieldId . '/';
    }

    /**
     * Creates relative path for a value
     * 
     * @param int $id Entity id
     * @param int $fieldId Field id
     * @param string $file
     * @return string
     */
    private static function createValuePath($id, $fieldId, $file)
    {
        return self::PARAM_UPLOAD_PATH . '/' . $id . '/' . $fieldId . '/' . $file;
    }

    /**
     * Save fields
     * 
     * @param int $id Current entity id
     * @param array $fields
     * @param array $translations
     * @param array $files Optional request files
     * @return boolean
     */
    public function saveFields($id, array $fields, array $translations = array(), $files = array())
    {
        // Remove previous values
        $this->fieldMapper->deleteByColumn('entity_id', $id);

        // Regular fields
        foreach ($fields as $fieldId => $value) {
            // If current field is a file by its type, then do upload first
            if (isset($files['regular'][$fieldId])) {
                $file =& $files['regular'][$fieldId];
                $destination = self::createDestinationPath($id, $fieldId);

                if (self::uploadFile($destination, $file) !== false) {
                    // Override value with relative path
                    $value = self::createValuePath($id, $fieldId, $file->getUniqueName());
                }
            }

            $this->fieldMapper->persist(array(
                'entity_id' => $id,
                'field_id' => $fieldId,
                'value' => $value
            ));
        }

        // If there are no translatable fields, then save them
        if (!empty($translations)) {
            // Otherwise save with translatable fields
            $data = self::parseLocalizedInput($id, $translations);

            foreach ($data['options'] as $field) {
                // Get all locales by field id
                $locales = $data['translations'][$field['field_id']];

                // If current field is a file by its type, then do upload first
                foreach ($locales as &$locale) {
                    if (isset($files['translatable'][$locale['lang_id']][$field['field_id']])) {
                        // Current file instance
                        $file = $files['translatable'][$locale['lang_id']][$field['field_id']];

                        // Upload a file first
                        if (self::uploadFile(self::createDestinationPath($id, $field['field_id']), $file)) {
                            $locale['value'] = self::createValuePath($id, $fieldId, $file->getUniqueName());
                        }
                    }
                }

                $this->fieldMapper->saveEntity($field, $locales);
            }
        }

        return true;
    }

    /**
     * Append fields on an entity
     * 
     * @param \Krystal\Stdlib\VirtualEntity $entity
     * @return void
     */
    public function appendFields(VirtualEntity $entity)
    {
        $id = $entity->getId();

        // If entity has id
        if ($id) {
            $output = array();
            $rows = $this->fieldMapper->findFields($id);

            // Find translations
            $fieldIds = array_column($rows, 'id');

            // Find and normalize translations
            $translations = ArrayUtils::arrayList($this->fieldMapper->findActiveTranslations($fieldIds), 'field_id', 'value');

            // Start preparing data
            foreach($rows as $row){
                // Special case to convert to boolean
                if ($row['type'] == FieldTypeCollection::TYPE_BOOLEAN) {
                    $row['value'] = boolval($row['value']);
                }

                // Turn a string into array, if required
                if ($row['type'] == FieldTypeCollection::TYPE_ARRAY) {
                    $row['value'] = explode(PHP_EOL, $row['value']);
                }

                // Override value from current language session, if translatable field encountered
                if ($row['translatable'] == 1 && isset($translations[$row['id']])) {
                    $row['value'] = $translations[$row['id']];
                }

                $output[$row['id']] = $row['value'];
            }

            // Append prepared data
            $entity->setFields($output);
        }
    }

    /**
     * Append field translations
     * 
     * @param int $id Entity id
     * @param array $raw
     * @return array
     */
    private function appendTranslations($id, array $raw)
    {
        // Find attached translations (to be merged)
        $translations = $this->fieldMapper->findTranslationsByEntityId($id);

        foreach ($raw as $index => $field) {
            foreach ($translations as $translation) {
                
                if ($translation['field_id'] == $field['id']) {
                    // Create if not created
                    if (!isset($raw[$index]['translations'])) {
                        $raw[$index]['translations'] = array();
                    }
                    
                    $raw[$index]['translations'][$translation['lang_id']] = $translation['value'];
                }
            }
        }

        return $raw;
    }
    
    /**
     * Returns attached fields with their values
     * 
     * @param int $id Entity id
     * @return array
     */
    public function getFields($id)
    {
        // Grab raw rows first
        $rows = $this->fieldMapper->findFields($id);

        // Get count
        $types = array_column($rows, 'type');
        $count = count($types);

        if (!empty($rows)) {
            // Separate by translatable and non-translatable attributes
            $groups = ArrayUtils::arrayPartition($rows, 'translatable', false);

            // Give them meaningful key names now
            $groups['regular'] = $groups[0];
            $groups['translatable'] = $groups[1];

            // Append translations
            $groups['translatable'] = $this->appendTranslations($id, $groups['translatable']);

            // And unset numbers
            unset($groups[0], $groups[1]);

            // Now separate groups by categories. This simplifies rendering
            foreach ($groups as $name => $group) {
                $groups[$name] = ArrayUtils::arrayPartition($groups[$name], 'category', false);
            }

            return array(
                'data' => $groups,
                'count' => $count
            );

        } else {
            return array();
        }
    }

    /**
     * Get attached category ids
     * 
     * @param int $id Entity id
     * @return array
     */
    public function getAttachedCategories($id)
    {
        return $this->fieldMapper->findAttachedSlaves($id);
    }
}
