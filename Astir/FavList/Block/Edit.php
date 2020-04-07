<?php

namespace Astir\FavList\Block;

use Magento\Framework\View\Element\Template;
use Astir\FavList\Model\FavListFactory;
use Astir\FavList\Model\ItemFactory;
use Astir\FavList\Helper\Data;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Catalog\Block\Product\Context as CatalogContext;

class Edit extends Template
{

	protected $_listFactory;
    protected $_itemFactory;
    protected $_helperData;
    protected $_productFactory;
    protected $formKey;
    protected $_orderCollectionFactory ;
    protected $imageBuilder;

    public function __construct(
    	FavListFactory $listFactory,
        ItemFactory $itemFactory,
        Data $helperData,
        ProductFactory $productFactory,
        FormKey $formKey,
        CollectionFactory $orderCollectionFactory,
        CatalogContext $catalogContext,
        Template\Context $context, array $data = []
    ){
        parent::__construct($context, $data);
        $this->_listFactory=$listFactory;
        $this->_itemFactory = $itemFactory;
        $this->_helperData = $helperData;
        $this->_productFactory = $productFactory;
        $this->formKey = $formKey;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->imageBuilder = $catalogContext->getImageBuilder();
    }

    public function getListCollectionById($listId) {
        $listModel = $this->_listFactory->create();
        return $listModel->load($listId);
    }

    public function getItemsByListId($listId) {
        $itemModel = $this->_itemFactory->create();
        $items = $itemModel->getCollection()
        ->addFieldToFilter("list_id",["eq"=>$listId]);
        return $items;
    }
    public function getProductById($proId) {
        return $this->_productFactory->create()->load($proId);
    }

    public function getCustomerId() {
        return $this->_helperData->getCustomerId();
    }

    public function getListId() {
        $listId = $this->getData("list_id");
        return $listId;
    }

    public function getFormKey() {
        return $this->formKey->getFormKey();
    }

    public function getOrderMonths() {
        $order_months = array();
        $order_months[] = array(
            'from' => date("Y-m-1 00:00:00", strtotime("-1 month")),
            'to' => date("Y-m-t 23:59:59", strtotime("-1 month"))
        );
        $order_months[] = array(
            'from' => date("Y-m-1 00:00:00", strtotime("-2 month")),
            'to' => date("Y-m-t 23:59:59", strtotime("-2 month"))
        );
        $order_months[] = array(
            'from' => date("Y-m-1 00:00:00", strtotime("-3 month")),
            'to' => date("Y-m-t 23:59:59", strtotime("-3 month"))
        );
        $order_months[] = array(
            'from' => date("Y-m-1 00:00:00", strtotime("-4 month")),
            'to' => date("Y-m-t 23:59:59", strtotime("-4 month"))
        );
        foreach ($order_months as $key => $month) {
            $orders = $this->_orderCollectionFactory->create()
                        ->addFieldToSelect('*')
                        ->addFieldToFilter('customer_id', $this->getCustomerId());
            $orders->addAttributeToFilter('created_at', array(
                'from' => $month['from'],
                'to' => $month['to']
            ));
            $order_months[$key]['orders'] = $orders;
        }
        return $order_months;
    }

    public function getImage($product, $imageId, $attributes = []) {
        return $this->imageBuilder->create($product, $imageId, $attributes);
    }
}