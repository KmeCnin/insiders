<?php

namespace App\Command\Rule;

use App\Service\Transporter\RuleTransporter;
use App\Service\Transporter\TransporterInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
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

    protected function configure()
    {
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
        $this->transporter = $this->getContainer()->get(RuleTransporter::class);
        $this->transporter->setOutput($output);

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

    abstract protected function transport(): void;

    abstract protected function backup(): void;

    abstract protected function recover(): void;
}
