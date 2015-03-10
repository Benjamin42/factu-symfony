<?php

namespace Factu\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Factu\AppBundle\Entity\ServiceRepository;

class CommandeServiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amt')
            ->add('service', 'entity', array(
              'class'    => 'FactuAppBundle:Service',
              'query_builder' => function(ServiceRepository $er) {
                  return $er->createQueryBuilder('u')
                      ->where('u.active=true');
              },
              'property' => 'title',
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
            'data_class' => 'Factu\AppBundle\Entity\CommandeService'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'factu_appbundle_commandeservice';
    }
}
