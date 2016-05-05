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
    '/%s/module/block' => array(
        'controller' => 'Admin:Block@gridAction'
    ),
    
    '/%s/module/block/page/(:var)' => array(
        'controller' => 'Admin:Block@gridAction'
    ),
    
    '/%s/module/block/delete/(:var)' => array(
        'controller' => 'Admin:Block@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/block/add' => array(
        'controller' => 'Admin:Block@addAction'
    ),
    
    '/%s/module/block/edit/(:var)' => array(
        'controller' => 'Admin:Block@editAction'
    ),
    
    '/%s/module/block/save' => array(
        'controller' => 'Admin:Block@saveAction',
        'disallow' => array('guest')
    )
);