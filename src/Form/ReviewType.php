<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Pseudonyme'])
            ->add('email')
            ->add('content', TextareaType::class, ['label' => 'Texte'])
            ->add('rating', ChoiceType::class, [
                'label' => 'Avis',
                'choices'  => [
                    'Nul' => 1,
                    'Bof' => 2,
                    'Bon' => 3,
                    'Très bon' => 4,
                    'Excellent' => 5,
                ],

            ])
            ->add('reactions', ChoiceType::class, [
                'label' => 'Ce film vous a fait...',  
                'choices'  => [
                    'Rire' => 'smile',
                    'Pleurer' => 'Pleurer',
                    'Réfléchir' => 'Réfléchir',
                    'Dormir' => 'Dormir',
                    'Rêver' => 'Rêver',
                ],
                'expanded' => true,
                'multiple' => true,

            ])
            ->add('watchedAt', DateType::class, [
                'label' => 'Vu le :',
                'widget' => 'single_text',

                ])
            ->add('Poster', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
