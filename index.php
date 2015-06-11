<?php

// Make paths relative to the root folder
chdir(__DIR__);

require('/vendor/Krystal/Application/AppFactory.php');
Krystal\Application\AppFactory::build(require('config/application.php'))->run();