
# Introduction

HTML tables are very common component in many web applications today. Krystal provides a powerful, lightweight and flexible data grid component that can generate tables. Just provide a data (typically that you received from a database) and define how you want to output data (whether a column should be inline-editable or contain a filter, and so on). By default it styles output using Bootstrap 3 corresponding CSS-classes, but you can easily override these ones if needed. 

# Features

 - Inline-edit support
 - Filtering (supports input and select elements)
 - Sorting
 - Batch removal
 - Custom action buttons
 - Custom data output format

# Getting started

The major requirement is to provide data source array and its configuration on how to render it. A source must be an array with nested arrays that contain key and value. For example:

    $source = array(
       array(
           'name' => 'Dave',
           'age' => 27,
           'email' => 'dave@example.com'  
       ),
       array(
    	   'name' => 'Julia',
    	   'age' => 30,
    	   'email' => 'julia@example.com'
       ),
       array(
    	   'name' => 'Josh',
    	   'age' => 20,
    	   'email' => 'josh@example.com'
       )
    );

To render this in a template, we'd the following:

    <?php
    
    // Import Grid component
    use Krystal\Grid\Grid;
    
    ?>
    
    <div class="table-responsive">
       <?= Grid::render($source, array(
          'tableClass' => 'table table-hover table-bordered table-striped table-condensed',
          'columns' => array(
              array(
                 'column' => 'name'
              ),
              array(
                  'column' => 'age'
              ),
              array(
                   'column' => 'email'
              )
           ));
    </div>

Now let's break it down into small pieces and address each of them.

The method `Krystal\Grid\Grid::render()` expects two mandatory arguments - the first one is data source array, the second one is configuration array that defines on how to render table.

Now let's explore the configuration array a little bit deeper. 

## Table class

The key `tableClass` defines a class that generated table will have. If you omit this, then the default CSS class `table table-hover table-bordered table-striped`will be used.

## Columns 

The key `columns` expects a collection of arrays. Each array represents a column to be rendered in a table.

# Advanced usage

The aforementioned example shows a very basic example of usage. In real-world scenarios, you would expect table generator to handle more cases.

## Overriding default label

By default, when rendering a column, its name is normalized and then used to display in heading row. For example the following column configuration:

    array(
       'column' => 'name'
    )

will have a heading row labeled as *Name*. If you want to override default heading label, you can do it by providing `label` key with its value:

    array(
       'column' => 'name',
       'label' => 'Customer name'
    )

It will render a column name with labeled heading row *Customer name*

## Overriding default value

Sometimes your source array might contain some status codes or constants, like `-1`, `0` or `1`. In most cases, you would usually want to convert them to some readable values for your users. For example, instead of showing `-1` or `0` in table columns, you would want to show `Canceled` and `Success` respectively.

Here comes the option `value` to handle this. As a value it expects a callback function that returns some new value.

Consider this:

    array(
       'column' => 'status',
       'value' => function($row){
           if ($row['status'] == -1) {
               return 'Canceled';
           }
           
           if ($row['status'] == 0){
              return 'Success';
           }
       }
    )

Here in `value` callback function, we use conditional logic to render text. Pay attention to `$row` parameter, which is internally represents current row when iterating over all rows.

So now, when table is rendered, all values in `status` column will be replaced to `Canceled` and `Success`.


## Hiding columns

Sometimes, you would want to show or hide some columns conditionally. By default, all columns are visible. To change this, you can set optional parameter `hidden` to `true`, like this:

    array(
       'column' => 'name',
       'hidden' => true,
       'label' => 'Customer name'
    )

## Enabling batch operations

Whenever you need batch removal or status change, you can set `batch` option to true. However, this won't work by itself. You also need to provide column name which should be considered as a primary key.

In order to accept selected checkboxes, you should also wrap entire table in `form` tag.

    <?php
    
    // Import Grid component
    use Krystal\Grid\Grid;
    
    ?>
    
    <form action="...." method="...">
     <div class="table-responsive">
       <?= Grid::render($source, array(
          'batch' => true,
          'pk' => 'id', // Must be in source array
          'columns' => array(
              // ...
           ));
     </div>
     
     <button type="submit">Apply batch</submit>
     
    </form>

On form submission, you can accept selected items in `$_POST['batch']` or `$_GET['batch']` (depending on form method).
