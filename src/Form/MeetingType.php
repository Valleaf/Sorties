<?php

namespace App\Form;

use App\Entity\Meeting;
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
                'data'=>'Meeting at the Bar-a-Go-Go',
                'help' => 'The name of your get-together',
            ])
            ->add('timeStarting', DateTimeType::class,[
                'years'=>[2021,2022]
            ])
            ->add('length',ChoiceType::class,[
                'choices'  => [
                    '30 minutes' => 30,
                    '1 Hour' => 60,
                    '1 hour & 30 minutes' => 90,
                    '2 hours' => 120,
                    '2 hours & 30 minutes' => 150,
                    '3 hours' => 180,
                    '3 hours & 30 minutes' => 210,
                    '4 hours' => 240,
                    '4 hours & 30 minutes' => 270,
                    '5 hours' => 300,
                    '5 hours & 30 minutes' => 330,
                    '6 hours' => 360,
                    '6 hours & 30 minutes' => 390,
                    '7 hours' => 420,
                    '7 hours & 30 minutes' => 450,
                    '8 hours' => 480,
                    '8 hours & 30 minutes' => 510,
                    '9 hours' => 540,
                    '9 hours & 30 minutes' => 570,
                    '10 hours' => 600,
            ],
                'help' => 'Duration',
            ])
            ->add('maxParticipants',NumberType::class)
            ->add('information',)
            ->add('registerUntil',DateTimeType::class)
            ->add('place',PlaceType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Meeting::class]);
    }


}
