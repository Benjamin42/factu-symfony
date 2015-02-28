<?php

namespace Factu\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numClient',      'number')
            ->add('numTva',         'text', array('required' => false))
            ->add('civilite', 'entity', array(
              'class'    => 'FactuAppBundle:Type',
              'property' => 'title',
              'multiple' => false
            ))
            ->add('nom',            'text')
            ->add('nomInfo',        'text', array('required' => false))
            ->add('prenom',         'text', array('required' => false))
            ->add('tel',            'text', array('required' => false))
            ->add('portable',       'text', array('required' => false))
            ->add('fax',            'text', array('required' => false))
            ->add('email',          'email', array('required' => false))
            ->add('commentaire',    'textarea', array('required' => false))
            ->add('rue',            'text')
            ->add('bat',            'text', array('required' => false))
            ->add('bp',             'text', array('required' => false))
            ->add('codePostal',     'text', array('required' => false))
            ->add('ville',          'text')
            ->add('pays', 'entity', array(
              'class'    => 'FactuAppBundle:Country',
              'property' => 'name',
              'multiple' => false
            ))
            ->add('save',           'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Factu\AppBundle\Entity\Client'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'factu_appbundle_client';
    }
}
