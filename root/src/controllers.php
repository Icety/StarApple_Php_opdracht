<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/*
 * POSTS:
 * /post/new                  -> Add a new post
 * /post/save                 -> Save the ajax call, this can be either a new post or an edit of an existing post
 * /post/edit/PostID          -> Edit an existing post
 * /post/PostID               -> View an existing post
 * /post/overview/page        -> View a list of posts with a max of 10 posts per page
*/

/* /post/new
 * In: Null
 * Out: asks up the form from the posts class, sent towards twig:post/new.htm
*/
$app->get('/post/new', function () use ($app) {
    $values = array(
        "type" => "new",
        "PostID" => "0"
    );
    $form = $app['posts']->getForm($values);
    return $app['twig']->render('post/new.htm', array('form' => $form->createView()));
});

/* /post/save
 * In: The post request from the ajax call
 * Out: One sentence telling the user whether the save succeeded or not
*/
$app->post('post/save', function (Request $request) use ($app) {
    $form = $app['posts']->getForm();
    if ('POST' === $request->getMethod()) {
        $form->bindRequest($app['request']);
        if ($form->isValid()) {
            $data = $form->getData();
            $return = $app['posts']->save($data);
            return $return;
        } else {
          return 'Please fill in both the title and the message';
        }
    }
});

/* /post/edit/{PostID}
 * In: PostID, the id of the post that is being edited
 * Out: The information belonging to the PostID is called and sent towards twig:post/edit.htm
*/
$app->get('/post/edit/{PostID}', function ($PostID) use ($app) {
    $data = $app['posts']->getPost($PostID);
    $data['type'] = 'edit';
    $form = $app['posts']->getForm($data);
    return $app['twig']->render('post/edit.htm', array('form' => $form->createView()));
});

/* /post/overview/{page}
 * In: page, telling from where to where rows have to be taken out of the database, ($page-1)*10  -  ($page*10)-1
 * Out: The page, amount of pages available and a max of 10 rows sent towards twig:post/overview.htm
*/
$app->get('/post/overview/{page}', function ($page) use ($app) {
    $posts = $app['posts']->overview($page);
    $pageCount = $app['posts']->countPages();
    return $app['twig']->render('post/overview.htm', array('page' => $page, 'pageCount' => $pageCount, 'posts' => $posts));
});

/* /post/overview
 * In: Null
 * Out: If you get here you get redirected to /post/overview/1 -> Page 1 of the overview
*/
$app->get('/post/overview', function () use ($app) {
    return $app->redirect('/post/overview/1');
});

/* /post/{PostID}
 * In: PostID, The ID of the post your viewing
 * Out: All information about the given post, sent towards twig:post/view.htm
*/
$app->get('/post/{PostID}', function ($PostID) use ($app) {
    $post = $app['posts']->getPost($PostID);
    return $app['twig']->render('post/view.htm', array('post' => $post));
});


/*
 * AUTHORS:
 * /author/new                -> Add a new author
 * /post/save                 -> Save the ajax call, this can be either a new author or an edit of an existing author
 * /author/edit/AuthorID      -> Edit an existing author
 * /author/AuthorID           -> View an existing author
 * /author/overview/page      -> View a list of authors with a max of 10 users per page
*/

/* /author/new
 * In: Null
 * Out: asks up the form from the authors class, sent towards twig:author/new.htm
*/
$app->get('/author/new', function () use ($app) {
    $values = array(
        "type" => "new",
        "AuthorID" => "0"
    );
    $form = $app['authors']->getForm($values);
    return $app['twig']->render('author/new.htm', array('form' => $form->createView()));
});

/* /author/save
 * In: The author request from the ajax call
 * Out: One sentence telling the user whether the save succeeded or not
*/
$app->post('/author/save/{password}', function (Request $request, $password = true) use ($app) {
    $form = $app['authors']->getForm(array(), $password);
    if ('POST' === $request->getMethod()) {
        $form->bindRequest($app['request']);
        if ($form->isValid()) {
            $data = $form->getData();
            $return = $app['authors']->save($data);
            return $return;
        } else {
          return 'Please fill in all fields.';
        }
    }
});

/* /author/edit/{PostID}
 * In: AuthorID, the id of the author that is being edited
 * Out: The information belonging to the AuthorID is called and sent towards twig:author/edit.htm
*/
$app->get('/author/edit/{AuthorID}', function ($AuthorID) use ($app) {
    $data = $app['authors']->getAuthor($AuthorID);
    $data['type'] = 'edit';
    $form = $app['authors']->getForm($data, false);
    return $app['twig']->render('author/edit.htm', array('form' => $form->createView()));
});

/* /author/overview/{page}
 * In: page, telling from where to where rows have to be taken out of the database, ($page-1)*10  -  ($page*10)-1
 * Out: The page, amount of pages available and a max of 10 rows sent towards twig:author/overview.htm
*/
$app->get('/author/overview/{page}', function ($page) use ($app) {
    $authors = $app['authors']->overview($page);
    $pageCount = $app['authors']->countPages();
    return $app['twig']->render('author/overview.htm', array('page' => $page, 'pageCount' => $pageCount, 'authors' => $authors));
});

/* /author/overview
 * In: Null
 * Out: If you get here you get redirected to /author/overview/1 -> Page 1 of the overview
*/
$app->get('/author/overview', function () use ($app) {
    return $app->redirect('/author/overview/1');
});

/* /author/login
 * In: Null
 * Out: A session containing the AuthorID
 * This could be added to make it a real working system, but for this assignment I don't think it's needed
*/
$app->get('/author/login', function () use ($app) {
    //$form = $app['login']->getForm();
    //return $app['twig']->render('login/login.htm', array());
    return 'To be added in the future!';
});

/* /author/{AuthorID}
 * In: AuthorID, The ID of the author your viewing
 * Out: All information about the given author, sent towards twig:author/view.htm
*/
$app->get('/author/{AuthorID}', function ($AuthorID) use ($app) {
    $author = $app['authors']->getAuthor($AuthorID);
    return $app['twig']->render('author/view.htm', array('author' => $author));
});



return $app;
