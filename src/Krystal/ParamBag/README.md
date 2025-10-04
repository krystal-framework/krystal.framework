ParamBag
=======

The `ParamBag` class provides a simple and consistent way to **store**, **access**, and **manage static configuration parameters**.

This class helps you:

-   Retrieve configuration values safely with default fallbacks
-   Check if one or more parameters exist
-   Add or update parameters dynamically at runtime
-   Access all configuration parameters at once


## Initialization and Access

    <?php
    
    use Krystal\ParamBag\ParamBag;
    
    $params = new ParamBag([
        'site_name' => 'My Website',
        'debug' => true
    ]);
    
    echo $params->get('site_name'); 
    // Output: My Website

## Getting values with defaults

    echo $params->get('non_existing', 'default_value'); 
    // Output: default_value

## Checking for existence

    if ($params->has('debug')) {
        echo 'Debug mode is enabled.';
    }
    
    if ($params->hasMany(['site_name', 'items_per_page'])) {
        echo 'All required parameters are present.';
    }

## Setting and updating values

    $params->set('timezone', 'UTC');
    $params->set('debug', false);
    
    $params->setMany([
        'api_key' => '12345',
        'version' => '2.0.1'
    ]);
    
    print_r($params->getAll());

This will output


    [
        'site_name' => 'My Website',
        'debug' => false,
        'timezone' => 'UTC',
        'api_key' => '12345',
        'version' => '2.0.1',
    ]
