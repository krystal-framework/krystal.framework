Grid widget
=========

The data grid is one of the most commonly used components in modern web applications. Krystal provides a powerful yet lightweight and highly flexible widget that simplifies the creation of feature-rich HTML tables.

You only need to supply your data (usually fetched from a database) and configure how each column should be rendered — for example, whether it supports inline editing, filtering, sorting, or custom formatting.

## Features

The GridViewWidget provides a rich set of built-in features to enhance your data tables:

-   **Inline editing** - Allows users to edit cell values directly in the table without leaving the page.
-   **Column filtering** - Adds filter controls to column headers. Supports text inputs as well as <select> dropdowns for predefined options.
-   **Sorting** - clickable column headers enable client-side or server-side sorting of rows (ascending/descending).
-   **Batch Operations** - enables checkboxes for selecting multiple rows, with support for bulk actions such as batch deletion/removal.
-   **Custom action buttons** - Add per-row action buttons (e.g., Edit, View, Delete) or global toolbar buttons with custom callbacks or URLs.
-   **Custom data rendering** Flexible output formatting per column — use callbacks, HTML markup, conditional styling, or transform raw data (e.g., dates, prices, status badges).

These features can be enabled individually or combined, giving you full control over the grid's behavior while keeping the implementation lightweight and intuitive.


## Getting started

The core requirement for using GridViewWidget is to provide two things:

1.  **A data source array** — containing the actual rows to display.
2.  **A configuration array** — defining how the table should be rendered (columns, features, styling, etc.).

**Data Source Format**

The data source **must** be a multidimensional array where:

-   The outer array contains all rows.
-   Each row is an **associative array** with keys representing column identifiers and values holding the cell data.

**Example:**

    $rows = [
        [
            'name'  => 'Dave',
            'age'   => 27,
            'email' => 'dave@example.com'
        ],
        [
            'name'  => 'Julia',
            'age'   => 30,
            'email' => 'julia@example.com'
        ],
        [
            'name'  => 'Josh',
            'age'   => 20,
            'email' => 'josh@example.com'
        ]
    ];

This format aligns perfectly with common data retrieval methods, such as:

-   PDO
-   ORM results
-   API responses

Rows should generally share the same keys for consistent rendering, though extra fields not used in columns are ignored and can be useful for custom logic.

To display the data grid in your view template, use the following minimal code:

    <?php
    
    use Krystal\Widget\GridView\GridViewWidget;
    
    ?>

    <div class="table-responsive">
        <?= $this->widget(new GridViewWidget($rows, [
            'tableClass' => 'table table-hover table-bordered table-striped table-condensed',
            'columns' => [
                [
                    'column' => 'name'
                ],
                [
                    'column' => 'age'
                ],
                [
                    'column' => 'email'
                ]
            ]
        ])); ?>
    </div>

This is the **minimal configuration** required to render a functional, styled table. The widget will automatically:

-   Generate `<thead>` with column headers (capitalized key names by default).
-   Render all rows in `<tbody>`.
-   Apply Bootstrap 5 responsive and styling classes.

You can further customize headers, add sorting, filters, actions, or custom rendering — see the Column Configuration section for details.

## Table class

The key `tableClass` defines a class that generated table will have. If you omit this, then the default CSS class `table table-hover table-bordered table-striped`will be used.

## Columns 

The key `columns` expects a collection of arrays. Each array represents a column to be rendered in a table.

## Advanced usage

The aforementioned example shows a very basic example of usage. In real-world scenarios, you would expect table generator to handle more cases.

### Overriding default label

By default, when rendering a column, its name is normalized and then used to display in heading row. For example the following column configuration:

    [
       'column' => 'name'
    ]

will have a heading row labeled as *Name*. If you want to override default heading label, you can do it by providing `label` key with its value:

    [
       'column' => 'name',
       'label' => 'Customer name'
    ]

It will render a column name with labeled heading row *Customer name*

### Overriding default value

Sometimes your source array might contain some status codes or constants, like `-1`, `0` or `1`. In most cases, you would usually want to convert them to some readable values for your users. For example, instead of showing `-1` or `0` in table columns, you would want to show `Canceled` and `Success` respectively.

Here comes the option `value` to handle this. As a value it expects a callback function that returns some new value.

Consider this:

    [
       'column' => 'status',
       'value' => function($row){
           if ($row['status'] == -1) {
               return 'Canceled';
           }
           
           if ($row['status'] == 0){
              return 'Success';
           }
       }
    ]

Here in `value` callback function, we use conditional logic to render text. Pay attention to `$row` parameter, which is internally represents current row when iterating over all rows.

So now, when table is rendered, all values in `status` column will be replaced to `Canceled` and `Success`.


### Hiding columns

Sometimes, you would want to show or hide some columns conditionally. By default, all columns are visible. To change this, you can set optional parameter `hidden` to `true`, like this:

    [
       'column' => 'name',
       'hidden' => true,
       'label' => 'Customer name'
    ]

### Enabling batch operations

Whenever you need batch removal or status change, you can set `batch` option to true. However, this won't work by itself. You also need to provide column name which should be considered as a primary key.

In order to accept selected checkboxes, you should also wrap entire table in `form` tag.

    <?php
    
    // Import Grid component
    use Krystal\Widget\GridView\GridViewWidget;
    
    ?>
    
    <form action="....">
	    <div class="table-responsive">
	        <?= $this->widget(new GridViewWidget($rows, [
	            'batch' => true,
	            'pk'    => 'id', // Must be in source array
	            'columns' => [
	                // ...
	            ]
	        ])); ?>
	    </div>     
     <button type="submit">Apply batch</submit>
     
    </form>

On form submission, you can accept selected items in `$_POST['batch']` or `$_GET['batch']` (depending on form method).

