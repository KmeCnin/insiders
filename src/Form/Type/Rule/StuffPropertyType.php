<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\StuffKind;
use App\Entity\Rule\StuffProperty;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('price', IntegerType::class, [
                'empty_data' => '',
            ])
            ->add('short', TextType::class, [
                'empty_data' => '',
            ])
            ->add('stuffKinds', EntityType::class, [
                'class' => StuffKind::class,
                'multiple' => true,
                'choice_value' => function ($kind) {
                    if (!$kind instanceof StuffKind) {
                        return null;
                    }
                    return $kind->getSlug();
                }
            ])
        ;
    }

    public function getParent()
    {
        return AbstractRuleType::class;
    }
}
