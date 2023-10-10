<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serie', EntityType::class, [
                'class' => Serie::class,
                'choice_label' => 'name',
                'query_builder' => function(SerieRepository $serieRepository) {
                    return $serieRepository->createQueryBuilder('s')
                        ->addOrderBy('s.name', 'ASC');
                }
            ])
            ->add('number', NumberType::class, [
                'label' => 'Numéro de la Saison'
            ])
            ->add('firstAirDate', DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('overview',TextType::class, [
                'required' => false
            ])
            ->add('poster', TextType::class, [
                'required' => false
            ])
            ->add('tmdbId', NumberType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
