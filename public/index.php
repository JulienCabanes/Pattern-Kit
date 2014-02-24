<?php
// Composer autoload
require __DIR__.'/../vendor/autoload.php';

// Create & run the app
$app = new Silex\Application;
require __DIR__.'/../app/app.php';
$app->run();