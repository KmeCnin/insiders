<?php

namespace App\Service;

use App\Entity\Rule\RuleInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Templating\EngineInterface;

class RulesAugmenter
{
    private $em;
    private $rulesHub;
    private $template;

    public function __construct(
        EntityManagerInterface $em,
        RulesHub $rulesHub,
        EngineInterface $template
    ) {
        $this->em = $em;
        $this->rulesHub = $rulesHub;
        $this->template = $template;
    }

    public function augment(?string $text, string $modal = null): ?string{
        if (null === $text) {
            return null;
        }

        $replace = function (array $matches) use ($modal) {
            try {
                [$code, $id] = explode(':', $matches[3]);
            } catch (\Exception $e) {
                throw new \Exception(sprintf(
                    'Augmentation badly formatted `%s` from string %s',
                    $matches[3],
                    $matches[0]
                ));
            }

            /** @var RuleInterface $entry */
            $entity = $this->em->getRepository($this->rulesHub->codeToClass($code))->find($id);
            if (null === $entity) {
                throw new \Exception(sprintf('%s with id %s does not exist.', $code, $id));
            }

            $params = [
                'display' => $matches[2],
                'entity' => $entity,
                'modal' => $modal,
            ];

            if ($this->template->exists(sprintf('includes/popovers/%s.html.twig', $code))) {
                return $this->template->render(
                    sprintf('includes/popovers/%s.html.twig', $code),
                    $params
                );
            }

            return $this->template->render(
                'includes/popovers/rule.html.twig',
                $params
            );
        };

        return preg_replace_callback('/(\[(.+)\])\((.+)\)/U', $replace, $text);
    }
}
