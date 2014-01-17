<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class CommentType extends AbstractType
{

	
	public function __construct()
	{
	}


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'textarea')
			->add('lastCommentAt', 'hidden_datetime', array(
				'mapped' => false,
				))
			->add('create', 'submit', array(
				'attr'	=> array(
					'class' => 'btn btn-success top-10',
					'value' => 'button.create',
				)));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eeemarv\EeemarvBundle\Entity\Comment'
        ));
    }

    public function getName()
    {
        return 'eeemarv_comment_type';
    }
}
