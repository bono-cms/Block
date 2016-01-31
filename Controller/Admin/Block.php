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
     * @param integer $page Current page
     * @return string
     */
    public function gridAction($page = 1)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Block/admin/browser.js');

        // Add a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('HTML Blocks');

        $blockManager = $this->getModuleService('blockManager');

        $paginator = $blockManager->getPaginator();
        $paginator->setUrl('/admin/module/block/page/(:var)');

        return $this->view->render('browser', array(
            'blocks'    => $blockManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
            'paginator' => $paginator
        ));
    }

    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $block
     * @param string $title
     * @return string
     */
    private function createForm(VirtualEntity $block, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Block/admin/block.form.js');

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('HTML Blocks', 'Block:Admin:Block@gridAction')
                                       ->addOne($title);

        return $this->view->render('block.form', array(
            'block' => $block
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity(), 'Add a block');
    }

    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $block = $this->getModuleService('blockManager')->fetchById($id);

        if ($block !== false) {
            return $this->createForm($block, 'Edit the block');
        } else {
            return false;
        }
    }

    /**
     * Deletes a block by its associated id
     * 
     * @return string
     */
    public function deleteAction()
    {
        return $this->invokeRemoval('blockManager');
    }

    /**
     * Persists a block
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('block');

        return $this->invokeSave('blockManager', $input, array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'content' => new Pattern\Content()
                )
            )
        ));
    }
}
