<?php
namespace TH\RatedProduct\Block;
use Magento\Framework\View\Element\Template;
class SpecialOffers extends \Magento\Catalog\Block\Product\Widget\NewWidget
{
	public function getProductCollection($productId) 
	{
		$obj = \Magento\Framework\App\ObjectManager::getInstance();
		$productCollection = $obj->create('Magento\Catalog\Model\Product')->load($productId);
		return $productCollection;
	}

	public function getAllProductIdBySpecial()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$productStatus = $objectManager->create('Magento\Catalog\Model\Product\Attribute\Source\Status');
		$productVisibility = $objectManager->create('Magento\Catalog\Model\Product\Visibility');
		$stockFilter = $objectManager->create('\Magento\CatalogInventory\Helper\Stock');
		$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
		$collection = $productCollection->create()->addAttributeToSelect('*');
		$collection->addAttributeToFilter('status', ['in' => $productStatus->getVisibleStatusIds()]);
		$collection->setVisibility($productVisibility->getVisibleInSiteIds());
		$stockFilter->addInStockFilterToCollection($collection);
		$collection->load();
		$arrProId = [];
		$today =  time();
		foreach ($collection as $_product) { 
			$specialPrice = $_product->getPriceInfo()->getPrice('special_price')->getValue();
			if ($specialPrice) {
                            
                $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
                $finalPrice = $_product->getPriceInfo()->getPrice('final_price')->getValue();
                $regularPrice = $_product->getPriceInfo()->getPrice('regular_price')->getValue();
                $specialPriceFromDate = $_product->getSpecialFromDate();
				$specialPriceToDate = $_product->getSpecialToDate();
                if ($finalPrice > 0 && $regularPrice > 0 && $regularPrice > $finalPrice) {
                	if($today >= strtotime( $specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || 
								    $today >= strtotime( $specialPriceFromDate) && is_null($specialPriceToDate)) {
                		$arrProId[] = $_product->getId();
                	}
                }
            }
		}
		return $arrProId;
	}
}