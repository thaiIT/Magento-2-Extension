<?php

namespace MGS\FShippingBar\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Checkout\CustomerData\Cart as CoreCart;

class Cart extends CoreCart
{
    protected $checkoutSession;
    protected $checkoutCart;
    protected $catalogUrl;
    protected $quote = null;
    protected $checkoutHelper;
    protected $itemPoolInterface;
    protected $summeryCount;
    protected $layout;
    protected $blockFreeship;
    protected $helper;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Checkout\Model\Cart $checkoutCart,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Magento\Checkout\CustomerData\ItemPoolInterface $itemPoolInterface,
        \Magento\Framework\View\LayoutInterface $layout,
        \MGS\FShippingBar\Block\Freeshippingbar\Freeshipbar $blockFreeship,
        \MGS\FShippingBar\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($checkoutSession, $catalogUrl, $checkoutCart, $checkoutHelper, $itemPoolInterface, $layout, $data);
        $this->blockFreeship = $blockFreeship;
        $this->helper = $helper;
    }

    public function getSectionData()
    {
        $totals = $this->getQuote()->getTotals();
        $subtotalAmount = $totals['subtotal']->getValue();
        return [
            'summary_count' => $this->getSummaryCount(),
            'subtotalAmount' => $subtotalAmount,
            'subtotal' => isset($totals['subtotal'])
                ? $this->checkoutHelper->formatPrice($subtotalAmount)
                : 0,
            'possible_onepage_checkout' => $this->isPossibleOnepageCheckout(),
            'items' => $this->getRecentItems(),
            'extra_actions' => $this->layout->createBlock(\Magento\Catalog\Block\ShortcutButtons::class)->toHtml(),
            'isGuestCheckoutAllowed' => $this->isGuestCheckoutAllowed(),
            'website_id' => $this->getQuote()->getStore()->getWebsiteId(),
            'finalcost' => $this->blockFreeship->getFinalCost(),
            'freeshippinggoal' => $this->helper->getFshippingGoal(),
            'empty_goal' => $this->helper->getConfig('mgs_fshippingbar/content_fshipping/empty_goal'),
            'before_achieved_goal' => $this->helper->getConfig('mgs_fshippingbar/content_fshipping/before_achieved_goal'),
            'after_achieved_goal' => $this->helper->getConfig('mgs_fshippingbar/content_fshipping/after_achieved_goal'),
            'awayfromdhippingprice' => $this->blockFreeship->getAwayFromShippingPrice()
        ];
    }
}
