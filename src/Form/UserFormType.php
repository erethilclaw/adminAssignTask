<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_INACTIVE = 'ROLE_INACTIVE';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pickcode')
            ->add('name')
            ->add('surenames')
            ->add('password', PasswordType::class, [
                'help' => 'min: 7 caràcters, 1 nùmeric, 1 majùscula',
                'empty_data' => ''
            ])
            ->add('roles', ChoiceType::class, [
                'choices'=> [
                    'Usuari' => self::ROLE_USER,
                    'Admin' => self::ROLE_ADMIN,
                    'Inactiu' => self::ROLE_INACTIVE
                ],
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}