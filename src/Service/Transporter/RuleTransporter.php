<?php

namespace App\Service\Transporter;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class RuleTransporter implements TransporterInterface
{
    private $em;
    private $connection;
    /** @var OutputInterface */
    private $output;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->connection = $em->getConnection();
    }

    public function entities()
    {
        return [
            Arcane::class,
            Ability::class,
        ];
    }

    protected function fromNamespaceToFile(string $namespace)
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $parts = explode('\\', $namespace);
        return $nameConverter->normalize(array_pop($parts));
    }

    public function export(string $to)
    {
        $fs = new Filesystem();
        foreach ($this->entities() as $entity) {
            $this->log("Exporting entity <comment>".$entity."</comment>...");

            $table = $this->em->getClassMetadata($entity)->getTableName();
            $data = $this->connection
                ->executeQuery("SELECT * FROM $table")
                ->fetchAll();

            $filePath = $to.'/'.$this->fromNamespaceToFile($entity).'.json';
            $fs->appendToFile(
                $filePath,
                json_encode(
                    $data,
                    JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE
                )
            );
            $this->log("    <info>Done</info> into file ".$filePath);
        }
    }

    public function import(string $from)
    {
        foreach ($this->entities() as $entity) {
            $table = $this->em->getClassMetadata($entity)->getTableName();
            $this->log("Importing entity <comment>".$entity."</comment> into table <comment>".$table."</comment>...");

            $filePath = $from.'/'.$this->fromNamespaceToFile($entity).'.json';
            $data = json_decode(file_get_contents($filePath), true);

            foreach ($data as $entry) {
                $columns = implode(', ', array_keys($entry));
                $values = '"'.implode('", "', array_values($entry)).'"';

                $sql = "REPLACE INTO $table ($columns) VALUES ($values)";
                $this->connection->query('SET FOREIGN_KEY_CHECKS=0');
                $this->connection->executeUpdate($sql);
                $this->connection->query('SET FOREIGN_KEY_CHECKS=1');
            }
            $this->log("    <info>Done</info>.");
        }
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    private function log(string $msg)
    {
        if ($this->output) {
            $this->output->writeln($msg);
        }
    }
}
