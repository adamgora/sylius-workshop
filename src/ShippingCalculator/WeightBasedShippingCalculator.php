<?php

namespace App\ShippingCalculator;

use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

class WeightBasedShippingCalculator implements CalculatorInterface
{

    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        // TODO: Implement calculate() method.
    }

    public function getType(): string
    {
        // TODO: Implement getType() method.
    }
}
