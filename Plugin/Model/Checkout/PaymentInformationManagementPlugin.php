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

use Magento\Checkout\Model\PaymentInformationManagement;
use Magento\Framework\Filter\FilterManager;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\QuoteRepository;
use Psr\Log\LoggerInterface;

class PaymentInformationManagementPlugin
{
    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PaymentInformationManagement constructor.
     * @param FilterManager $filterManager
     * @param QuoteRepository $quoteRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        FilterManager $filterManager,
        QuoteRepository $quoteRepository,
        LoggerInterface $logger
    ) {
        $this->filterManager = $filterManager;
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
    }

    /**
     * Save order comment in quote
     *
     * @param PaymentInformationManagement $subject
     * @param int $cartId
     * @param PaymentInterface $paymentMethod
     */
    public function beforeSavePaymentInformation(
        PaymentInformationManagement $subject,
        $cartId,
        PaymentInterface $paymentMethod
    ) {
        try {
            $orderComment = '';
            $extensionAttributes = $paymentMethod->getExtensionAttributes();
            if ($extensionAttributes->getUpOrderComment()) {
                $comment = trim($extensionAttributes->getUpOrderComment());
                $orderComment = $this->filterManager->stripTags($comment);

            }
            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setUpOrderComment($orderComment);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
