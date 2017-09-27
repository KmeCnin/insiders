<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\StuffPropertyKind;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StuffPropertyKindType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StuffPropertyKind::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    public function getParent()
    {
        return AbstractRuleType::class;
    }
}
