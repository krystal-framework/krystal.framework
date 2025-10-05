Tree
====

The Tree component simplifies work with hierarchical data stored in tables. Currently there's only one supported algorithm implementation, which is called Adjacency List Model.

# Adjacency List Model

This is probably of the popular and easy-to-understand algorithm to store hierarchal data. The point is simple: you store a reference to a parent identifier in each table row. Typically this is done by adding `parent_id` column.

## Working with trees

The easiest way to learn its usage is to take a look at very common usage case, so let's start from this.
Suppose you have a table that looks like so:

    id | parent_id | title
    1  |    0      | Operating systems
    2  |    1      | MacOS
    3  |    1      | Windows

and after performing fetching query, you got the following result-set:

array(

    	array(
    		'id' => '1',
    		'parent_id' => '0',
    		'title' => 'Operating systems'
    	),
    	array(
    		'id' => '2',
    		'parent_id' => '1',
    		'title' => 'MacOS'
    	),
    	array(
    		'id' => '3',
    		'parent_id' => '1',
    		'title' => 'Windows'
    	)
    )

From there, you can render the result-set. You have two options to do so. Each renderer accepts column name which is used as a title when building an output.

## Rendering as a list

Rendering result-set as a list is a perfect option for displaying it in select-box (in select HTML element).
As example, it's typically done like this:

    <?php
    
    // Import tree builder and required renderer
    use Krystal\Tree\AdjacencyList\TreeBuilder;
    use Krystal\Tree\AdjacencyList\Render\PhpArray;
    
    // $data is aformentioned result-set
    
    $builder = new TreeBuilder($data);
    $list = $builder->render(new PhpArray('title'));
    
    ?>

Then in a template you can render it like a typical array:

    <select>
    
    <?php foreach ($list as $key => $value): ?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
    <?php ?>
    
    </select>

## Rendering as a dropdown (with ul -> li tags)

You would want to render a result-set as a dropdown in case you render something like a menu, with `ul -> li` tags.

    <?php
    
    use Krystal\Tree\AdjacencyList\TreeBuilder;
    use Krystal\Tree\AdjacencyList\Render\Dropdown;
    
    // $data is aformentioned result-set
    
    $builder = new TreeBuilder($data);
    $menu = $builder->render(new Dropdown('title', array(/* Additional options can be passed here */)));
    
    echo $menu;
    
    ?>

This will render the following snippet:

    <ul>
    	<li><a href="#">Operating systems</a>
    		<ul>
    			<li><a href="#">Macintosh</a></li>
    			<li><a href="#">Windows</a></li>
    		</ul>
    	</li>
    </ul>

You probably noted, that all `href` attributes have `#` by default. This is because there's no key called `url`. If you want default `#` to be replaced by your own url, you need to provide a collection with appended `url` pair, like this:

    //..
    
    array(
    	'id' => '2',
    	'parent_id' => '1',
    	'title' => 'MacOS',
    	'url' => 'http://example.com' // <- That overrides default value of href
    ),
    
    // ..

## Options

@TODO