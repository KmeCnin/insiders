<?php

namespace App\Service;

use App\Entity\Rule\Ability;
use App\Entity\Rule\LexiconEntry;
use App\Entity\Rule\RuleInterface;
use App\Entity\Rule\Skill;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class RulesAugmenter
{
    private $em;
    private $twig;

    public function __construct(EntityManagerInterface $em, Environment $twig)
    {
        $this->em = $em;
        $this->twig = $twig;
    }

    public function augment(?string $text): ?string
    {
        if (null === $text) {
            return null;
        }

        $replace = function (array $matches) {
            [$type, $id] = explode(':', $matches[3]);
            $template = 'default';
            switch ($type) {
                case 'ability':
                    $template = 'ability';
                    $namespace = Ability::class;
                    break;
                case 'lexicon':
                    $namespace = LexiconEntry::class;
                    break;
                case 'skill':
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

            return $this->twig->render('includes/popovers/'.$template.'.html.twig', array(
                'display' => $matches[2],
                'entity' => $entity,
            ));
        };

        return preg_replace_callback('/(\[(.+)\])\((.+)\)/U', $replace, $text);
    }
}
