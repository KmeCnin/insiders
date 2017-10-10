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
        return $converter->convert($html);
    }
}
