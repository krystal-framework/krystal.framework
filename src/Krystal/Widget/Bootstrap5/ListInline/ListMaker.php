<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\ListInline;

use Krystal\Form\NodeElement;

final class ListMaker
{
    /**
     * List data
     * 
     * @var array
     */
    private $items = [];

    /**
     * List group options
     * 
     * @var array
     */
    private $classes = [];

    /**
     * State initialization
     * 
     * @param array $items
     * @param array $classes
     * @return void
     */
    public function __construct(array $items = [], array $classes = [])
    {
        $this->items = $items;
        $this->classes = $classes;
    }

    /**
     * Renders a list
     * 
     * @return void
     */
    public function render()
    {
        $ulClass = 'list-inline';
        $liClass = 'list-inline-item';

        if (isset($this->classes['ul'])) {
            $ulClass .= ' ' . $this->classes['ul'];
        }

        if (isset($this->classes['li'])) {
            $liClass .= ' ' . $this->classes['li'];
        }

        $ul = new NodeElement();
        $ul->openTag('ul')
           ->addAttribute('class', $ulClass)
           ->finalize();

        foreach ($this->items as $item) {
            $li = new NodeElement('li');
            $li->openTag('li')
               ->addAttribute('class', $liClass);

            // Set text
            if (isset($item['text']) && !isset($item['link'])) {
                $li->setText($item['text']);
            }

            // Set link
            if (isset($item['link'], $item['text'])) {
                $a = new NodeElement();
                $a->openTag('a');

                if (isset($this->classes['a'])) {
                    $a->addAttribute('class', $this->classes['a']);
                }

                // Whether to open in new window
                if (isset($item['blank']) && $item['blank'] === true) {
                    $a->addAttribute('target', '_blank');
                }

                $a->addAttribute('href', $item['link']);

                // Icon
                if (isset($item['icon'])) {
                    $i = new NodeElement();
                    $i->openTag('i')
                      ->addAttribute('class', $item['icon'])
                      ->finalize()
                      ->closeTag();

                    $item['text'] = $i->render() . ' ' . $item['text'];
                }

                $a->setText($item['text'])
                  ->closeTag();

                $li->appendChild($a);
            }

            $li->closeTag();
            $ul->appendChild($li);
        }

        $ul->closeTag();

        return $ul->render();
    }
}
