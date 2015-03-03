<?php

namespace Factu\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BdlType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numBdl',           'number', array('required' => false))
            ->add('title',            'text')
            ->add('dateBdl',          'date', array('required' => false, 'widget' =>'single_text', 'format' =>'dd/MM/yyyy'))
            ->add('toDelivered',      'checkbox', array('required' => false))
            ->add('isDelivered',        'checkbox', array('required' => false))
            ->add('dateDelivered',      'date', array('required' => false, 'widget' =>'single_text', 'format' =>'dd/MM/yyyy'))
            ->add('save',               'submit')
            ->add('client', 'entity', array(
              'class'    => 'FactuAppBundle:Client',
              'property' => 'formatedLabel',
              'multiple' => false
            ))
            ->add('commandeProducts', 'collection', array(
                'type' => new CommandeProductType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('commandeServices', 'collection', array(
                'type' => new CommandeServiceType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Factu\AppBundle\Entity\Bdl'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'factu_appbundle_bdl';
    }
}
