<?php

namespace forms\type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class registerPostType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('name', 'text', array(
      'label'           => 'Nombre',
        'required'      => true,
        'max_length'    => 100,
        'attr'          => array(
          'class'       => 'span8',
        )
    ));

   $builder->add('surnames', 'text', array(
      'label'           => 'Apellidos',
        'required'      => true,
        'max_length'    => 100,
        'attr'          => array(
          'class'       => 'span8',
        )
      ));

     $builder->add('mail', 'email', array(
      'label'           => 'Email')
    );

     $builder->add('username', 'text', array(
      'label'           => 'Nombre de usuario',
        'required'      => true,
        'max_length'    => 100,
        'attr'          => array(
          'class'       => 'span8',
        )
      ));


     $builder->add('password', 'repeated', array(
       'type'               => 'password',
       'invalid_message'    => 'Los campos de la contraseña deben de ser iguales.',
       'options'            => array(
       'attr'               => array(
        'class'             => 'password-field')),
       'required'           => true,
       'max_length'         => 20,
       'attr'               => array(
        'class'             => 'span8'),
       'first_options'      => array(
        'label'             => 'Contraseña'),
       'second_options'     => array(
        'label'             => 'Repita contraseña'),
     ));

     $builder->add('roles', 'hidden', array(
      'data'         => 'ROLE_USER',
     ));

  }

  public function getName()
  {
    return 'Register';
  }
}

?>
