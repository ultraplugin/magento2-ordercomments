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

use Magento\Framework\Filter\FilterManager;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\QuoteRepository;

class PaymentInformationManagement
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
     * PaymentInformationManagement constructor.
     * @param FilterManager $filterManager
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        FilterManager $filterManager,
        QuoteRepository $quoteRepository
    ) {
        $this->filterManager = $filterManager;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Save order comment in quote
     *
     * @param \Magento\Checkout\Model\PaymentInformationManagement $subject
     * @param int $cartId
     * @param PaymentInterface $paymentMethod
     */
    public function beforeSavePaymentInformation(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
        $cartId,
        PaymentInterface $paymentMethod
    ) {
        $comment = '';
        $extensionAttributes = $paymentMethod->getExtensionAttributes();
        if ($extensionAttributes->getUpOrderComment()) {
            $comment = $this->filterManager->stripTags($extensionAttributes->getUpOrderComment());
        }
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setUpOrderComment($comment);
    }
}
