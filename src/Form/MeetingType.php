<?php

namespace App\Form;

use App\Entity\Meeting;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('name',TextType::class,[
                'data'=>'Soirée au Bar-a-Go-Go',
                'help' => 'Le nom de la sortie',
                'label'=>'Nom',
            ])
            ->add('timeStarting', DateTimeType::class,[
                'years'=>[2021,2022],
                'label'=>'Debut de la soirée',
            ])
            ->add('length',ChoiceType::class,[
                'choices'  => [
                    '30 minutes' => 30,
                    '1 Heure' => 60,
                    '1 Heure & 30 minutes' => 90,
                    '2 heures' => 120,
                    '2 heures & 30 minutes' => 150,
                    '3 heures' => 180,
                    '3 heures & 30 minutes' => 210,
                    '4 heures' => 240,
                    '4 heures & 30 minutes' => 270,
                    '5 heures' => 300,
                    '5 heures & 30 minutes' => 330,
                    '6 heures' => 360,
                    '6 heures & 30 minutes' => 390,
                    '7 heures' => 420,
                    '7 heures & 30 minutes' => 450,
                    '8 heures' => 480,
                    '8 heures & 30 minutes' => 510,
                    '9 heures' => 540,
                    '9 heures & 30 minutes' => 570,
                    '10 heures' => 600,
            ],
                'label' => 'Durée',
            ])
            ->add('maxParticipants',NumberType::class,[
                'data' => 10,
                'scale' => 0,
                'label'=>'Participants maximum',
                'invalid_message'=>'Nombre de participants maximum entre 8 et 300'
            ])
            ->add('information',TextType::class,[
                'help'=>'Résumé de la sortie',
                'invalid_message'=>'Au minimum 10 caractères'
            ])
            ->add('registerUntil',DateTimeType::class,[
                'years'=>[2021,2022],
                'label'=>'Date limite d\'inscription',
            ])
            ->add('place',EntityType::class,array(
                'class'=>'App\Entity\Place',
                'label'=>'Lieu',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c');
                },
            ));
          //  ->add('place',PlaceType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Meeting::class]);
    }


}
