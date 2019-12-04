<?php

namespace App\Controller;

use App\Entity\Customer\Customer;
use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;
use App\Entity\Product\ProductVariant;
use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
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
    /**
     * @var ShopperContextInterface
     */
    private $shopperContext;
    /**
     * @var StateMachineFactoryInterface
     */
    private $stateMachineFactory;

    public function __construct(
        ProductVariantRepositoryInterface $productVariantRepository,
        FactoryInterface $orderFactory,
        FactoryInterface $orderItemFactory,
        OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        ShopperContextInterface $shopperContext,
        StateMachineFactoryInterface $stateMachineFactory
    )
    {
        $this->productVariantRepository = $productVariantRepository;
        $this->orderFactory = $orderFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->shopperContext = $shopperContext;
        $this->stateMachineFactory = $stateMachineFactory;
    }

    public function __invoke(Request $request): Response
    {
        $order = $this->prepareOrder($request);
        $this->setOrderData($order);

        $orderCheckoutStateMachine = $this->stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_ADDRESS);
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_SHIPPING);
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_PAYMENT);
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_COMPLETE);
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

    /**
     * @param Order $order
     */
    private function setOrderData(Order $order): void
    {
        /** @var Customer $customer */
        $customer = $this->shopperContext->getCustomer();

        $order->setCustomer($customer);
        $order->setShippingAddress($customer->getDefaultAddress());
        $order->setBillingAddress($customer->getDefaultAddress());
        $order->setChannel($this->shopperContext->getChannel());
        $order->setLocaleCode($this->shopperContext->getLocaleCode());
        $order->setCurrencyCode($this->shopperContext->getCurrencyCode());
    }
}
