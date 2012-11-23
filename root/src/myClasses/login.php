<?php
namespace myClasses;
use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

//This part could be worked out more to create a loginsystem, but since I don't think that's needed for the assignment, It's not worked out/used

class Login {
  
  function __construct($app) {
    $this->app = $app;
  }

  function getForm($data = array()) {
    $form = $this->app['form.factory']->createBuilder('form',$data)
        ->add('email', 'email', array('constraints' => array(new Assert\NotBlank())))
        ->add('password', 'password', array('constraints' => array(new Assert\NotBlank())))
        $form = $form->getForm();
    return $form;
  }

  function login($email, $password) {
      session_start();
      $auther = $app['db']->fetchAssoc("SELECT 'UserID' FROM `authors` WHERE `email`= ? AND `password`= ?", array($email, $password));
      $_SESSION['AuthorID'] = $auther->AuthorID;
  }

  function checkPage() {
    
  }  
  
}