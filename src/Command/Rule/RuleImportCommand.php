<?php

namespace App\Command\Rule;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RuleImportCommand extends AbstractRuleCommand
{

    protected function configure()
    {
        $this->setName('rule:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Backup old data");
        $this->backup();

        $output->writeln("Start to import rules:");
        foreach (new \DirectoryIterator($this->absPath(self::PATH_CURRENT)) as $file) {
            if($file->isDot()) {
                continue;
            }

            $output->writeln("\tApp\Entity\Rule\\".$file->getBasename('.json'));

            $entries = json_decode(file_get_contents($file->getPathname()), true);
            foreach ($entries as $entry) {
                $output->writeln("\t\t{$entry['name']}");
            }
        }
    }

    private function backup(): void
    {
        foreach ($this->rules() as $rule) {
            $entries = [];
            foreach ($this->list($rule->namespace) as $entry) {
                $entries[] = $entry;
            }

            $this->export(self::PATH_BACKUP_IMPORT, $rule, $entries);
        }
    }
}
