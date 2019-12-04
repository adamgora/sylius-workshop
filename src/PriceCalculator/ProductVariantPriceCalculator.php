<?php

namespace App\PriceCalculator;

use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductVariantPriceCalculator implements ProductVariantPriceCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        return 1000;
    }
}
