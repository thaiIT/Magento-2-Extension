<?php

namespace Convert\CustomCheckout\Block\Checkout;

class LayoutProcessor {
	protected $httpContext;
	public function __construct(
		\Magento\Framework\App\Http\Context $httpContext,
		\Magento\Framework\App\ResponseFactory $responseFactory,
		\Magento\Framework\UrlInterface $url
	) {
		$this->httpContext = $httpContext;
	}
	/*
	public function aroundProcess($subject, $proceed, $jsLayout) {
		if ($this->customerLoggedIn()) {
			unset($jsLayout['components']['checkout']['children']['steps']['children']['checkout-login-step']);
			$returnValue = $proceed($jsLayout);
			return $returnValue;
		}
		return $proceed($jsLayout);
	}
	public function customerLoggedIn()
	{
		return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
	}
	*/
	public function afterProcess(
		\Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
		array  $jsLayout
	) {
      	//Shipping Address
		$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone']['sortOrder'] = 65;
		$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']['label'] = __('State');
		$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['label'] = __('Postal Code');
		unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone']['config']['tooltip']);
		return $jsLayout;
	}
}