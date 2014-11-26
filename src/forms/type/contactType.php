<?php 

namespace forms\type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class contactType extends AbstractType
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

    $builder->add('mail', 'email', array(
    'label'              => 'Email')
    );

    $builder->add('title', 'text', array(
      'label'             => 'Asunto',
        'required'        => true,
        'max_length'      => 200,
        'attr'            => array(
          'class'         => 'span8',
        )
    ));

    $builder->add('content', 'textarea', array(
      'label'           => 'Mensaje',
        'required'      => true,
        'max_length'    => 2500,
        'attr'          => array(
          'class'     => 'span8',
          'rows'      => '10'
          )
    ));

}

public function getName()
{
  return 'Task';
}
}

?>
