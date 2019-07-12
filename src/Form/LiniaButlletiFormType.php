<?php

namespace App\Form;

use App\Entity\LiniaButlleti;
use App\Entity\Ordre;
use App\Repository\OrdreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiniaButlletiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('hores', NumberType::class)
            ->add('observacions')
            ->add('ordre', EntityType::class, [
                'class' => Ordre::class,
                'query_builder' => function (OrdreRepository $ordreRepository) use($user) {
                return $ordreRepository->findOrdresByUser($user)
                    ;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LiniaButlleti::class,
        ]);

        $resolver->setRequired('user');


    }
}
