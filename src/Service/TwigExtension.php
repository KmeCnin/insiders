<?php

namespace App\Service;

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
            new \Twig_SimpleFilter('clear', [$this, 'clearFilter']),
        ];
    }

    public function markdownFilter($html)
    {
        $converter = new HtmlConverter();
        return strip_tags($converter->convert($this->removeAugmentation($html)));
    }

    public function removeAugmentation(?string $text): ?string
    {
        if (null === $text) {
            return null;
        }

        return preg_replace('/(\[(.+)\])\((.+)\)/U', '<em>$2</em>', $text);
    }

    public function augmentFilter(?string $text): ?string
    {
        return $this->container->get(RulesAugmenter::class)->augment($text);
    }

    public function clearFilter(?string $text): ?string
    {
        return $this->removeAugmentation($text);
    }
}
