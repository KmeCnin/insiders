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

        $this->output->writeln('Delete current data from database');
        // TODO: save export in order to backup if failed.
        foreach ($this->databaseRules() as $metaRule) {
            $cmd = $this->em->getClassMetadata($metaRule->namespace);
            $connection = $this->em->getConnection();
            $dbPlatform = $connection->getDatabasePlatform();
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
