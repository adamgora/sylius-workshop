<?php

namespace App\Provider;

use App\Entity\Customer\Customer;

class CustomerBasedUnitPriceFactorProvider implements UnitPriceFactorProviderInterface
{

    public function provide(Customer $customer): float
    {
        return $customer->isVip() ? 0.9 : 1.0;
    }
}
