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
use forms\type\newPostType;
use forms\type\editPostType;

class backendController implements ControllerProviderInterface
{
  private function addPost($app, $formData) 
  {
    $postToAdd = new Post($app);
    $postToAdd->setTitle($formData['title']);
    $postToAdd->setContent($formData['content']);
    $postToAdd->save();
  }

  public function connect(Application $app)
  {
    $backend = $app['controllers_factory'];

    $backend->before(function () use($app)
    {
      if(!$app['security']->isGranted('ROLE_ADMIN'))
      {
        return new RedirectResponse($app['url_generator']->generate('home'));
      }
    });


    $backend->match('/', function() use($app)
    {
      $posts = PostRepository::getAllPosts($app);

      return $app['twig']->render('backend/listPosts.twig', array(
        'articles' => $posts
        ));
    })
    ->bind('backend');


    $backend->match('/createPost/', function(Request $request) use($app)
    {
      $form = $app['form.factory']->create(new newPostType());

      if('POST' == $request->getMethod())
      {
        $form->bind($request);

        if($form->isValid())
        {
          $this->addPost($app, $form->getData()); 
        }
      }

      return $app['twig']->render('backend/createPost.twig', array ('form' => $form->createView()));
    })
    ->bind('newPost');


    $backend->match('/{id}/edit', function(Request $request, $id) use($app)
    {
      $post = new Post($app);
      $post->editPostById($id);

      if(!$post)
      {
       $post->redirectToBackend();
      }

      $form = $app['form.factory']->create(new editPostType(), $post);
      if('POST' == $request->getMethod())
      {
        $form->bind($request);
        if($form->isValid())
        {
          $this->addPost($app, $form->getData());
          $post = $form->getData();
          $post->redirectToBackend();
        }
      }

      return $app['twig']->render('backend/editPost.twig', array('article' => $post, 'form' => $form->createView()));

    })
    ->bind('editPost');



    $backend->match('/{id}/delete', function($id) use($app)
    {
      $currentPost = new Post($app, $id);
      $currentPost->deletePost();
      return new RedirectResponse($app['url_generator']->generate('backend'));
    })
      ->bind('deletePost');


  return $backend;
  }
}

