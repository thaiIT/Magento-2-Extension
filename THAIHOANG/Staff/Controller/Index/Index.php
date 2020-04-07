<?php

namespace THAIHOANG\Staff\Controller\Index;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{
    public $pageFactory;
    public function __construct(Context $context,PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $this->_view->getPage()->getConfig()->getTitle()->set(__("My Staff"));
        $resultPage = $this->pageFactory->create();
        return $resultPage;
    }
}