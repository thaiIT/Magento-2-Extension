<?php
namespace TH\RatedProduct\Block;
use Magento\Framework\View\Element\Template;
class HighestRated extends \Magento\Catalog\Block\Product\Widget\NewWidget
{
	public function getProductCollection($productId) 
	{
		$obj = \Magento\Framework\App\ObjectManager::getInstance();
		$productCollection = $obj->create('Magento\Catalog\Model\Product')->load($productId);
		return $productCollection;
	}

	// public function getAllRating() {
	// 	$obj = \Magento\Framework\App\ObjectManager::getInstance();
	// 	$ratingCollection = $obj->get('Magento\Review\Model\ResourceModel\Rating\Option\Vote\Collection')->addRatingInfo()->addOptionInfo()->addRatingOptions();
	// 	$ratingCollection->setOrder('percent','DESC');
	// 	return $ratingCollection;
	// } 

	// public function getAllProductIdByRating() {
	// 	$productIds = [];
	// 	$allRating = $this->getAllRating();
	// 	foreach ($allRating as $rating) {
	// 		$productIds[] = $rating['entity_pk_value'];
	// 	}
	// 	return $productIds;
	// }

	public function getAllProductIdByRating()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$reviewFactory = $objectManager->get(\Magento\Review\Model\ReviewFactory::class);
		$storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
		$productStatus = $objectManager->create('Magento\Catalog\Model\Product\Attribute\Source\Status');
		$productVisibility = $objectManager->create('Magento\Catalog\Model\Product\Visibility');
		$stockFilter = $objectManager->create('\Magento\CatalogInventory\Helper\Stock');
		$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
		$collection = $productCollection->create()->addAttributeToSelect('*');
		$collection->addAttributeToFilter('status', ['in' => $productStatus->getVisibleStatusIds()]);
		$collection->setVisibility($productVisibility->getVisibleInSiteIds());
		$stockFilter->addInStockFilterToCollection($collection);
		$collection->load();
		$rating = array();
		foreach ($collection as $product) {
			$reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
			$ratingSummary = $product->getRatingSummary()->getRatingSummary();
			if($ratingSummary!=null){
				$rating[$product->getId()] = $ratingSummary;
			}
		}
		arsort($rating);
		$arrProid = [];
		foreach($rating as $x => $x_value)
	   	{
	   		if ($x_value != 0) {
	   			$arrProid[] =  $x;
	   		}
	   	}
		return $arrProid;
	}
}