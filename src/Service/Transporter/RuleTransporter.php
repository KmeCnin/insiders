<?php

namespace App\Service\Transporter;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Champion;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\Deity;
use App\Entity\Rule\RuleInterface;
use App\Entity\Rule\SecondaryRuleInterface;
use App\Service\Serializer\Encoder\PrettyJsonEncoder;
use App\Service\Serializer\Normalizer\RuleNormalizer;
use App\Service\Serializer\Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class RuleTransporter implements TransporterInterface
{
    private $em;
    private $connection;
    private $serializer;
    /** @var OutputInterface */
    private $output;
    /** @var string[] */
    private $entities;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->connection = $em->getConnection();
        $this->serializer = new Serializer(
            new RuleNormalizer($em),
            new PrettyJsonEncoder()
        );
        $this->entities = $this->defaultEntities();
    }

    protected function defaultEntities()
    {
        return [
            Arcane::class,
            Characteristic::class,
            Ability::class,
            Deity::class,
            Champion::class,
            // Only for import, must be done again in order to refer previous ids:
            Deity::class,
        ];
    }

    public function export(string $to)
    {
        $fs = new Filesystem();
        foreach (array_unique($this->entities) as $entity) {
            $this->log("Exporting entity <comment>".$entity."</comment>...");

            $entities = $this->em
                ->getRepository($entity)
                ->findAll();

            $filePath = $to.'/'.$this->fromNamespaceToFile($entity).'.json';
            $fs->appendToFile(
                $filePath,
                $this->serializer->serialize($entities)
            );
            $this->log("    <info>Done</info> into file ".$filePath);
        }
    }

    public function import(string $from)
    {
        foreach ($this->entities as $namespace) {
            $table = $this->em->getClassMetadata($namespace)->getTableName();
            $this->log("Importing entity <comment>".$namespace."</comment> into table <comment>".$table."</comment>...");

            $filePath = $from.'/'.$this->fromNamespaceToFile($namespace).'.json';
            $entities = $this->serializer->deserialize(
                file_get_contents($filePath),
                $namespace
            );

            /** @var RuleInterface $entity */
            foreach ($entities as $entity) {
                $this->em->persist($entity);
            }
            $this->em->flush();
            $this->log("    <info>Done</info>.");
        }
    }

    protected function removeSecondaryRules(RuleInterface $entity)
    {
        $meta = $this->em->getClassMetadata(get_class($entity));
        $associations = $meta->getAssociationMappings();
        foreach ($associations as $assoc) {
            $instance = new $assoc['targetEntity']();
            if ($instance instanceof SecondaryRuleInterface) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $accessor->setValue($entity, $assoc['fieldName'], new ArrayCollection([]));
            }
        }
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function setEntities(array $entities)
    {
        $this->entities = $entities;
    }

    protected function fromNamespaceToFile(string $namespace)
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $parts = explode('\\', $namespace);
        return $nameConverter->normalize(array_pop($parts));
    }

    protected function log(string $msg)
    {
        if ($this->output) {
            $this->output->writeln($msg);
        }
    }
}
