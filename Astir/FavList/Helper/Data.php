<?php
namespace Astir\FavList\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\PageCache\Version;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLE = "amlist/general/active";
	protected $customerSession;
    protected $_listFactory;
    protected $_itemFactory;
    protected $cacheTypeList;
    protected $cacheFrontendPool;

    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Astir\FavList\Model\FavListFactory $listFactory,
        \Astir\FavList\Model\ItemFactory $itemFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, 
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
    ){
    	$this->customerSession = $customerSession;
        $this->_listFactory = $listFactory;
        $this->_itemFactory = $itemFactory;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context);
    }

    public function isEnable(){
        return $this->scopeConfig->getValue(self::XML_PATH_ENABLE,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function flushCache() {
        $this->cacheTypeList->cleanType('full_page');
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }

    public function getCustomerId() {
    	return $this->customerSession->getCustomerId();
    }

    public function getCurrentListId() {
        $model =   $this->_listFactory->create();
        $lists =   $model->getCollection()
                        ->addFieldToFilter("customer_id",["eq"=>$this->getCustomerId()])
                        ->addFieldToFilter("is_default",["eq"=>1]);
        foreach ($lists as $value) {
            return $value->getListId();
        }
    }

    public function getArrProductInList() {
        $arrProId = [];
        $itemModel = $this->_itemFactory->create();
        $items = $itemModel->getCollection()
                    ->addFieldToFilter("list_id",["eq"=>$this->getCurrentListId()]);
        foreach ($items as $value) {
            $arrProId[] = $value->getProductId();
        }
        return $arrProId;
    }

    public function checkProInList($proId) {
        return in_array($proId, $this->getArrProductInList());
    }

}
