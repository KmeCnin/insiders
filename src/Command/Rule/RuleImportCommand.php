<?php

namespace App\Command\Rule;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class RuleImportCommand extends AbstractRuleCommand
{
    const PATH_BACKUP_IMPORT = '/Command/Rule/Backup/Import';

    protected function configure()
    {
        $this->setName(self::BASE_NAME.':import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->backup();

        $this->output->writeln(
            'Importing <comment>rules</comment> data from json files to database...'
        );
        $this->transporter->import($this->absPath(self::PATH_PUBLIC));
    }

    private function backup(): void
    {
        $backupPath = $this->absPath(self::PATH_BACKUP_IMPORT);
        $this->output->writeln(sprintf(
            'Creating backup at <comment>%s</comment>',
            $backupPath
        ));

        // Remove old backup.
        $fs = new Filesystem();
        foreach (new \DirectoryIterator($backupPath) as $file) {
            if (!$file->isDot()) {
                $fs->remove($file->getPathname());
            }
        }

        // Export current data.
        $this->transporter->export($backupPath);

        $this->output->writeln("");
    }
}
