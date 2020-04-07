<?php

namespace MGS\FShippingBar\Model\Config\Source;

class Customergroup extends \Magento\Backend\Block\Template
{
	protected $_customerGroup;
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup,        
		array $data = []
	) {
		$this->_customerGroup = $customerGroup;        
		parent::__construct($context, $data);
	}

	public function toOptionArray() {
		return $this->_customerGroup->toOptionArray();
	}
}