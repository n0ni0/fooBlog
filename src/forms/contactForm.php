<?php 

namespace form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class contactForm extends AbstractType
{
	public  function buildForm(FormBuilderInterface $form, array $options)
	{
		$form->add('nombre', 'text', array(
			'label'         => 'Nombre',
            'required'      => true,
            'max_length'    => 100,
            'attr'          => array(
            	'class'     => 'span8',
            	)
            ));

		$form->add('correo', 'email', array()
            );
        
        $form->add('asunto', 'text', array(
                'label'         => 'Asunto',
                'required'      => true,
                'max_length'    => 200,
                'attr'          => array(
                	'class'     => 'span8',
                    )
                ));

        $form->add('mensaje', 'textarea', array(
                'label'         => 'Mensaje',
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
	return 'datos';
	}
}

?>