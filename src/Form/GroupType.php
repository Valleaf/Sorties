<?php


namespace App\Form;


use App\Entity\City;
use App\Entity\Group;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name');
        $editedObject = $builder->getData();
        $builder->add('members',EntityType::class,array(
                'class'=>'App\Entity\User',
                'multiple'=>true,
                'expanded'=>true,
                'query_builder'=> function(EntityRepository $er) {
                    return $er->createQueryBuilder('u');
                },
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Group::class]);
    }
}