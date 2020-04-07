<?php

namespace Astir\FavList\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $customerSession;
    protected $helper;

    public function __construct (
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Astir\FavList\Helper\Data $helper
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->helper = $helper;
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

        $resultPage = $this->resultPageFactory->create();
        $navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('amlist');
        }
        $resultPage->getConfig()->getTitle()->set(__('Favourites'));
        return $resultPage;
    }
}