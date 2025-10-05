
Config
======

Working with configuration data is a pretty common task. For example, when developing administration panels, you would want some preferences to be configurable. Krystal's configuration component is designed for that purpose.

# SQL Adapter

In case you want to store configuration data in a table, you can use SQL adapter to do so. Before you start you have to configure it.

## Configuration

The configuration is located under `config` section, that defines an adapter and its associated options. The typical component configuration looks like this:

    'config' => array(
    	'adapter' => 'sql',
    	'options' => array(
    		'connection' => 'mysql',
    		'table' => 'config'
    	)
    )

`adapter` - defines a type of adapter.
`option -> connection` - defines a database driver (which is defined in `db` section).
`option -> table` - defines a table name.

The table is not created by default. To create a table, simply run this file: `/vendor/Krystal/Config/Sql/sql-schema.sql` in your terminal.

## Available methods

Once you configure the component, you can use its service `config`, which is available as property in controllers.

### store()

    \Krystal\Config\Sql\SqlConfigService::store($module, $name, $value)

Stores a new configuration entry.

### getAllByModule()

     \Krystal\Config\Sql\SqlConfigService::getAllByModule($module)

Returns all configuration data associated by a module.

### get()

    \Krystal\Config\Sql\SqlConfigService::get($module, $key, $default = false)

Returns a configuration value from partucular module. The 3-rd argument defines a default value to be returned in case requested one doesn't exist.

### has()

    \Krystal\Config\Sql\SqlConfigService::has($module, $key)

Determines whether module has a particular key.

### removeAll()

    \Krystal\Config\Sql\SqlConfigService::removeAll()

Removes all confuguration data.

### remove()

    \Krystal\Config\Sql\SqlConfigService::remove($module, $name)

Removes a module's assoociated configuration key.

### removeAllByModule()

    \Krystal\Config\Sql\SqlConfigService::removeAllByModule($module)

Removes all configuration data associated with provided module.