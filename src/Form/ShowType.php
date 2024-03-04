<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Show;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre",
                "empty_data" => ""
            ])
            ->add('duration', IntegerType::class, [
                "label" => "Durée",
                "empty_data" => ""
            ])
            ->add('summary', TextareaType::class, [
                "label" => "Sommaire",
                "empty_data" => ""
            ])
            ->add('synopsis', TextareaType::class, [
                "label" => "Synopsis",
                "empty_data" => ""
            ])
            ->add('type', ChoiceType::class, [
                "label" => "Type",
                "choices" => [
                    "Série" => "Série",
                    "Film" => "Film"
                ]
            ])
            ->add('releaseDate', DateType::class, [
                "label" => "Date de sortie"
            ])
            ->add('country', TextType::class, [
                "label" => "Pays",
                "empty_data" => ""
            ])
            ->add('poster', UrlType::class, [
                "label" => "Url de l'image",
                "empty_data" => ""
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Show::class,
        ]);
    }
}