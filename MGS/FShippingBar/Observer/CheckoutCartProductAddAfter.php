<?php

namespace MGS\FShippingBar\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\Checkout\Model\Cart;
use MGS\FShippingBar\Helper\Data;
use Magento\Framework\App\RequestInterface;

class CheckoutCartProductAddAfter implements ObserverInterface
{
    public function __construct(
        Cart $cart,
        RequestInterface $request,		
        Data $helper,
		\Magento\Framework\Pricing\Helper\Data $priceHelper
    ) {
        $this->_cart = $cart;
		$this->_request = $request;
        $this->_helper = $helper;
		$this->_priceHelper = $priceHelper;
        
    }

    public function execute(Observer $observer)
    {
        return $this->getPrice();
    }
    
    public function getPrice()
    {
        $totals = $this->_cart->getQuote()->getTotals();
		
        $subTotal = $totals['subtotal']->getValue();        
        
        $freeshippingbarCost = $this->_helper->getFshippingGoal();
        
        if($subTotal <= $freeshippingbarCost){
            $finalCost = $freeshippingbarCost - $subTotal;
            return $finalCost;
        } else {
            return $subTotal;
        }
    }

    public function getAwayFromShippingPrice()
    {
        $totals = $this->_cart->getQuote()->getTotals();
        $subTotal = $totals['subtotal']->getValue();        
        
        $freeshippingbarCost = $this->_helper->getFshippingGoal();
        
        if($subTotal <= $freeshippingbarCost){
            $awayFromShippingPrice = $freeshippingbarCost - $subTotal;
            $awayFromShippingPriceWithCurrency = $this->_priceHelper->currency($awayFromShippingPrice, true, false);
            return $awayFromShippingPriceWithCurrency;
        } else {            
            return $this->_priceHelper->currency($subTotal, true, false);;
        }
        
    }
}

