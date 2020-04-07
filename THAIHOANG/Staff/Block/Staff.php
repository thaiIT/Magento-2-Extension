<?php
namespace THAIHOANG\Staff\Block;
use Magento\Framework\View\Element\Template;
use THAIHOANG\Staff\Model\ResourceModel\Staff\CollectionFactory;
use Magento\Framework\ObjectManagerInterface;
use THAIHOANG\Staff\Helper\Data;
class Staff extends Template
{
    protected $_staffFactory;
    protected $_objectManager;
    protected $_dataHelper;
    public function __construct(CollectionFactory $staffFactory,ObjectManagerInterface $objectManager,Data $dataHelper,Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_staffFactory=$staffFactory;
        $this->_objectManager=$objectManager;
        $this->_dataHelper=$dataHelper;
    }
    public function getBaseURLMedia(){
        $media=$this->_objectManager->get("Magento\Store\Model\StoreManagerInterface")
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media;
    }
    public function getStaffList() {
        $model = $this->_staffFactory->create();
        $staffs = $model->addFieldToFilter("status",["eq"=>true])
                        ->setCurPage($this->getCurrentPage())
                        ->setPageSize($this->getLimit());
        return $staffs;
    }

    public function getCurrentPage() {
        $request = $this->getRequest();
        if ($request->getParam("page")) {
            $page = $request->getParam("page");
        } else {
            $page = 1;
        }
        return $page;
    }

    public function getLimit() {
        $request = $this->getRequest();
        if($request->getParam('limit')) {
            $limit = $request->getParam('limit');
        } else {
            $limit = $this->_dataHelper->getStaffPerPage();
        }
        return $limit;
    }

    public function getPager() {
        $pager = $this->getChildBlock("staff_list_pager");
        $staffPerPage = $this->_dataHelper->getStaffPerPage();
        $collection = $this->getStaffList();
        $pager->setTemplate("THAIHOANG_Staff::pager.phtml");
        $pager->setPageVarName("page");
        $pager->setAvailableLimit([$staffPerPage=>$staffPerPage,3=>3,4=>4]);
        $pager->setTotalNum($collection->getSize());
        $pager->setCollection($collection);
        $pager->setShowPerPage(TRUE);
        return $pager->toHtml();
    }

    public function checkModuleEnable() {
        if($this->_dataHelper->isEnable == 0) {
            return false;
        } else {
            return true;
        }
    }


}