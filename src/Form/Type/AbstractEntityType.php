<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class AbstractEntityType extends AbstractType
{
    public function getParent()
    {
        return FormType::class;
    }
}
