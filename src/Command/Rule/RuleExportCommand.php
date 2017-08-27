<?php

namespace App\Command\Rule;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class RuleExportCommand extends AbstractRuleCommand
{
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
            $output->writeln("\t".$rule->namespace);

            $entries = [];
            foreach ($this->list($rule->namespace) as $entry) {
                $output->writeln("\t\t{$entry->getName()}");
                $entries[] = $entry;
            }

            $this->export(self::PATH_CURRENT, $rule, $entries);
        }
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
                $this->absPath(self::PATH_BACKUP_EXPORT.'/'.$file->getFilename()),
                true
            );
            $fs->remove($file->getPathname());
        }
    }
}
