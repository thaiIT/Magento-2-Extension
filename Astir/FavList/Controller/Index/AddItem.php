<?php

namespace Astir\FavList\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Astir\FavList\Model\ItemFactory;
use Astir\FavList\Model\FavListFactory;

class AddItem extends \Magento\Framework\App\Action\Action
{
    protected $_itemFactory;
    protected $_listFactory;
    protected $resultPageFactory;
    protected $helper;

    public function __construct (
        Context $context,
        ItemFactory $itemFactory,
        FavListFactory $listFactory,
        \Astir\FavList\Helper\Data $helper
    ){
        parent::__construct($context);
        $this->_itemFactory = $itemFactory;
        $this->_listFactory = $listFactory;
        $this->helper = $helper;
    }
    
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $productId = $this->getRequest()->getParam('product');
        $itemModel = $this->_itemFactory->create();
        
        $qty = (isset($params['qty']) && !empty($params['qty'])) ? $params['qty'] : 1;

        if(!$this->getCurrentListId()) {
            if ($this->getCountList()) {
                $this->messageManager->addError(__("Please set a default list."));
            } else {
                $this->messageManager->addError(__("Please create a new list."));
            }
            return $this->_redirect("amlist");
        }

        if (in_array($productId, $this->getArrProductInList())) {
            $itemId = $this->getCurrentItemId($productId);
            $itemModel->load($itemId);
            $itemModel->setQty($qty);
            $itemModel->save();
            $this->helper->flushCache();
            return $this->_redirect("amlist/index/editList",array('id'=>$this->getCurrentListId()));
        }
        
        $itemModel->setListId($this->getCurrentListId());
        $itemModel->setProductId($productId);
        $itemModel->setQty($qty);
        $itemModel->save();
        $this->helper->flushCache();
        $this->messageManager->addSuccess(__("Product has been successfully added to the folder."));
        return $this->_redirect("amlist/index/editList",array('id'=>$this->getCurrentListId()));
    }

    public function getCurrentListId() {
        return $this->helper->getCurrentListId();
    }

    public function getCountList() {
        $model =   $this->_listFactory->create();
        $lists =   $model->getCollection()
                        ->addFieldToFilter("customer_id",["eq"=>$this->getCustomerId()]);
        return count($lists);
    }

    public function getCurrentItemId($productId) {
        $itemModel = $this->_itemFactory->create();
        $items = $itemModel->getCollection()
                        ->addFieldToFilter("list_id",["eq"=>$this->getCurrentListId()])
                        ->addFieldToFilter("product_id",["eq"=>$productId]);
        foreach ($items as $value) {
            return $value->getItemId();
        }
    }

    public function getArrProductInList() {
        return $this->helper->getArrProductInList();
    }

    public function getCustomerId() {
        return $this->helper->getCustomerId();
    }
}