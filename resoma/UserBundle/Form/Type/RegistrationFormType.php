<?php

namespace Resoma\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('nom', 'text', array(
                    'label'     => 'Nom : '));
        $builder->add('prenom', 'text', array(
                    'label'     => 'Prénom : '));
        $builder->add('avatar', 'hidden', array('data' => 'defaut.jpg'));
        $builder->add('tuto', 'hidden', array('data' => 0));
    }

    public function getName()
    {
        return 'resoma_user_registration';
    }
}