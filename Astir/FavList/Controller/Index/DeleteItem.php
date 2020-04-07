<?php

namespace Astir\FavList\Controller\Index;

use Magento\Framework\App\Action\Context;
use Astir\FavList\Model\ItemFactory;

class DeleteItem extends \Magento\Framework\App\Action\Action
{
    protected $_itemFactory;
    protected $helper;

    public function __construct (
        Context $context,
        ItemFactory $itemFactory,
        \Astir\FavList\Helper\Data $helper
    ){
        $this->_itemFactory = $itemFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $listId = $this->getRequest()->getParam('listId');
        if($id) {
            try {
                $model = $this->_itemFactory->create();
                $model->load($id);
                if($model->getItemId()) {
                    $model->delete();
                    $this->helper->flushCache();
                    $this->messageManager->addSuccess(__("Item has been successfully removed"));
                    return $this->_redirect("amlist/index/editList",array('id'=>$listId));
                } else {
                    $this->messageManager->addError(__("This item no longer exists"));
                    return $this->_redirect("amlist/index/editList",array('id'=>$listId));
                }
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                return $this->_redirect("amlist/index/editList",array('id'=>$listId));
            }
        } else {
            $this->messageManager->addError(__("We can not find any id to delete"));
            return $this->_redirect("amlist");
        }
    }
}