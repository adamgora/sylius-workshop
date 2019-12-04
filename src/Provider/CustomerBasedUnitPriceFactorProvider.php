<?php

namespace App\Provider;

use App\Entity\Customer\Customer;
use App\Entity\Order\OrderItem;

class CustomerBasedUnitPriceFactorProvider implements UnitPriceFactorProviderInterface
{

    public function provide(OrderItem $orderItem): float
    {
        /** @var Customer $customer */
        $customer = $orderItem->getOrder()->getCustomer();

        return $customer->isVip() ? 0.9 : 1.0;
    }
}
