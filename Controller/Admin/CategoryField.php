<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Controller\Admin;

use Krystal\Stdlib\VirtualEntity;

final class CategoryField extends AbstractCategoryController
{
    /**
     * Adds category field
     * 
     * @param int $categoryId
     * @return string
     */
    public function addAction($categoryId)
    {
        $category = $this->getModuleService('categoryService')->fetchById($categoryId);

        if ($category !== false) {
            $field = new VirtualEntity();
            $field->setCategoryId($categoryId);

            return $this->createForm($category, $field);
        } else {
            return false;
        }
    }

    /**
     * Renders edit form
     * 
     * @param int $id Field ID
     * @return string
     */
    public function editAction($id)
    {
        $field = $this->getModuleService('categoryFieldService')->fetchById($id);

        if ($field !== false) {
            $category = $this->getModuleService('categoryService')->fetchById($field->getCategoryId());

            return $this->createForm($category, $field);
        } else {
            return false;
        }
    }

    /**
     * Deletes category field
     * 
     * @param int $id Field ID
     * @return string
     */
    public function deleteAction($id)
    {
        $this->getModuleService('categoryFieldService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return $this->json([
            'refresh' => true
        ]);
    }

    /**
     * Saves a field
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('field');

        $validator = $this->createValidation();

        $validator->field('field.name')
                  ->required();

        if ($validator->isPassed()) {
            $categoryFieldService = $this->getModuleService('categoryFieldService');
            $categoryFieldService->save($input);

            if ($input['id']) {
                $this->flashBag->set('success', 'The element has been updated successfully');
                return $this->json([
                    'refresh' => true
                ]);
            } else {
                $this->flashBag->set('success', 'The element has been created successfully');
                return $this->json([
                    'redirect' => $this->createUrl('Block:Admin:CategoryField@editAction', [$categoryFieldService->getLastId()])
                ]);
            }
        } else {
            return $this->json([
                'errors' => $validator->getErrors()
            ]);
        }
    }
}