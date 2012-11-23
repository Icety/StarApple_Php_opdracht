<?php
namespace myClasses;
use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

class Post {
  
  function __construct($app) {
    $this->app = $app;
  }

  /* getForm()
   * In: $data, the information that has to be in the form already
   * Out: The complete form (type, PostId, title and message) created by form.factory
  */
  function getForm($data = array()) {
    $form = $this->app['form.factory']->createBuilder('form',$data)
        ->add('type', 'hidden')
        ->add('PostID', 'hidden')
        ->add('title', 'text', array('constraints' => array(new Assert\NotBlank())))
        ->add('message', 'textarea', array('constraints' => array(new Assert\NotBlank())))
        ->getForm();
    return $form;
  }

  /* save()
   * In: $data, the information that needs to be saved
   * Out: A message whether the save succeeded.
   * Info: If data['type'] is 'new', the values are inserted into the database
   *       If it is 'edit', the values are saved for row with PostID 'data['PostID']'
  */
  function save($data) {
    if ($data['type'] == 'new') {             
      $sql = "INSERT INTO `posts` (`title`,`message`,`posted`,`edited`) VALUES (?, ?, ?, ?)";
      $this->app['db']->executeUpdate($sql, array($data['title'], $data['message'], date('d-m-Y H:i'), date('d-m-Y H:i')));
      return 'Message added.';
    }
    elseif ($data['type'] == 'edit') {
      $sql = "UPDATE `posts` SET `title`= ? , `message`= ?, `edited`= ? WHERE `PostID`= ?";
      $this->app['db']->executeUpdate($sql, array($data['title'], $data['message'], date('d-m-Y H:i'), $data['PostID']));
      return 'Message updated.';
    }
    else {
      return 'Something has gone wrong, plz refresh your page';
    }
  }

  /* overview()
   * In: $page, the pagenr used to make a limit in the sql-query
   * Out: an array containing up to 10 posts ordered by the last time they were edited
  */
  function overview($page) {
    $page = ($page - 1) * 10;
    $sql = "SELECT * FROM `posts` ORDER BY `edited` DESC LIMIT ".$page.",10";
    return $this->app['db']->fetchAll($sql);
  }

  /* countPages()
   * In: Null
   * Out: Returns the amount of posts available divided by 10 rounded up
  */
  function countPages() {
    $sql = $this->app['db']->executeQuery("SELECT COUNT(*) FROM `posts`", array());
    $count = $sql->fetch();
    return ceil($count['COUNT(*)'] / 10);
  }

  /* getPost()
   * In: $PostID, the ID of the post that needs to be returned
   * Out: an array containing all info about the post belonging to 'PostID'
  */
  function getPost($PostID) {
    return $this->app['db']->fetchAssoc("SELECT * FROM `posts` WHERE `PostID` = ?", array($PostID));
  }
  
}