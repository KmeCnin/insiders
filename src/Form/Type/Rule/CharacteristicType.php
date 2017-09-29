<?php

namespace App\Form\Type\Rule;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Rule\Characteristic;

class CharacteristicType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Characteristic::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
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
