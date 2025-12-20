
Grid widget
=========

The data grid is one of the most commonly used components in modern web applications. Krystal provides a powerful yet lightweight and highly flexible widget that simplifies the creation of feature-rich HTML tables.

You only need to supply your data (usually fetched from a database) and configure how each column should be rendered — for example, whether it supports inline editing, filtering, sorting, or custom formatting.

## Features

The GridViewWidget provides a rich set of built-in features to enhance your data tables:

-   **Inline editing** - Allows users to edit cell values directly in the table without leaving the page.
-   **Column filtering** - Adds filter controls to column headers. Supports text inputs as well as `<select>` dropdowns for predefined options.
-   **Sorting** - clickable column headers enable client-side or server-side sorting of rows (ascending/descending).
-   **Batch Operations** - enables checkboxes for selecting multiple rows, with support for bulk actions such as batch deletion/removal.
-   **Custom action buttons** - Add per-row action buttons (e.g., Edit, View, Delete) or global toolbar buttons with custom callbacks or URLs.
-   **Custom data rendering** - Flexible output formatting per column — use callbacks, HTML markup, conditional styling, or transform raw data (e.g., dates, prices, status badges).

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

### Overriding default label

By default, when rendering a table column, the component automatically generates the header label by normalizing the column name (e.g., converting underscores to spaces and capitalizing words).

**For a column defined as:**

    [
       'column' => 'name'
    ]

The header will be rendered as **Name**.
For a column like `first_name`, it would become **First name**.

To specify a custom header label, add the label key to the column configuration:


    [
       'column' => 'name',
       'label' => 'Customer name'
    ]

This will render the column header as **Customer name**.

**Additional notes**

-   The label value completely overrides the automatically generated one.
-   You can use any string, including HTML.

### Overriding default value


Your data source may contain numeric codes, constants, or raw values (e.g., -1, 0, 1) that are not user-friendly. To display more meaningful text in table cells, you can transform these values using the value option.

The value option accepts a **callback function** that receives the current row data and returns the desired display value.

**Basic usage**

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

**How it works**

-   The callback receives `$row` — an array representing the **current row** being rendered.
-   Whatever the callback returns will be displayed in the table cell for that column.
-   If the callback returns null or nothing (no return statement for a case), the cell will be empty.



### Hiding columns

By default, all defined columns are visible in the rendered table. To hide a specific column, set the optional hidden parameter to true in its configuration.

**Basic usage**

    [
       'column' => 'name',
       'hidden' => true,
       'label' => 'Customer name'
    ]

This will exclude the `name` column from being rendered.

### Enabling batch operations

Batch operations (e.g., delete multiple rows, change status in bulk) are supported via checkboxes that appear in the first column of the table. 

**To enable this feature:**

1.  Set the batch option to true.
2.  Specify the primary key column name using the `pk` option (this column must exist in your data rows).
3.  Wrap the entire table in a `<form>` tag so selected items can be submitted.
4.  Add a submit button to process the batch action.

**Basic usage**

    <?php
    
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


**How it works**

-   A checkbox column is automatically added as the first column.
-   Each checkbox has the value of the row’s primary key (defined by `'pk' => 'id'`).
-   When the form is submitted, selected IDs are sent as an array in `$_POST['batch']` (or `$_GET['batch']` if using GET).

### Filters and sorting

The widget supports per-column filtering and sorting directly in the table header. By default, columns have sorting enabled (with clickable header links) when rendering a filter.

**Enabling basic text filtering**

To add a filter input to a column, set the `filter` option to `true`. This renders a text input field by default.

    [
        'column' => 'name',
        'label'  => 'Product Name',
        'filter' => true
    ]


**Dropdown (select) filtering**

For a dropdown filter instead of a text input, provide an associative array of value-label pairs to the `filter` option and set `'type' => 'select'`.

    [
        'column' => 'color',
        'label'  => 'Product Color',
        'type'   => 'select',
        'filter' => [
            'r' => 'Red',
            'b' => 'Blue',
            'y' => 'Yellow'
        ]
    ]

**Notes**

-   The array keys represent the actual values in your data.
-   The array values are the display labels shown in the dropdown.


**Disabling sorting**

Sorting is enabled by default for all columns (header becomes clickable with asc/desc indicators). To disable sorting on a specific column, explicitly set sorting to `false`.

    [
        'column' => 'name',
        'label'  => 'Product Name',
        'filter' => true,
        'sorting' => false // Prevents sorting on this column
    ]


**Important notes**

-   Filters and sorting typically work via GET parameters (e.g., ?`filter[name]=foo&sort=price&order=desc`).
-   Your controller or data provider must handle these query parameters to apply filtering and sorting to the dataset.
-   The filter form is automatically wrapped and submitted when inputs change (usually via JavaScript auto-submit or a dedicated "Apply" button, depending on your implementation).