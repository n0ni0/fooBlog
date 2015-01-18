<?php 

namespace forms\type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class editPostType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder->add('title', 'text', array(
      'label'      => 'Título',
      'required'   => true,
      'max_length' => 255,
      'attr'       => array(
        'class'    => 'span8',
        )
    ));

    $builder->add('content', 'textarea', array(
      'label'      => 'Contenido',
      'required'   => false,
      'max_length' => 2000,
      'attr'       => array(
        'class'    => 'span8',
        'rows'     => '10',
       )
    ));

    $builder->add('created', 'text', array(
      'label'     => 'Fecha creación',
      'read_only' => 'true',
      'attr'      => array(
        'class'   => 'span8',
        )
    ));
  }


  public function getName()
  {
    return "editPost";
  }
}

