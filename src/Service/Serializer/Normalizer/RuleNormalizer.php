<?php

namespace App\Service\Serializer\Normalizer;

use App\Entity\EntityInterface;
use App\Entity\Rule\Increase;
use App\Entity\Rule\RuleInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
        $denormalized = [];
        foreach ($array as $key => $entry) {
            $denormalized[] = $this->castToDenormalized($namespace, $entry);
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

    private function castToDenormalized(string $namespace, array $entry, RuleInterface $entity = null): EntityInterface
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $meta = $this->em->getClassMetadata($namespace);
        $entity = isset($entry['id'])
            ? $this->em->find($namespace, $entry['id']) ?: new $namespace()
            : new $namespace();

        foreach ($entry as $property => $value) {
            if ($meta->hasField($property)) {
                $accessor->setValue($entity, $property, $value);
            } elseif ($meta->hasAssociation($property) &&
                $assoc = $meta->getAssociationMapping($property)
            ) {
                $subNamespace = $assoc['targetEntity'];
                if (is_iterable($value)) {
                    $collection = [];
                    foreach ($value as $subEntry) {
                        $collection[] = $this->castToDenormalized(
                            $subNamespace,
                            $subEntry
                        );
                    }
                    $accessor->setValue($entity, $property, $collection);
                } else {
                    $accessor->setValue(
                        $entity,
                        $property,
                        $this->em->getRepository($subNamespace)
                            ->findOneBy(['slug' => $value])
                    );
                }
            }
        }

        return $entity;
    }
}
