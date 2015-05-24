<?php

namespace Resoma\ProfilBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParametreType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', 'textarea', array('required'  => false))
            ->add('file', 'file', array('required'  => false, 'label' => 'Avatar'))
            ->add('disabled', 'checkbox', array('label' => 'Désactiver', 'required'  => false));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Resoma\ProfilBundle\Entity\Parametre'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'resoma_profilbundle_parametre';
    }
}