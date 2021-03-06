<?php 

namespace forms\type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class newPostType extends AbstractType
{
	public function buildForm (FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text', array(
            'label'     => 'Título',
            'required'    => true,
            'max_length'  => 255,
            'attr'      => array(
                'class' => 'span8',
            ) 
        ));

        $builder->add('content', 'textarea', array(
            'label'     => 'Contenido',
            'required'    => true,
            'max_length'  => 3000,
            'attr'      =>array(
                'class' => 'span8',
                'rows'  => '10',
            )
        ));
	}

	public function getName()
	{
		return "newPost";
	}
}


?>