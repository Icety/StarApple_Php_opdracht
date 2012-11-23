<?php
namespace myClasses;
use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

class Author {
  
  function __construct($app) {
    $this->app = $app;
  }

  /* getForm()
   * In: $data, the information that has to be in the form already
   *     $password, true if the password has to be shown in the form
   * Out: The complete form created by form.factory
  */
  function getForm($data = array(), $password = true) {
    $form = $this->app['form.factory']->createBuilder('form',$data)
        ->add('type', 'hidden')
        ->add('AuthorID', 'hidden')
        ->add('firstname', 'text', array('constraints' => array(new Assert\NotBlank())))
        ->add('lastname', 'text', array('constraints' => array(new Assert\NotBlank())))
        ->add('street', 'text', array('constraints' => array(new Assert\NotBlank())))
        ->add('streetnr', 'text', array('constraints' => array(new Assert\NotBlank())))
        ->add('zipcode', 'text', array('constraints' => array(new Assert\NotBlank())))
        ->add('city', 'text', array('constraints' => array(new Assert\NotBlank())))
        ->add('telephone', 'text', array('constraints' => array(new Assert\NotBlank())))
        ->add('email', 'email', array('constraints' => array(new Assert\NotBlank())));
        if ($password) {
          $form = $form
          ->add('password1', 'password', array('constraints' => array(new Assert\NotBlank())))
          ->add('password2', 'password', array('constraints' => array(new Assert\NotBlank())));
        }
        $form = $form->getForm();
    return $form;
  }

  /* save()
   * In: $data, the information that needs to be saved
   * Out: A message whether the save succeeded.
   * Info: If data['type'] is 'new', the values are inserted into the database
   *       If it is 'edit', the values are saved for row with PostID 'data['PostID']'
  */

  function save($data) {
    $insert = "";
    $insert2 = "";
    $update = "";
    $values = array();
    //Goes through all values of $data and puts them in variables insert, insert2, update and values, with which the sql can be build
    //This way on adding more info to the form, only the js and the database have to be edited, the rest will adapt automatically
    foreach($data as $key => $value) {
      if ($key != 'AuthorID' && $key != 'type' && $key != 'password1' && $key != 'password2') {   //AuthorID, type and passwords can not be changed, therefor filtered away
        $insert .= '`'.$key.'`,';
        $insert2 .= '?,';
        $update .= '`'.$key.'`= ?,';
        $values[] = $value;
      }
    }
    $insert = substr($insert, 0, -1);
    $insert2 = substr($insert2, 0, -1);
    $update = substr($update, 0, -1);

    if ($data['type'] == 'new') {
      if ($data['password1'] == $data['password2']) {      //Check if the passwords are the same, add the password to $insert(2), $values and execute the sql
        $insert .= ',`password`';
        $insert2 .= ',?';
        $values[] = md5($data['password1']);
        $sql = "INSERT INTO `authors` ($insert) VALUES ($insert2)";
        $this->app['db']->executeUpdate($sql, $values);
        return 'Congratulations! You can now login and post much as you want.';
      }
      else {
        return 'The passwords didn\'t match.';
      }
    }
    elseif ($data['type'] == 'edit') {                    //Add AuthorID to $values and execute the sql
      $values[] = $data['AuthorID'];
      $sql = "UPDATE `authors` SET $update WHERE `AuthorID`= ?";
      $this->app['db']->executeUpdate($sql, $values);
      return 'Save succesful!';
    }
    else {
      return 'Something has gone wrong, plz refresh your page';
    }
  }

  /* overview()
   * In: $page, the pagenr used to make a limit in the sql-query
   * Out: an array containing up to 10 authors ordered by last registered
  */
  function overview($page) {
    $page = ($page - 1) * 10;
    $sql = "SELECT * FROM `authors` ORDER BY `AuthorID` DESC LIMIT ".$page.",10";
    return $this->app['db']->fetchAll($sql);
  }

  /* countPages()
   * In: Null
   * Out: Returns the amount of authors available divided by 10 rounded up
  */
  function countPages() {
    $sql = $this->app['db']->executeQuery("SELECT COUNT(*) FROM `authors`", array());
    $count = $sql->fetch();
    return ceil($count['COUNT(*)'] / 10);
  }

  /* getAuthor()
   * In: $AuthorID, the ID of the author that needs to be returned
   * Out: an array containing all info about the author belonging to 'AuthorID'
  */
  function getAuthor($AuthorID) {
    $result = $this->app['db']->fetchAssoc("SELECT * FROM `authors` WHERE `AuthorID` = ?", array($AuthorID));
    unset($result['password']);
    return $result;
  }
  
}