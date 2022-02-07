<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('release_date', DateType::class, [
                'label' => 'Date de sortie',
                'widget' => 'single_text',
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée du film',
                'invalid_message' => 'Entrez un nombre valide'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Format',
                'choices' => [
                    'Film' => 'Film',
                    'Série' => 'Série'
                ]
            ])
            ->add('synopsis', TextareaType::class)
            ->add('summary', TextareaType::class, [
                'label' => 'Résumé'
            ])
            ->add('rating', IntegerType::class, [
                'label' => 'Note',
                'attr' => [
                    'min' => 0,
                    'max' => 5
                ]
            ])
            ->add('poster', TextareaType::class)
            ->add('genres', EntityType::class, [
                'class' => Genre::class,      
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
