<?php
namespace THAIHOANG\Staff\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    const XML_PATH_ENABLE = "staff/view/enable";
    const XML_PATH_ITEMS_PER_PAGE = "staff/view/items_per_page";
    public function isEnable(){
        return $this->scopeConfig->getValue(self::XML_PATH_ENABLE,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getStaffPerPage(){
        return $this->scopeConfig->getValue(self::XML_PATH_ITEMS_PER_PAGE,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
