<?php

namespace Astir\FavList\Block;

use Magento\Framework\View\Element\Template;
use Astir\FavList\Model\FavListFactory;
use Astir\FavList\Model\ItemFactory;

class Index extends Template
{

	protected $_listFactory;
    protected $_itemFactory;
	protected $helperData;

    public function __construct(
    	FavListFactory $listFactory,
        ItemFactory $itemFactory,
    	\Astir\FavList\Helper\Data $helperData,
    	Template\Context $context, array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_listFactory=$listFactory;
        $this->_itemFactory = $itemFactory;
        $this->helperData = $helperData;
    }

	public function getListCollection() {
		$model = $this->_listFactory->create();
        $lists = $model->getCollection()->addFieldToFilter("customer_id",["eq"=>$this->getCustomerId()]);
        return $lists;
	}

    public function getCountItems($listId) {
        $itemModel = $this->_itemFactory->create();
        $items = $itemModel->getCollection()
                        ->addFieldToFilter("list_id",["eq"=>$listId]);
        return count($items);
    }

    public function getCustomerId() {
        return $this->helperData->getCustomerId();
    }
}