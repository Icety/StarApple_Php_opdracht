<?php
namespace myClasses;
use Silex\ServiceProviderInterface;
use Silex\Application;

class MyServiceProvider implements ServiceProviderInterface
{
  public function register(Application $app)
  {
      //Includes all classes that take care of all database traffic
      $app['posts'] = $app->share(function() use ($app) {
          return new Post($app);
      });
      $app['authors'] = $app->share(function() use ($app) {
          return new Author($app);
      });
      /*
      $app['login'] = $app->share(function() use ($app) {
          return new Login($app);
      });
      */
  }    
  public function boot(Application $app) {
  }
}