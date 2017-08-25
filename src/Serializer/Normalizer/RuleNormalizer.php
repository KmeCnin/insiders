<?php

namespace App\Serializer\Normalizer;

use App\Entity\Rule\RuleInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class RuleNormalizer extends AbstractNormalizer
{
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof RuleInterface;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $data instanceof RuleInterface;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        $data = [];
        foreach ($this->extractAttributes($object) as $attribute) {
            $attributeValue = $this->getAttributeValue($object, $attribute);
            $data = $this->updateData($data, $attribute, $attributeValue);
        }

        return $data;
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {

    }

    private function extractAttributes($object): array
    {
        $reflectionObject = new \ReflectionObject($object);

        $attributes = array();
        foreach ($reflectionObject->getProperties() as $property) {
            $attributes[] = $property->name;
        }

        return $attributes;
    }

    private function getAttributeValue($object, $attribute)
    {
        try {
            $reflectionProperty = new \ReflectionProperty(get_class($object), $attribute);
        } catch (\ReflectionException $reflectionException) {
            return null;
        }

        // Override visibility
        if (!$reflectionProperty->isPublic()) {
            $reflectionProperty->setAccessible(true);
        }

        $value = $reflectionProperty->getValue($object);

        // Cast object to their id
        if (is_object($value)) {
            return $value->getId();
        }

        return $value;
    }

    private function updateData(array $data, $attribute, $attributeValue)
    {
        if ($this->nameConverter) {
            $attribute = $this->nameConverter->normalize($attribute);
        }

        $data[$attribute] = $attributeValue;

        return $data;
    }
}
