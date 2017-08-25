<?php

namespace App\Command;

use App\Entity\Rule\RuleInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
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

            $entries = [];
            foreach ($this->list($rule) as $entry) {
                $output->writeln("\t\t{$entry->getName()}");
                $entries[] = $entry;
            }

            $this->export($entries);
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
                $this->absPath(self::PATH_BACKUP.'/'.$file->getFilename()),
                true
            );
            $fs->remove($file->getPathname());
        }
    }

    private function export(array $entries): void
    {
        $fs = new Filesystem();
        $serializer = new Serializer([new PropertyNormalizer()], [new JsonEncoder()]);

        $reflect = new \ReflectionClass(reset($entries));
        $file = $reflect->getShortName().'.json';

        $normalized = [];
        foreach ($entries as $entry) {
            $normalized[] = $serializer->normalize($entry, null, [
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH
            ]);
        }

        $serialized = $serializer->encode($normalized, JsonEncoder::FORMAT);

        $fs->appendToFile(
            $this->absPath(self::PATH_CURRENT.'/'.$file),
            json_encode(json_decode($serialized), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE)
        );
    }

    private function absPath(string $path): string
    {
        return $this->getContainer()->get('kernel')->getRootDir().$path;
    }
}
