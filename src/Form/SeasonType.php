<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Season;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', NumberType::class, [
                'label' => 'Numéro saison'
            ])
            ->add('episodesNumber', NumberType::class, [
                'label' => 'Nombre d\'épisodes'
            ])
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => 'title',
                'label' => 'Série'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
