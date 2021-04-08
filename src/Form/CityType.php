<?php


namespace App\Form;


use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'data'=>'Lyon',
                'label'=>'Nom de la ville',
                'help'=>'La ville doit avoir un nom'
            ])
            ->add('postcode',NumberType::class,[
                'scale'=>0,
                'data'=>69130,
                'label'=>'Code Postal',
                'invalid_message'=>'Le code postal doit comporter 5 chiffres'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => City::class]);
    }
}