<?php
namespace TH\RatedProduct\Block;
use Magento\Framework\View\Element\Template;
class BestsellerProduct extends \Magento\Catalog\Block\Product\Widget\NewWidget
{
	public function getProductCollection($productId) 
	{
		$obj = \Magento\Framework\App\ObjectManager::getInstance();
		$productCollection = $obj->create('Magento\Catalog\Model\Product')->load($productId);
		return $productCollection;
	}

	public function getAllBestsellerProductId()
	{
		$limit = 10;
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$productCollection = $objectManager->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory');
		$collection = $productCollection->create();
		$collection->setPageSize($limit);
		$arrProId = [];
		foreach ($collection as $_product) { 
            $arrProId[] = $_product->getProductId();
		}
		return $arrProId;
	}
}