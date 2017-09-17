<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\CanonicalStuff;
use App\Form\Type\StuffType;
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

        $builder->add('stuff', StuffType::class);
    }

    public function getParent()
    {
        return AbstractRuleType::class;
    }
}
