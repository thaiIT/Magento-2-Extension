<?php

namespace Astir\FavList\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action {

	protected $_resultPageFatory;

	public function __construct(Context $context, PageFactory $pageFactory) {
		parent::__construct($context);
		$this->_resultPageFatory = $pageFactory;
	}
	
	public function execute() {
		$resultPage = $this->_resultPageFatory->create();
		$resultPage->setActiveMenu("Magento_Customer::customer_favlist");
		$resultPage->getConfig()->getTitle()->prepend(__("Favourites List"));
		return $resultPage;
	}
}