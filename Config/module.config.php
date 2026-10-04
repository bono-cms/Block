<?php

/**
 * Module configuration container
 */

return [
    'name'  => 'Block',
    'description' => 'HTML Blocks module allows you to dynamically handle HTML blocks',
    'menu' => [
        'name' => 'HTML Blocks',
        'icon' => 'fas fa-otter fa-5x',
        'items' => [
            [
                'route' => 'Block:Admin:Block@indexAction',
                'name' => 'View all blocks'
            ],
            [
                'route' => 'Block:Admin:Block@addAction',
                'name' => 'Add new block'
            ]
        ]
    ]
];