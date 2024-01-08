<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Tabs;

use Krystal\Form\NodeElement;

class Tabs
{
    /**
     * Items to be rendered
     * 
     * @var array
     */
    private $items = [];

    /**
     * State initialization
     * 
     * @param array $items
     * @param boolean $hide Whether to hide empty tabs
     * @return void
     */
    public function __construct($items, $hide = false)
    {
        // Do we need to hide empty tabs? If so, remove empty entries from target array
        if ($hide) {
            foreach ($items as $index => $item) {
                if (isset($item['text'])) {
                    $item['text'] = trim($item['text']);
                }

                if (!isset($item['text']) || empty($item['text'])) {
                    unset($items[$index]);
                }
            }
        }

        $this->items = $items;
    }

    /**
     * Renders navs and tabs
     * 
     * @return string
     */
    public function render()
    {
        return $this->renderNav() . $this->renderTabs();
    }

    /**
     * Renders navigation menu
     * 
     * @return string
     */
    public function renderNav()
    {
        $ul = new NodeElement();
        $ul->openTag('ul')
           ->addAttribute('class', 'nav nav-tabs');

        foreach ($this->items as $index => $item) {
            $aClass = $index == 0 ? 'nav-link active' : 'nav-link';

            $li = new NodeElement();
            $li->openTag('li')
               ->addAttribute('class', 'nav-item')
               ->finalize();

            $a = new NodeElement();
            $a->openTag('a')
              ->addAttributes([
                'class' => $aClass,
                'data-bs-toggle' => 'tab',
                'href' => '#tab-' . md5($item['name'])
              ])
            ->setText($item['name'])
            ->closeTag();

            // Append prepared elements
            $li->appendChild($a)
               ->closeTag();

            $ul->appendChild($li);
        }

        $ul->closeTag();

        return $ul->render();
    }

    /**
     * Render tabs
     * 
     * @return string
     */
    public function renderTabs()
    {
        $wrap = new NodeElement();
        $wrap->openTag('div')
             ->addAttribute('class', 'tab-content')
             ->finalize();

        foreach ($this->items as $index => $item) {
            $divClass = $index == 0 ? 'tab-pane fade active show' : 'tab-pane fade';

            $div = new NodeElement();
            $div->openTag('div')
                ->addAttributes([
                'class' => $divClass,
                'id' => 'tab-' . md5($item['name'])
            ])
            ->setText($item['text'])
            ->closeTag();

            $wrap->appendChild($div);
        }

        return $wrap->closeTag()
                    ->render();
    }
}
