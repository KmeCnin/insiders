<?php

namespace App\Command\Rule;

use App\Service\Transporter\RuleTransporter;
use App\Service\Transporter\TransporterInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->transporter = $this->getContainer()->get(RuleTransporter::class);
        $this->transporter->setOutput($output);
    }

    protected function absPath(string $path): string
    {
        return $this->getContainer()->get('kernel')->getRootDir().$path;
    }
}
