<?php
/**
 * UltraPlugin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ultraplugin.com license that is
 * available through the world-wide-web at this URL:
 * https://ultraplugin.com/end-user-license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    UltraPlugin
 * @package     Ultraplugin_OrderComment
 * @copyright   Copyright (c) UltraPlugin (https://ultraplugin.com/)
 * @license     https://ultraplugin.com/end-user-license-agreement
 */

namespace Ultraplugin\OrderComment\Plugin\Model\Order;

use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Api\Data\OrderExtensionFactory;

class SetOrderComment
{
    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var OrderExtensionFactory
     */
    protected $orderExtensionFactory;

    /**
     * SetOrderComment constructor.
     * @param OrderFactory $orderFactory
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(
        OrderFactory $orderFactory,
        OrderExtensionFactory $extensionFactory
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderExtensionFactory = $extensionFactory;
    }

    /**
     * Set order comment in order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {
        $this->setOrderComment($order);
        return $order;
    }

    /**
     * Set order comment in order list
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $orderSearchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $orderSearchResult
    ) {
        foreach ($orderSearchResult->getItems() as $order) {
            $this->setOrderComment($order);
        }
        return $orderSearchResult;
    }

    /**
     * Set order comment
     *
     * @param OrderInterface $order
     * @return void
     */
    public function setOrderComment(OrderInterface $order)
    {
        if ($order instanceof \Magento\Sales\Model\Order) {
            $comment = $order->getUpOrderComment();
        } else {
            $orderObj = $this->orderFactory->create();
            $orderObj->load($order->getId());
            $comment = $orderObj->getUpOrderComment();
        }

        $extensionAttributes = $order->getExtensionAttributes();
        $orderExtension = $extensionAttributes ?: $this->getOrderExtensionFactory()->create();
        $orderExtension->setUpOrderComment($comment);
        $order->setExtensionAttributes($orderExtension);
    }

    /**
     * Get extension factory
     *
     * @return OrderExtensionFactory
     */
    public function getOrderExtensionFactory()
    {
        return $this->orderExtensionFactory;
    }
}
