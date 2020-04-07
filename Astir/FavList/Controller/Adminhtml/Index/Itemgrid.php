<?php
 
namespace Astir\FavList\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Customer\Controller\RegistryConstants;

class Itemgrid extends Action
{

    protected $resultLayoutFactory;
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
		\Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
		$this->_coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout;
    }
}
