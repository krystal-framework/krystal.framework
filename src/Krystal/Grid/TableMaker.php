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

final class TableMaker
{
    /**
     * Table data
     * 
     * @var array
     */
    private $data = array();

    /**
     * All possible table options
     * 
     * @var array
     */
    private $options = array();

    const GRID_PARAM_ACTIONS = 'actions';
    const GRIG_PARAM_EDITABLE = 'editable';
    const GRID_PARAM_FILTER = 'filter';
    const GRID_PARAM_COLUMNS = 'columns';
    const GRID_PARAM_COLUMN = 'column';
    const GRID_PARAM_LABEL = 'label';
    const GRID_PARAM_TYPE = 'type';
    const GRID_PARAM_PK = 'pk';

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
        $this->options = $options;
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
     * Creates table header
     * 
     * @param array $rows
     * @return \Krystal\Form\NodeElement
     */
    private function createTopHeadingRow(array $rows)
    {
        $elements = array();

        foreach ($rows as $row) {
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

        foreach ($rows as $row) {
            // Find out whether a column needs to have a filter
            $filter = $this->findOptionByColumn($row[self::GRID_PARAM_COLUMN], 'filter');
            $name = $this->createInputName('filter', $row[self::GRID_PARAM_COLUMN]);

            if ($filter) {
                $elements[] = $this->createInput('createHeader', $row[self::GRID_PARAM_COLUMN], $name, false);
            } else {
                $elements[] = $this->createRow(null, '');
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
     * @param array $row
     * @return string
     */
    private function createBodyRow(array $row)
    {
        $id = null;
        $elements = array();

        foreach ($row as $key => $value) {
            // Grab the ID
            if ($this->isPkColumnName($key)) {
                $id = $value;
            }

            if ($this->findOptionByColumn($key, self::GRIG_PARAM_EDITABLE) == true) {
                $name = $this->createInputName($key, $id);
                $element = $this->createInput('createRow', $key, $name, $value);
            } else {
                $element = $this->createRow(null, $value);
            }

            // Push prepared element
            $elements[] = $element;
        }

        if ($this->hasActions()) {
            $elements[] = $this->createBodyHeader('');
        }

        return $this->createTableRow($elements);
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
     * Find out whether action settings have been defined in cofiguration
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
     * @param array $row
     * @param boolean $value Whether to include a value attribute
     * @return string
     */
    private function createInput($builder, $column, $name, $value)
    {
        $options = $this->findOptionsByColumn($column);

        switch ($options[self::GRID_PARAM_TYPE]) {
            case 'text':
                $element = new Element\Text();
                $text = $element->render(array('name' => $name, 'value' => $value, 'class' => 'form-control'));
                
            return call_user_func(array($this, $builder), null, $text);
        }
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
            if ($children instanceof NodeElement){
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
        return $this->createElement('table', $children, 'table');
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
        return $this->createElement('th', array(), 'text-center', $text);
    }

    /**
     * Creates heading element that contains text and only
     * 
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createBodyHeader($text)
    {
        return $this->createElement('td', array(), 'text-center', $text);
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
        return $this->createElement('th', $children, 'text-center', $text);
    }

    /**
     * Creates table data tag
     * 
     * @param array|null $children
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createRow($children = array(), $text = null)
    {
        return $this->createElement('td', $children, 'text-center', $text);
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
        $class = ($column) ? 'glyphicon glyphicon-arrow-down': 'glyphicon glyphicon-arrow-up';
        //$class = $this->filter->isSortedByDesc($column) ? 'glyphicon glyphicon-arrow-down': 'glyphicon glyphicon-arrow-up';

        return $this->createIcon($class);
    }
}
