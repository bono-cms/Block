<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(
	
	'/admin/module/block' => array(
		'controller' => 'Admin:Browser@indexAction'
	),
	
	'/admin/module/block/page/(:var)' => array(
		'controller' => 'Admin:Browser@indexAction'
	),
	
	'/admin/module/block/delete.ajax' => array(
		'controller' => 'Admin:Browser@deleteAction',
		'disallow' => array('guest')
	),
	
	'/admin/module/block/delete-selected.ajax' => array(
		'controller' => 'Admin:Browser@deleteSelectedAction',
		'disallow' => array('guest')
	),
	
	'/admin/module/block/add' => array(
		'controller' => 'Admin:Add@indexAction'
	),
	
	'/admin/module/block/add.ajax' => array(
		'controller' => 'Admin:Add@addAction',
		'disallow' => array('guest')
	),
	
	'/admin/module/block/edit/(:var)' => array(
		'controller' => 'Admin:Edit@indexAction'
	),
	
	'/admin/module/block/edit.ajax' => array(
		'controller' => 'Admin:Edit@updateAction',
		'disallow' => array('guest')
	)
);
