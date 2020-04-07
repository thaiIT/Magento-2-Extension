<?php
namespace Astir\FavList\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class ListFavAjax extends Action
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
            $response['content'] = $this->layoutFactory->create()->createBlock('Astir\FavList\Block\Index')->setTemplate('Astir_FavList::list.phtml')->toHtml();
        }
        catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = __($e->getMessage());
            $response['content'] = '';
        }
        return $result->setData($response);
   }
}