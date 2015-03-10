<?php

namespace Factu\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Factu\AppBundle\Entity\BdlRepository;

class CommandeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numFactu',           'number', array('required' => false))
            ->add('bdl', 'entity', array(
              'class'    => 'FactuAppBundle:Bdl',
              'query_builder' => function(BdlRepository $er) {
                  return $er->createQueryBuilder('u')
                      ->orderBy('u.dateBdl', 'DESC');
              },
              'property' => 'formatedLabel',
              'multiple' => false,
              'required' => false
            ))
            ->add('dateFactu',          'date', array('required' => false, 'widget' =>'single_text', 'format' =>'dd/MM/yyyy'))
            ->add('toDelivered',            'checkbox', array('required' => false))
            ->add('isPayed',            'checkbox', array('required' => false))
            ->add('isDelivered',        'checkbox', array('required' => false))
            ->add('datePayed',          'date', array('required' => false, 'widget' =>'single_text', 'format' =>'dd/MM/yyyy'))
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
            'data_class' => 'Factu\AppBundle\Entity\Commande'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'factu_appbundle_commande';
    }
}
