<?php 
namespace Astir\FavList\Controller\Index;

class AddList extends \Magento\Framework\App\Action\Action 
{
	public function execute() {
		$this->_forward('editList');
	}
}