<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\Champion;
use App\Entity\Rule\Deity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChampionType extends AbstractRuleType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Champion::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('dignity', TextType::class)
            ->add('deity', EntityType::class, [
                'class' => Deity::class,
                'choice_value' => function ($deity) {
                    if (!$deity instanceof Deity) {
                        return null;
                    }
                    return $deity->getSlug();
                }
            ])
            ->add('description', TextareaType::class)
        ;
    }

    public function getParent()
    {
        return AbstractRuleType::class;
    }
}
