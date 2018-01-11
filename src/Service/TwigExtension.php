<?php

namespace App\Service;

use App\Entity\Rule\AbstractRule;
use App\Entity\Rule\RuleInterface;
use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwigExtension extends \Twig_Extension
{
    private static $modals = [];

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('markdown', [$this, 'markdownFilter']),
            new \Twig_SimpleFilter('augment', [$this, 'augmentFilter']),
            new \Twig_SimpleFilter('augment_in_modal', [$this, 'augmentInModalFilter']),
            new \Twig_SimpleFilter('clear', [$this, 'clearFilter']),
            new \Twig_SimpleFilter('roman', [$this, 'romanFilter']),
            new \Twig_SimpleFilter('rule_link', [$this, 'ruleLinkFilter']),
            new \Twig_SimpleFilter('create_rule_modal', [$this, 'createRuleModal']),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('render_rule_modals', [$this, 'renderRuleModals']),
        ];
    }

    public function markdownFilter($html)
    {
        $converter = new HtmlConverter();
        return strip_tags($converter->convert($this->removeAugmentation($html)));
    }

    public function augmentFilter(?string $text): ?string
    {
        return $this->container->get(RulesAugmenter::class)
            ->augment($text, RulesAugmenter::CREATE_MODALS);
    }

    public function augmentInModalFilter(?string $text): ?string
    {
        return $this->container->get(RulesAugmenter::class)
            ->augment($text, RulesAugmenter::DO_NOT_CREATE_MODALS);
    }

    public function clearFilter(?string $text): ?string
    {
        return $this->removeAugmentation($text);
    }

    public function ruleLinkFilter(AbstractRule $rule): ?string
    {
        return $this->container->get(RulesHub::class)->linkFromEntity($rule);
    }

    public function romanFilter(?int $n): ?string
    {
        $res = '';
        $romanNumber_Array = [
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1,
        ];

        foreach ($romanNumber_Array as $roman => $number){
            $matches = (int) ($n / $number);
            $res .= str_repeat($roman, $matches);
            $n %= $number;
        }

        return $res;
    }

    public function removeAugmentation(?string $text): ?string
    {
        if (null === $text) {
            return null;
        }

        return preg_replace('/(\[(.+)\])\((.+)\)/U', '<em>$2</em>', $text);
    }

    public function createRuleModal(RuleInterface $entity): string
    {
        $id = sprintf('modal___%s___%s', $entity::CODE, $entity->getId());

        // Add the modal to the list of modals to create at the very end of
        // the rendering of the page.
        static::$modals[] = $id;

        return $id;
    }

    public function renderRuleModals(): string
    {
        $doctrine = $this->container->get('doctrine');
        $rulesHub = $this->container->get(RulesHub::class);
        $template = $this->container->get('templating');

        $map = [];
        foreach (static::$modals as $idString) {
            $parts = explode('___', $idString);
            $map[$parts[1]][] = $parts[2];
        }

        $rendered = '';
        foreach ($map as $code => $ids) {
            $repo = $doctrine->getRepository($rulesHub->codeToClass($code));
            $entities = $repo->findBy(['id' => $ids]);
            /** @var RuleInterface $entity */
            foreach ($entities as $entity) {
                $params = [
                    'modalId' => sprintf('modal___%s___%s', $code, $entity->getId()),
                    'rule' => $entity,
                ];
                if ($template->exists(sprintf('includes/popups/%s.html.twig', $code))) {
                    $rendered .= $template->render(
                        sprintf('includes/popups/%s.html.twig', $code),
                        $params
                    );
                }
                $rendered .= $template->render(
                    'includes/popups/rule.html.twig',
                    $params
                );
            }
        }

        return $rendered;
    }
}
