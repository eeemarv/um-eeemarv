<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('postalCode')
            ->add('name')
            ->add('country', 'country', array(
              'empty_value' => ''));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eeemarv\EeemarvBundle\Entity\Place'
        ));
    }

    public function getName()
    {
        return 'eeemarv_place_type';
    }
}
