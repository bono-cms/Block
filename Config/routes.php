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
        'controller' => 'Admin:Block@gridAction'
    ),
    
    '/admin/module/block/page/(:var)' => array(
        'controller' => 'Admin:Block@gridAction'
    ),
    
    '/admin/module/block/delete' => array(
        'controller' => 'Admin:Block@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/block/add' => array(
        'controller' => 'Admin:Block@addAction'
    ),
    
    '/admin/module/block/edit/(:var)' => array(
        'controller' => 'Admin:Block@editAction'
    ),
    
    '/admin/module/block/save' => array(
        'controller' => 'Admin:Block@saveAction',
        'disallow' => array('guest')
    )
);
