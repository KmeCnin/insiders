<?php

namespace App\Command\Rule;

use App\Entity\Rule\RuleInterface;
use App\Serializer\Normalizer\RuleNormalizer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * @return \Iterator|MetaRule[]
     */
    protected function rules(): \Iterator
    {
        foreach (new \DirectoryIterator($this->absPath(self::PATH_RULES)) as $file) {
            $metaRule = MetaRule::fromFile($file);

            if (null === $metaRule) {
                continue;
            }

            yield $metaRule;
        }
    }

    /**
     * @return RuleInterface[]
     */
    protected function list(string $rule): array
    {
        return $this->getContainer()->get('doctrine')
            ->getRepository($rule)
            ->findAll();
    }

    protected function export(string $dir, MetaRule $rule, array $entries): void
    {
        $fs = new Filesystem();
        $serializer = new Serializer([new RuleNormalizer()], [new JsonEncoder()]);

        $normalized = [];
        foreach ($entries as $entry) {
            $normalized[] = $serializer->normalize($entry);
        }

        $serialized = $serializer->encode($normalized, JsonEncoder::FORMAT);

        $fs->appendToFile(
            $this->absPath($dir.'/'.$rule->short.'.json'),
            json_encode(json_decode($serialized), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE)
        );
    }
}
