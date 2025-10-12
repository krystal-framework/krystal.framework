<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

final class Radio implements FormElementInterface
{
    /**
     * Whether must be checked on rendering or not
     * 
     * @var boolean
     */
    private $active;

    /**
     * State initialization
     * 
     * @param boolean $active
     * @return void
     */
    public function __construct($active)
    {
        $this->active = $active;
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $attrs['type'] = 'radio';

        $node = new NodeElement();
        $node->openTag('input')
             ->addAttributes($attrs);

        // Check if active
        if ($this->active) {
            $node->addProperty('checked');
        }

        return $node->finalize(true)
                    ->render();
    }
}
