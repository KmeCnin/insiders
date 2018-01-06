<?php

namespace App\Service;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Attribute;
use App\Entity\Rule\Burst;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\LexiconEntry;
use App\Entity\Rule\Page;
use App\Entity\Rule\RuleInterface;
use App\Entity\Rule\Skill;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class RulesAugmenter
{
    public const CREATE_MODALS = true;
    public const DO_NOT_CREATE_MODALS = false;

    private $em;
    private $twig;

    public function __construct(EntityManagerInterface $em, Environment $twig)
    {
        $this->em = $em;
        $this->twig = $twig;
    }

    public function augment(?string $text, bool $createModals = self::CREATE_MODALS): ?string
    {
        if (null === $text) {
            return null;
        }

        $replace = function (array $matches) use ($createModals) {
            try {
                [$type, $id] = explode(':', $matches[3]);
            } catch (\Exception $e) {
                throw new \Exception(sprintf(
                    'Augmentation badly formatted `%s` from string %s',
                    $matches[3],
                    $matches[0]
                ));
            }
            $template = 'default';
            switch ($type) {
                case Ability::CODE:
                    $template = Ability::CODE;
                    $namespace = Ability::class;
                    break;
                case Arcane::CODE:
                    $namespace = Arcane::class;
                    break;
                case Attribute::CODE:
                    $namespace = Attribute::class;
                    break;
                case Burst::CODE:
                    $namespace = Burst::class;
                    break;
                case Characteristic::CODE:
                    $namespace = Characteristic::class;
                    break;
                case LexiconEntry::CODE:
                    $namespace = LexiconEntry::class;
                    break;
                case Page::CODE:
                    $template = Page::CODE;
                    $namespace = Page::class;
                    break;
                case Skill::CODE:
                    $namespace = Skill::class;
                    break;
                default:
                    throw new \Exception(sprintf('Unrecognized entity identifier %s.', $type));
            }

            /** @var RuleInterface $entry */
            $entity = $this->em->getRepository($namespace)->find($id);
            if (null === $entity) {
                throw new \Exception(sprintf('%s with id %s does not exist.', $type, $id));
            }

            return $this->twig->render('includes/popovers/'.$template.'.html.twig', [
                'display' => $matches[2],
                'code' => $type,
                'entity' => $entity,
                'createModals' => $createModals,
            ]);
        };

        return preg_replace_callback('/(\[(.+)\])\((.+)\)/U', $replace, $text);
    }
}
