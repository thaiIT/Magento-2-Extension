<?php

namespace TH\Test\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RemoveBlockProduct implements ObserverInterface
{
	protected $_registry;

	public function __construct(
		\Magento\Framework\Registry $registry
	) {
		$this->_registry = $registry;
	}

	public function execute(Observer $observer)
	{
		$product = $this->_registry->registry('current_product');
		if ($product){
			$attributeText = $product->getData('size');
			$attributeUrl = $product->getData('color');
	        if ($attributeText || $attributeUrl) {
				$layout = $observer->getLayout();
				$layout->unsetElement('product.info.price');
				// $layout->unsetElement('product.price.tier');
	        }
		}

	}
}