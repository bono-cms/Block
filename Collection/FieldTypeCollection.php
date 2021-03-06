<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Collection;

use Krystal\Stdlib\ArrayCollection;

final class FieldTypeCollection extends ArrayCollection
{
    const TYPE_TEXT = 1;
    const TYPE_TEXTAREA = 2;
    const TYPE_NUMBER = 3;
    const TYPE_BOOLEAN = 4;
    const TYPE_WYSIWYG = 5;
    const TYPE_ARRAY = 6;
    const TYPE_FILE = 7;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::TYPE_TEXT => 'Text',
        self::TYPE_TEXTAREA => 'Textarea',
        self::TYPE_NUMBER => 'Number',
        self::TYPE_BOOLEAN => 'Boolean',
        self::TYPE_WYSIWYG => 'WYSIWYG',
        self::TYPE_ARRAY => 'Array',
        self::TYPE_FILE => 'File'
    );
}
