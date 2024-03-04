<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                "label" => "Pseudo",
                "attr" => [
                    "placeholder" => "Jambon du 34"
                ]
            ])
            ->add('email', EmailType::class, [
                "label" => "Email",
                "attr" => [
                    "placeholder" => "jambon@gmail.com"
                ]
            ])
            ->add('content', TextareaType::class, [
                "label" => "Contenu"
            ])
            ->add('rating', ChoiceType::class, [
                "label" => "Note",
                "choices" => [
                    "Excellent" => 5,
                    "Très bien" => 4,
                    "Bien" => 3,
                    "Pas ouf" => 2,
                    "Une bouse" => 1
                ]
            ])
            ->add('reactions', ChoiceType::class, [
                "label" => "Vos sentiments sur le film",
                "choices" => [
                    'Rire' => 'smile',
                    'Pleurer' => 'cry',
                    'Réfléchir' => 'think',
                    'Dormir' => 'sleep',
                    'Rever' => 'dream',
                ],
                // choix multiple
                "multiple" => true,
                // boutons
                "expanded" => true,
                "help" => "Plusieurs choix possible"
            ])
            ->add('watchedAt', DateType::class, [
                "label" => "Date de visionnage"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}