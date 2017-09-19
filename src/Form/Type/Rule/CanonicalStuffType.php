<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\StuffKind;
use App\Entity\Rule\StuffProperty;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CanonicalStuffType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CanonicalStuff::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('kind', EntityType::class, [
                'class' => StuffKind::class,
                'choice_value' => function ($kind) {
                    if (!$kind instanceof StuffKind) {
                        return null;
                    }
                    return $kind->getSlug();
                }
            ])
            ->add('expendable', CheckboxType::class)
            ->add('effectiveness', IntegerType::class)
            ->add('properties', EntityType::class, [
                'class' => StuffProperty::class,
                'multiple' => true,
                'choice_value' => function ($property) {
                    if (!$property instanceof StuffProperty) {
                        return null;
                    }
                    return $property->getSlug();
                }
            ])
        ;
    }

    public function getParent()
    {
        return AbstractRuleType::class;
    }
}
