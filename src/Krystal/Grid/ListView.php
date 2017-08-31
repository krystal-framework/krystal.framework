<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Grid;

use Krystal\Form\NodeElement;
use Krystal\I18n\TranslatorInterface;

class ListView
{
    /**
     * Data to be rendered
     * 
     * @var array
     */
    private $data = array();

    /**
     * Rendering options
     * 
     * @var array
     */
    private $options = array(
        self::LISTVIEW_PARAM_TABLE_CLASS => 'table table-condensed table-bordered table-striped table-hover'
    );

    /**
     * Any compliant translator instance
     * 
     * @var \Krystal\I18n\TranslatorInterface
     */
    private $translator;

    const LISTVIEW_PARAM_COLUMNS = 'columns';
    const LISTVIEW_PARAM_COLUMN = 'column';
    const LISTVIEW_PARAM_TRANSLATE = 'translate';
    const LISTVIEW_PARAM_VALUE = 'value';
    const LISTVIEW_PARAM_TITLE = 'title';
    const LISTVIEW_PARAM_TABLE_CLASS = 'tableClass';

    /**
     * State initialization
     * 
     * @param array $data
     * @param array $options
     * @param \Krystal\I18n\TranslatorInterface $translator
     * @return array
     */
    public function __construct(array $data, array $options, TranslatorInterface $translator = null)
    {
        $this->data = $data;
        $this->options = array_merge($this->options, $options);
        $this->translator = $translator;
    }

    /**
     * Renders the list grid
     * 
     * @return string
     */
    public function render()
    {
        $data = $this->createData();
        $table = $this->createTable($data, $this->options[self::LISTVIEW_PARAM_TABLE_CLASS]);
        return $table->render();
    }

    /**
     * Prepares data by reading from configuration
     * 
     * @return array
     */
    private function createData()
    {
        $output = array();
        $hasTranslator = $this->translator instanceof TranslatorInterface;

        foreach ($this->options[self::LISTVIEW_PARAM_COLUMNS] as $configuration) {
            // Do processing only if column available
            if (isset($this->data[$configuration[self::LISTVIEW_PARAM_COLUMN]])) {
                if (isset($configuration[self::LISTVIEW_PARAM_VALUE]) && is_callable($configuration[self::LISTVIEW_PARAM_VALUE])) {
                    // Grab value from returned value
                    $value = $configuration[self::LISTVIEW_PARAM_VALUE]($configuration[self::LISTVIEW_PARAM_COLUMN], $this->data[$configuration[self::LISTVIEW_PARAM_COLUMN]]);
                } else {
                    $value = $this->data[$configuration[self::LISTVIEW_PARAM_COLUMN]];
                }

                // Need to translate?
                if (isset($configuration[self::LISTVIEW_PARAM_TRANSLATE]) && $configuration[self::LISTVIEW_PARAM_TRANSLATE] == true) {
                    $value = $this->translator->translate($value);
                }

                // Title
                if (isset($configuration[self::LISTVIEW_PARAM_TITLE])) {
                    $key = $configuration[self::LISTVIEW_PARAM_TITLE];
                } else {
                    // Fallback to column name
                    $key = $configuration[self::LISTVIEW_PARAM_COLUMN];
                }

                // Prepare key
                $key = $hasTranslator ? $this->translator->translate($key) : $key;

                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * Creates a table
     * 
     * @param array $data
     * @param string $class Table class
     * @return \Krystal\Form\NodeElement
     */
    private function createTable(array $data, $class)
    {
        $table = new NodeElement();
        $table->openTag('table')
              ->setClass($class);

        $tbody = new NodeElement();
        $tbody->openTag('tbody');

        foreach ($data as $key => $value) {
            $row = $this->createRow($key, $value);
            $tbody->appendChild($row);
        }

        $tbody->closeTag();
        $table->appendChild($tbody);

        $table->closeTag();
        return $table;
    }

    /**
     * Creates table row
     * 
     * @param string $key
     * @param string $value
     * @return Krystal\Form\NodeElement
     */
    private function createRow($key, $value)
    {
        $tr = new NodeElement();
        $tr->openTag('tr');

        $first = new NodeElement();
        $first->openTag('td')
              ->finalize()
              ->setText($key)
              ->closeTag();

        $second = new NodeElement();
        $second->openTag('td')
              ->finalize()
              ->setText($value)
              ->closeTag();

        $tr->appendChildren(array($first, $second));
        $tr->closeTag();

        return $tr;
    }
}
