<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\StuffKind;
use App\Entity\Rule\StuffProperty;
use App\Entity\Rule\StuffPropertyKind;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StuffPropertyType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StuffProperty::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('fp', NumberType::class)
            ->add('short', TextType::class, [
                'empty_data' => '',
            ])
            ->add('description', TextareaType::class, [
                'empty_data' => '',
            ])
            ->add('kind', EntityType::class, [
                'class' => StuffPropertyKind::class,
            ])
            ->add('stuffKinds', EntityType::class, [
                'class' => StuffKind::class,
                'multiple' => true,
            ])
        ;
    }

    public function getParent()
    {
        return AbstractRuleType::class;
    }
}
