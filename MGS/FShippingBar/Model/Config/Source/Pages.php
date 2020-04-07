<?php

namespace MGS\FShippingBar\Model\Config\Source;

class Pages implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
    {
        return [
        	['value' => 'all', 'label' => __('All Pages')], 
        	['value' => 'home', 'label' => __('Home Page')],
        	['value' => 'category', 'label' => __('Catalog Pages')],
        	['value' => 'product', 'label' => __('Product Pages')],
        	['value' => 'cart', 'label' => __('Shopping cart')],
        	['value' => 'checkout', 'label' => __('Checkout')]
        ];

    }
}