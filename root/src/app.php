<?php
// web/index.php

require_once __DIR__.'/../vendor/autoload.php';


use Symfony\Component\Form\FormError;

use Silex\Provider\FormServiceProvider;


$app = new Silex\Application();
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

//Connection to the database using Doctrine DBAL, stored in $app['db']
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => array(
      'driver'   => 'pdo_mysql',
      'host' => 'localhost',
      'user' => 'root',
      'password' => 'usbw',
      'dbname' => 'forum'
  ),
));

//Registering of Twig, stored in $app['twig']
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'     => __DIR__. '/../src/views',
    'twig.options'  => array(
        'debug' => true,
        'cache' => __DIR__. '/../src/cache',
    ),
));

//Registering of the serviceProvider that includes the classes that take care of the database traffic
$app->register(new myClasses\MyServiceProvider(), array());

//Unquote this if you want to reset the cache
//$app['twig']->clearCacheFiles();

//Unquote this if you are testing the site
//$app['debug'] = true;


return $app;