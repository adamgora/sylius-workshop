<?php

namespace App\Controller;

use App\Entity\Customer\Customer;
use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;
use App\Entity\Product\ProductVariant;
use Doctrine\Common\Persistence\ObjectManager;
use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

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
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(
        ProductVariantRepositoryInterface $productVariantRepository,
        FactoryInterface $orderFactory,
        FactoryInterface $orderItemFactory,
        OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        ShopperContextInterface $shopperContext,
        StateMachineFactoryInterface $stateMachineFactory,
        ObjectManager $objectManager
    )
    {
        $this->productVariantRepository = $productVariantRepository;
        $this->orderFactory = $orderFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->shopperContext = $shopperContext;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->objectManager = $objectManager;
    }

    public function __invoke(Request $request): Response
    {
        $order = $this->prepareOrder($request);
        $this->setOrderData($order);
        $this->applyTransitions($order);
        $this->persist($order);
        $this->addSuccessMessage($request);

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @return Order
     */
    private function prepareOrder(Request $request): Order
    {
        $variantId = $request->attributes->getInt('variantId');

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

    /**
     * @param Order $order
     * @throws \SM\SMException
     */
    private function applyTransitions(Order $order): void
    {
        $orderCheckoutStateMachine = $this->stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_ADDRESS);
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_SHIPPING);
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_PAYMENT);
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_COMPLETE);
    }

    /**
     * @param Order $order
     */
    private function persist(Order $order): void
    {
        $this->objectManager->persist($order);
        $this->objectManager->flush();
    }

    /**
     * @param Request $request
     */
    private function addSuccessMessage(Request $request): void
    {
        /** @var FlashBagInterface $flashbag */
        $flashBag = $request->getSession()->getFlashBag();
        $flashBag->add('success', 'Thank you for placing this order');
    }
}
