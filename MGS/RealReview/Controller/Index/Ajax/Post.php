<?php

namespace MGS\RealReview\Controller\Index\Ajax;

use Magento\Review\Model\Review;

class Post extends \Magento\Framework\App\Action\Action
{
    protected $formKeyValidator;
    protected $resultJsonFactory;
    protected $reviewFactory;
    protected $ratingFactory;
    protected $reviewSession;
    protected $productRepository;
    protected $dataHelper;
    protected $storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Framework\Session\Generic $reviewSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \MGS\RealReview\Helper\Data $dataHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->formKeyValidator = $formKeyValidator;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->reviewFactory = $reviewFactory;
        $this->ratingFactory = $ratingFactory;
        $this->reviewSession = $reviewSession;
        $this->productRepository = $productRepository;
        $this->dataHelper = $dataHelper;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $response = [];
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $response['success'] = 0;
            $response['message'] = __('Formkey error. Please reload the page and try again.');
        }
        $result = $this->resultJsonFactory->create();
        $data = $this->reviewSession->getFormData(true);
        if ($data) {
            $rating = [];
            if (isset($data['ratings']) && is_array($data['ratings'])) {
                $rating = $data['ratings'];
            }
        } else {
            $data = $this->getRequest()->getPostValue();
            $rating = $this->getRequest()->getParam('ratings', []);
        }
        if (($product = $this->initProduct()) && !empty($data)) {
            $review = $this->reviewFactory->create()->setData($data);
            $review->unsetData('review_id');
            $validate = $review->validate();
            if ($validate === true) {
                try {
                    $review->setEntityId($review->getEntityIdByCode(Review::ENTITY_PRODUCT_CODE))
                        ->setEntityPkValue($product->getId())
                        ->setCustomerId($this->dataHelper->getCustomerId())
                        ->setStoreId($this->storeManager->getStore()->getId())
                        ->setStores([$this->storeManager->getStore()->getId()]);
                    if ($this->dataHelper->autoApprove()) {
                        $review->setStatusId(Review::STATUS_APPROVED);
                    } else {
                        $review->setStatusId(Review::STATUS_PENDING);
                    }
                    $review->save();

                    foreach ($rating as $ratingId => $optionId) {
                        $this->ratingFactory->create()
                            ->setRatingId($ratingId)
                            ->setReviewId($review->getId())
                            ->setCustomerId($this->dataHelper->getCustomerId())
                            ->addOptionVote($optionId, $product->getId());
                    }

                    $review->aggregate();
                    $response['success'] = 1;
                    if ($this->dataHelper->autoApprove()) {
                        $response['message'] = __('You have rated this product.');
                    } else {
                        $response['message'] = __('You submitted your review for moderation.');
                    }
                } catch (LocalizedException $e) {
                    $this->reviewSession->setFormData($data);
                    $response['success'] = 0;
                    $response['message'] = __('We can\'t post your review right now.');
                }
            } else {
                $this->reviewSession->setFormData($data);
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $response['success'] = 0;
                        $response['message'] = $errorMessage;
                    }
                } else {
                    $response['success'] = 0;
                    $response['message'] = __('We can\'t post your review right now.');
                }
            }

        }
        return $result->setData($response);
    }

    public function initProduct()
    {
        $productId = (int)$this->getRequest()->getParam('product_id');
        $product = $this->loadProduct($productId);
        return $product;
    }

    protected function loadProduct($productId)
    {
        if (!$productId) {
            return false;
        }
        try {
            $product = $this->productRepository->getById($productId);
            if (!$product->isVisibleInCatalog() || !$product->isVisibleInSiteVisibility()) {
                throw new NoSuchEntityException();
            }
        } catch (NoSuchEntityException $noEntityException) {
            return false;
        }

        return $product;

    }


}