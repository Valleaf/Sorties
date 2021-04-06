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
        $future = new \DateTime();
        $future = date_add($future, date_interval_create_from_date_string('10 days'));
        $future->setTime(21,30);

        $builder
            ->add('name',TextType::class,[
                'data'=>'Meeting at the Bar-a-Go-Go',
                'help' => 'The name of your get-together',
            ])
            ->add('timeStarting', DateTimeType::class,[
                'years'=>[2021,2022],
                'html5'=>true
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
            ->add('maxParticipants',NumberType::class,[
                'empty_data' => 10,
                'scale' => 0,
            ])
            ->add('information',)
            ->add('registerUntil',DateTimeType::class,[
                'years'=>[2021,2022],
                ])
            ->add('place',EntityType::class,array(
                'class'=>'App\Entity\Place',
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
