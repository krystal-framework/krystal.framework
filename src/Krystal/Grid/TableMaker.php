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
use Krystal\Db\Filter\QueryContainerInterface;
use Krystal\Form\Element;
use Closure;

final class TableMaker
{
    /**
     * Table data
     * 
     * @var array
     */
    private $data = array();

    /**
     * Default table options
     * 
     * @var array
     */
    private $options = array(
        'tableClass' => 'table table-hover table-bordered table-striped',
        'tableHeaderClass' => 'text-center',
        'tableDataClass' => 'text-center',
        'rowClass' => 'text-center',
        'activeClass' => 'text-info',
        'arrowDownIcon' => 'glyphicon glyphicon-arrow-down',
        'arrowUpIcon' => 'glyphicon glyphicon-arrow-up',
        'inputClass' => 'form-control',
        'batch' => true // Whether to generate batch selection
    );

    const GRID_PARAM_ACTIONS = 'actions';
    const GRIG_PARAM_EDITABLE = 'editable';
    const GRID_PARAM_FILTER = 'filter';
    const GRID_PARAM_COLUMNS = 'columns';
    const GRID_PARAM_COLUMN = 'column';
    const GRID_PARAM_LABEL = 'label';
    const GRID_PARAM_TYPE = 'type';
    const GRID_PARAM_PK = 'pk';
    const GRID_PARAM_VALUE = 'value';

    /**
     * State initialization
     * 
     * @param array $data
     * @param array $options
     * @param \Krystal\Db\Filter\QueryContainerInterface $filter
     * @return void
     */
    public function __construct(array $data, array $options, QueryContainerInterface $filter = null)
    {
        $this->data = $data;
        $this->options = array_merge($this->options, $options);
        $this->filter = $filter;
    }

    /**
     * Renders the grid
     * 
     * @return string
     */
    public function render()
    {
        $columns = $this->options[self::GRID_PARAM_COLUMNS];

        $head = $this->createTableHeader(array(
            $this->createTopHeadingRow($columns), 
            $this->createBottomHeadingRow($columns)
        ));

        $body = $this->createTableBody($this->createBodyRows($this->data));

        return $this->createTable(array($head, $body))
                    ->render();
    }

    /**
     * Checks whether a column has corresponding configuration
     * 
     * @param string $column Target column to be checked
     * @return boolean
     */
    private function hasColumnConfiguration($column)
    {
        $columns = $this->options[self::GRID_PARAM_COLUMNS];

        foreach ($columns as $configuration) {
            // Find linearly
            if ($column == $configuration[self::GRID_PARAM_COLUMN]) {
                return true;
            }
        }

        // Not found by default
        return false;
    }

    /**
     * Creates table header
     * 
     * @param array $rows
     * @return \Krystal\Form\NodeElement
     */
    private function createTopHeadingRow(array $rows)
    {
        $elements = array();

        if ($this->options['batch'] === true) {
            $checkbox = Element::checkbox(null, false, array(), false);
            $elements[] = $this->createColumn(null, $checkbox);
        }

        foreach ($rows as $row) {
            // Ignore if configuration for current column isn't provided
            if (!$this->hasColumnConfiguration($row[self::GRID_PARAM_COLUMN])) {
                continue;
            }

            $column = isset($row[self::GRID_PARAM_COLUMN]) ? $row[self::GRID_PARAM_COLUMN] : null;
            $label = isset($row[self::GRID_PARAM_LABEL]) ? $row[self::GRID_PARAM_LABEL] : null;

            $elements[] = $this->createHeader($this->createHeaderLink($column, ' ' . $label));
        }

        if ($this->hasActions()) {
            $elements[] = $this->createTextHeader('Actions');
        }

        return $this->createTableRow($elements);
    }

