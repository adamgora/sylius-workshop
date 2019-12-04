<?php

namespace App\Controller;

use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;
use App\Entity\Product\ProductVariant;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OneClickCheckoutAction
{
    /**
     * @var ProductVariantRepositoryInterface
     */
    private $productVariantRepository;
    /**
     * @var FactoryInterface
     */
    private $orderFactory;
    /**
     * @var FactoryInterface
     */
    private $orderItemFactory;
    /**
     * @var OrderItemQuantityModifierInterface
     */
    private $orderItemQuantityModifier;

    public function __construct(
        ProductVariantRepositoryInterface $productVariantRepository,
        FactoryInterface $orderFactory,
        FactoryInterface $orderItemFactory,
        OrderItemQuantityModifierInterface $orderItemQuantityModifier
    )
    {
        $this->productVariantRepository = $productVariantRepository;
        $this->orderFactory = $orderFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
    }

    public function __invoke(Request $request): Response
    {
        $order = $this->prepareOrder($request);
    }

    /**
     * @param Request $request
     * @return Order
     */
    private function prepareOrder(Request $request): Order
    {
        $variantId = $request->attributes->getInt('vartiantId');

        /** @var ProductVariant $productVariant */
        $productVariant = $this->productVariantRepository->find($variantId);

        /** @var Order $order */
        $order = $this->orderFactory->createNew();

        /** @var OrderItem $orderItem */
        $orderItem = $this->orderItemFactory->createNew();

        $orderItem->setVariant($productVariant);

        $this->orderItemQuantityModifier->modify($orderItem, 1);

        $order->addItem($orderItem);

        return $order;
    }
}
