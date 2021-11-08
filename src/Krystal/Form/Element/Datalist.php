<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

final class Datalist
{
    /**
     * List of items
     * 
     * @var array
     */
    private $list;

    /**
     * State initialization
     * 
     * @param array $list
     * @return void
     */
    public function __construct(array $list = [])
    {
        $this->list = $list;
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        // Datalist ID
        $id = sprintf('list-%s', microtime(true));

        $attrs['list'] = $id;

        $input = $this->createInput($attrs);
        $datalist = $this->createDatalist($id, $this->list);

        return $input . $datalist;
    }

    /**
     * Creates input element
     * 
     * @param array $attributes
     * @return string
     */
    private function createInput(array $attributes)
    {
        $input = new NodeElement();
        $input->openTag('input')
              ->addAttributes($attributes)
              ->finalize();

        return $input->render();
    }

    /**
     * Renders datalist element with options
     * 
     * @param string $id
     * @param array $list
     * @return string
     */
    private function createDatalist($id, array $list)
    {
        $datalist = new NodeElement();
        $datalist->openTag('datalist')
                 ->addAttribute('id', $id)
                 ->finalize();

        foreach ($list as $value) {
            $option = new NodeElement();
            $option->openTag('option')
                   ->addAttribute('value', $value)
                   ->finalize()
                   ->closeTag();

            $datalist->appendChild($option);
        }

        return $datalist->closeTag()
                        ->render();
    }
}
