<?php

namespace MGS\FShippingBar\Block\Freeshippingbar;

use Magento\Framework\View\Element\Template;

class Freeshipbar extends Template
{
    public function __construct(
        Template\Context $context,
        \MGS\FShippingBar\Observer\CheckoutCartProductAddAfter $observer,
		\MGS\FShippingBar\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_observer = $observer;
		$this->_helper = $helper;
    }
    
    public function getFinalCost()
    {
        return $this->_observer->getPrice();
    }

    public function getAwayFromShippingPrice()
    {
        return $this->_observer->getAwayFromShippingPrice();
        
    }
}