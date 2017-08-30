<?php

namespace App\Command\Rule;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class RuleExportCommand extends AbstractRuleCommand
{
    const PATH_BACKUP_EXPORT = '/Command/Rule/Backup/Export';

    protected function configure()
    {
        $this->setName(self::BASE_NAME.':export');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->backup();

        $this->output->writeln(
            'Exporting <comment>rules</comment> data from database to json files...'
        );
        $this->transporter->export($this->absPath(self::PATH_PUBLIC));
    }

    private function backup(): void
    {
        $backupPath = $this->absPath(self::PATH_BACKUP_EXPORT);
        $publicPath = $this->absPath(self::PATH_PUBLIC);
        $this->output->writeln(sprintf(
            'Creating backup at <comment>%s</comment>',
            $backupPath
        ));

        // Remove old backup.
        $fs = new Filesystem();
        foreach (new \DirectoryIterator($publicPath) as $file) {
            if (!$file->isDot()) {
                $fs->copy($file->getPathname(), $backupPath.'/'.$file->getBasename());
                $fs->remove($file->getPathname());
            }
        }

        $this->output->writeln("    <info>Done</info>.\n");
    }
}
