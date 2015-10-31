<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

interface TemplateViewInterface
{
    /**
     * Returns content of glued layout and its fragment
     * 
     * @param string $layout Path to a layout
     * @param string $fragment Path to a fragment
     * @param string $variable Variable name which represents a fragment
     * @return string
     */
    public function getFileContentWithLayout($layout, $fragment, $variable);

    /**
     * Includes a file a returns its content as a string
     * 
     * @param string $file Path to the file
     * @return string
     */
    public function getFileContent($file);
}
