<?php

namespace App\Service;

use League\HTMLToMarkdown\HtmlConverter;

class TwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('markdown', [$this, 'markdownFilter']),
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
}
