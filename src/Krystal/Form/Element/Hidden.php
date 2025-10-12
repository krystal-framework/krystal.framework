<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

final class Hidden implements FormElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $attrs['type'] = 'hidden';

        $node = new NodeElement();
        $node->openTag('input')
             ->addAttributes($attrs)
             ->finalize(true);

        return $node->render();
    }
}
