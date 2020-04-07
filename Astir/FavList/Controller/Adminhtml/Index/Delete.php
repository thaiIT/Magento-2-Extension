<?php

namespace Astir\FavList\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Astir\FavList\Model\FavListFactory;

class Delete extends Action
{

    protected $_favListFactory;

    public function __construct(Action\Context $context, FavListFactory $favListFactory)
    {
        $this->_favListFactory = $favListFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if($id) {
            try {
                $model = $this->_favListFactory->create();
                $model->load($id);
                if($model->getListId()) {
                    $model->delete();
                    $this->messageManager->addSuccess(__("This folder has been deleted"));
                    return $this->_redirect("*/*/");
                } else {
                    $this->messageManager->addSuccess(__("This folder no longer exists"));
                    return $this->_redirect("*/*/");
                }
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                return $this->_redirect("*/*/");
            }
        } else {
            $this->messageManager->addError(__("We can not find any id to delete"));
            return $this->_redirect("*/*/");
        }
    }
}