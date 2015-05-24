<?php

require_once('vendor/slim/slim/Slim/Slim.php');

require_once('add_value.php');
require_once('latest_value.php');
require_once('values_for_serie.php');

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/hello/:name', function($name) {
    echo "Hello, $name!";
  });

$app->post('/new/value', function() use ($app){

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

$app->post('/get/values/for/serie', function() use ($app){

  try {
    echo values_for_serie($app->request);
  }
  catch(Exception $e)
  {
    echo $e->getMessage();
  }
});

$app->run();

?>
