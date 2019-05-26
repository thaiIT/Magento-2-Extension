<?php

namespace MGS\RealReview\Block\Product\View\Edit;

class Content extends \Magento\Framework\View\Element\Template
{
    protected $_objectManager;
    protected $_reviewsColFactory;
    protected $_dataHelper;
    protected $productRepository;
    protected $_ratingFactory;
    protected $_voteCollection = false;
    protected $jsLayout;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory,
        \MGS\RealReview\Helper\Data $dataHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory $votesFactory,
        array $data = []
    )
    {
        $this->_reviewsColFactory = $collectionFactory;
        $this->_dataHelper = $dataHelper;
        $this->productRepository = $productRepository;
        $this->_ratingFactory = $ratingFactory;
        $this->_votesFactory = $votesFactory;
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) ? $data['jsLayout'] : [];
    }

    public function getCustomerId()
    {
        return $this->_dataHelper->getCustomerId();
    }

    public function getProductInfo()
    {
        $proId = $this->getProductId();
        return $this->productRepository->getById($proId, false, $this->_storeManager->getStore()->getId());
    }

    public function getProductId() {
        if($proId = $this->getProId()) {
            $productId= $proId;
        } else {
            $productId = $this->getRequest()->getParam('id', false);
        }
        return $productId;
    }

    public function getReviewId() {
        foreach ($this->getCollection() as $r) {
            return $r->getReviewId();
        }
    }

    public function getEditAction()
    {
        if ($this->_dataHelper->useAjax()) {
            $url = $this->getUrl('mgsrealreview/index_ajax/update');
        } else {
            $url = $this->getUrl(
                'mgsrealreview/index/update',
                [
                    '_secure' => $this->getRequest()->isSecure(),
                    'id' => $this->getReviewId(),
                ]
            );
        }
        return $url;
    }

    public function getCollection()
    {
        $collection = $this->_reviewsColFactory->create()->addStoreFilter(
            $this->_storeManager->getStore()->getId()
        )->addStatusFilter(
            \Magento\Review\Model\Review::STATUS_APPROVED
        )->addEntityFilter(
            'product',
            $this->getProductId()
        )->addFieldToFilter(
            'customer_id',
            ["eq" => $this->getCustomerId()]
        );

        return $collection;
    }

    public function getRatings()
    {
        return $this->_ratingFactory->create()->getResourceCollection()->addEntityFilter(
            'product'
        )->setPositionOrder()->addRatingPerStoreName(
            $this->_storeManager->getStore()->getId()
        )->setStoreFilter(
            $this->_storeManager->getStore()->getId()
        )->setActiveFilter(
            true
        )->load()->addOptionToItems();
    }


    public function isSelected($option, $rating)
    {
        $this->_voteCollection = $this->_votesFactory->create()->setReviewFilter(
            $this->getReviewId()
        )->addOptionInfo()->load()->addRatingOptions();

        if ($this->_voteCollection) {
            foreach ($this->_voteCollection as $vote) {
                if ($option->getId() == $vote->getOptionId()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getJsLayout()
    {
        return \Zend_Json::encode($this->jsLayout);
    }
}