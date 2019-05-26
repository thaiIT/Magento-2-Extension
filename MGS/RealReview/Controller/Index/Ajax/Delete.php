<?php

namespace MGS\RealReview\Controller\Index\Ajax;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    protected $reviewFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Framework\Registry $registry
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->reviewFactory = $reviewFactory;
        $registry->register('isSecureArea', true);
    }

    public function execute()
    {
        $response = [];
        $result = $this->resultJsonFactory->create();
        $reviewId = $_POST['review_id'];
        $review = $this->reviewFactory->create()->load($reviewId);
        if (!$review->getId()) {
            $response['success'] = 0;
            $response['message'] = __('The review was removed or does not exist.');
        } else {
            try {
                $this->reviewFactory->create()->setId($reviewId)->aggregate()->delete();
                $response['success'] = 1;
                $response['message'] = __('The review has been deleted.');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $response['success'] = 0;
                $response['message'] = $e->getMessage();
            } catch (\Exception $e) {
                $response['success'] = 0;
                $response['message'] = __('Something went wrong delete review.');
            }
        }
        return $result->setData($response);
    }

}