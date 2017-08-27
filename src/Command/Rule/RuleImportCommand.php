<?php

namespace App\Command\Rule;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RuleImportCommand extends AbstractRuleCommand
{
    protected function configure()
    {
        $this->setName(self::BASE_NAME.':import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->backup();

        $this->output->writeln(sprintf(
            'Start to import rules from %s',
            $this->absPath(self::PATH_CURRENT)
        ));
        $this->importFrom($this->absPath(self::PATH_CURRENT));
    }

    private function backup(): void
    {
        $this->output->writeln(sprintf(
            'Create backup at %s',
            $this->absPath(self::PATH_BACKUP_IMPORT)
        ));

        $this->exportTo($this->absPath(self::PATH_BACKUP_IMPORT));
    }
}
