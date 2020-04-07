<?php

namespace Astir\FavList\Controller\Index;

use Magento\Framework\App\Action\Context;
use Astir\FavList\Model\FavListFactory;

class SetDefaultList extends \Magento\Framework\App\Action\Action
{
    protected $_listFactory;
    protected $helper;

    public function __construct (
        Context $context,
        FavListFactory $listFactory,
        \Astir\FavList\Helper\Data $helper
    ){
        $this->_listFactory=$listFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $listId = (int) $this->getRequest()->getParam('id');
        $model =   $this->_listFactory->create();

        if(!$model->load($listId)->getId()) {
            $this->messageManager->addError(__("This folder no longer exists"));
            return $this->_redirect("amlist");
        }

        $lists =  $model->getCollection()
                        ->addFieldToFilter("customer_id",["eq"=>$this->getCustomerId()])
                        ->addFieldToFilter("is_default",["eq"=>1]);
        
        if(count($lists)) {
            foreach ($lists as $value) {
                $model->load($value->getListId())->setIsDefault(0)->save();
                $this->helper->flushCache();
            }
        }

        if ($listId) {
            $model->load($listId)->setIsDefault(1)->save();
            $this->helper->flushCache();
            $this->messageManager->addSuccess(__('Folder %1 has been set as default.', $model->load($listId)->getTitle()));
        }
        
        return $this->_redirect("amlist");
    }

    public function getCustomerId() {
        return $this->helper->getCustomerId();
    }
}