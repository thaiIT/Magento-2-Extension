<?php

namespace Astir\FavList\Block\Adminhtml\FavList\Edit\Tabs;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection as EavCollection;
use Magento\Store\Model\WebsiteFactory;
use Astir\FavList\Model\ItemFactory;
use Astir\FavList\Model\FavListFactory;

class Product extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{

    protected $_coreRegistry = null;
    protected $_storeManager;
    protected $_productFactory;
    protected $_productType;
    protected $_productVisibility;
    protected $_status;
    protected $_attrset;
    protected $_websiteFactory;
    protected $_itemFactory;
    protected $_listFactory;

    public function __construct(Context $context, Data $backendHelper, Registry $coreRegistry, StoreManagerInterface $storeManager, ProductFactory $productFactory, Type $type, Visibility $productVisibility, Status $status, EavCollection $eavCollection, WebsiteFactory $websiteFactory, ItemFactory $itemFactory, FavListFactory $listFactory, array $data = [] ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->_productType = $type;
        $this->_productVisibility = $productVisibility;
        $this->_status = $status;
        $this->_attrset = $eavCollection;
        $this->_websiteFactory = $websiteFactory;
        $this->_itemFactory = $itemFactory;
        $this->_listFactory=$listFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('productItem');
        $this->setUseAjax(true);
        $this->setEmptyText(__('No Folder Found'));
    }

    protected function getAmlistId() {
        $amlistId = $this->getRequest()->getParam('id');
        return $amlistId;
    }

