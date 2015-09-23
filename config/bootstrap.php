<?php

// Make paths relative to the root folder
chdir(dirname(__DIR__));

// Include application factory
require(dirname(__DIR__).'/vendor/Krystal/Application/AppFactory.php');

// Return prepared application's instance
return Krystal\Application\AppFactory::build(require(__DIR__.'/app.php'));