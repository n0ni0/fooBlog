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

class frontendController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {

        $frontend = $app['controllers_factory'];

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
        $frontend->match('/contacto/', function (Request $request) use ($app)
        {
            $posts = PostRepository::getAllPosts($app);

            $form = $app['form.factory']->createBuilder('form')
                ->add('nombre', 'text', array(
                    'label'         => 'Nombre',
                    'required'      => true,
                    'max_length'    => 100,
                    'attr'          => array(
                        'class'     => 'span8',
                        )
                ))
                ->add('correo', 'email', array()
                )
                ->add('asunto', 'text', array(
                    'label'         => 'Asunto',
                    'required'      => true,
                    'max_length'    => 200,
                    'attr'          => array(
                        'class'     => 'span8',
                        )
                ))
                ->add('mensaje', 'textarea', array(
                    'label'         => 'Mensaje',
                    'required'      => true,
                    'max_length'    => 2500,
                    'attr'          => array(
                        'class'     => 'span8',
                        'rows'      => '10'
                        )
                ))
                ->getForm();

            if('POST' == $request->getMethod()) 
            {
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
                        array('nombre'    => $datos['nombre'],
                              'correo'    => $datos['correo'],
                              'asunto'    => $datos['asunto'],
                              'mensaje'   => $datos['mensaje'],

                        )), 'text/html'
                        ));
                }
            // -- Vista del formulario antes de enviar el correo
            return $app['twig']->render('frontend/contacto.twig', array(
                'mensaje' => 'Mensaje enviado, te responderemos lo antes posible.',
                'form'    => $form->createView(),
                ''
                ));

            }

            // -- Vista del formulario después de enviar el correo
            return $app['twig']->render('frontend/contacto.twig', array(
                'mensaje'  => 'Formulario de contacto:',
                'form'     => $form->createView(),
                'articles' => $posts,
            ));    

        })
        ->bind('contacto');



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