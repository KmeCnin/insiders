<?php

namespace App\Command\Rule;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

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

        $this->output->writeln(sprintf(
            'Start to export rules to %s',
            $this->absPath(self::PATH_CURRENT)
        ));
        $this->exportTo($this->absPath(self::PATH_CURRENT));
    }

    private function backup(): void
    {
        $this->output->writeln(sprintf(
            'Create backup at %s',
            $this->absPath(self::PATH_BACKUP_EXPORT)
        ));

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
