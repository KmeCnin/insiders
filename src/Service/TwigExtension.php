<?php

namespace App\Service;

use League\HTMLToMarkdown\HtmlConverter;

class TwigExtension extends \Twig_Extension
{
    private $rulesAugmenter;

    public function __construct(RulesAugmenter $rulesAugmenter)
    {
        $this->rulesAugmenter = $rulesAugmenter;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('markdown', [$this, 'markdownFilter']),
        ];
    }

    public function markdownFilter($html)
    {
        $converter = new HtmlConverter();
        return strip_tags($converter->convert($this->rulesAugmenter->augment($html)));
    }
}
