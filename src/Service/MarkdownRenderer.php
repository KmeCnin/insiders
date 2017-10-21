<?php

namespace App\Service;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Attribute;
use App\Entity\Rule\Burst;
use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\Deity;
use App\Entity\Rule\LexiconEntry;
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
            Attribute::class => 'attributes',
            Arcane::class => 'arcanes',
            Burst::class => 'bursts',
            CanonicalStuff::class => 'stuffs',
            Characteristic::class => 'characteristics',
            Deity::class => 'deities',
        ];
    }

    public static function files(): array
    {
        return [
            Ability::class => 'capacités',
            Attribute::class => 'attributs',
            Arcane::class => 'arcanes',
            Burst::class => 'décharges',
            CanonicalStuff::class => 'équipements',
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
                    'lexicon' => $this->em->getRepository(LexiconEntry::class)
                        ->find('ability'),
                    'arcanes' => $this->em->getRepository(Arcane::class)->findAll(),
                    'abilities' => $this->em->getRepository(Ability::class)->findAll(),
                ];
                break;
            case Attribute::class:
                $parameters = [
                    'lexicon' => $this->em->getRepository(LexiconEntry::class)
                        ->find('attribute'),
                    'attributes' => $this->em->getRepository(Attribute::class)->findAll(),
                ];
                break;
            case Arcane::class:
                $parameters = [
                    'lexicon' => $this->em->getRepository(LexiconEntry::class)
                        ->find('arcane'),
                    'arcanes' => $this->em->getRepository(Arcane::class)->findAll(),
                ];
                break;
            case Burst::class:
                $parameters = [
                    'lexicon' => $this->em->getRepository(LexiconEntry::class)
                        ->find('burst'),
                    'arcanes' => $this->em->getRepository(Arcane::class)->findAll(),
                    'bursts' => $this->em->getRepository(Burst::class)->findAll(),
                ];
                break;
            case CanonicalStuff::class:
                $parameters = [
                    'lexicon' => $this->em->getRepository(LexiconEntry::class)
                        ->find('stuff'),
                    'weapons' => $this->em->getRepository(CanonicalStuff::class)
                        ->findAllWeapons(),
                    'armors' => $this->em->getRepository(CanonicalStuff::class)
                        ->findAllArmors(),
                    'objects' => $this->em->getRepository(CanonicalStuff::class)
                        ->findAllObjects(),
                    'expendables' => $this->em->getRepository(CanonicalStuff::class)
                        ->findAllExpendables(),
                ];
                break;
            case Characteristic::class:
                $parameters = [
                    'lexicon' => $this->em->getRepository(LexiconEntry::class)
                        ->find('characteristic'),
                    'characteristics' => $this->em->getRepository(Characteristic::class)->findAll(),
                ];
                break;
            case Deity::class:
                $parameters = [
                    'lexicon' => $this->em->getRepository(LexiconEntry::class)
                        ->find('deity'),
                    'deities' => $this->em->getRepository(Deity::class)->findAll(),
                ];
                break;
            default:
                $parameters = [];
        }

        return $this->twig->render(static::view($namespace), $parameters);
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
