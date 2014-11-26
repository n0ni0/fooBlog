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

        //-- LOGIN ---------------------------------------------------------------------------------------------------------
        $frontend->get('/login', function(Request $request) use ($app)
        {
          return $app['twig']->render('/frontend/login.twig', array(
            'error'          => $app['security.last_error']($request),
            'last_username'  => $app['session']->get('_security.last_username'),
          ));
        })
        ->bind('login');


        //-- REGISTER -------------------------------------------------------------------------------------------------------
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
            'mensaje'    => 'Formulario de registro:',
            'form'       => $form->createView()
          ));
        })
        ->bind('register');


        // -- PORTADA con artículos -----------------------------------------------------------------------------------------
        $frontend->get('/', function () use ($app) {

            $posts = PostRepository::getAllPosts($app);

            return $app['twig']->render('frontend/index.twig', array(
              'articles'   => $posts,
            ));
        })
        ->bind('portada');


        // -- AYUDA ---------------------------------------------------------------------------------------------------------
        $frontend->get('/ayuda/', function () use ($app)
        {

            $posts = PostRepository::getAllPosts($app);

            return $app['twig']->render('frontend/ayuda.twig', array(
                'articles'   => $posts,
            ));
        })
        ->bind('ayuda');



        // -- CONTACTO -------------------------------------------------------------------------------------------------------
        $frontend->match('/contacto', function(Request $request) use ($app) 
        {
            $posts = PostRepository::getAllPosts($app);

            $form = $app['form.factory']->create(new contactType($posts));
            $form->bind($request);

            if($form->isValid())
                {
                    $datos = $form->getData();
                    // -- Preparación del email y del envio -------------------------------
                    $app['mailer']->send(\Swift_Message::newInstance()
                    ->setSubject('Formulario de conctacto blog silex')
                    ->setFrom(array('ajimenez.bf@gmail.com' => 'noreply@gmail.com'))
                    ->setTo(array('ajimenez.bf@ono.com'))
                    ->setBody($app['twig']->render('frontend/email.twig',
                        array('nombre'    => $datos['name'],
                              'correo'    => $datos['mail'],
                              'asunto'    => $datos['title'],
                              'mensaje'   => $datos['content'],

                        )), 'text/html'
                    ));

                     return new RedirectResponse($app['url_generator']->generate('gracias'));
                }

            $form = $app['form.factory']->create(new contactType());
            // -- Vista del formulario antes de enviar el correo
            return $app['twig']->render('frontend/contacto.twig', array(
                'mensaje'  => 'Formulario de contacto:',
                'form'     => $form->createView(),
                'articles' => $posts,
                ));
        })
        -> bind('contacto');



        // -- CONTACTO ENVIADO ---------------------------------------------------------------------------------------------------------
        $frontend->get('/Gracias/', function () use ($app)
        {
            $posts = PostRepository::getAllPosts($app);

            return $app['twig']->render('frontend/gracias.twig', array(
                'articles'   => $posts,
            ));
        })
        ->bind('gracias');



        // -- POSTS ------------------------------------------------------------------------------------
        $frontend->get('/articulo/{id}', function($id) use ($app)
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
