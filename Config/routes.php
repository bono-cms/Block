<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return [
    '/%s/module/block' => [
        'controller' => 'Admin:Block@indexAction'
    ],

    '/%s/module/block/delete/(:var)' => [
        'controller' => 'Admin:Block@deleteAction',
        'disallow' => ['guest']
    ],
    
    '/%s/module/block/add' => [
        'controller' => 'Admin:Block@addAction'
    ],
    
    '/%s/module/block/add-translatable' => [
        'controller' => 'Admin:Block@addTranslatableAction'
    ],

    '/%s/module/block/edit/(:var)' => [
        'controller' => 'Admin:Block@editAction'
    ],
    
    '/%s/module/block/save' => [
        'controller' => 'Admin:Block@saveAction',
        'disallow' => ['guest']
    ],
    
    // Categories
    '/%s/module/block/category/delete/(:var)' => [
        'controller' => 'Admin:Category@deleteAction',
        'disallow' => ['guest']
    ],
    
    '/%s/module/block/category/add' => [
        'controller' => 'Admin:Category@addAction'
    ],
    
    '/%s/module/block/category/edit/(:var)' => [
        'controller' => 'Admin:Category@editAction'
    ],
    
    '/%s/module/block/category/save' => [
        'controller' => 'Admin:Category@saveAction',
        'disallow' => ['guest']
    ],

    // Category fields
    '/%s/module/block/category/field/save' => [
        'controller' => 'Admin:CategoryField@saveAction'
    ],

    '/%s/module/block/category/field/delete/(:var)' => [
        'controller' => 'Admin:CategoryField@deleteAction'
    ],

    '/%s/module/block/category/field/add/(:var)' => [
        'controller' => 'Admin:CategoryField@addAction'
    ],
    
    '/%s/module/block/category/field/edit/(:var)' => [
        'controller' => 'Admin:CategoryField@editAction'
    ]
];