    protected function _addColumnFilterToCollection($column) {
    	if ($this->getCollection()) {
            // if ($column->getId() == 'websites') {
            //     $this->getCollection()->joinField('websites', 'catalog_product_website', 'website_id', 'product_id=entity_id', null, 'left');
            // }
        }

        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedItems();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    protected function _prepareCollection()
    {
        if ($this->getListModel()->getListId()) {
            $this->setDefaultFilter(['in_products' => 1]);
        }
        $collection = $this->getCollectionProduct();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_products',
            [
                'type' => 'checkbox',
                'name' => 'in_products',
                'values' => $this->_getSelectedItems(),
                'index' => 'entity_id',
                'align' => 'center',
                'header_css_class' => 'a-center'
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'name' => 'entity_id',
                'header' => __('ID'),
                'index' => 'entity_id',
                'width' => '100px'
            ]
        );

        $this->addColumn(
            'name',
            [
                'name' => 'product_name',
                'header' => __('Name'),
                'index' => 'name',
                'align' => 'left'
            ]
        );

        $this->addColumn(
        	'type', 
        	[
                'name' => 'product_type',
	            'header' => __('Type'),
	            'width' => '60px',
	            'index' => 'type_id',
	            'type' => 'options',
	            'options' => $this->_productType->getOptionArray(),
        	]
        );

        $this->addColumn(
        	'set_name',
	        [
                'name' => 'product_setname',
	            'header' => __('Attrib. Set Name'),
	            'width' => '100px',
	            'index' => 'attribute_set_id',
	            'type' => 'options',
	            'options' => $this->getAttrSet(),
	        ]
		);

        $this->addColumn(
            'sku',
            [
                'name' => 'product_sku',
                'header' => __('SKU'),
                'index' => 'sku',
                'align' => 'left'
            ]
        );

        $this->addColumn(
        	'price', 
        	[
                'name' => 'product_price',
	            'header' => __('Price'),
	            'type' => 'price',
	            'currency_code' => '$',
	            'index' => 'price',
        	]
        );

        $this->addColumn(
        	'qty',
        	[
                'name' => 'product_qty',
	            'header' => __('Qty'),
                'width' => '100px',
                'type' => 'number',
                'index' => 'qty',
        	]
        );

        $this->addColumn(
            'visibility', 
            [
                'name'     => 'product_visibility',
                'header' => __('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type' => 'options',
                'options' => $this->_productVisibility->getOptionArray(),
            ]
        );

        $this->addColumn(
        	'status', 
        	[
                'name'   => 'product_status',
	            'header' => __('Status'),
	            'width' => '70px',
	            'index' => 'status',
	            'type' => 'options',
	            'options' => $this->_status->getOptionArray(),
	        ]
        );

        
     //    $this->addColumn(
     //    	'websites',
	    //     [
	    //         'header' => __('Websites'),
	    //         'width' => '100px',
	    //         'index' => 'websites',
	    //         'type' => 'options',
	    //         'sortable' => false,
	    //         'options' => $this->_websiteFactory->create()->getCollection()->toOptionHash(),
	    //     ]
	    // );
	    

        $this->addColumn(
        	'position', 
        	[
                'name'   => 'position',
	            'header' => __('Qty To Add'),
	            'type'   => 'number',
	            'validate_class' => 'validate-number',
	            'index' => 'position',
	            'width' => 60,
	            'sortable' => false,
	            'filter' => false,
	            'editable' => true,
	            'edit_only' => true
		    ]
	    );

        return parent::_prepareColumns();
    }

    protected function _getSelectedItems() {
        $productIds = $this->getProductIds();
        if (is_null($productIds)) {
            $productIds = array_keys($this->getSelectedProducts());
        }
        return $productIds;
    }

    public function getSelectedProducts() {
        $productIds = [];
        $amlistId = $this->getAmlistId();
        $listModel = $this->_listFactory->create()->load($amlistId);
        if ($listModel->getListId()) {
            $collection = $this->_itemFactory->create()->getCollection()
                    ->addFieldToFilter('list_id', $amlistId);
            foreach ($collection as $product) {
                $productIds[$product->getProductId()] = array('position' => $product->getQty());
            }
        }
        return $productIds;
    }

    public function getGridUrl()
    {
        return $this->getUrl('amlist/index/itemgrid', ['_current' => true]);
    }

    public function getRowUrl($item)
    {
        return 'javascript:void(0)';
    }

    protected function _afterToHtml($html)
    {
        $html .= $this->_getCustomJs();
        return parent::_afterToHtml($html);
    }

    protected function getListModel() 
    {
        $amlistId = $this->getAmlistId();
        return $this->_listFactory->create()->load($amlistId); 
    }



    protected function getStore() 
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    protected function getCollectionProduct() 
    {
        $collection =  $this->_productFactory->create()->getCollection()->addAttributeToSelect('sku')->addAttributeToSelect('name')->addAttributeToSelect('attribute_set_id')->addAttributeToSelect('type_id');

        $collection->joinField('qty', 'cataloginventory_stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        $collection->joinField('position', 'amlist_item', 'qty', 'product_id=entity_id', '{{table}}.list_id='.$this->getAmlistId(), 'left');

	    $collection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner');
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left');

        return $collection;
    }

    protected function getAttrSet() 
    {
    	$typeId = $this->_productFactory->create()->getResource()->getTypeId();
    	return $this->_attrset->setEntityTypeFilter($typeId)->load()->toOptionHash();
    }

    protected function getItemCollection()
    {
        $productData = [];
        $amlistId = $this->getAmlistId();
        $iCollection = $this->_itemFactory->create()->getCollection()->addFieldToFilter('list_id', $amlistId);
        foreach ($iCollection as $item) {
            array_push($productData,(object) [
                "key" => $item->getProductId(),
                "value" => $item->getQty()
            ]);
        }
        return $productData;
    }

    protected function _getCustomJs()
    {
        $arrDefault = count($this->getItemCollection()) ? json_encode($this->getItemCollection()) : '';
        $script = "<script type='text/javascript'>
        require([
            'jquery'
        ], function($) {
            var elmAppend = $('#staff_edit_tabs_product_content');
            
            if(!$('#amlist_product_ids').length) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'amlist_product_ids',
                    name: 'amlist[product_ids]',
                    value:  '$arrDefault'
                }).appendTo(elmAppend);
            }

            elmAppend.find('td input[type=checkbox]').change(function() {
                if($(this).is(':checked')) {
                    var posVal = $(this).closest('tr ').find('input[name=position]').val() ? $(this).closest('tr').find('input[name=position]').val() : 1;
                    var attachArray = $('#amlist_product_ids').val() ? JSON.parse($('#amlist_product_ids').val()) : [];
                    attachArray.push({
                        key : $(this).val(),
                        value : posVal
                    });
                    $('#amlist_product_ids').attr('value', JSON.stringify(attachArray));
                } else {
                    var attachArray = $('#amlist_product_ids').val() ? JSON.parse($('#amlist_product_ids').val()) : [];
                    attachArray = attachArray.filter(e => e.key !== $(this).val());
                    $('#amlist_product_ids').attr('value', JSON.stringify(attachArray));
                }
            });

            elmAppend.find('td input[name=position]').change(function() {
                var inputCheckbox = $(this).closest('tr').find('input[type=checkbox]');
                var posVal = $(this).val() ? $(this).val() : 1;
                if(inputCheckbox.is(':checked')) {
                    var attachArray = $('#amlist_product_ids').val() ? JSON.parse($('#amlist_product_ids').val()) : [];
                    attach = attachArray.find(e => e.key == inputCheckbox.val());
                    attach.value = posVal;
                    $('#amlist_product_ids').attr('value', JSON.stringify(attachArray));
                }
            });
        });
        </script>";
        return $script;
    }
}