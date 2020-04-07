<?php

namespace Astir\FavList\Controller\Index;

use Astir\FavList\Model\FavListFactory;
use Astir\FavList\Model\ItemFactory;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\ProductFactory;

class CartAction extends \Magento\Framework\App\Action\Action
{
	protected $_favFactory;
	protected $_cart;
	protected $_productFactory;
	protected $_itemFactory;

	public function __construct(Context $context, FavListFactory $favFactory, ItemFactory $itemFactory, Cart $cart, ProductFactory $productFactory)
    {
    	$this->_favFactory = $favFactory;
    	$this->_itemFactory = $itemFactory;
    	$this->_cart = $cart;
    	$this->_productFactory = $productFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $req = [];
        $listId = $this->getRequest()->getParam('id');
        $listModel = $this->_favFactory->create()->load($listId);
        if (!$listModel->getId()) {
            $this->_redirect('amlist');
            return;
        }
        foreach ($this->getItems($listId) as $item) {
        	try {
                $qty = $item->getQty();
                $product = $this->_productFactory->create()->load($item->getProductId())->setQty(max(0.01, $qty));
                $this->_cart->addProduct($product, ['qty' => $product->getQty()]);
            } catch (Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                return $this->_redirect('*/*/edit', array('id' => $listId));
            }
        }
        $this->_cart->save();
        $this->messageManager->addSuccess(__("Your favourites added to cart"));
        $this->_redirect('checkout/cart');
    }
    

    protected function getItems($listId) {
    	$itemModel = $this->_itemFactory->create();
        $items = $itemModel->getCollection()->addFieldToFilter("list_id",["eq"=>$listId]);
        return $items;
    }
}