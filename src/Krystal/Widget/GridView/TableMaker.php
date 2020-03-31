<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\GridView;

use Krystal\Form\NodeElement;
use Krystal\Db\Filter\QueryContainerInterface;
use Krystal\Form\Element;
use Krystal\I18n\TranslatorInterface;
use Krystal\Text\TextUtils;
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
        'arrowDownIcon' => 'fas fa-angle-down',
        'arrowUpIcon' => 'fas fa-angle-up',
        'inputClass' => 'form-control',
        'batch' => false // Whether to generate batch selection
    );

    const GRID_PARAM_TD_ATTRIBUTES = 'attributes';
    const GRID_PARAM_BATCH_CALLBACK = 'batchCallback';
    const GRID_PARAM_ROW_ATTRS = 'rowAttributes';
    const GRID_PARAM_ACTIONS = 'actions';
    const GRIG_PARAM_EDITABLE = 'editable';
    const GRID_PARAM_FILTER = 'filter';
    const GRID_PARAM_COLUMNS = 'columns';
    const GRID_PARAM_COLUMN = 'column';
    const GRID_PARAM_NAME = 'name';
    const GRID_PARAM_LABEL = 'label';
    const GRID_PARAM_TYPE = 'type';
    const GRID_PARAM_PK = 'pk';
    const GRID_PARAM_VALUE = 'value';
    const GRID_PARAM_HIDDEN = 'hidden';
    const GRID_PARAM_BATCH = 'batch';
    const GRID_PARAM_SORTING = 'sorting';
    const GRID_PARAM_TRANSLATE = 'translate';
    const GRID_PARAM_TRANSLATEABLE = 'translateable';

    /**
     * Any compliant translator instance
     * 
     * @var \Krystal\I18n\TranslatorInterface
     */
    private $translator;

    /**
     * Query container
     * 
     * @var \Krystal\Db\Filter\QueryContainerInterface
     */
    private $filter;

    /**
     * State initialization
     * 
     * @param array $data
     * @param array $options
     * @param \Krystal\I18n\TranslatorInterface $translator
     * @param \Krystal\Db\Filter\QueryContainerInterface $filter
     * @return void
     */
    public function __construct(array $data, array $options, TranslatorInterface $translator = null, QueryContainerInterface $filter = null)
    {
        $this->data = $data;
        $this->options = array_merge($this->options, $options);
        $this->translator = $translator;
        $this->filter = $filter;
    }

    /**
     * Checks whether translation instance was injected
     * 
     * @return boolean
     */
    private function hasTranslator()
    {
        return $this->translator instanceof TranslatorInterface;
    }

    /**
     * Determines whether current configuration has at least one defined filter
     * 
     * @return boolean
     */
    private function hasAtLeastOneFilter()
    {
        $columns = $this->options[self::GRID_PARAM_COLUMNS];

        foreach ($columns as $column) {
            if (isset($column[self::GRID_PARAM_FILTER]) && $column[self::GRID_PARAM_FILTER] == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renders the grid
     * 
     * @return string
     */
    public function render()
    {
        $columns = $this->options[self::GRID_PARAM_COLUMNS];

        if ($this->hasAtLeastOneFilter()) {
            $head = $this->createTableHeader(array(
                $this->createTopHeadingRow($columns),
                $this->createBottomHeadingRow($columns)
            ));
        } else {
            $head = $this->createTableHeader(array(
                $this->createTopHeadingRow($columns)
            ));
        }

        $body = $this->createTableBody($this->createBodyRows($columns, $this->data));

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
            // By default a column is considered as visible
            if (!isset($configuration[self::GRID_PARAM_HIDDEN])) {
                $configuration[self::GRID_PARAM_HIDDEN] = false;
            }

            // Find linearly (must exists and must not be hidden)
            if ($column == $configuration[self::GRID_PARAM_COLUMN] && $configuration[self::GRID_PARAM_HIDDEN] != true) {
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

        if ($this->options[self::GRID_PARAM_BATCH] === true) {
            $checkbox = Element::checkbox(null, false, array(), false);
            $elements[] = $this->createColumn(null, $checkbox);
        }

        foreach ($rows as $row) {
            // Ignore if configuration for current column isn't provided
            if (!$this->hasColumnConfiguration($row[self::GRID_PARAM_COLUMN])) {
                continue;
            }

            $column = isset($row[self::GRID_PARAM_COLUMN]) ? $row[self::GRID_PARAM_COLUMN] : null;
            // Use label if provided explicitly, otherwise use normalized column name as a fallback
            $label = isset($row[self::GRID_PARAM_LABEL]) ? $row[self::GRID_PARAM_LABEL] : TextUtils::normalizeColumn($row[self::GRID_PARAM_COLUMN]);

            // If sorting isn't defined, then assume that it's true by default
            if (!isset($row[self::GRID_PARAM_SORTING]) && isset($row[self::GRID_PARAM_FILTER]) && $row[self::GRID_PARAM_FILTER] == true) {
                $row[self::GRID_PARAM_SORTING] = true;
            } else {
                $row[self::GRID_PARAM_SORTING] = false;
            }

            // If translation isn't defined, assume true by default
            if (!isset($row[self::GRID_PARAM_TRANSLATE])) {
                $row[self::GRID_PARAM_TRANSLATE] = true;
            }

            // Translate if required
            if ($row[self::GRID_PARAM_TRANSLATE] == true && $this->hasTranslator()) {
                $label = $this->translator->translate($label);
            }

            // Creating a link or raw text here
            if ($row[self::GRID_PARAM_SORTING] === true) {
                // Use explicit key parameter for sorting if provided
                $inputName = isset($row[self::GRID_PARAM_NAME]) ? $row[self::GRID_PARAM_NAME] : $column;

                $elements[] = $this->createHeader($this->createHeaderLink($inputName, ' ' . $label));
            } else {
                $elements[] = $this->createTextHeader($label);
            }
        }

        if ($this->hasActions()) {
            $actionLabel = 'Actions';
            $actionLabel = $this->hasTranslator() ? $this->translator->translate($actionLabel) : $actionLabel;
            $elements[] = $this->createTextHeader($actionLabel);
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

        if ($this->options[self::GRID_PARAM_BATCH] === true) {
            $elements[] = $this->createColumn(null, null);
        }

        foreach ($rows as $row) {
            // Ignore if configuration for current column isn't provided
            if (!$this->hasColumnConfiguration($row[self::GRID_PARAM_COLUMN])) {
                continue;
            }

            // Find out whether a column needs to have a filter
            $filter = $this->findOptionByColumn($row[self::GRID_PARAM_COLUMN], self::GRID_PARAM_FILTER);

            // Use provided name if available, otherwise name it as a column
            $inputName = isset($row[self::GRID_PARAM_NAME]) ? $row[self::GRID_PARAM_NAME] : $row[self::GRID_PARAM_COLUMN];

            // Full-qualified input name
            $name = $this->createInputName(self::GRID_PARAM_FILTER, $inputName);

            if ($filter) {
                // If filter is array, then assume its for select
                $selected = $this->filter->get($inputName);

                if (is_array($filter)) {
                    $filter = array_replace(array('' => ''), $filter);
                    $elements[] = $this->createInput('createHeader', $row[self::GRID_PARAM_COLUMN], $name, $selected, $filter);
                    // If explicit boolean filter provided
                } else if ($filter === 'boolean') {
                    $options = array(
                        '0' => 'No',
                        '1' => 'Yes'
                    );

                    // Translate boolean values on demand
                    if ($this->hasTranslator()) {
                        $options = $this->translator->translateArray($options);
                    }

                    $filter = array_replace(array('' => ''), $options);
                    $elements[] = $this->createInput('createHeader', $row[self::GRID_PARAM_COLUMN], $name, $selected, $filter);
                    
                } else {
                    $elements[] = $this->createInput('createHeader', $row[self::GRID_PARAM_COLUMN], $name, $selected);
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
     * @param array $columns
     * @param array $data Column data
     * @return array
     */
    private function createBodyRows($columns, array $data)
    {
        $rows = array();

        foreach ($data as $row) {
            $rows[] = $this->createBodyRow($columns, $row);
        }

        return $rows;
    }

    /**
     * Create one single body row element
     * 
     * @param array $columns
     * @param array $data
     * @return string
     */
    private function createBodyRow($columns, $data)
    {
        $id = null;

        // Columns to be used when creating a row
        $children = array();

        // Handle batch callback and state
        if ($this->options[self::GRID_PARAM_BATCH] === true) {
            // Apply callback if provided
            if (isset($this->options[self::GRID_PARAM_BATCH_CALLBACK]) && is_callable($this->options[self::GRID_PARAM_BATCH_CALLBACK])) {
                // Result of batch callback function call
                $batchRequired = (bool) call_user_func($this->options[self::GRID_PARAM_BATCH_CALLBACK], $data);
            } else {
                // By default, batch is required for all rows
                $batchRequired = true;
            }

            if ($batchRequired) {
                $batchOutput = Element::checkbox($this->createInputName(self::GRID_PARAM_BATCH, $data[$this->getPkColumn()]), false, array(), false);
            } else {
                $batchOutput = null;
            }

            $children[] = $this->createColumn(null, $batchOutput);
        }
        // Done handling batch

        foreach ($columns as $configuration) {
			$column = $configuration[self::GRID_PARAM_COLUMN];
			$value = isset($data[$column]) ? $data[$column] : null;

            // Ignore if configuration for current column isn't provided
            if (!$this->hasColumnConfiguration($column)) {
                continue;
            }

            // Grab the ID
            if ($this->getPkColumn() !== false && $this->isPkColumnName($column)) {
                $id = $value;
            }

            // Grab column attributes if present
            $tdAttributes = isset($configuration[self::GRID_PARAM_TD_ATTRIBUTES]) ? $configuration[self::GRID_PARAM_TD_ATTRIBUTES] : array();
            $tdAttributes = $this->parseAttributes($tdAttributes, $data);

            // Find out whether current row is editable or not
            $editable = $this->findOptionByColumn($column, self::GRIG_PARAM_EDITABLE);
            // Get custom value (which is provided by a callback) if possible
            $callback = $this->findOptionByColumn($column, self::GRID_PARAM_VALUE);

            // If a callback function is provided for custom value, then use its returned value providing current row as argument
            if ($callback instanceof Closure) {
                $value = $callback($data);
            }

            // If translation isn't defined, assume true by default
            if (!isset($configuration[self::GRID_PARAM_TRANSLATEABLE])) {
                $configuration[self::GRID_PARAM_TRANSLATEABLE] = false;
            }

            // Translate if required
            if ($configuration[self::GRID_PARAM_TRANSLATEABLE] == true && $this->hasTranslator()) {
                $value = $this->translator->translate($value);
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
                $column = $this->createColumn(null, $value, $tdAttributes);
            }

            // Push prepared element
            $children[] = $column;
        }

        // If action columns provided, then create action links
        if ($this->hasActions()) {
            $links = array();

            foreach ($this->options[self::GRID_PARAM_ACTIONS] as $name => $callback) {
                // Append only if callable type provided as a value
                if (is_callable($callback)) {
                    $links[] = $callback($data);
                }
            }

            $children[] = $this->createColumn(null, join(PHP_EOL, $links));
        }

        // Now append row attributes if defined
        $trAttributes = array();

        if (isset($this->options[self::GRID_PARAM_ROW_ATTRS])) {
            $trAttributes = $this->parseAttributes($this->options[self::GRID_PARAM_ROW_ATTRS], $data);
        }

        return $this->createTableRow($children, $trAttributes);
    }

    /**
     * Parse attributes
     * 
     * @param array $attributes
     * @param mixed $data
     * @return array Normalized attributes
     */
    private function parseAttributes(array $attributes, $data)
    {
        $output = array();

        foreach ($attributes as $name => $value) {
            // If closure is provided, then execute it and get returned value
            if (is_callable($value)) {
                $value = $value($data);
            }

            // Don't append NULL-like attributes
            if ($value != null) {
                $output[$name] = $value;
            }
        }

        return $output;
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
                    if (is_array($row) && $row[self::GRID_PARAM_COLUMN] == $column) {
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
        if (isset($this->options[self::GRID_PARAM_PK])) {
            return $this->options[self::GRID_PARAM_PK];
        } else {
            return false;
        }
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

        // Input type. If not provided explicitly, use text
        $type = isset($options[self::GRID_PARAM_TYPE]) ? $options[self::GRID_PARAM_TYPE] : 'text';

        $text = Element::dynamic($type, $name, $value, array('class' => $this->options['inputClass']), $extra);

        return call_user_func(array($this, $method), null, $text);
    }

    /**
     * Shared element builder abstraction
     * 
     * @param string $type
     * @param array $children
     * @param array $attributes Optional element attributes
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createElement($type, $children = array(), array $attributes = array(), $text = null)
    {
        $element = new NodeElement();
        $element->openTag($type);

        if (!empty($attributes)) {
            $element->addAttributes($attributes);
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
        return $this->createElement('i', array(), array('class' => $class), false);
    }

    /**
     * Creates table element
     * 
     * @param array $children
     * @return \Krystal\Form\NodeElement
     */
    private function createTable(array $children)
    {
        return $this->createElement('table', $children, array('class' => $this->options['tableClass']));
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
     * @param array $attributes Optional attributes
     * @return \Krystal\Form\NodeElement
     */
    private function createTableRow(array $children, $attributes = array())
    {
        return $this->createElement('tr', $children, $attributes);
    }

    /**
     * Creates heading element that contains text and only
     * 
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createTextHeader($text)
    {
        return $this->createElement('th', array(), array('class' => $this->options['tableHeaderClass']), $text);
    }

    /**
     * Creates heading element that contains text and only
     * 
     * @param string $text
     * @return \Krystal\Form\NodeElement
     */
    private function createBodyHeader($text)
    {
        return $this->createElement('td', array(), array('class' => $this->options['tableDataClass']), $text);
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
        return $this->createElement('th', $children, array('class' => $this->options['tableHeaderClass']), $text);
    }

    /**
     * Creates table data tag
     * 
     * @param array|null $children
     * @param string $text
     * @param array $attributes Optional attributes to be merged
     * @return \Krystal\Form\NodeElement
     */
    private function createColumn($children = array(), $text = null, array $attributes = array())
    {
        $attributes = array_merge(
            array('class' => $this->options['tableDataClass']),
            $attributes
        );

        return $this->createElement('td', $children, $attributes, $text);
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

        if ($this->filter->isSortedBy($column)) {
            $a->setClass('text-info');
        }

        $a->addAttribute('href', $this->filter->getColumnSortingUrl($column))
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
        $class = $this->filter->isSortedByDesc($column) ? $this->options['arrowDownIcon'] : $this->options['arrowUpIcon'];
        return $this->createIcon($class);
    }
}
