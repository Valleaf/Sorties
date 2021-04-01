<?php


namespace App\Form;


use App\Entity\Place;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',)
            ->add('adress',)
            ->add('city',CityType::class)
            ->add('longitude',NumberType::class ,[
                'scale'=>5])
            ->add('latitude',NumberType::class ,[
                'scale'=>5]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(['data_class'=>Place::class]);
    }

}