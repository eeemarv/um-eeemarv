<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class PageType extends AbstractType
{
	
	public function __construct()
	{
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject')                   
            ->add('content', 'eeemarv_ckeditor_type', array(
				'config_name' => 'eeemarv_page',
				))
			->add('create', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eeemarv\EeemarvBundle\Entity\Page'
        ));
    }

    public function getName()
    {
        return 'eeemarv_page_type';
    }
}
