<?php

namespace App\Service\Transporter;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class RuleTransporter implements TransporterInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function entities()
    {
        return [
            Arcane::class,
            Ability::class,
        ];
    }

    protected function fromNamespaceToFile(string $namespace)
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $parts = explode('\\', $namespace);
        return $nameConverter->normalize(array_pop($parts));
    }

    public function export(string $to)
    {
        $fs = new Filesystem();
        foreach ($this->entities() as $entity) {
            $table = $this->em->getClassMetadata($entity)->getTableName();
            $data = $this->em->getConnection()
                ->executeQuery("SELECT * FROM $table")
                ->fetchAll();

            $fs->appendToFile(
                $to.DIRECTORY_SEPARATOR.$this->fromNamespaceToFile($entity).'.json',
                json_encode(
                    $data,
                    JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE
                )
            );
        }
    }

    public function import(string $from)
    {
    }
}
