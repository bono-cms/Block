<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\View;

use Block\Collection\FieldTypeCollection;
use Krystal\Form\Element;

final class FieldMaker
{
    /**
     * Render translatable field
     * 
     * @param int $id Field id
     * @param int $languageId Current language id
     * @param string $value Field value
     * @param int $type Type constant
     * @return string
     */
    public static function makeTranslatableField($id, $languageId, $value, $type)
    {
        return self::makeField(sprintf('field[translatable][%s][%s]', $languageId, $id), $value, $type);
    }

    /**
     * Renders non-translatable field
     * 
     * @param int $id Field id
     * @param string $value Field value
     * @param int $type Type constant
     * @return string
     */
    public static function makeRegularField($id, $value, $type)
    {
        return self::makeField(sprintf('field[regular][%s]', $id), $value, $type);
    }

    /**
     * Render field depending on a type
     * 
     * @param string $name
     * @param string $value
     * @param int $type Type constant
     * @return string
     */
    private static function makeField($name, $value, $type)
    {
        switch ($type) {
            case FieldTypeCollection::TYPE_FILE:
                return Element::hidden($name, $value) . Element::file($name, null, array('class' => 'form-control'));

            case FieldTypeCollection::TYPE_TEXT:
                return Element::text($name, $value, array('class' => 'form-control'));

            case FieldTypeCollection::TYPE_TEXTAREA || FieldTypeCollection::TYPE_ARRAY:
                return Element::textarea($name, $value, array('class' => 'form-control'));

            case FieldTypeCollection::TYPE_NUMBER:
                return Element::number($name, $value, array('class' => 'form-control'));

            case FieldTypeCollection::TYPE_BOOLEAN:
                return Element::checkbox($name, $value, array('class' => 'form-control'));

            case FieldTypeCollection::TYPE_WYSIWYG:
                return Element::textarea($name, $value, array('class' => 'form-control', 'data-wysiwyg' => 'true'));
        }
    }
}
