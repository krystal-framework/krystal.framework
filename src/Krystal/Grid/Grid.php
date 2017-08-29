<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Grid;

use Krystal\I18n\TranslatorInterface;

final class Grid
{
    /**
     * Renders the grid
     * 
     * @param array $data
     * @param array $options
     * @param \Krystal\I18n\TranslatorInterface $translator
     * @return string
     */
    public static function render(array $data, array $options, TranslatorInterface $translator = null)
    {
        $maker = new TableMaker($data, $options, $translator);
        return $maker->render();
    }
}
