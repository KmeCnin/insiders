<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\Deity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MarkdownRenderer
{
    const PATH_EXPORT = '/../public/book';

    private $em;
    private $twig;
    private $root;
    /** @var OutputInterface */
    private $output;

    public function __construct(
        EntityManagerInterface $em,
        \Twig_Environment $twig,
        string $root
    ) {
        $this->em = $em;
        $this->twig = $twig;
        $this->root = $root;
    }

    public static function entities(): array
    {
        return [
            Ability::class => 'abilities',
            Arcane::class => 'arcanes',
            Characteristic::class => 'characteristics',
            Deity::class => 'deities',
        ];
    }

    public static function files(): array
    {
        return [
            Ability::class => 'capacités',
            Arcane::class => 'arcanes',
            Characteristic::class => 'caractéristiques',
            Deity::class => 'divinités',
        ];
    }

    public static function view(string $namespace): string
    {
        return 'markdown/'.self::entities()[$namespace].'.md.twig';
    }

    public function export(): void
    {
        $fs = new Filesystem();
        foreach (self::files() as $namespace => $file) {
            $this->log("Exporting entity <comment>".$namespace."</comment>...");
            $filePath = $this->root.self::PATH_EXPORT.'/'.$file.'.md';
            $fs->dumpFile($filePath, $this->render($namespace));
            $this->log("    <info>Done</info> into file ".$filePath);
        }
    }

    public function render(string $namespace): string
    {
        switch ($namespace) {
            case Ability::class:
                $parameters = [
                    'arcanes' => $this->em->getRepository(Arcane::class)->findAll(),
                    'abilities' => $this->em->getRepository(Ability::class)->findAll(),
                ];
                break;
            case Arcane::class:
                $parameters = [
                    'arcanes' => $this->em->getRepository(Arcane::class)->findAll(),
                ];
                break;
            case Characteristic::class:
                $parameters = [
                    'characteristics' => $this->em->getRepository(Characteristic::class)->findAll(),
                ];
                break;
            case Deity::class:
                $parameters = [
                    'deities' => $this->em->getRepository(Deity::class)->findAll(),
                ];
                break;
            default:
                $parameters = [];
        }

        return $this->twig->render($this->view($namespace), $parameters);
    }

    public function setOutput(OutputInterface $output): self
    {
        $this->output = $output;

        return $this;
    }

    private function log(string $msg)
    {
        if ($this->output) {
            $this->output->writeln($msg);
        }
    }
}
