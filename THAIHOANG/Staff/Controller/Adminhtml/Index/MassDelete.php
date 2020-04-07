<?php
namespace THAIHOANG\Staff\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use THAIHOANG\Staff\Model\ResourceModel\Staff\CollectionFactory;
class MassDelete extends \Magento\Backend\App\Action
{
    protected $_filter;
    protected $_collectionFactory;
    public function __construct(Action\Context $context, Filter $filter, CollectionFactory $collection)
    {
        $this->_filter = $filter;
        $this->_collectionFactory = $collection;
        parent::__construct($context);
    }
    public function execute()
    {
        $collectionObject = $this->_collectionFactory->create();
        $collection = $this->_filter->getCollection($collectionObject);
        $imageHelper = $this->_objectManager->get('THAIHOANG\Staff\Helper\Image');
        $numberRecord = $collection->getSize();
        foreach ($collection as $item) {
            $imageHelper->removeImage($item->getAvatar());
            $item->delete();
        }
       $this->messageManager->addSuccess(__('A total of %1 records have been deleted',$numberRecord));
        return $this->_redirect("*/*/");
    }
}