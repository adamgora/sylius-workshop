<?php

namespace App\Provider;

use App\Entity\Customer\Customer;

interface UnitPriceFactorProviderInterface
{
    public function provide(Customer $customer): float;
}
