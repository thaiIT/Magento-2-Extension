<?php 
namespace Astir\FavList\Controller\Index;

use Magento\Framework\App\Action\Context;
use Astir\FavList\Model\ItemFactory;

class UpdateSuggestedStock extends \Magento\Framework\App\Action\Action 
{
	protected $_itemFactory;

	public function __construct (
        Context $context,
        ItemFactory $itemFactory
    ){
        parent::__construct($context);
        $this->_itemFactory = $itemFactory;
    }

	public function execute() {
		$itemModel = $this->_itemFactory->create();
		$formData = $_POST['itemData'];
		if (count($formData)) {
			foreach ($formData as $key => $value) {
				$itemModel->load($key)->setQty($value)->save();
			}
			$this->messageManager->addSuccess(__("Quantities have been successfully updated"));
		} else {
			$this->messageManager->addError(__("Can not save. Please try again."));
		}
	}
}