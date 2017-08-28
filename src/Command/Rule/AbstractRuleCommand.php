<?php

namespace App\Command\Rule;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\RuleInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractRuleCommand extends ContainerAwareCommand
{
    const PATH_BACKUP_EXPORT = '/Command/Rule/Backup/Export';
    const PATH_BACKUP_IMPORT = '/Command/Rule/Backup/Import';
    const PATH_CURRENT = '/../public/rules';
    const PATH_RULES = '/Entity/Rule';

    const BASE_NAME = 'rule';

    /** @var InputInterface */
    protected $input;
    /** @var OutputInterface */
    protected $output;
    /** @var Serializer */
    protected $serializer;
    /** @var EntityManagerInterface */
    protected $em;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->serializer = $this->getContainer()->get('jms_serializer');
    }

    protected function absPath(string $path): string
    {
        return $this->getContainer()->get('kernel')->getRootDir().$path;
    }

    protected function exportTo(string $dir)
    {
        $fs = new Filesystem();

        foreach ($this->databaseRules() as $rule) {
            $this->output->writeln("\t".$rule->namespace);

            $entries = [];
            foreach ($this->databaseEntries($rule) as $entry) {
                $this->output->writeln("\t\t{$entry->getName()}");
                $entries[] = $entry;
            }

            $serialized = $this->serializer->serialize(
                $entries,
                JsonEncoder::FORMAT
            );

            $fs->appendToFile(
                $dir.'/'.$rule->short.'.json',
                json_encode(
                    json_decode($serialized, true),
                    JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE
                )
            );
        }
    }

    protected function importFrom(string $dir)
    {
        foreach ($this->jsonRules() as $rule) {
            $this->output->writeln("\t".$rule->namespace);

            /** @var RuleInterface $entry */
            foreach ($this->jsonEntries($rule, $dir) as $entry) {
                $this->output->writeln("\t\t{$entry->getName()}");
                if ($this->em->find(get_class($entry), $entry->getId())) {
                    $this->em->merge($entry);
                } else {
                    $this->em->persist($entry);
                }
                $this->em->flush();
            }
        }
    }

    /**
     * @return \Iterator|MetaRule[]
     */
    protected function databaseRules(): \Iterator
    {
        foreach (new \DirectoryIterator($this->absPath(self::PATH_RULES)) as $file) {
            $metaRule = MetaRule::fromPhp($file);

            if (null === $metaRule) {
                continue;
            }

            yield $metaRule;
        }
    }

    /**
     * @return \Iterator|MetaRule[]
     */
    protected function jsonRules(): \Iterator
    {
        yield new MetaRule(Arcane::class);
        yield new MetaRule(Ability::class);


//        foreach (new \DirectoryIterator($this->absPath(self::PATH_CURRENT)) as $file) {
//            $metaRule = MetaRule::fromJson($file);
//
//            if (null === $metaRule) {
//                continue;
//            }
//
//            yield $metaRule;
//        }
    }

    /**
     * @return RuleInterface[]
     */
    protected function databaseEntries(MetaRule $rule): array
    {
        return $this->getContainer()->get('doctrine')
            ->getRepository($rule->namespace)
            ->findAll();
    }

    /**
     * @return RuleInterface[]|\Iterator
     */
    protected function jsonEntries(MetaRule $rule, string $dir): \Iterator
    {
        $path = $dir.'/'.$rule->short.'.json';
        $entries = file_get_contents($path);

        foreach (json_decode($entries, true) as $entry) {
            yield $this->serializer->deserialize(
                json_encode($entry),
                $rule->namespace,
                JsonEncoder::FORMAT
            );
        }
    }
}
