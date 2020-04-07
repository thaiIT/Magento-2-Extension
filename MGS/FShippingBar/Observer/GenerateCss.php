<?php

namespace MGS\FShippingBar\Observer;

use Magento\Framework\Event\ObserverInterface;

class GenerateCss implements ObserverInterface
{
	protected $_helper;
	
	public function __construct(
		\MGS\FShippingBar\Helper\Data $helper
    ) {
		$this->_helper = $helper;
    }
	
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_helper->generateCss();
    }
}