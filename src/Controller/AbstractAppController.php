<?php

namespace App\Controller;

use App\Entity\Rule\Ability;
use App\Entity\Rule\LexiconEntry;
use App\Entity\Rule\RuleInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractAppController extends AbstractController
{
    protected function augment(?string $text): ?string
    {
        if (null === $text) {
            return null;
        }

        $replace = function (array $matches) {
            [$type, $id] = explode(':', $matches[3]);
            $template = 'default';
            switch ($type) {
                case 'lexicon':
                    $namespace = LexiconEntry::class;
                    break;
                case 'ability':
                    $template = 'ability';
                    $namespace = Ability::class;
                    break;
                default:
                    throw new \Exception(sprintf('Unrecognized entity identifier %s.', $type));
            }

            /** @var RuleInterface $entry */
            $entity = $this->getDoctrine()->getRepository($namespace)->find($id);
            if (null === $entity) {
                throw new \Exception(sprintf('%s with id %s does not exist.', $type, $id));
            }

            return $this->renderView('includes/popovers/'.$template.'.html.twig', array(
                'display' => $matches[2],
                'entity' => $entity,
            ));
        };

        return preg_replace_callback('/(\[(.+)\])\((.+)\)/U', $replace, $text);
    }
}
