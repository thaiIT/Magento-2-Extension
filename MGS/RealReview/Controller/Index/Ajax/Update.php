<?php

namespace MGS\RealReview\Controller\Index\Ajax;

class Update extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    protected $reviewFactory;
    protected $ratingFactory;
    protected $_dataHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \MGS\RealReview\Helper\Data $dataHelper
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->reviewFactory = $reviewFactory;
        $this->ratingFactory = $ratingFactory;
        $this->_dataHelper = $dataHelper;
    }

    public function execute()
    {
        $response = [];
        $result = $this->resultJsonFactory->create();
        if (($data = $this->getRequest()->getPostValue()) && ($reviewId = $this->getRequest()->getParam('id'))) {
            $review = $this->reviewFactory->create()->load($reviewId);
            if (!$review->getId()) {
                $response['success'] = 0;
                $response['message'] = __('The review was removed or does not exist.');
            } else {
                try {
                    $review->addData($data)->save();

                    $arrRatingId = $this->getRequest()->getParam('ratings', []);
                    $votes = $this->_objectManager->create('Magento\Review\Model\Rating\Option\Vote')
                        ->getResourceCollection()
                        ->setReviewFilter($reviewId)
                        ->addOptionInfo()
                        ->load()
                        ->addRatingOptions();
                    foreach ($arrRatingId as $ratingId => $optionId) {
                        if ($vote = $votes->getItemByColumnValue('rating_id', $ratingId)) {
                            $this->ratingFactory->create()
                                ->setVoteId($vote->getId())
                                ->setReviewId($review->getId())
                                ->updateOptionVote($optionId);
                        } else {
                            $this->ratingFactory->create()
                                ->setRatingId($ratingId)
                                ->setReviewId($review->getId())
                                ->addOptionVote($optionId, $review->getEntityPkValue());
                        }
                    }
                    $review->aggregate();
                    $response['success'] = 1;
                    $response['message'] = __('The review has been updated.');
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $response['success'] = 0;
                    $response['message'] = $e->getMessage();
                } catch (\Exception $e) {
                    $response['success'] = 0;
                    $response['message'] = __('Something went wrong while updating this review.');
                }
            }
        } else {
            $response['success'] = 0;
            $response['message'] = __('Something went wrong while updating this review.');
        }
        return $result->setData($response);
    }

}