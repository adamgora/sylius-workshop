<?php

namespace App\PriceCalculator;

use App\Provider\UnitPriceFactorProviderInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductVariantPriceCalculator implements ProductVariantPriceCalculatorInterface
{
    /**
     * @var UnitPriceFactorProviderInterface
     */
    private $unitPriceFactorProvider;

    /**
     * @var ProductVariantPriceCalculatorInterface
     */
    private $baseVariantPriceCalculator;

    public function __construct(
        ProductVariantPriceCalculatorInterface $baseVariantPriceCalculator,
        UnitPriceFactorProviderInterface $unitPriceFactorProvider
    ) {
        $this->unitPriceFactorProvider = $unitPriceFactorProvider;
        $this->baseVariantPriceCalculator = $baseVariantPriceCalculator;
    }

    /**
     * @inheritDoc
     */
    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        $basePrice = $this->baseVariantPriceCalculator->calculate($productVariant, $context);

        if (!$context['orderItem']) {
            return $basePrice;
        }

        return (int) $basePrice * $this->unitPriceFactorProvider->provide($context['orderItem']);
    }
}
