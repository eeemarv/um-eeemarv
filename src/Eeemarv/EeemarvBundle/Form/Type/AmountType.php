<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

use Eeemarv\EeemarvBundle\Form\DataTransformer\CurrencyTransformer;

class AmountType extends AbstractType
{
    /**
     * @var integer
     */
    private $currencyRate;

    /**
     * @param integer $currencyRate
     */
    public function __construct($currencyRate)
    {
        $this->currencyRate = $currencyRate;
    }	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
		$currencyTransformer = new CurrencyTransformer($this->currencyRate);
		$builder->addModelTransformer($currencyTransformer);
    }

    public function getName()
    {
        return 'eeemarv_amount_type';
    }
    
    public function getParent()
    {
		return 'number';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		parent::setDefaultOptions($resolver);
		$resolver->setDefaults(array(
				'data_precision' => 0,
        ));  		
	}  	

}

