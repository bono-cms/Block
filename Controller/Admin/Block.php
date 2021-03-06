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

use Cms\Controller\Admin\AbstractController;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Validate\Pattern;

final class Block extends AbstractController
{
    /**
     * Renders a grid
     * 
     * @return string
     */
    public function indexAction()
    {
        // Current page number
        $page = $this->request->getQuery('page', 1);

        // Add a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('HTML Blocks');

        $blockManager = $this->getModuleService('blockManager');

        return $this->view->render('index', array(
            'blocks'    => $blockManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
            'categories' => $this->getModuleService('categoryService')->fetchAll(),
            'paginator' => $blockManager->getPaginator()
        ));
    }

    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $block
     * @param string $title
     * @return string
     */
    private function createForm($block, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Block/admin/block.form.js');

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('HTML Blocks', 'Block:Admin:Block@indexAction')
                                       ->addOne($title);

        return $this->view->render('block.form', array(
            'block' => $block
        ));
    }

    /**
     * Creates shared add form
     * 
     * @param boolean $translatable Whether this block is translatable
     * @param string $title Page title
     * @return string
     */
    private function createAddForm($translatable, $title)
    {
        $block = new VirtualEntity();
        $block->setTranslatable($translatable);

        return $this->createForm($block, $title);
    }

    /**
     * Renders translatable form
     * 
     * @return string
     */
    public function addTranslatableAction()
    {
        return $this->createAddForm(true, 'Add new translatable block');
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createAddForm(false, 'Add a block');
    }

    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $block = $this->getModuleService('blockManager')->fetchById($id, true);

        if ($block !== false) {
            $name = $this->getCurrentProperty($block, 'name');
            return $this->createForm($block, $this->translator->translate('Edit the block "%s"', $name));
        } else {
            return false;
        }
    }

    /**
     * Deletes a block by its associated id
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        $historyService = $this->getService('Cms', 'historyManager');
        $service = $this->getModuleService('blockManager');

        // Batch removal
        if ($this->request->hasPost('batch')) {
            $ids = array_keys($this->request->getPost('batch'));

            $service->delete($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

            // Save in the history
            $historyService->write('Block', 'Batch removal of %s blocks', count($ids));

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if (!empty($id)) {
            $block = $this->getModuleService('blockManager')->fetchById($id, false);

            $service->delete($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');

            // Save in the history
            $historyService->write('Block', 'Removed "%s" block', $block->getName());
        }

        return '1';
    }

    /**
     * Persists a block
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('block');

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name()
                )
            )
        ));

        if ($formValidator->isValid()) {
            $historyService = $this->getService('Cms', 'historyManager');
            $service = $this->getModuleService('blockManager');

            // Save data
            $service->save($this->request->getPost());

            // Update
            if (!empty($input['id'])) {
                $this->flashBag->set('success', 'The element has been updated successfully');

                $historyService->write('Block', 'Updated block "%s"', $input['name']);
                return '1';

            } else {
                // Create
                $this->flashBag->set('success', 'The element has been created successfully');

                $historyService->write('Block', 'Added new block "%s"', $input['name']);
                return $service->getLastId();
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
