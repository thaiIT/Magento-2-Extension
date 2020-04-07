<?php

namespace Astir\FavList\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Astir\FavList\Model\FavListFactory;
use Magento\Framework\Registry;

class Edit extends Action
{
    protected $_resultPageFatory;
    protected $_listFactory;
    protected $_coreRegistry;

    public function __construct(Action\Context $context, PageFactory $pageFactory, FavListFactory $listFactory, Registry $registry)
    {
        parent::__construct($context);
        $this->_resultPageFatory = $pageFactory;
        $this->_listFactory = $listFactory;
        $this->_coreRegistry = $registry;
    }

    public function execute()
    {
        $listId = $this->getRequest()->getParam('id');
        $model = $this->_listFactory->create();
        if ($listId) {
            $model->load($listId);
            if (!$model->getListId()) {
                $this->messageManager->addError(__("This folder no longer exists"));
                return $this->_redirect("*/*/");
            }
            $title = "Edit Favourites List: ".$model->getTitle();
        } else {
            $title = "Add New Favourites List";
        }
        $data = $this->_session->getFormData(true);
        if(!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('list',$model);
        $resultPage = $this->_resultPageFatory->create();
        $resultPage->setActiveMenu("Magento_Customer::customer_favlist");
        $resultPage->getConfig()->getTitle()->prepend(__($title));
        return $resultPage;
    }
}