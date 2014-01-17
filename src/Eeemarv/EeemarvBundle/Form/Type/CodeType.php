<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Eeemarv\EeemarvBundle\Form\DataTransformer\CodeTransformer;


class CodeType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
		$codeTransformer = new CodeTransformer($this->om);
		$builder->addModelTransformer($codeTransformer);
    }

    public function getName()
    {
        return 'eeemarv_code_type';
    }
    
    public function getParent()
    {
		return 'text';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		parent::setDefaultOptions($resolver);
/*        $resolver->setDefaults(array(

        ));  		 */
	}  	

}

