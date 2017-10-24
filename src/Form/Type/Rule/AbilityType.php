<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Increase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbilityType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Ability::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('arcane', EntityType::class, [
                'class' => Arcane::class,
            ])
            ->add('abilitiesRequired', EntityType::class, [
                'class' => Ability::class,
                'multiple' => true,
            ])
            ->add('short', TextType::class, [
                'empty_data' => '',
            ])
            ->add('description', TextareaType::class, [
                'empty_data' => '',
            ])
            ->add('increases', CollectionType::class, [
                'entry_type' => IncreaseType::class,
                'empty_data' => new Increase(),
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function getParent(): ?string
    {
        return AbstractRuleType::class;
    }
}
