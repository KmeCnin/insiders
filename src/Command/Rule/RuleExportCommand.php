<?php

namespace App\Command\Rule;

use Symfony\Component\Filesystem\Filesystem;

class RuleExportCommand extends AbstractRuleCommand
{
    const PATH_BACKUP = '/Command/Rule/Backup/Export';

    protected function configure()
    {
        parent::configure();

        $this->setName(self::BASE_NAME.':export');
    }

    protected function transport(): void
    {
        $this->output->writeln(
            'Exporting <comment>rules</comment> data from database to json files...'
        );

        $this->transporter->export($this->absPath(self::PATH_PUBLIC));
    }

    protected function backup(): void
    {
        $backupPath = $this->absPath(self::PATH_BACKUP);
        $publicPath = $this->absPath(self::PATH_PUBLIC);
        $this->output->writeln(sprintf(
            'Creating backup at <comment>%s</comment>',
            $backupPath
        ));

        // Remove old backup.
        $fs = new Filesystem();
        foreach (new \DirectoryIterator($publicPath) as $file) {
            if ($this->fileIsHandled($file)) {
                $fs->copy(
                    $file->getPathname(),
                    $backupPath.'/'.$file->getBasename(),
                    true
                );
                $fs->remove($file->getPathname());
            }
        }

        $this->output->writeln("    <info>Done</info>.\n");
    }

    protected function recover(): void
    {
        $backupPath = $this->absPath(self::PATH_BACKUP);
        $publicPath = $this->absPath(self::PATH_PUBLIC);
        $this->output->writeln(sprintf(
            'Recovering backup from <comment>%s</comment>',
            $backupPath
        ));

        $fs = new Filesystem();
        foreach (new \DirectoryIterator($backupPath) as $file) {
            if ($this->fileIsHandled($file)) {
                $fs->copy(
                    $file->getPathname(),
                    $publicPath.'/'.$file->getBasename(),
                    true
                );
            }
        }
    }
}
