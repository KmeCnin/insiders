<?php

namespace App\Service\Transporter;

use App\Entity\NormalizableInterface;
use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Attribute;
use App\Entity\Rule\Burst;
use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\Champion;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\Deity;
use App\Entity\Rule\LexiconEntry;
use App\Entity\Rule\Page;
use App\Entity\Rule\RuleInterface;
use App\Entity\Rule\Skill;
use App\Entity\Rule\StuffCategory;
use App\Entity\Rule\StuffKind;
use App\Entity\Rule\StuffProperty;
use App\Entity\Rule\StuffPropertyKind;
use App\Exception\ImportException;
use Doctrine\ORM\EntityManagerInterface;
use MJS\TopSort\Implementations\StringSort;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class RuleTransporter implements TransporterInterface
{
    private $em;
    private $connection;
    private $formFactory;
    /** @var OutputInterface */
    private $output;
    /** @var string[] */
    private $entities;

    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ) {
        $this->em = $em;
        $this->connection = $em->getConnection();
        $this->formFactory = $formFactory;
        $this->entities = $this->defaultEntities();
    }

    private function defaultEntities()
    {
        return [
            Arcane::class,
            Characteristic::class,
            Skill::class,
            Ability::class,
            Champion::class,
            Deity::class,
            StuffCategory::class,
            StuffKind::class,
            StuffPropertyKind::class,
            StuffProperty::class,
            CanonicalStuff::class,
            Attribute::class,
            Burst::class,
            LexiconEntry::class,
            Page::class,
        ];
    }

    public function export(string $to): void
    {
        $fs = new Filesystem();
        foreach (array_unique($this->entities) as $namespace) {
            $this->log("Exporting entity <comment>".$namespace."</comment>...");

            $entities = $this->em
                ->getRepository($namespace)
                ->findAll();

            $normalized = array_map(function (NormalizableInterface $entity) {
                return $entity->normalize();
            }, $entities);

            $encoded = json_encode(
                $normalized,
                JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE
            );

            $filePath = $to.'/'.$this->fromNamespaceToFile($namespace).'.json';
            $fs->appendToFile($filePath, $encoded);
            $this->log("    <info>Done</info> into file ".$filePath);
        }
    }

    public function import(string $from): void
    {
        foreach ($this->entities as $namespace) {
            $table = $this->em->getClassMetadata($namespace)->getTableName();
            $this->log("Importing entity <comment>".$namespace."</comment> into table <comment>".$table."</comment>...");

            $filePath = $from.'/'.$this->fromNamespaceToFile($namespace).'.json';
            $data = json_decode(file_get_contents($filePath), true);

            $data = $this->sort($data, $namespace);

            /** @var RuleInterface $entity */
            foreach ($data as $entry) {
                $id = $entry['id'];
                $entity = $this->em->find($namespace, $id) ?: new $namespace();
                $form = $this->createForm($namespace, $entity);
                $form->submit($entry);
                if (!$form->isValid()) {
                    throw new ImportException($form, $entry);
                }

                $this->log(sprintf("    Import <comment>%s</comment>", $entity->getId()));

                $this->em->persist($entity);
                $this->em->flush();
            }
            $this->log("    <info>Done</info>.");
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

    private function fromNamespaceToFile(string $namespace)
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $parts = explode('\\', $namespace);
        return $nameConverter->normalize(array_pop($parts));
    }

    private function log(string $msg)
    {
        if ($this->output) {
            $this->output->writeln($msg);
        }
    }

    private function createForm(string $entityNamespace, RuleInterface $entity)
    {
        $parts = explode('\\', $entityNamespace);
        $entityName = array_pop($parts);
        $type = 'App\Form\Type\Rule\\'.$entityName.'Type';
        return $this->formFactory->create($type, $entity, [
            'csrf_protection' => false,
        ]);
    }

    private function sort(array $data, string $namespace): array
    {
        switch ($namespace) {
            case Ability::class:
                return $this->sortAccordingMultiple($data, 'abilitiesRequired');
            default:
                return $data;
        }
    }

    private function sortAccordingMultiple(array $data, string $required): array
    {
        $sorter = new StringSort();
        foreach ($data as $index => $entry) {
            $sorter->add($entry['id'], $entry[$required]);
        }
        $result = $sorter->sort();

        $sorted = [];
        foreach ($result as $id) {
            foreach ($data as $entry) {
                if ($id === $entry['id']) {
                    $sorted[] = $entry;
                    break;
                }
            }
        }

        return $sorted;
    }
}
