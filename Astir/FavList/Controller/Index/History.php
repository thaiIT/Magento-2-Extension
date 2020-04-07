<?php

namespace Astir\FavList\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Astir\FavList\Model\FavListFactory;
use Magento\Framework\Registry;

class History extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $customerSession;
    protected $helper;
    protected $_listFactory;
    protected $_coreRegistry;

    public function __construct (
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Astir\FavList\Helper\Data $helper,
        FavListFactory $listFactory,
        Registry $registry
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->helper = $helper;
        $this->_listFactory=$listFactory;
        $this->_coreRegistry = $registry;
    }
    
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $isLogin = $this->customerSession->isLoggedIn();

        if(!$isLogin) {
            $url = $this->_url->getUrl('amlist');
            $customerBeforeAuthUrl = $this->_url->getUrl('customer/account/login', ['referer' => base64_encode($url)]);
            return $resultRedirect->setPath($customerBeforeAuthUrl);
        }
        
        if ($this->helper->isEnable() == 0) {
            $customerAccountUrl = $this->_url->getUrl('customer/account/');
            return $resultRedirect->setPath($customerAccountUrl);
        }

        $id = $this->getRequest()->getParam('id');

        if(!$id) {
            $this->messageManager->addError(__("We can not find id."));
            $customerAccountUrl = $this->_url->getUrl('customer/account/');
            return $resultRedirect->setPath($customerAccountUrl);
        } else {
            $listModel = $this->_listFactory->create()->load($id);
            $this->_coreRegistry->register('current_list',$listModel);
        }

        $resultPage = $this->resultPageFactory->create();
        $navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('amlist');
        }

        $resultPage->getConfig()->getTitle()->set(__('My Favourites'));
        return $resultPage;
    }
}