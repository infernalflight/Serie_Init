<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Serie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => false,
            ])
            ->add('included', IncludedType::class, [
                'inherit_data' => true,
                'label' => false
            ])
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
            ->add('lastAirDate', DateType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('backdrop', TextType::class, [

            ])
            ->add('poster_file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Ce format n\'est pas OK',
                        'maxSizeMessage' => 'Ce fichier est trop lourd. Max: 1Mo'
                    ])
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
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
