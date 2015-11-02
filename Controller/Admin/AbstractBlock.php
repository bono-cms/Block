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

use Krystal\Validate\Pattern;

abstract class AbstractBlock extends AbstractAdminController
{
    /**
     * Returns prepared and configured validator's instance
     * 
     * @param array $input Raw input data
     * @return \Krystal\Validate\ValidatorChain
     */
    final protected function getValidator(array $input)
    {
        return $this->validatorFactory->build(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'content' => new Pattern\Content()
                )
            )
        ));
    }

    /**
     * Returns template path
     * 
     * @return string
     */
    final protected function getTemplatePath()
    {
        return 'block.form';
    }

    /**
     * Loads shared plugins
     * 
     * @return void
     */
    final protected function loadSharedPlugins()
    {
        $this->view->getPluginBag()
                   ->appendScript('@Block/admin/block.form.js');
    }

    /**
     * Loads breadcrumbs
     * 
     * @param string $title
     * @return void
     */
    final protected function loadBreadcrumbs($title)
    {
        $this->view->getBreadcrumbBag()->addOne('HTML Blocks', 'Block:Admin:Browser@indexAction')
                                       ->addOne($title);
    }
}
