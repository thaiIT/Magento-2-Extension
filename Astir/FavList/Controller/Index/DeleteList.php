<?php

namespace Astir\FavList\Controller\Index;

use Magento\Framework\App\Action\Context;
use Astir\FavList\Model\FavListFactory;

class DeleteList extends \Magento\Framework\App\Action\Action
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
        $id = $this->getRequest()->getParam('id');
        if($id) {
            try {
               $model = $this->_listFactory->create();
               $model->load($id);
               if($model->getListId()) {
                   $model->delete();
                   $this->helper->flushCache();
                   $this->messageManager->addSuccess(__("Folder has been successfully removed"));
                   return $this->_redirect("amlist");
               } else {
                   $this->messageManager->addError(__("This folder no longer exists"));
                   return $this->_redirect("amlist");
               }
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                return $this->_redirect("amlist");
            }
        } else {
            $this->messageManager->addError(__("We can not find any id to delete"));
            return $this->_redirect("amlist");
        }
    }
}