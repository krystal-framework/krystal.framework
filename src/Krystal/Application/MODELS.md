Models
======

Despite that you have seen model classes in many popular frameworks, there are no models in MVC. Those frameworks call classes that extend ActiveRecord implementation as "models".

Krystal is different. It respects SOLID, follows SoC and avoids ActiveRecord.

# What is a model?

A model itself a concept of data/logic abstraction. When it comes to web-applications, it consist of these parts:

- Data Mapper

It provides a set of methods that abstract table access.

- Domain object/layer

It provides an object that performs calculation or processes business rules. A domain object is optional. For example, if your form has an ability to upload images putting a watermarks on them, then a class that does image processing would be domain object.

- Service

A service is just a brigde between domain objects (if you have them) and data mappers. When instantiating a service class, its constructor usually accepts a data mapper and a domain object (if present).

All service objects must be registered within `getServiceProviders()` in module definition class. There's no a generalized answer on creating models (services). It's always up to you to decide how to build that.


# Best practice

When working with services, you'd probably want to turn raw result-sets that you receive from mappers to entities. For example, when passing an array of raw-result set from mappers to templates, the iteration process typically looks like so:

    <?php foreach ($books as $book)?>
    
    <h2><?php echo $book['title']; ?></h2>
    <article><?php echo $book['description']; ?></article>
    
    <?php endforeach; ?>

That's easy to write, but we didn't filter our data we got from a data mapper. As a best practice, you can extend `\Krystal\Application\Model\AbstractManager` and implement protected `toEntity()` method that returns an entity object and then use either `prepareResult()` or `prepareResults()` depending what you used in mapper to retrieve a result-set - `query()` or `queryAll()`.

For example, the service might look so:

    <?php
    
    use Krystal\Application\Model\AbstractManager;
    use Krystal\VirtualEntity;
    
    class BookManager extends AbstractManager
    {
        private $bookMapper;
    
        public function __construct($bookMapper)
        {
            $this->bookMapper = $bookMapper;
        }
    
        protected function toEntity(array $row)
        {
            $book = new VirtualEntity();
            $book->setId($row['id'])
                 // Filter undesired characters
                 ->setTitle(htmlentities($row['title']))
                 ->setDescription(htmlentities($row['description']));
            
            return $book;
        }
    
        public function getAll()
        {
            $books = $this->bookMapper->fetchAll();
            return $this->prepareResults($books);
        }
    }

Then in a template, you can safely render the result-set like this:

    <?php foreach ($books as $book)?>
    
    <h2><?php echo $book->getTitle(); ?></h2>
    <article><?php echo $book->getDescription(); ?></article>
    
    <?php endforeach; ?>
