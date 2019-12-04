<?php

namespace App\Provider;

use App\Entity\Order\OrderItem;

interface UnitPriceFactorProviderInterface
{
    public function provide(OrderItem $orderItem): float;
}
