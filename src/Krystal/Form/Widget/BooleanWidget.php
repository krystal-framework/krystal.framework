<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Widget;

use Krystal\Form\Element\Select;
use Krystal\I18n\TranslatorInterface;

class BooleanWidget
{
    /**
     * Renders the widget
     * 
     * @param array $attrs Element attributes
     * @param string $active (Should be either 1 or 0)
     * @param \Krystal\I18n\TranslatorInterface $translator
     * @param string $prompt Prompt text
     * @return string
     */
    public static function render($attrs, $active, TranslatorInterface $translator = null, $prompt = '')
    {
        $list = array(
            '1' => 'Yes',
            '0' => 'No'
        );

        $defaults = array('' => $prompt);

        if ($translator !== null) {
            $list = $translator->translateArray($list);
            $defaults = $translator->translateArray($defaults);
        }

        $element = new Select($list, $active, $defaults);
        return $element->render($attrs);
    }
}
