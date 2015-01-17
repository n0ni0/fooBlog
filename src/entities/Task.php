<?php 

namespace entities;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Task
{
  protected $name;
  protected $mail;
  protected $title;
  protected $content;

  public function getName()
  {
    return $this->name;
  }

  public function getMail()
  {
    return $this->mail;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getContent()
  {
    return $this->content;
  }


  public function setName()
  {
    $this->name = $name;
    return $this;
  }

  public function setMail()
  {
    $this->mail = $mail;
    return $this;
  }

  public function setTitle()
  {
    $this->title = $title;
    return $this;
  }

  public function setContent()
  {
    $this->content = $contens;
    return $this;
  }

  public function addContact($app, $form)
  {
    $app['mailer']->send(\Swift_Message::newInstance()
                  ->setSubject('Formulario de conctacto blog silex')
                  ->setFrom(array('ajimenez.bf@gmail.com' => 'noreply@gmail.com'))
                  ->setTo(array('ajimenez.bf@ono.com'))
                  ->setBody($app['twig']->render('frontend/email.twig',
                     array('name'    => $form['name'],
                           'mail'    => $form['mail'],
                           'title'    => $form['title'],
                           'content'   => $form['content'],
                         )),
                  'text/html'
                    ));
  }


  public function thanksForContact($app)
  {
     return new RedirectResponse($app['url_generator']->generate('thanks'));
  }

}
?>
