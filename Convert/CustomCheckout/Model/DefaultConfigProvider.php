<?php

namespace Convert\CustomCheckout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\UrlInterface;

class DefaultConfigProvider implements ConfigProviderInterface
{
	protected $_urlInterface;

	public function __construct(
		UrlInterface $urlInterface
	) {
		$this->_urlInterface = $urlInterface;
	} 

	public function getConfig()
	{
		$output['takeTestUrl'] = $this->takeTestUrl();
		return $output;
	}

	public function takeTestUrl() {
		return $this->_urlInterface->getUrl('takeTestUrl');
	}

}