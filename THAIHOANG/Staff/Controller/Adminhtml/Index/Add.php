<?php 
namespace THAIHOANG\Staff\Controller\Adminhtml\Index;

class Add extends \Magento\Backend\App\Action {
    const ADMIN_RESOURCE = "THAIHOANG_Staff::staff_save";
	public function execute() {
		$this->_forward('edit');
	}
}