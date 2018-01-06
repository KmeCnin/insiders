<?php

namespace App\Service;

use App\Entity\Rule\AbstractRule;
use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwigExtension extends \Twig_Extension
{
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
            'I'  => 1
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
}
