<?php


namespace App\Form;


use App\Entity\Place;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',)
            ->add('adress',)
            ->add('longitude',NumberType::class ,[
                'scale'=>5])
            ->add('latitude',NumberType::class ,[
                'scale'=>5])
            ->add('city',EntityType::class,array(
                'class'=>'App\Entity\City',
                'query_builder'=>function (EntityRepository $er){
                return $er->createQueryBuilder('c');
                },
    ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(['data_class'=>Place::class]);
    }

}