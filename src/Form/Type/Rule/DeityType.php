<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\Arcane;
use App\Entity\Rule\Champion;
use App\Entity\Rule\Deity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeityType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Deity::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('dignity', TextType::class, [
                'empty_data' => '',
            ])
            ->add('arcane', EntityType::class, [
                'class' => Arcane::class,
                'choice_value' => function ($arcane) {
                    if (!$arcane instanceof Arcane) {
                        return null;
                    }
                    return $arcane->getSlug();
                }
            ])
            ->add('champion', EntityType::class, [
                'class' => Champion::class,
                'choice_value' => function ($champion) {
                    if (!$champion instanceof Champion) {
                        return null;
                    }
                    return $champion->getSlug();
                }
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
