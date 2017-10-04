<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\Arcane;
use App\Entity\Rule\Burst;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BurstType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Burst::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('arcane', EntityType::class, [
                'class' => Arcane::class,
                'choice_value' => function ($arcane) {
                    if (!$arcane instanceof Arcane) {
                        return null;
                    }
                    return $arcane->getSlug();
                }
            ])
            ->add('short', TextType::class, [
                'empty_data' => '',
            ])
            ->add('description', TextareaType::class, [
                'empty_data' => '',
            ])
        ;
    }

    public function getParent()
    {
        return AbstractRuleType::class;
    }
}
