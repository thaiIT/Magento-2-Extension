<?php

namespace MGS\RealReview\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $reviewFactory;
    protected $resultRedirect;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        ResultFactory $result,
        \Magento\Framework\Registry $registry
    )
    {
        parent::__construct($context);
        $this->reviewFactory = $reviewFactory;
        $this->resultRedirect = $result;
        $registry->register('isSecureArea', true);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
        $reviewId = $this->getRequest()->getParam('review_id', false);
        $review = $this->reviewFactory->create()->load($reviewId);
        if (!$review->getId()) {
            $this->messageManager->addError(__('The review was removed or does not exist.'));
        } else {
            try {
                $this->reviewFactory->create()->setId($reviewId)->aggregate()->delete();
                $this->messageManager->addSuccess(__('The review has been deleted.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong delete review.'));
            }
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}