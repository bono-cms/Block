<?php

/**
 * Module configuration container
 */

return array(
    'name'  => 'Block',
    'description' => 'HTML Blocks module allows you to dynamically handle HTML blocks',
    'menu' => array(
        'name' => 'HTML Blocks',
        'icon' => 'fa fa-cubes fa-5x',
        'items' => array(
            array(
                'route' => 'Block:Admin:Block@gridAction',
                'name' => 'View all blocks'
            ),
            array(
                'route' => 'Block:Admin:Block@addAction',
                'name' => 'Add new block'
            )
        )
    )
);
