<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Observer\Frontend;

use Amasty\RequestQuote\Model\HidePrice\Provider;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item;

class HidePrice implements ObserverInterface
{
    /**
     * @var Provider
     */
    private $hidePriceProvider;

    public function __construct(Provider $hidePriceProvider)
    {
        $this->hidePriceProvider = $hidePriceProvider;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $items = $observer->getData('items');

        /** @var Item $item */
        foreach ($items as $item) {
            if ($this->hidePriceProvider->isHidePrice($item->getProduct())) {
                $item->setCustomPrice(0)->setOriginalCustomPrice(0);
            }
        }

        return $this;
    }
}
