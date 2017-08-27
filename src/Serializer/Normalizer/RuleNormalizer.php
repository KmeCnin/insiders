<?php

namespace App\Serializer\Normalizer;

use App\Entity\Rule\RuleInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class RuleNormalizer extends AbstractObjectNormalizer
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
        $normalizedData = $this->prepareForDenormalization($data);
        $reflectionClass = new \ReflectionClass($class);
        $object = $this->instantiateObject($normalizedData, $class, $context, $reflectionClass, false, $format);

        foreach ($normalizedData as $attribute => $value) {
            if ($this->nameConverter) {
                $attribute = $this->nameConverter->denormalize($attribute);
            }
        }
    }

    private function extractAttributes($object): array
    {
        $reflectionObject = new \ReflectionObject($object);

        $attributes = array();
        foreach ($reflectionObject->getProperties() as $property) {
            $attributes[] = $property->name;

            $value = $this->validateAndDenormalize($class, $attribute, $value, $format, $context);
            try {
                $this->setAttributeValue($object, $attribute, $value, $format, $context);
            } catch (InvalidArgumentException $e) {
                throw new UnexpectedValueException($e->getMessage(), $e->getCode(), $e);
            }
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
