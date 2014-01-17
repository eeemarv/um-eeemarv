<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Eeemarv\Eeemarv\Form\DataTransformer\ParentCategoryTransformer;
use Eeemarv\EeemarvBundle\Entity\Category;


class ParentCategoryType extends AbstractType
{

	/**
	 * @var EntityManager
	 **/
	
	private $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->addModelTransformer(new ParentCategoryTransformer($this->em));
    }

    public function getName()
    {
        return 'eeemarv_parent_category_type';
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
        $resolver->setDefaults(array(
            'class' => 'Eeemarv\EeemarvBundle\Entity\Category',
			'empty_value' => '', 
			'empty_data' => null,
			'required' => false,
            'invalid_message' => 'The selected parent category does not exist',
            'query_builder' => function(EntityRepository $er) {
				return $er->createQueryBuilder('c')
					->where('c.level <> 0')
					->orderBy('c.root, c.left', 'ASC');
				},
  
        ));
	}
	
	public function getParent()
	{
		return 'entity';
	} 	

}
