<?php

namespace Factu\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Factu\AppBundle\Entity\ProductRepository;

class CommandeProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qty',          'number', array('required' => false))
            ->add('qtyGift',      'number', array('required' => false))
            ->add('forcedPrice',  'number', array('required' => false))
            ->add('product',      'entity', array(
              'class'    => 'FactuAppBundle:Product',
              'query_builder' => function(ProductRepository $er) {
                  return $er->createQueryBuilder('u')
                      ->where('u.active=true');
              },
              'property' =>       'title',
              'multiple' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Factu\AppBundle\Entity\CommandeProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'factu_appbundle_commandeproduct';
    }
}
