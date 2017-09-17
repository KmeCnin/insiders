<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\StuffKind;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StuffKindType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StuffKind::class,
        ));
    }

    public function getParent()
    {
        return AbstractRuleType::class;
    }
}
