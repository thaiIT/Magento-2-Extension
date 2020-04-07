<?php

namespace Astir\FavList\Block;

use Magento\Framework\View\Element\Template;
use Astir\FavList\Model\ItemFactory;
use Magento\Framework\Registry;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Astir\FavList\Helper\Data;
use Magento\Catalog\Model\ProductFactory;

class History extends Template
{
    protected $_itemFactory;
	protected $helperData;
    protected $_coreRegistry;
    protected $_orderCollection;
    protected $_productFactory;

    public function __construct(ItemFactory $itemFactory, Data $helperData, Registry $registry, CollectionFactory $orderCollection, ProductFactory $productFactory,Template\Context $context, array $data = [] ){
        parent::__construct($context, $data);
        $this->_itemFactory = $itemFactory;
        $this->helperData = $helperData;
        $this->_coreRegistry = $registry;
        $this->_orderCollection=$orderCollection;
        $this->_productFactory = $productFactory;
    }

	public function getList() {
		return $this->_coreRegistry->registry('current_list');
	}

    public function getItems() {
        $itemModel = $this->_itemFactory->create();
        return $itemModel->getCollection()->addFieldToFilter("list_id",["eq"=>$this->getList()->getListId()]);
    }

    public function getCustomerId() {
        return $this->helperData->getCustomerId();
    }

    public function getOrderData($product_id) {
        $ordercollection = $this->_orderCollection->create();
        $ordercollection->addFieldToSelect('*');
        $ordercollection->addAttributeToFilter('customer_id',$this->getCustomerId());
        $ordercollection->getSelect()
            ->join(
                'sales_order_item',
                'main_table.entity_id = sales_order_item.order_id',
                ['sales_order_item.created_at as sales_order_item_created_at','sales_order_item.qty_ordered as  qty_ordered_item']
            )->where('product_id = '.$product_id);
        $ordercollection->setOrder('created_at','DESC')->load();
        return $ordercollection;
    }

    public function getProductById($product_id) {
        return $this->_productFactory->create()->load($product_id);
    }
}