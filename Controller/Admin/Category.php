<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Controller\Admin;

use Krystal\Stdlib\VirtualEntity;

final class Category extends AbstractCategoryController
{
    /**
     * Renders the add form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity, new VirtualEntity);
    }

    /**
     * Renders the edit form
     * 
     * @param int $id Category ID
     * @return mixed
     */
    public function editAction($id)
    {
        $category = $this->getModuleService('categoryService')->fetchById($id);

        if ($category !== false) {
            $field = new VirtualEntity();
            $field->setCategoryId($id);

            return $this->createForm($category, $field);
        } else {
            return false;
        }
    }

    /**
     * Deletes a category by its ID
     * 
     * @param int $id Category ID
     * @return string
     */
    public function deleteAction($id)
    {
        $this->getModuleService('categoryService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return $this->json([
            'refresh' => true
        ]);
    }

    /**
     * Saves a category
     * 
     * @return mixed
     */
    public function saveAction()
    {
        $input = $this->request->getPost('category');

        $categoryService = $this->getModuleService('categoryService');
        $categoryService->save($input);

        if ($input['id']) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return $this->json([
                'refresh' => true
            ]);
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $this->json([
                'redirect' => $this->createUrl('Block:Admin:Category@editAction', [$categoryService->getLastId()]),
            ]);
        }
    }
}