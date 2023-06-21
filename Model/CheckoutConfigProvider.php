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

namespace Ultraplugin\OrderComment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * XML paths for extension configurations
     */
    public const XML_PATH_EXTENSION_ENABLE = 'up_order_comment/general/enable';
    public const XML_PATH_COMMENT_LABEL = 'up_order_comment/general/comment_label';
    public const XML_PATH_COMMENT_PLACEHOLDER = 'up_order_comment/general/comment_placeholder_text';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * CheckoutConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Set comment configuration data
     *
     * @return array[]
     */
    public function getConfig()
    {
        $config = [
            'up_order_comment' => [
                'is_enabled' => $this->isEnabled(),
                'comment_label' => __($this->getCommentLabel()),
                'comment_placeholder' => __($this->getCommentPlaceholder())
            ]
        ];
        return $config;
    }

    /**
     * Check is order comment is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_EXTENSION_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get order comment label
     *
     * @return string
     */
    public function getCommentLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_COMMENT_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get comment placeholder text
     *
     * @return string
     */
    public function getCommentPlaceholder()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_COMMENT_PLACEHOLDER,
            ScopeInterface::SCOPE_STORE
        );
    }
}
