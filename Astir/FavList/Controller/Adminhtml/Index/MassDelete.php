<?php

namespace Astir\FavList\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Astir\FavList\Model\FavListFactory;

class MassDelete extends Action
{

    protected $_filter;
    protected $_favListFactory;

    public function __construct(Action\Context $context, Filter $filter, FavListFactory $favListFactory)
    {
        $this->_filter = $filter;
        $this->_favListFactory = $favListFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $listCollection = $this->_favListFactory->create()->getCollection();
        $collection = $this->_filter->getCollection($listCollection);
        $numberRecord = $collection->getSize();
        foreach ($collection as $item) {
            $item->delete();
        }
        $this->messageManager->addSuccess(__('A total of %1 records have been deleted',$numberRecord));
        return $this->_redirect("*/*/");
    }
}