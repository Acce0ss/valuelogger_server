<?php

require 'vendor/slim/slim/Slim/Slim.php';

require 'add_value.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/hello/:name', function($name) {
    echo "Hello, $name!";
  });

$app->post('/add/value', function() use ($app){

  add_value($app->request);
});

$app->run();

?>
