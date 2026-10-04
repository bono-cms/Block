<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Stdlib\VirtualEntity;
use Block\Collection\FieldTypeCollection;

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
        $this->view->getBreadcrumbBag()->addOne('HTML Blocks', 'Block:Admin:Block@indexAction')
                                       ->addOne($category->getId() ? $this->translator->translate('Edit the category "%s"', $category->getName()) : 'Add new category');

        $fTypeCol = new FieldTypeCollection();

        return $this->view->render('category.form', [
            'category' => $category,
            'field' => $field,
            'fields' => $category->getId() ? $this->getModuleService('categoryFieldService')->fetchAll($category->getId()) : [],
            'fieldTypes' => $fTypeCol->getAll()
        ]);
    }
}