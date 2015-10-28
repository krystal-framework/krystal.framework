<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList;

use Krystal\Tree\AdjacencyList\Render\AbstractRenderer;

interface TreeInterface
{
    /**
     * Renders an interface
     * 
     * @param \Krystal\Tree\AdjacencyList\Render\AbstractRenderer $renderer Any renderer which extends AbstractRenderer
     * @param string $active
     * @return string
     */
    public function render(AbstractRenderer $renderer, $active = null);
}
