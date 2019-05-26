<?php

namespace MGS\RealReview\Block\Product\View;

class ListView extends \Magento\Review\Block\Product\View\ListView
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $toolbar = $this->getLayout()->getBlock('product_review_list.toolbar.custom');
        if ($toolbar) {
            $dataHelper = \Magento\Framework\App\ObjectManager::getInstance()->create('MGS\RealReview\Helper\Data');
            if ($dataHelper->isEnable()) {
                $toolbar->setLimit($dataHelper->getReviewPerPage());
            }
            $toolbar->setCollection($this->getReviewsCollection());
            $this->setChild('toolbar', $toolbar);
        }

        return $this;
    }

    public function getReviewAnswer($reviewId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('review_detail');
        $fields = array('review_answer');
        $sql = $connection->select()
            ->from($tableName, $fields)
            ->where('review_id = ?', $reviewId);
        $result = $connection->fetchAll($sql);
        return $result;
    }

    public function filterOutputHtml($string)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $filterProvider = $objectManager->create("\Magento\Cms\Model\Template\FilterProvider");
        return $filterProvider->getPageFilter()->filter($string);
    }
}
