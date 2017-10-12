<?php

namespace App\Form\Type\Rule;

use App\Entity\Rule\Increase;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncreaseType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Increase::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('short', TextType::class, [
                'empty_data' => '',
            ])
            ->add('description', CKEditorType::class, [
                'empty_data' => '',
            ])
        ;
    }
}
