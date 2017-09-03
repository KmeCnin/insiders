<?php

namespace App\Service\Serializer\Normalizer;

use App\Entity\EntityInterface;
use App\Entity\Rule\Increase;
use App\Entity\Rule\RuleInterface;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class RuleNormalizer implements NormalizerInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function normalize($entities, $extended = null): array
    {
        $normalized = [];
        foreach ($entities as $key => $entity) {
            foreach ($this->propertiesToDisplay($entity) as $property) {
                $normalized[$key][$property->getName()] = $this
                    ->castToNormalized($property, $entity);
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

    private function propertiesToDisplay(EntityInterface $entity): array
    {
        switch (true) {
            case $entity instanceof Increase:
                $propertiesToHide = ['id', 'ability'];
                break;
            default:
                $propertiesToHide = [];
        }

        $meta = new \ReflectionClass($entity);
        $properties = [];
        foreach ($meta->getProperties() as $property) {
            if (!in_array($property->getName(), $propertiesToHide)) {
                $properties[] = $property;
            }
        }

        return $properties;
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

    private function castToNormalized(\ReflectionProperty $property, EntityInterface $entity)
    {
        $property->setAccessible(true);
        $value = $property->getValue($entity);

        if (!is_object($value)) {
            return $value;
        }

        if ($value instanceof RuleInterface) {
            return $value->getSlug();
        }

        if (is_iterable($value)) {
            return $this->normalize($value);
        }

        throw new \Exception(sprintf(
            'No type found for property %s with value %s',
            $property,
            json_encode($value)
        ));
    }

    private function castToDenormalized($value, string $property, ClassMetadata $meta): ?string
    {
        if (null === $value) {
            return self::NULL;
        }

        if ($meta->hasField($property)) {
            switch ($meta->getTypeOfField($property)) {
                case Type::BOOLEAN:
                    return $value ? '1' : '0';
                default:
                    return (string) '"'.str_replace('"', '\"', $value).'"';
            }
        }

        if ($assoc = $meta->getAssociationMapping($property)) {
            /** @var RuleInterface $entity */
            $entity = $this->em->getRepository($assoc['targetEntity'])
                ->findOneBy(['slug' => $value]);
            return $entity ? $entity->getId() : self::NULL;
        }

        throw new \Exception(sprintf(
            'No type found for property %s with value %s in class %s',
            $property,
            $value,
            $meta->getName()
        ));
    }
}
