<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Observer\Frontend\Quote\Item;

class Qty implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        $quoteItem = $observer->getEvent()->getItem();
        if ($quoteItem->getOptionByCode('requestquote_price') &&
            $quoteItem->getOrigData('qty') != $quoteItem->getData('qty')
        ) {
            $quoteItem->setHasError(true);
            $quoteItem->setMessage(__('It is not possible to edit items quantity of the approved Quote.'));
        }

        return $this;
    }
}
