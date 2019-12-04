<?php

namespace App\Twig\Helper;

use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\Templating\Helper\Helper;
use Webmozart\Assert\Assert;

class PriceHelper extends Helper
{
    /** @var ProductVariantPriceCalculatorInterface */
    private $productVariantPriceCalculator;
    /**
     * @var CustomerContextInterface
     */
    private $customerContext;

    public function __construct(
        ProductVariantPriceCalculatorInterface $productVariantPriceCalculator,
        CustomerContextInterface $customerContext
    )
    {
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
        $this->customerContext = $customerContext;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function getPrice(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');

        $customer = $this->customerContext->getCustomer();

        if(null !== $customer) {
            $context['customer'] = $customer;
        }

        return $this
            ->productVariantPriceCalculator
            ->calculate($productVariant, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'sylius_calculate_price';
    }
}
