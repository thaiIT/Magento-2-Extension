<?php

namespace MGS\RealReview\Block;

class Form extends \Magento\Review\Block\Form
{
    protected $_objectManager;
    protected $_orderFactory;
    protected $_sessionFactory;
    protected $_dataHelper;
    protected $_reviewCollectionFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Review\Helper\Data $reviewData,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \MGS\RealReview\Helper\Data $dataHelper,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        array $data = []
    )
    {
        $this->_objectManager = $objectManager;
        $this->_orderFactory = $orderFactory;
        $this->_sessionFactory = $sessionFactory;
        $this->_dataHelper = $dataHelper;
        $this->_reviewCollectionFactory = $reviewCollectionFactory;
        parent::__construct($context, $urlEncoder, $reviewData, $productRepository, $ratingFactory, $messageManager, $httpContext, $customerUrl, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        if ($this->isEnable()) {
            if (!$this->getAlreadyPurchasedProduct()) {
                $this->setTemplate('MGS_RealReview::review/form.phtml');
            } else {
                if ($this->getAlreadyReview()) {
                    $this->setTemplate('MGS_RealReview::review/message_already_review.phtml');
                } else {
                    $this->setTemplate('MGS_RealReview::form.phtml');
                }
            }
        } else {
            $this->setTemplate('Magento_Review::form.phtml');
        }
    }

    public function getAlreadyPurchasedProduct()
    {
        if (!$this->getCustomerId()) {
            return false;
        }
        try {
            $product = $this->getProductInfo();
            $orders = $this->getCustomerOrders();
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    $items = $order->getAllVisibleItems();
                    foreach ($items as $item) {
                        if ($item->getProductId() == $product->getId()) {
                            return true;
                        }
                    }
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCustomerOrders()
    {
        $orderCollection = $this->_orderFactory->create()->getCollection()
            ->addFieldToFilter('customer_id', $this->getCustomerId())
            ->addFieldToFilter('status', \Magento\Sales\Model\Order::STATE_COMPLETE);
        return $orderCollection;
    }

    public function getCustomerId()
    {
        return $this->_dataHelper->getCustomerId();
    }

    public function isEnable()
    {
        return $this->_dataHelper->isEnable();
    }

    public function getAlreadyReview()
    {
        if (!$this->getCustomerId()) {
            return false;
        }
        try {
            $product = $this->getProductInfo();
            $collectionReview = $this->_reviewCollectionFactory->create()->addStoreFilter($this->getCurrentStoreId())
                ->addEntityFilter('product', $product->getId())
                ->setDateOrder();
            if (count($collectionReview) > 0) {
                foreach ($collectionReview as $collection) {
                    if($collection->getCustomerId() == $this->getCustomerId()) {
                        return true;
                    }
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getProId() {
        return $this->getProductInfo()->getId();
    }

    public function getAlreadyReviewApproved()
    {
        if (!$this->getCustomerId()) {
            return false;
        }
        try {
            $product = $this->getProductInfo();
            $collectionReview = $this->_reviewCollectionFactory->create()->addStoreFilter($this->getCurrentStoreId())
                ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
                ->addEntityFilter('product', $product->getId())
                ->setDateOrder();
            if (count($collectionReview) > 0) {
                foreach ($collectionReview as $collection) {
                    if($collection->getCustomerId() == $this->getCustomerId()) {
                        return true;
                    }
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCurrentStoreId() {
        $storeManager = $this->_objectManager->create('Magento\Store\Model\StoreManagerInterface');
        $currentStoreId = $storeManager->getStore()->getId();
        return $currentStoreId;
    }

    public function getFormAction() {
        if ($this->isEnable()) {
            if($this->_dataHelper->useAjax()) {
                $url = $this->getUrl('mgsrealreview/index_ajax/post');
            } else {
                $url = $this->getUrl('mgsrealreview/index/post', ['_secure' => $this->getRequest()->isSecure(),'id' => $this->getProductId(),]);
            }
        } else {
            $url = $this->getUrl('review/product/post', ['_secure' => $this->getRequest()->isSecure(),'id' => $this->getProductId(),]);
        }
        return $url;
    }


}