    /**
     * Creates filtering row right after column captions
     * 
     * @param array $rows
     * @return string
     */
    private function createBottomHeadingRow(array $rows)
    {
        $elements = array();

        if ($this->options['batch'] === true) {
            $elements[] = $this->createColumn(null, null);
        }

        foreach ($rows as $row) {
            // Ignore if configuration for current column isn't provided
            if (!$this->hasColumnConfiguration($row[self::GRID_PARAM_COLUMN])) {
                continue;
            }

            // Find out whether a column needs to have a filter
            $filter = $this->findOptionByColumn($row[self::GRID_PARAM_COLUMN], 'filter');
            $name = $this->createInputName('filter', $row[self::GRID_PARAM_COLUMN]);

            if ($filter) {
                // If filter is array, then assume its for select
                if (is_array($filter)) {
                    $filter = array_merge(array('0' => ''), $filter);
                    $elements[] = $this->createInput('createHeader', $row[self::GRID_PARAM_COLUMN], $name, null, $filter);
                } else {
                    $elements[] = $this->createInput('createHeader', $row[self::GRID_PARAM_COLUMN], $name, false);
                }

            } else {
                $elements[] = $this->createColumn(null, '');
            }
        }

        if ($this->hasActions()) {
            $elements[] = $this->createTextHeader('');
        }

        return $this->createTableRow($elements);
    }

    /**
     * Create many body rows elements
     * 
     * @param array $data Column data
     * @return array
     */
    private function createBodyRows(array $data)
    {
        $rows = array();

        foreach ($data as $row) {
            $rows[] = $this->createBodyRow($row);
        }

        return $rows;
    }

    /**
     * Create one single body row element
     * 
     * @param array $data
     * @return string
     */
    private function createBodyRow(array $data)
    {
        $id = null;

        // Columns to be used when creating a row
        $columns = array();

        if ($this->options['batch'] === true) {
            $checkbox = Element::checkbox(sprintf('batch[%s]', $data[$this->getPkColumn()]), false, array(), false);
            $columns[] = $this->createColumn(null, $checkbox);
        }

        foreach ($data as $column => $value) {
            // Ignore if configuration for current column isn't provided
            if (!$this->hasColumnConfiguration($column)) {
                continue;
            }

            // Grab the ID
            if ($this->isPkColumnName($column)) {
                $id = $value;
            }

            // Find out whether current row is editable or not
            $editable = $this->findOptionByColumn($column, self::GRIG_PARAM_EDITABLE);
            // Get custom value (which is provided by a callback) if possible
            $callback = $this->findOptionByColumn($column, self::GRID_PARAM_VALUE);

            // If a callback function is provided for custom value, then use its returned value providing current row as argument
            if ($callback instanceof Closure) {
                $value = $callback($data);
            }

            if ($editable == true) {
                $name = $this->createInputName($column, $id);
                $filter = $this->findOptionByColumn($column, self::GRID_PARAM_FILTER);

                if (is_array($filter)) {
                    $column = $this->createInput('createColumn', $column, $name, $value, $filter);
                } else {
                    $column = $this->createInput('createColumn', $column, $name, $value);
                }

            } else {
                $column = $this->createColumn(null, $value);
            }

            // Push prepared element
            $columns[] = $column;
        }

        // If action columns provided, then create action links
        if ($this->hasActions()) {
            $links = array();

            foreach ($this->options[self::GRID_PARAM_ACTIONS] as $name => $callback) {
                $links[] = $callback($data);
            }

            $columns[] = $this->createColumn(null, join(PHP_EOL, $links));
        }

        return $this->createTableRow($columns);
    }

    /**
     * Find options by particular column
     * 
     * @param string $column Column name
     * @param string $option Option key
     * @return mixed Null on failure
     */
    private function findOptionByColumn($column, $key)
    {
        $options = $this->findOptionsByColumn($column);
        return isset($options[$key]) ? $options[$key] : null;
    }

