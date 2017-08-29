<?php

namespace App\Command\Rule;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RuleExportCommand extends AbstractRuleCommand
{
    protected function configure()
    {
        $this->setName(self::BASE_NAME.':export');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->backup();

        $this->transporter->export($this->absPath(self::PATH_CURRENT));
    }

    private function backup(): void
    {
        // TODO
    }
}
