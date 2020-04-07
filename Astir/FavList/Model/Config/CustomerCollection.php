<?php

namespace Astir\FavList\Model\Config;

use Magento\Framework\Option\ArrayInterface;
use Magento\Customer\Model\CustomerFactory;

class CustomerCollection implements ArrayInterface 
{

	protected $customerFactory;

	public function __construct(CustomerFactory $customerFactory) {
		$this->customerFactory = $customerFactory;
	}
	
	public function toOptionArray() {
		$customers = [];
		$collection = $this->customerFactory->create()->getCollection()->addAttributeToSelect('*');
		foreach ($collection as $cus) {
			$customers[$cus->getId()] = $cus->getFirstname() . ' ' . $cus->getLastname() . ' - ' . $cus->getEmail();
		}

		return $customers;
	}

	public function getFirstname($customerId) {
		return $this->customerFactory->create()->load($customerId)->getFirstname();
	}

	public function getLastname($customerId) {
		return $this->customerFactory->create()->load($customerId)->getLastname();
	}

	public function getEmail($customerId) {
		return $this->customerFactory->create()->load($customerId)->getEmail();
	}
}