<?php 
namespace Astir\FavList\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Astir\FavList\Model\ItemFactory;
use Astir\FavList\Model\FavListFactory;

class Save extends \Magento\Framework\App\Action\Action 
{
	protected $_itemFactory;
    protected $_listFactory;
    protected $resultPageFactory;
    protected $helper;

	public function __construct (
        Context $context,
        ItemFactory $itemFactory,
        FavListFactory $listFactory,
        \Astir\FavList\Helper\Data $helper
    ){
        parent::__construct($context);
        $this->_itemFactory = $itemFactory;
        $this->_listFactory=$listFactory;
        $this->helper = $helper;
    }

	public function execute() {

		$listModel = $this->_listFactory->create();
		$request = $this->getRequest();
		$listId = $request->getParam("list_id");
		$formData = $request->getPostValue();
		if ($formData) {
			if($listId) {
				if (!$listModel->load($listId)->getListId()) {
					$this->messageManager->addError(__("This list no longer exists"));
					return $this->_redirect("amlist");
				}
				$listModel->load($listId)->setTitle($formData['list_title'])->save();
				$this->messageManager->addSuccess(__("Folder has been successfully saved"));
				return $this->_redirect("amlist/index/editList",["id"=>$listModel->getListId()]);
			} else {
				$listModel->setTitle($formData['list_title']);
				$listModel->setCustomerId($this->getCustomerId());
				$listModel->setCreatedAt(date('Y-m-d H:i:s'));
				$listModel->setIsDefault(0);
				$listModel->save();
				$this->helper->flushCache();
			}
			$this->messageManager->addSuccess(__("Folder has been successfully saved"));
			return $this->_redirect("amlist");
		}
		$this->messageManager->addError(__("Unable to find folder for saving"));
		return $this->_redirect("amlist");
	}

	public function getCustomerId() {
        return $this->helper->getCustomerId();
    }
}