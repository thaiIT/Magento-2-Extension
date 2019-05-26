<?php

namespace MGS\RealReview\Helper;

use Magento\Framework\App\Helper\Context;
use MGS\RealReview\Model\Customer\Context as CustomerContext;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $httpContext;
    protected $_objectManager;
    protected $currentCustomer;

    public function __construct(
        Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        array $data = []
    )
    {
        $this->httpContext = $httpContext;
        $this->_objectManager = $objectManager;
        $this->currentCustomer = $currentCustomer;
        parent::__construct($context, $data);
    }

    const XML_PATH_ENABLE = "mgs_realreview/general/enable";
    const XML_PATH_AJAX = "mgs_realreview/general/use_ajax";
    const XML_PATH_AUTOAPPROVE = "mgs_realreview/general/auto_approve";
    const XML_PATH_ITEMS_PER_PAGE = "mgs_realreview/general/number_review";

    public function isEnable()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ENABLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function useAjax()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_AJAX, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function autoApprove()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_AUTOAPPROVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getReviewPerPage()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ITEMS_PER_PAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function customerLoggedIn()
    {
        return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getCustomerId()
    {
        return $this->httpContext->getValue(CustomerContext::CONTEXT_CUSTOMER_ID);
    }


}
