<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget;

use Krystal\Form\NodeElement;

abstract class AbstractListWidget
{
    /**
     * Attribute options
     * 
     * @var array
     */
    protected $options = array();

    /**
     * Returns option value if present
     * 
     * @param string $key
     * @param mixed $default Default value if provided doesn't exist
     * @return mixed
     */
    protected function getOption($key, $default = null)
    {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    /**
     * Creates list item
     * 
     * @param string $class List item class
     * @param string $text Item inner text
     * @return string
     */
    protected function createListItem($class, $text)
    {
        $li = new NodeElement();
        $li->openTag('li')
           ->addAttribute('class', $class)
           ->finalize(false);

        if ($text !== null) {
            $li->setText($text);
        }

        return $li->closeTag();
    }

    /**
     * Creates a list
     * 
     * @param string $class UL class
     * @param array $children
     * @return string
     */
    protected function renderList($class, array $children)
    {
        $ul = new NodeElement();
        $ul->openTag('ul')
           ->addAttribute('class', $class)
           ->appendChildren($children)
           ->closeTag();

        return $ul->render();
    }

    /**
     * Create link
     * 
     * @param string $text Link text
     * @param string $href URL
     * @param string $class Link class
     * @return string
     */
    protected function renderLink($text, $href, $class)
    {
        $a = new NodeElement();
        $a->openTag('a')
          ->addAttributes(array(
            'class' => $class,
            'href' => $href
          ))
          ->finalize(false)
          ->setText($text)
          ->closeTag();

        return $a->render();
    }
}
