<?php

namespace App\Command;

use App\Entity\Rule\RuleInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RuleExportCommand extends ContainerAwareCommand
{
    const PATH_BACKUP = '/../public/rules/backup';
    const PATH_CURRENT = '/../public/rules/current';
    const PATH_RULES = '/Entity/Rule';

    protected function configure()
    {
        $this->setName('rule:export');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Backup old data");
        $this->backup();

        $output->writeln("Start to export rules:");
        foreach ($this->rules() as $rule) {
            $output->writeln("\t".$rule);
            foreach ($this->list($rule) as $entry) {
                $output->writeln("\t\t{$entry->getName()}");
                $this->export($entry);
            }
        }
    }

    private function rules(): \Iterator
    {
        foreach (new \DirectoryIterator($this->absPath(self::PATH_RULES)) as $file) {
            if($file->isDot()) {
                continue;
            }

            $class = $file->getBasename('.php');
            $namespace = 'App\Entity\Rule\\'.$class;
            try {
                $instance = new $namespace();
            } catch (\Error $e) {
                continue;
            }

            if (!$instance instanceof RuleInterface) {
                continue;
            }

            yield $namespace;
        }
    }

    /**
     * @return RuleInterface[]
     */
    private function list(string $rule): array
    {
        return $this->getContainer()->get('doctrine')
            ->getRepository($rule)
            ->findAll();
    }

    private function backup(): void
    {
        $fs = new Filesystem();

        foreach (new \DirectoryIterator($this->absPath(self::PATH_CURRENT)) as $file) {
            if($file->isDot()) {
                continue;
            }
            $fs->copy(
                $file->getPathname(),
                $this->absPath(self::PATH_BACKUP.$file->getFilename()),
                true
            );
            $fs->remove($file->getPathname());
        }
    }

    private function export(RuleInterface $entry): void
    {
        $fs = new Filesystem();
        $reflect = new \ReflectionClass($entry);
        $file = $reflect->getShortName().'.json';

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $json = $serializer->serialize($entry, JsonEncoder::FORMAT);

        $fs->appendToFile($this->absPath(self::PATH_CURRENT.'/'.$file), $json);
    }

    private function absPath(string $path): string
    {
        return $this->getContainer()->get('kernel')->getRootDir().$path;
    }
}
