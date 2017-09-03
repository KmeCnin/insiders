<?php

namespace App\Command\Rule;

use App\Service\Transporter\RuleTransporter;
use App\Service\Transporter\TransporterInterface;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractRuleCommand extends ContainerAwareCommand
{
    const PATH_PUBLIC = '/../public/rules';
    const BASE_NAME = 'rule';

    /** @var InputInterface */
    protected $input;
    /** @var OutputInterface */
    protected $output;
    /** @var TransporterInterface */
    protected $transporter;
    /** @var string|null */
    protected $entity;

    protected function configure()
    {
        $this->addArgument(
            'entity',
            InputArgument::OPTIONAL,
            'Handle only the given entity. (ex: Ability)',
            null
        );
        $this->addOption(
            'recover',
            null,
            InputOption::VALUE_NONE,
            'Recover backup data.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->entity = $input->getArgument('entity');
        $this->transporter = $this->getContainer()->get(RuleTransporter::class);
        $this->transporter->setOutput($output);
        if ($this->entity) {
            $this->transporter->setEntities(['App\Entity\Rule\\'.$this->entity]);
        }

        if ($input->getOption('recover')) {
            $this->recover();
            return;
        }

        $this->backup();

        $this->transport();
    }

    protected function absPath(string $path): string
    {
        return $this->getContainer()->get('kernel')->getRootDir().$path;
    }

    protected function fileIsHandled(\DirectoryIterator $file): bool
    {
        if ($file->isDot()) {
            return false;
        }

        if (null === $this->entity) {
            return true;
        }

        $naming = new UnderscoreNamingStrategy();

        return $naming->classToTableName('App\Entity\Rule\\'.$this->entity)
            === $file->getBasename('.json');
    }

    abstract protected function transport(): void;

    abstract protected function backup(): void;

    abstract protected function recover(): void;
}
