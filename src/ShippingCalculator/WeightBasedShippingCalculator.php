<?php

namespace App\ShippingCalculator;

use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

class WeightBasedShippingCalculator implements CalculatorInterface
{
    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        $totalWeight = 0.0;

        /** @var OrderItemInterface $item */
        foreach ($subject->getOrder()->getItems() as $item) {
            $totalWeight += $this->getWeight($item);
        }

        if ($totalWeight >= $configuration['limit_weight']) {
            return $configuration['above_or_equal'];
        }

        return $configuration['below'];
    }

    public function getType(): string
    {
        return 'weight_based';
    }

    private function getWeight(OrderItemInterface $item): float
    {
        return $item->getVariant()->getShippingWeight() * $item->getQuantity();
    }
}
