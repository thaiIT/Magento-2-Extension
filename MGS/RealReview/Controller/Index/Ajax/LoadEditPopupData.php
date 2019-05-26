<?php
namespace MGS\RealReview\Controller\Index\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class LoadEditPopupData extends Action
{
    protected $resultJsonFactory;
    protected $layoutFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    public function execute()
    {

        $response = array();
        $result = $this->resultJsonFactory->create();
        try {
            $response['success'] = 1;
            $response['message'] = 'success';
            $response['content'] = $this->layoutFactory->create()->createBlock('MGS\RealReview\Block\Product\View\Edit\Content')->setProId($this->getProId())->setTemplate('MGS_RealReview::product/view/edit/review_edit_form.phtml')->toHtml();
        }
        catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = __($e->getMessage());
            $response['content'] = '';
        }
        return $result->setData($response);
    }

    public function getProId() {
        $proId = $_POST['product_id'];
        return $proId;
    }

}