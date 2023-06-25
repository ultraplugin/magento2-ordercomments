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

namespace Ultraplugin\OrderComment\Plugin\Model\Checkout;

use Magento\Checkout\Model\GuestPaymentInformationManagement;
use Magento\Checkout\Model\Session;
use Magento\Framework\Filter\FilterManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Psr\Log\LoggerInterface;

class GuestPaymentInformationManagementPlugin
{
    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param FilterManager $filterManager
     * @param Session $checkoutSession
     * @param CartRepositoryInterface $cartRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        FilterManager $filterManager,
        Session $checkoutSession,
        CartRepositoryInterface $cartRepository,
        LoggerInterface $logger
    ) {
        $this->filterManager = $filterManager;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->logger = $logger;
    }

    /**
     * Save order comment in quote
     *
     * @param GuestPaymentInformationManagement $subject
     * @param int $cartId
     * @param string $email
     * @param PaymentInterface $paymentMethod
     */
    public function beforeSavePaymentInformation(
        GuestPaymentInformationManagement $subject,
        $cartId,
        $email,
        PaymentInterface $paymentMethod
    ) {
        try {
            $extensionAttributes = $paymentMethod->getExtensionAttributes();
            if ($extensionAttributes->getUpOrderComment()) {
                $comment = trim($extensionAttributes->getUpOrderComment());
                $orderComment = $this->filterManager->stripTags($comment);
                $quoteId = $this->checkoutSession->getQuoteId();
                $quote = $this->cartRepository->get($quoteId);
                $quote->setUpOrderComment($orderComment);
                $this->cartRepository->save($quote);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
