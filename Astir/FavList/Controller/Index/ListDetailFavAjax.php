<?php
namespace Astir\FavList\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class ListDetailFavAjax extends Action
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
            if ($this->getListId()) {
                $response['content'] = $this->layoutFactory->create()->createBlock('Astir\FavList\Block\Edit')->setListId($this->getListId())->setTemplate('Astir_FavList::view/content_list.phtml')->toHtml();
            } else {
                $response['content'] = $this->layoutFactory->create()->createBlock('Astir\FavList\Block\Edit')->setTemplate('Astir_FavList::view/content_list.phtml')->toHtml();
            }
        }
        catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = __($e->getMessage());
            $response['content'] = '';
        }
        return $result->setData($response);
    }
    public function getListId() {
        $listId = isset($_POST['list_id']) ? $_POST['list_id'] : false;
        return $listId;
    }
}