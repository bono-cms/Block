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

final class Add extends AbstractBlock
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'block' => new VirtualEntity(),
			'title' => 'Add a block'
		)));
	}

	/**
	 * Adds a block
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('block'));

		if ($formValidator->isValid()) {

			$blockManager = $this->getBlockManager();

			if ($blockManager->add($this->request->getPost('block'))) {

				$this->flashBag->set('success', 'A block has been created successfully');
				return $blockManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
