<?php

namespace App\Command\Rule;

use App\Entity\Rule\RuleInterface;
use App\Service\Transporter\RuleTransporter;
use App\Service\Transporter\TransporterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractRuleCommand extends ContainerAwareCommand
{
    const PATH_BACKUP_EXPORT = DIRECTORY_SEPARATOR.'Command'.DIRECTORY_SEPARATOR.'Rule'.DIRECTORY_SEPARATOR.'Backup'.DIRECTORY_SEPARATOR.'Export';
    const PATH_BACKUP_IMPORT = '/Command/Rule/Backup/Import';
    const PATH_CURRENT = DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'rules';
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
    /** @var TransporterInterface */
    protected $transporter;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->serializer = new Serializer(
            [new ObjectNormalizer(), new ArrayDenormalizer()],
            [new JsonEncoder()]
        );
        $this->transporter = $this->getContainer()->get(RuleTransporter::class);
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

            foreach ($this->jsonEntries($rule, $dir) as $entry) {
                $this->output->writeln("\t\t{$entry->getName()}");
                $this->em->persist($entry);
            }
            $this->em->flush();
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
        foreach (new \DirectoryIterator($this->absPath(self::PATH_CURRENT)) as $file) {
            $metaRule = MetaRule::fromJson($file);

            if (null === $metaRule) {
                continue;
            }

            yield $metaRule;
        }
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
     * @return RuleInterface[]
     */
    protected function jsonEntries(MetaRule $rule, string $dir): array
    {
        $path = $dir.'/'.$rule->short.'.json';
        $entries = file_get_contents($path);

        return $this->serializer->deserialize(
            $entries,
            $rule->namespace.'[]',
            JsonEncoder::FORMAT
        );
    }
}
