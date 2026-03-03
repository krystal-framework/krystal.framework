Models
======

Krystal implements MVC more precisely than many popular frameworks, avoiding the common misuse of "Model" for ActiveRecord.

## What is a model?

The **model** is not a single class — it is a **composition** of three main responsibilities:

1. **Data Mapper**. Low-level abstraction for database table access. Contains query methods (`find`, `fetchAll`, `insert`, `update`, `delete`, etc.). Returns raw arrays or booleans — no business logic here.

2. **Domain Object / Entity.** (optional) Rich object that represents a business concept (e.g. `Book`, `User`, `Order`). Contains getters, setters, behavior, validation rules, or calculations. Often implemented as a simple DTO, `VirtualEntity`, or a custom class.

3. **Service.** The central piece — orchestrates data access and business logic. Constructor typically receives the mapper (and optionally other dependencies). All services are registered in the module's `getServiceProviders()` method.

## Recommended structure

Organize your model layer following this clean, scalable folder layout inside each module. This separation keeps data access, business logic, and domain representation clearly divided.

```
News/
├── Module.php
├── config/
│   └── routes.php
├── Service/
│   └── PostManager.php          ← Service / Manager
├── Storage/
│   └── MySQL/
│       └── PostMapper.php       ← Data Mapper
└── Entity/
    └── Post.php                 ← Optional rich domain entity
```
## Example

When fetching raw data from a mapper, it's best to convert it into safe, object-oriented entities before passing it to templates. This protects against XSS, ensures consistent data shape, and makes templates cleaner.

Extend `AbstractManager` and implement `toEntity()` to transform each row:


    <?php
    
    namespace Book\Service;
    
    use Krystal\Application\Model\AbstractManager;
    use Krystal\Stdlib\VirtualEntity;
    use Book\Storage\MySQL\BookMapper;
    
    final class BookService extends AbstractManager
    {
        private BookMapper $mapper;
    
        public function __construct(BookMapper $mapper)
        {
            $this->mapper = $mapper;
        }
    
        /**
         * Convert raw DB row into a safe entity
         */
        protected function toEntity(array $row): VirtualEntity
        {
            $book = new VirtualEntity();
            $book->setId((int) $row['id'])
                 ->setTitle(htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'))
                 ->setDescription(htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'))
                 ->setCreatedAt($row['created_at']);
    
            return $book;
        }
    
        /**
         * Fetch all books as safe entities
         */
        public function getAll(): array
        {
            $rows = $this->mapper->fetchAll();
            
            // Or use for single row
            // $this->prepareResult($row);

            return $this->prepareResults($rows);
        }
    }

