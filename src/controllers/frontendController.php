<?php

namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use repositories\PostRepository;
use entities\Post;
use entities\Task;
use entities\Users;
use forms\type\contactType;
use forms\type\registerPostType;


class frontendController implements ControllerProviderInterface
{
  public function connect(Application $app)
  {
    $frontend = $app['controllers_factory'];

    $frontend->get('/login', function(Request $request) use ($app)
    {
      return $app['twig']->render('/frontend/login.twig', array(
        'error'          => $app['security.last_error']($request),
        'last_username'  => $app['session']->get('_security.last_username'),
        ));
    })
    ->bind('login');


    $frontend->match('/register', function (Request $request) use($app)
    {
      $form = $app['form.factory']->create(new registerPostType());
      if('POST' == $request->getMethod())
      {
        $form->bind($request);
          if($form->isValid())
          {
           $user = new Users($app);
           $user->addUser($form->getData());
          }
      }
       return $app['twig']->render('/frontend/register.twig', array(
         'message'   => 'Formulario de registro:',
        'form'       => $form->createView()
        ));
    })
    ->bind('register');


    $frontend->get('/', function () use ($app)
    {
      $posts = PostRepository::getAllPosts($app);

      return $app['twig']->render('frontend/index.twig', array(
        'articles'   => $posts,
        ));
    })
    ->bind('home');


    $frontend->get('/help/', function () use ($app)
    {
      $posts = PostRepository::getAllPosts($app);

      return $app['twig']->render('frontend/help.twig', array(
        'articles'   => $posts,
        ));
    })
    ->bind('help');



    $frontend->match('/contact', function(Request $request) use ($app) 
    {
      $posts = PostRepository::getAllPosts($app);

      $form = $app['form.factory']->create(new contactType($posts));
      $form->bind($request);

      if($form->isValid())
      {
        $contact = new Task();
        $contact->addContact($app, $form->getData());
        $contact->thanksForContact($app);
      }

      $form = $app['form.factory']->create(new contactType());

      return $app['twig']->render('frontend/contact.twig', array(
        'message'  => 'Formulario de contacto:',
        'form'     => $form->createView(),
        'articles' => $posts,
        ));
    })
    -> bind('contact');


    $frontend->get('/thanks/', function () use ($app)
    {
      $posts = PostRepository::getAllPosts($app);

      return $app['twig']->render('frontend/thanks.twig', array(
        'articles'   => $posts,
        ));
    })
    ->bind('thanks');


    $frontend->get('/article/{id}', function($id) use ($app)
    {
      $currentPost = new Post($app, $id);
      $latestPosts = PostRepository::getAllPosts($app);

      return $app['twig']->render('frontend/post.twig', array(
        'currentPost'    => $currentPost,
        'articles'       => $latestPosts,
        ));
    })
    ->bind('posts');


  return $frontend;
  }
}
?>
