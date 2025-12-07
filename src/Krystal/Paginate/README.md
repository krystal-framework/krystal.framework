Pagination
=========

Splitting a large dataset from a database table into smaller, manageable chunks is a common task when displaying extensive results.

Krystal provides a dedicated component designed specifically to handle this efficiently.

## Style adapters

First, we need to set up a style adapter. The adapter accepts a single option called `style`, which defines an optional visual style for the service’s `getPageNumbers()` method. Before we configure the component, let’s review the available adapter options.

### Digg

If your dataset contains a large number of pages, this style adapter is an excellent choice. For instance, if there are 40 pages, the rendered page array would look like this:

    1  2  3  ... 40
    1  ...  3  4  5  6  7 ... 40

This similar to [StackOverFlow's style](http://stackoverflow.com/users)


### Slide

If you prefer the pagination style used by Yahoo, this adapter is the right choice. The rendered page array will look something like this:
 
    3  5  6  7  8

If the page number is 5


## Configuring the service

Open the configuration file, typically located at `config/app.php`. In the `components` section, you’ll find a `paginator` subsection with a single option named `style`. This option accepts either `Digg` or `Slide` as the style adapter. If the option is omitted, no style adapter will be applied.

That’s all for the configuration! 

## Usage

Now let’s explore how to use pagination in practice. This section assumes you already have a basic understanding of data mappers.
 
### Fetching a result-set from a table

First, create a module named `Book`. Suppose you have a database table called `books` containing around 40 records. Since database interactions are typically handled through data mappers, let’s create a class for this table and name it `BookMapper`, as shown below:
       
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


Easy, right?

Note that the `paginate()` method doesn’t belong to the Pagination component — it’s part of the Database component, which implements a Smart Pagination algorithm. When you call this method with `$page` and `$perPageCount`, it automatically handles pagination logic behind the scenes for you.


### Manual count

Sometimes, when working with complex SQL queries — such as those involving subqueries or advanced aggregations — the `paginate()` method may not be able to calculate the total count correctly, as it’s primarily designed for simple queries. 

In these situations, you can implement a custom counter method to handle counting correctly, and use `paginateRaw()` instead of `paginate()`.

    class BookMapper extends AbstractMapper
    {
            private function countRecords()
            {
                // Your own custom method that does the couting and returns int value
            }

            public function fetchBooks($page, $itemsPerPage)
            {
                  db = $this->db->select('*')
                                ->from('my_table')
                                ->paginateRaw($this->countRecords(), $page, $itemsPerPage);
    
                  return $db->queryAll();
             }            
    }



## Defining routes and controller

For pagination to work, it needs to know which page to display. This is usually done by passing a page parameter through the route, for example: `/books/page/3`.

Let’s implement this now. Open your routing configuration file and add the following two routes:
> 

    // ....
    '/books' => array(
      'controller' => 'Book@indexAction'
    ),
    
    '/books/page/(:var)' => array(
      'controller' => 'Book@indexAction'
    )
    // ...

All set. We’ll use two routes that point to the same action in the `Book` controller. Now, let’s create the `Book` controller with an `indexAction`. For simplicity, we’ll skip creating a separate service and instantiate the `BookMapper` directly within the action.
> 

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
             return $this->view->render('books', [
                 'books' => $books,
                 'paginator' => $paginator
             ]);
        }
    }


> Since our class extends `AbstractMapper`, we’ve automatically inherited the `getPaginator()` method, which returns the pagination service object. Keep in mind that the pagination URL must match the route associated with the controller’s action.

That’s how it works. Let’s quickly review what we’ve done: first, we created a mapper; then, we fetched the result set and configured the pagination URL so that when users click a page link, they’re redirected to the correct page.

Finally, let’s take a look at the list of available methods provided by the `$paginator` service object.

## Available methods

Typically, you’ll use these methods within your view templates to render the pagination block.
> 
### Get first page

    \Krystal\Paginate\Paginator::getFirstPage()

Returns first page number, which is always 1.

### Get last page

    \Krystal\Paginate\Paginator::getLastPage()

Returns last page number. If we have 40 pages, the last one is 39.

### Get summary

    \Krystal\Paginate\Paginator::getSummary($separator = '-')

Returns a summary string, typically used to display the range of records shown on the current page — for example, `(1–3)`.

### Is current page?

    \Krystal\Paginate\Paginator::isCurrentPage($page)

Checks whether the given page number is the current page. This method is typically used in view templates within a `foreach` loop to add an `active` class to the corresponding element.

### Has pages?

    \Krystal\Paginate\Paginator::hasPages()

Determines whether at least one page is available. Returns a boolean value.

### Has adapter?

    \Krystal\Paginate\Paginator::hasAdapter()

Determines whether style adapter was defined in configuration. Returns boolean.

### Get page numbers

    \Krystal\Paginate\Paginator::getPageNumbers()

Returns an array of page numbers. This method is aware of style adapter, so if you defined a one in configuration, it will break-down the resulting array accordingly.

### Has next page?

    \Krystal\Paginate\Paginator::hasNextPage()

Determines whether current page has a next one. For example, if you're on 39 page and want to know if there's 40'th page, you'd use this method to determine it.

### Has previous page?

    \Krystal\Paginate\Paginator::hasPreviousPage()

Determines whether there's a previous page, which is opposite to `hasNextPage()` you see above.

### Get next page

    \Krystal\Paginate\Paginator::getNextPage()

Returns next page number if any.

### Get previous page

    \Krystal\Paginate\Paginator::getPreviousPage()

Returns previous page number if any.

### Get current page

    \Krystal\Paginate\Paginator::getCurrentPage()

Returns current page number.

### Get next page URL

    \Krystal\Paginate\Paginator::getNextPageUrl()

Returns next page URL.

### Get previous page URL

    \Krystal\Paginate\Paginator::getPreviousPageUrl()

Returns previous page URL.

### Get items per page

    \Krystal\Paginate\Paginator::getItemsPerPage()

Returns per page count number.

### Get total amount

    \Krystal\Paginate\Paginator::getTotalAmount()

Returns total amount of records.

