<?php

namespace MGS\FShippingBar\Block;

use Magento\Framework\View\Element\Template;

class Fsbheader extends Template
{
    protected $_customerSession;
    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
		\MGS\FShippingBar\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerSession = $customerSession;
		$this->_helper = $helper;
    }
    
    public function getCustomerGroupId()
    {   
        if($this->isLoggedIn()) {
            $customerGroupId = $this->_customerSession->getCustomer()->getGroupId();
        } else {
            $customerGroupId = 0;
        }
        return $customerGroupId;
    }

    public function isLoggedIn() {
        return $this->_customerSession->isLoggedIn();
    }

    public function getCustomerGroupConfig() {
        $customerGroupConfig = $this->_helper->getCustomerGroup();
        return explode(',',$customerGroupConfig);
    }

    public function inCustomerGroup() {
        return in_array($this->getCustomerGroupId(), $this->getCustomerGroupConfig());
    }
}