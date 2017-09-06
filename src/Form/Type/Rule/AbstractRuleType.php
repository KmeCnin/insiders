<?php

namespace App\Form\Type\Rule;

use App\Form\Type\AbstractEntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AbstractRuleType extends AbstractEntityType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('id', IntegerType::class)
            ->add('slug', TextType::class)
            ->add('name', TextType::class)
            ->add('enabled', CheckboxType::class)
        ;
    }

    public function getParent()
    {
        return AbstractEntityType::class;
    }
}
