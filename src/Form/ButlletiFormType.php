<?php

namespace App\Form;

use App\Entity\Butlleti;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButlletiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('dataButlleti', DateType::class, [
                'attr' => [
                    'class' => 'datePicker',
                    'autocomplete' => 'off',
                ],
                'widget' => 'single_text',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
            ])
            ->add('liniesButlleti', CollectionType::class, [
                'entry_type' => LiniaButlletiFormType::class,
                'entry_options' => [
                    'label' => false,
                    'user' => $user
                ],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Butlleti::class,
        ]);

        $resolver->setRequired('user');
    }
}
