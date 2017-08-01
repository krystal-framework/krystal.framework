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
use UnexpectedValueException;
use Closure;

final class Select implements FormElementInterface
{
    /**
     * List data
     * 
     * @var array
     */
    private $data = array();

    /**
     * First "Option" elements to be prepended
     * 
     * @var array
     */
    private $defaults = array();

    /**
     * Active element to be selected
     * 
     * @var string
     */
    private $active;

    /**
     * Optional visitor to add attributes
     * 
     * @var \Closure
     */
    private $optionVisitor;

    /**
     * State initialization
     * 
     * @param array $data
     * @param string|array $active An active value or an array of active values
     * @param array $defaults Optional first elements to be prepended
     * @param \Closure $optionVisitor Optional visitor to build attributes of option tag
     * @return void
     */
    public function __construct(array $data, $active, array $defaults = array(), Closure $optionVisitor = null)
    {
        $this->data = $data;
        $this->active = $active;
        $this->defaults = $defaults;
        $this->optionVisitor = $optionVisitor;
    }

    /**
     * Creates default option node
     * 
     * @return \Krystal\Form\NodeElement
     */
    private function createDefaultOptionNodes()
    {
        $nodes = array();

        foreach ($this->defaults as $key => $value) {
            // Only scalars are allowed, the rest is ignored
            if (is_scalar($value)) {
                array_push($nodes, $this->createOptionNode($key, $value));
            }
        }

        return $nodes;
    }

    /**
     * Determines whether node is active
     * 
     * @param string $value
     * @return boolean
     */
    private function isActiveNode($value)
    {
        if (is_array($this->active)) {
            $actives = array_values($this->active);
            return in_array($value, $actives);
        } else {
            // Without type-casting it's error-prone
            return (string) $this->active == (string) $value;
        }
    }

    /**
     * Creates option node
     * 
     * @param string $value
     * @param string $text
     * @throws \UnexpectedValueException If visitor is not returning an associative array
     * @return \Krystal\Form\NodeElement
     */
    private function createOptionNode($value, $text)
    {
        $option = new NodeElement();
        $option->openTag('option')
               ->addAttribute('value', $value);

        // Mark as selected on demand
        if ($this->isActiveNode($value)) {
            $option->addProperty('selected');
        }

        // If callback is provided, then use it to build attributes
        if ($this->optionVisitor instanceof Closure) {
            $result = call_user_func($this->optionVisitor, $value, $text);

            // Visitor must return an array containing these keys
            if (is_array($result)) {
                $option->addAttributes($result);
            } else {
                // Incorrect returned value
                throw new UnexpectedValueException(
                    sprintf('The visitor must return associative array with attribute names and their corresponding values. Received - "%s"', gettype($result))
                );
            }
        }

        $option->finalize()
               ->setText($text)
               ->closeTag();

        return $option;
    }

    /**
     * Creates optgroup node with nested options
     * 
     * @param string $label
     * @param array $list
     * @return \Krystal\Form\NodeElement
     */
    private function createOptgroupNode($label, array $list)
    {
        $optgroup = new NodeElement();
        $optgroup->openTag('optgroup')
                 ->addAttribute('label', $label)
                 ->finalize();

        foreach ($this->getOptions($list) as $option) {
            $optgroup->appendChild($option);
        }

        $optgroup->closeTag();

        return $optgroup;
    }

    /**
     * Returns a collection of prepared option elements
     * 
     * @param array $list
     * @return array
     */
    private function getOptions(array $list)
    {
        // To be returned
        $elements = array();

        foreach ($list as $value => $text) {
            array_push($elements, $this->createOptionNode($value, $text));
        }

        return $elements;
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $select = new NodeElement();
        $select->openTag('select')
               ->addAttributes($attrs)
               ->finalize();

        // First of all lets look if we have defaults
        if (!empty($this->defaults)) {
            foreach ($this->createDefaultOptionNodes() as $node) {
                $select->appendChild($node);
            }
        }

        foreach ($this->data as $label => $list) {
            if (is_array($list)) {
                $select->appendChild($this->createOptgroupNode($label, $list));
            } else {
                $select->appendChild($this->createOptionNode($label, $list));
            }
        }

        $select->closeTag();

        return $select->render();
    }
}
