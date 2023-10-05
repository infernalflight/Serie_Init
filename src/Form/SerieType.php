<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => false,
            ])
            ->add('overview')
            ->add('status', ChoiceType::class, [
                'label' => 'Status of the série',
                'placeholder' => 'Choose a status',
                'choices' => [
                    'Returning serie' => 'returning',
                    'ended serie' => 'ended',
                    'canceled serie' => 'canceled',
                ],
            ])
            ->add('popularity')
            ->add('vote', TextType::class, [
                'required' => true,
            ])
            ->add('genres')
            ->add('firstAirDate', DateType::class, [
                'required' => true,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('lastAirDate', DateType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('backdrop', TextType::class, [

            ])
            ->add('poster')
            ->add('tmdbId')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
