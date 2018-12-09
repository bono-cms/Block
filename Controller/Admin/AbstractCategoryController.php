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

abstract class AbstractCategoryController extends AbstractController
{
    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $category
     * @param \Krystal\Stdlib\VirtualEntity $field
     * @return string
     */
    final protected function createForm(VirtualEntity $category, VirtualEntity $field)
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('HTML Blocks', 'Block:Admin:Block@gridAction')
                                       ->addOne($category->getId() ? 'Edit the category' : 'Add new category');

        return $this->view->render('category.form', array(
            'category' => $category,
            'field' => $field,
            'fields' => $category->getId() ? $this->getModuleService('categoryFieldService')->fetchAll($category->getId()) : array()
        ));
    }
}
