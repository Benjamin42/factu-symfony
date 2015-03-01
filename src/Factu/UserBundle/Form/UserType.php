<?php

namespace Factu\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login',            'text')
            ->add('nom',              'text')
            ->add('prenom',           'text', array('required' =>false))
            ->add('email',            'text')
            ->add('active',           'checkbox')
            ->add('password',           'text')
            ->add('roles', 'entity', array(
                  'class' => 'FactuUserBundle:Role',
                  'multiple' => true,
                  'expanded' => true,
                  'required' => true|false,
                  'property' => 'name',
                  'label' => 'name',
                  'error_bubbling' => true,
              ))
            ->add('save',               'submit')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Factu\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'factu_userbundle_user';
    }
}
