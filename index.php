<?php

require_once('vendor/slim/slim/Slim/Slim.php');

require_once('add_value.php');
require_once('latest_value.php');

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/hello/:name', function($name) {
    echo "Hello, $name!";
  });

$app->post('/add/value', function() use ($app){

  try {
    echo add_value($app->request);
  }
  catch(Exception $e)
  {
    echo $e->getMessage();
  }
});

$app->post('/get/latest/value', function() use ($app){

  try {
    echo get_latest_value($app->request);
  }
  catch(Exception $e)
  {
    echo $e->getMessage();
  }
});

$app->run();

?>
