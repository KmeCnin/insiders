<?php

namespace App\Command\Rule;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractRuleCommand extends ContainerAwareCommand
{
    const PATH_BACKUP_EXPORT = '/Command/Rule/Backup/Export';
    const PATH_BACKUP_IMPORT = '/Command/Rule/Backup/Import';
    const PATH_CURRENT = '/../public/rules';
    const PATH_RULES = '/Entity/Rule';

    protected function absPath(string $path): string
    {
        return $this->getContainer()->get('kernel')->getRootDir().$path;
    }
}
