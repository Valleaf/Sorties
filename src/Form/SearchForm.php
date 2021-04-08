<?php


namespace App\Form;


use App\Data\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q',TextType::class,[
                'label'=>false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Rechercher'
                ]
            ])
            ->add('campus',EntityType::class,[
                'label'=>false,
                'required'=>false,
                'class'=>Campus::class,
            ])
            ->add('min', DateType::class,[
                'label'=>false,
                'required'=>false,
                'years'=>[2021,2022],
                'widget'=>'single_text',
                'attr'=>[
                    'placeholder'=>'Entre '
                ]
            ])
            ->add('max', DateType::class,[
                'label'=>false,
                'required'=>false,
                'years'=>[2021,2022],
                'attr'=>[
                    'placeholder'=>'et  '
                ]
            ])
            ->add('isOrganizedBy', CheckboxType::class,[
                'label'=>false,
                'required'=>false,
            ])
            ->add('isRegisteredTo', CheckboxType::class,[
                'label'=>false,
                'required'=>false,
            ])
            ->add('isNotRegisteredTo', CheckboxType::class,[
                'label'=>false,
                'required'=>false,
            ])
            ->add('isOver', CheckboxType::class,[
                'label'=>false,
                'required'=>false,
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class'=>SearchData::class,
            'method'=>'GET',
            'csrf_protection'=>false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}