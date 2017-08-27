<?php

namespace App\Command\Rule;

use App\Entity\Rule\RuleInterface;

class MetaRule
{
    public $namespace;
    public $short;

    public function __construct(string $namespace, string $short)
    {
        $this->namespace = $namespace;
        $this->short = $short;
    }

    public static function fromFile(\DirectoryIterator $file): ?self
    {
        if($file->isDot()) {
            return null;
        }

        $class = $file->getBasename('.php');
        $namespace = 'App\Entity\Rule\\'.$class;
        try {
            $instance = new $namespace();
        } catch (\Error $e) {
            return null;
        }

        if (!$instance instanceof RuleInterface) {
            return null;
        }

        $reflect = new \ReflectionClass($instance);
        $short = $reflect->getShortName();

        return new static($namespace, $short);
    }
}
