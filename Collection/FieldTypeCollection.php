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

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::TYPE_TEXT => 'Text',
        self::TYPE_TEXTAREA => 'Textarea',
        self::TYPE_NUMBER => 'Number',
        self::TYPE_BOOLEAN => 'Boolean'
    );
}
