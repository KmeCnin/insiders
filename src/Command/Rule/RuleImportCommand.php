<?php

namespace App\Command\Rule;

use Symfony\Component\Filesystem\Filesystem;

class RuleImportCommand extends AbstractRuleCommand
{
    protected const PATH_BACKUP = '/Command/Rule/Backup/Import';

    protected function configure(): void
    {
        parent::configure();

        $this->setName(self::BASE_NAME.':import');
    }

    protected function transport(): void
    {
        $this->output->writeln(
            'Importing <comment>rules</comment> data from json files to database...'
        );

        $this->transporter->import($this->absPath(self::PATH_PUBLIC));
    }

    protected function backup(): void
    {
        $backupPath = $this->absPath(self::PATH_BACKUP);
        $this->output->writeln(sprintf(
            'Creating backup at <comment>%s</comment>',
            $backupPath
        ));

        // Remove old backup.
        $fs = new Filesystem();
        foreach (new \DirectoryIterator($backupPath) as $file) {
            if ($this->fileIsHandled($file)) {
                $fs->remove($file->getPathname());
            }
        }

        // Export current data.
        $this->transporter->export($backupPath);

        $this->output->writeln('');
    }

    protected function recover(): void
    {
        $backupPath = $this->absPath(self::PATH_BACKUP);
        $this->output->writeln(sprintf(
            'Recovering backup from <comment>%s</comment>',
            $backupPath
        ));

        $this->transporter->import($backupPath);
    }
}
