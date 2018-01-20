<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StuffCategoryRepository")
 */
class StuffCategory extends AbstractRule
{
    public const CODE = 'stuff_category';

    use ShortTrait;
    use DescriptionTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setShort('');
        $this->setDescription('');
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
        ]);
    }
}
