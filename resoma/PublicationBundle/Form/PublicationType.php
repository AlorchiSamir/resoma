<?php

namespace Resoma\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PublicationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', 'text')
            ->add('texte', 'textarea')
            ->add('categorie', 'entity', array(
                    'class' => 'ResomaPublicationBundle:Categorie',
                    'property' => 'abreviation'
            ))            
            // ->add('public', 'checkbox', array(
            //         'label'     => 'Public ?',
            //         'required'  => false,
            //     ))
            ->add('lien', 'text')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Resoma\PublicationBundle\Entity\Publication'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'resoma_publicationbundle_publication';
    }
}