    /**
     * Find column options by its associated column name
     * 
     * @param string $name Column name
     * @return mixed
     */
    private function findOptionsByColumn($column)
    {
        // Run a basic linear search
        foreach ($this->options as $collection) {
            // Make sure $collection is array
            if (is_array($collection)) {
                foreach ($collection as $row) {
                    if ($row[self::GRID_PARAM_COLUMN] == $column) {
                        return $row;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Checks whether its PK column name
     * 
     * @param string $column Column name
     * @return boolean
     */
    private function isPkColumnName($column)
    {
        return $this->getPkColumn() === $column;
    }

    /**
     * Returns PK column name
     * 
     * @return string
     */
    private function getPkColumn()
    {
        return $this->options[self::GRID_PARAM_PK];
    }

    /**
     * Find out whether action settings have been defined in configuration
     * 
     * @return boolean
     */
    private function hasActions()
    {
        return isset($this->options[self::GRID_PARAM_ACTIONS]);
    }

    /**
     * Create input name
     * 
     * @param string $group
     * @param string $name
     * @return string
     */
    private function createInputName($group, $name)
    {
        return sprintf('%s[%s]', $group, $name);
    }

    /**
     * Creates input element
     * 
     * @param string $method Dynamic method to be called on this class
     * @param string $column Column name
     * @param string $name Column name to be used when generating input name
     * @param mixed $value Column value to be used when generating input name
     * @param array $extra Extra options (i.e for select type - the options)
     * @return string
     */
    private function createInput($method, $column, $name, $value, array $extra = array())
    {
        $options = $this->findOptionsByColumn($column);
        $text = Element::dynamic($options[self::GRID_PARAM_TYPE], $name, $value, array('class' => $this->options['inputClass']), $extra);

        return call_user_func(array($this, $method), null, $text);
    }

    /**
     * Shared element builder abstraction
     * 
     * @param string $type
     * @param array $children
     * @param string $class Optional element class name
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createElement($type, $children = array(), $class = null, $text = null)
    {
        $element = new NodeElement();
        $element->openTag($type);

        if ($class !== null) {
            $element->setClass($class);
        }

        if (!empty($children)) {
            if ($children instanceof NodeElement) {
                $children = array($children);
            }

            $element->appendChildren($children);
        }

        if ($text !== null) {
            $element->setText($text);
        }

        $element->closeTag();

        return $element;
    }

    /**
     * Creates icon element with class
     * 
     * @param string $class
     * @return \Krystal\Form\NodeElement
     */
    private function createIcon($class)
    {
        return $this->createElement('i', array(), $class, false);
    }

    /**
     * Creates table element
     * 
     * @param array $children
     * @return \Krystal\Form\NodeElement
     */
    private function createTable(array $children)
    {
        return $this->createElement('table', $children, $this->options['tableClass']);
    }

    /**
     * Creates table header
     * 
     * @param array $children
     * @return \Krystal\Form\NodeElement
     */
    private function createTableBody(array $children)
    {
        return $this->createElement('tbody', $children);
    }

    /**
     * Creates table header
     * 
     * @param array $children
     * @return \Krystal\Form\NodeElement
     */
    private function createTableHeader(array $children)
    {
        return $this->createElement('thead', $children);
    }

    /**
     * Creates TR element with child elements
     * 
     * @param array $children
     * @param string $class
     * @return \Krystal\Form\NodeElement
     */
    private function createTableRow(array $children, $class = null)
    {
        return $this->createElement('tr', $children, $class);
    }

    /**
     * Creates heading element that contains text and only
     * 
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createTextHeader($text)
    {
        return $this->createElement('th', array(), $this->options['tableHeaderClass'], $text);
    }

    /**
     * Creates heading element that contains text and only
     * 
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createBodyHeader($text)
    {
        return $this->createElement('td', array(), $this->options['tableDataClass'], $text);
    }

    /**
     * Creates table heading tag
     * 
     * @param array|null $children
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createHeader($children = array(), $text = null)
    {
        return $this->createElement('th', $children, $this->options['tableHeaderClass'], $text);
    }

    /**
     * Creates table data tag
     * 
     * @param array|null $children
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createColumn($children = array(), $text = null)
    {
        return $this->createElement('td', $children, $this->options['tableDataClass'], $text);
    }

    /**
     * Creates a link for table header
     * 
     * @param string $column
     * @param string $text Inner text
     * @return \Krystal\Form\NodeElement
     */
    private function createHeaderLink($column, $text)
    {
        $a = new NodeElement();
        $a->openTag('a');

        //$this->filter->isSortedBy
        if (($column)) {
            $a->setClass('text-info');
        }

        $a->addAttribute('href', ($column))
          ->appendChildWithText($this->createHeaderLinkIcon($column), $text)
          ->closeTag();

        return $a;
    }

    /**
     * Creates header link icon
     * 
     * @param string $column
     * @return \Krystal\Form\NodeElement
     */
    private function createHeaderLinkIcon($column)
    {
        $class = ($column) ? $this->options['arrowDownIcon'] : $this->options['arrowUpIcon'];
        //$class = $this->filter->isSortedByDesc($column) ? 'glyphicon glyphicon-arrow-down': 'glyphicon glyphicon-arrow-up';

        return $this->createIcon($class);
    }
}
