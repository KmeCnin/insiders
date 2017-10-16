<?php

namespace App\Form\Type;

use App\Entity\Rule\StuffKind;
use App\Entity\Rule\StuffProperty;
use App\Entity\Stuff;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StuffType extends AbstractEntityType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Stuff::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class)
            ->add('kind', EntityType::class, [
                'class' => StuffKind::class,
            ])
            ->add('expendable', CheckboxType::class)
            ->add('effectiveness', IntegerType::class)
            ->add('properties', EntityType::class, [
                'class' => StuffProperty::class,
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function getParent()
    {
        return AbstractEntityType::class;
    }
}
