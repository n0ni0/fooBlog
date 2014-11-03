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

class backendController implements ControllerProviderInterface
{
  private function addPost($app, $formData) {
      $postToAdd = new Post($app);
      $postToAdd->setTitle($formData['titulo']);
      $postToAdd->setContent($formData['contenido']);

      $postToAdd->save();
  }

    public function connect(Application $app)
    {

    // Controladores relacionados con la parte de administración del sitio web
    $backend = $app['controllers_factory'];

    // Protección extra que asegura que al backend sólo acceden los administradores
    // no redirecciona a la portada si cancelas al introducir la contraseña
    $backend->before(function () use($app)
    {
      if(!$app['security']->isGranted('ROLE_ADMIN')){
        return new RedirectResponse($app['url_generator']->generate('portada'));
      }
    });


    // -- PORTADA --------------------------------------------------------------------------
    $backend->match('/', function() use($app)
    {
      $posts = PostRepository::getAllPosts($app);

      return $app['twig']->render('backend/listaArticulos.twig', array(
        'articles' => $posts
      ));
    })
    ->bind('backend');



    // -- CREAR articulo -------------------------------------------------------------------
    $backend->match('/creaArticulo/', function(Request $request) use($app)
    {
      // creamos los campos del formulario
      $form = $app['form.factory']->createBuilder('form')
        ->add('titulo', 'text', array(
            'label'     => 'Título',
            'required'    => true,
            'max_length'  => 255,
            'attr'      => array(
                'class' => 'span8',
            ) 
        ))
        ->add('contenido', 'textarea', array(
            'label'     => 'Contenido',
            'required'    => true,
            'max_length'  => 3000,
            'attr'      =>array(
                'class' => 'span8',
                'rows'  => '10',
            )
        ))
        ->getForm();
      // Si el método es POST ...
      if('POST' == $request->getMethod())
      { 
        //Asocia el formulario con los datos enviados por el usuario y dispara el mecanismo de validación
        $form->bind($request);

        if($form->isValid())
        {
          $this->addPost($app, $form->getData()); 
        }
      }
      // muestra el formulario
      return $app['twig']->render('backend/creaArticulo.twig', array ('form' => $form->createView()));
    })
    ->bind('nuevo-articulo');

    // - EDITAR articulo -------------------------------------------------------------------------------------------------------------
    $backend->match('/{id}/editar', function(Request $request, $id) use($app)
    {
      $post = Post::editPostById($id, $app);

      if(!$post)
      {
        return new RedirectResponse($app['url_generator']->generate('backend'));
      }

      $form = $app['form.factory']->createBuilder('form', $post)
        ->add('titulo', 'text', array(
          'label'     => 'Título',
          'required'    => true,
          'max_length'  => 255,
          'attr'      => array(
            'class'   => 'span8',
          )
        ))
        ->add('contenido', 'textarea', array(
          'label'     => 'Contenido',
          'required'    => false,
          'max_length'  => 2000,
          'attr'      => array(
            'class'   => 'span8',
            'rows'    => '10',
          )
        ))
        ->add('creado', 'text', array(
          'label'       => 'Fecha creación',
          'read_only'   => 'true',
          'attr'      => array(
            'class'   => 'span8',
          )
        ))
        ->getForm();

      if('POST' == $request->getMethod())
      {
        $form->bind($request);
        if($form->isValid())
        {
          $post = $form->getData();

          $app['db']->update('entrada',
            array('titulo' => $post['titulo'], 'contenido' => $post['contenido']),
            array('id' => $id)
          );

          return new RedirectResponse($app['url_generator']->generate('backend'));
        }
      }

      return $app['twig']->render('backend/editaArticulo.twig', array('articulo' => $post, 'form' => $form->createView()));

    })
    ->bind('editar-articulo');



    // - BORRAR articulo ----------------------------------------------------------------------------------------------------------
    $backend->match('/{id}/borrar', function($id) use($app)
    {
      $currentPost = new Post($app, $id);
      $currentPost->delete();
      return new RedirectResponse($app['url_generator']->generate('backend'));
    })
    ->bind('borrar-articulo');


    return $backend;
  }
}

?>
