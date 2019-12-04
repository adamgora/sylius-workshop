<?php

namespace App\AttributeType;

use Sylius\Component\Attribute\AttributeType\AttributeTypeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RangeAttributeType implements AttributeTypeInterface
{
    public const TYPE = 'range';

    public function getStorageType(): string
    {
        return AttributeValueInterface::STORAGE_JSON;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function validate(
        AttributeValueInterface $attributeValue,
        ExecutionContextInterface $context,
        array $configuration
    ): void
    {
    }
}
