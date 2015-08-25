Pagination
=========

Dropping a large result-set into small ones, you got from a table is a common task for web-developers. Krystal has a dedicated component for this task. Alright, let's start from the beginning, read on...

# Style adapters

First of all, we need to configure a style adapter. It has only one option, which is `style`. That option defines an optional style adapter for service's `getPageNumbers()` method. Let's take a look at available options, before we start configuring the component.

## Digg

If you have a large amount of page numbers, this style adapter is a great choice. For example, if you have 40 pages, a resulting rendered array with pages would look like as following:

    1  2  3  ... 40
    1  ...  3  4  5  6  7 ... 40

This similar to [StackOverFlow's style](http://stackoverflow.com/users)


# Slide

If you like how Yahoo styles their pagination block, then this adapter is what you want. The rendered array might look like so:

    3  5  6  7  8

If the page number is 5


# Configuring the service

Open configuration file, which is typically stored in `config/app.php`. In `components` section, you should see `paginator` sub-section with only one option `style`. See there? Good. This options accepts either `Digg` or `Slide` options for the style adapter respectively. If you omit the option, then no style adapter is used.

# Usage

You're done with configuration. Now it's time to see how pagination can be used. This section assumes that you have a basic knowledge of mappers.

## Fetching a result-set from a table

First of all, create module called `Book` for this.. Suppose we have a table called `books` and there are about 40 books.  Since all table interaction are usually wrapped in data mappers, let's create a class for this table and name it `BookMapper`, like this:
       
    <?php
    
    namespace Book\Storage\MySQL;
    
    use Krystal\Db\Sql\AbstractMapper;
    
    class BookMapper extends AbstractMapper
    {
            public function fetchAllBooksByPage($page, $perPageCount)
            {
                 return $this->db->select('*')
                                 ->from('books')
                                 ->paginate($page, $perPageCount)
                                 ->queryAll();
            }
    }

Pretty easy? Isn't it? 
Also note that `paginate()` method is not a part of Pagination component. It's a part of Database component that implements Smart-Pagination algorithm. When you call it passing `$page` and `$perPageCount` it will tweak pagination behind the scenes for you.

## Defining routes and controller

To make pagination work, it somehow must be aware of page parameter. Typically this is done by providing a route parameter, like `/books/page/3`. So, let's implement this right now. Open your configuration file, where you store all your routes and add new two routes:

    // ....
    '/books' => array(
      'controller' => 'Book@indexAction'
    ),
    
    '/books/page/(:var)' => array(
      'controller' => 'Book@indexAction'
    )
    // ...

Done. We'd use two routes for the same action in `Book` controller class. Now let's create `Book` controller with `indexAction`. For simplicity, we'd avoid a service and build `BookMapper` right in the action.

    class Book extends AbstractController
    {
        
        public function indexAction($page = 1)
        {
             // Grab the BookMapper first, before we start
             $mapper = '/Book/Storage/MySQL/BookMapper';
             $bookMapper = $this->mapperFactory->build($mapper);
             
             // $bookMapper is ready to be used
             // Now grab all books, this must be called first
             $books = $bookMapper->fetchAllBooksByPage($page, 5);
             
             // Now configure pagination URL
             $paginator = $bookMapper->getPaginator();
             $paginator->setUrl('/books/page/(:var)');

             // Done
             return $this->view->render('books', array(
                 'books' => $books,
                 'paginator' => $paginator
             ));
        }
    }

Since we extended `AbstractMapper` we already have inherited `getPaginator()` method that returns pagination service object. Also note, that pagination URL must equal to the route that has been attached to controller's action.

Yeah, that's how its used. Let's examine what we did, if it's still not clear. First, we created a mapper, then we fetched all result-set and configured pagination URL, so that when users click on your links then will be redirected to appropriate page number.

Finally, last thing we need to take a look at is a list of available methods that `$paginator` service object has. So read on.

# Available methods

Typically you would want to use these methods in view templates to build pagination block.

## getFirstPage()

Returns first page number, which is always 1

## getLastPage()

Returns last page number. If we have 40 pages, the last one is 39

## getSummary($separator = '-')

Returns summary string. This typically used for displaying how many records are show on current page, i.e (1-3)

## isCurrentPage($page)

Determines whether passed number is a current page. This method typically used in view templates inside `foreach` to add some kind of `class="active"`for elements.

## hasPages()

Determines whether there's at least one page available. Returns boolean.

## hasAdapter()

Determines whether style adapter was defined in configuration. Returns boolean.

## getPageNumbers()

Returns an array of page numbers. This method is aware of style adapter, so if you defined a one in configuration, it will break-down the resulting array accordingly.

## hasNextPage()

Determines whether current page has a next one. For example, if you're on 39 page and want to know if there's 40'th page, you'd use this method to determine it.

## hasPreviousPage()

Determines whether there's a previous page, which is opposite to `hasNextPage()` you see above.

## getNextPage()

Returns next page number if any.

## getPreviousPage()

Returns previous page number if any.

## getCurrentPage()

Returns current page number

## getNextPageUrl()

Returns next page URL

## getPreviousPageUrl()

Returns previous page URL

## getItemsPerPage()

Returns per page count number

## getTotalAmount()

Returns total amount of records