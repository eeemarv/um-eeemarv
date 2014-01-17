<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;


//@
use JMS\DiExtraBundle\Annotation\FormType;



/*
 * @FormType
 */
class ContactFormType extends AbstractType
{

	public function __construct()
	{

	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
		$builder				
			->add('email', 'email')
			->add('subject', 'text')
			->add('message', 'textarea')
			->add('recaptcha', 'ewz_recaptcha', array(
				'attr' => array('options' => array('theme' => 'clean')),
				'mapped' => false,
				'constraints'   => array(new True())))
			->add('send', 'submit');
    }

    public function getName()
    {
        return 'eeemarv_contact_type';
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
        $collectionConstraint = new Collection(array(
            'email' => array(
                new NotBlank(),
                new Email()
            ),
            'subject' => array(
                new NotBlank(),
                new Length(array('min' => 3))
            ),
            'message' => array(
                new NotBlank(),
                new Length(array('min' => 5))
			)
		));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
	}  	

}
