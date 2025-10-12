<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Tabs;

use Krystal\Form\NodeElement;
use InvalidArgumentException;

final class Tabs
{
    /**
     * Items to be rendered
     * 
     * @var array
     */
    private $items = [];

    /**
     * Current active index
     * 
     * @var int
     */
    private $index;

    /**
     * State initialization
     * 
     * @param array $items
     * @param boolean $hide Whether to hide empty tabs
     * @param int $active Active index
     * @return void
     */
    public function __construct(array $items, $hide = false, $active = 0)
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

            // Reset index after removal
            $items = array_values($items);
        }

        // Is index withing a range?
        if (!empty($items) && !isset($items[$active])) {
            throw new InvalidArgumentException(sprintf('Provided active index "%s" is out of range', $active));
        }

        $this->items = $items;
        $this->index = $index;
    }

    /**
     * Renders navs and tabs
     * 
     * @param string $fade Whether to append fade class to have fading effect
     * @return string
     */
    public function render($fade = true)
    {
        return $this->renderNav() . $this->renderTabs($fade);
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
            $aClass = $index == $this->index ? 'nav-link active' : 'nav-link';

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
     * @param string $fade Whether to append fade class to have fading effect
     * @return string
     */
    public function renderTabs($fade = true)
    {
        $wrap = new NodeElement();
        $wrap->openTag('div')
             ->addAttribute('class', 'tab-content')
             ->finalize();

        foreach ($this->items as $index => $item) {
            // Constuct div's class depending on fade class
            $divClass = ($index == $this->index ? 'tab-pane active show' : 'tab-pane') . ($fade ? ' fade' : '');

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
