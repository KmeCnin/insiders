<?php

namespace App\Service\Serializer\Normalizer;

use App\Entity\Rule\RuleInterface;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class SQLNormalizer implements NormalizerInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function normalize($data, $namespace = null): array
    {
        $meta = $this->em->getClassMetadata($namespace);

        $normalized = [];
        foreach ($data as $key => $entry) {
            foreach ($entry as $field => $value) {
                $property = $this->sqlFieldToDoctrineProperty($field, $meta);
                $normalized[$key][$property] = $this->castToNormalized(
                    $value,
                    $property,
                    $meta
                );
            }
        }

        return $normalized;
    }

    public function denormalize(array $array, $namespace = null)
    {
        $meta = $this->em->getClassMetadata($namespace);

        $denormalized = [];
        foreach ($array as $key => $entry) {
            foreach ($entry as $property => $value) {
                $field = $this->doctrinePropertyToSqlField($property, $meta);
                $denormalized[$key][$field] = $this->castToDenormalized(
                    $value,
                    $property,
                    $meta
                );
            }
        }

        return $denormalized;
    }

    private function sqlFieldToDoctrineProperty(string $field, ClassMetadata $meta): string
    {
        if ($meta->hasField($field)) {
            return $field;
        }

        $property = preg_replace('/_id$/', '', $field);
        if ($meta->getAssociationMapping($property)) {
            return $property;
        }

        throw new \Exception(sprintf(
            'Property %s does not exists in class %s',
            $field,
            $meta->getName()
        ));
    }

    private function doctrinePropertyToSqlField(string $property, ClassMetadata $meta): string
    {
        return $meta->hasField($property)
            ? $property
            : $property.'_id';
    }

    private function castToNormalized(string $value, string $property, ClassMetadata $meta)
    {
        if ($meta->hasField($property)) {
            switch ($meta->getTypeOfField($property)) {
                case Type::BIGINT:
                case Type::INTEGER:
                case Type::SMALLINT:
                    return (int) $value;
                case Type::DECIMAL:
                case Type::FLOAT:
                    return (float) $value;
                case Type::BOOLEAN:
                    return (bool) $value;
                default:
                    return $value;
            }
        }

        if ($assoc = $meta->getAssociationMapping($property)) {
            /** @var RuleInterface $entity */
            $entity = $this->em->getRepository($assoc['targetEntity'])->find($value);
            return $entity->getSlug();
        }

        throw new \Exception(sprintf(
            'No type found for property %s with value %s in class %s',
            $property,
            $value,
            $meta->getName()
        ));
    }

    private function castToDenormalized($value, string $property, ClassMetadata $meta): string
    {
        if ($meta->hasField($property)) {
            switch ($meta->getTypeOfField($property)) {
                case Type::BOOLEAN:
                    return $value ? '1' : '0';
                default:
                    return (string) $value;
            }
        }

        if ($assoc = $meta->getAssociationMapping($property)) {
            /** @var RuleInterface $entity */
            $entity = $this->em->getRepository($assoc['targetEntity'])
                ->findOneBy(['slug' => $value]);
            return $entity->getId();
        }

        throw new \Exception(sprintf(
            'No type found for property %s with value %s in class %s',
            $property,
            $value,
            $meta->getName()
        ));
    }
}
