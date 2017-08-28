<?php

namespace App\Command\Rule;

use App\Entity\Rule\RuleInterface;

class MetaRule
{
    public $namespace;
    public $short;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $parts = explode('\\', $namespace);
        $this->short = end($parts);
    }

    public static function fromPhp(\DirectoryIterator $file): ?self
    {
        return self::from($file, 'php');
    }

    public static function fromJson(\DirectoryIterator $file): ?self
    {
        return self::from($file, 'json');
    }

    private static function from(\DirectoryIterator $file, string $extension): ?self
    {
        if($file->isDot()) {
            return null;
        }

        $class = $file->getBasename('.'.$extension);
        $namespace = 'App\Entity\Rule\\'.$class;
        try {
            $instance = new $namespace();
        } catch (\Error $e) {
            return null;
        }

        if (!$instance instanceof RuleInterface) {
            return null;
        }

        return new static($namespace);
    }
}
