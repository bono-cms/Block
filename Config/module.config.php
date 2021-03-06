<?php

/**
 * Module configuration container
 */

return array(
    'name'  => 'Block',
    'description' => 'HTML Blocks module allows you to dynamically handle HTML blocks',
    'menu' => array(
        'name' => 'HTML Blocks',
        'icon' => 'fas fa-otter fa-5x',
        'items' => array(
            array(
                'route' => 'Block:Admin:Block@indexAction',
                'name' => 'View all blocks'
            ),
            array(
                'route' => 'Block:Admin:Block@addAction',
                'name' => 'Add new block'
            )
        )
    )
);
