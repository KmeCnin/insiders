<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page extends AbstractRule
{
    use DescriptionTrait;

    public const CODE = 'page';

    public function __construct()
    {
        parent::__construct();

        $this->setDescription('');
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'description' => $this->getDescription(),
        ]);
    }
}
