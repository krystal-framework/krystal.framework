<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

final class Object implements FormElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $node = new NodeElement();

        return $node->openTag('object')
                    ->addAttributes($attrs)
                    ->finalize()
                    ->closeTag()
                    ->render();
    }
}
