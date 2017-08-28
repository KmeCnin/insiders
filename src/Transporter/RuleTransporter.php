<?php

namespace App\Transporter;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RuleTransporter implements TransporterInterface
{
    private $serializer;
    private $rootDir;
    private $doctrine;

    public function __construct(
        SerializerInterface $serializer,
        string $rootDir,
        RegistryInterface $doctrine
    ) {
        $this->serializer = $serializer;
        $this->rootDir = $rootDir;
        $this->doctrine = $doctrine;
    }

    public function pathToRulesEntities()
    {
        return $this->rootDir.'/Entity/Rule';
    }

    public function export(string $to)
    {
        $entityFiles = new \DirectoryIterator($this->pathToRulesEntities());

        foreach ($entityFiles as $entityFile) {
            $entries = $this->doctrine->getRepository($entityFile->get)->findAll();
            foreach ($this->databaseEntries($rule)) {

            }
        }
    }

    public function import(string $from)
    {
    }
}
