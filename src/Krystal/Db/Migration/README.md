Migrations
===
The migrations component provides a structured way to manage and evolve your database schema over time. It allows you to create, run, rollback, and inspect database migrations seamlessly from the command line.

## Creating a migration
Every migration file returns an anonymous class implementing `MigrationInterface` with the `up()` and `down()` methods receiving the `DbInterface` instance directly. You can use the built-in query builder methods to manage schema structures declaratively.

    <?php
    
    use Krystal\Db\Migration\MigrationInterface;
    use Krystal\Db\Sql\DbInterface;
    
    return new class implements MigrationInterface
    {
        /**
         * Run the migration
         * 
         * @param \Krystal\Db\Sql\DbInterface $db
         * @return void
         */
        public function up(DbInterface $db)
        {
            $db->createTable('users', [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'name' => 'VARCHAR(255) NOT NULL',
                'email' => 'VARCHAR(255) NOT NULL'
            ], 'InnoDB', false, 'utf8mb4')
            ->execute();
        }
    
        /**
         * Reverse the migration
         * 
         * @param \Krystal\Db\Sql\DbInterface $db
         * @return void
         */
        public function down(DbInterface $db)
        {
            $db->dropTable('users', true)
               ->execute();
        }
    };

## Advanced schema modifications
You can modify existing table structures by chaining fluent query builder methods and finishing with `execute()`:

    <?php
    
    use Krystal\Db\Migration\MigrationInterface;
    use Krystal\Db\Sql\DbInterface;
    
    return new class implements MigrationInterface
    {
        public function up(DbInterface $db)
        {
            $db->alterTable('users')
               ->addColumn('status', 'VARCHAR(50) NOT NULL DEFAULT "active"')
               ->execute();
        }
    
        public function down(DbInterface $db)
        {
            $db->alterTable('users')
               ->dropColumn('status')
               ->execute();
        }
    };

## Available schema helper methods
-   `createTable($table, array $definitions, $engine = 'MyISAM', $ifNotExists = false, $charset = 'UTF8')` — Generates a table creation query.
-   `dropTable($target, $ifExists = true)` — Drops one or multiple tables with an optional `IF EXISTS` clause.
-   `alterTable($table)` — Initializes an `ALTER TABLE` statement.
-   `addColumn($column, $type)` — Appends a column addition clause.
-   `dropColumn($column)` — Appends a column removal clause.
-   `renameColumn($old, $new)` — Renames an existing column.
-   `alterColumn($column, $type)` — Modifies an existing column definition.
-   `renameTable($old, $new)` — Renames an existing table name.
-   `truncate($table)` — Empties a table.

## Running migration commands
To execute commands, open your project terminal, navigate to the `bin` directory located at the root of your project folder (`cd bin`), and run your commands across all platforms:

### Creating a new migration file
Generate a blank migration template file. By default, migrations are stored in `data/migrations`, but you can target a custom directory using the `--path` option.

The `--path` option accepts a path relative to the project root (e.g., `--path=module/Site/Migrations`).

    migrate migration:make CreateUsersTable

### Running migrations
Execute all pending database migrations:

    migrate migration:migrate

### Rolling back migrations
Rollback the last executed batch of database migrations:

    migrate migration:rollback

### Checking migration status
Display the execution status of each migration file:

    migrate migration:status