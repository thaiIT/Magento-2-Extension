<?php 
namespace THAIHOANG\Staff\Model;
class Staff extends \Magento\Framework\Model\AbstractModel {
	protected function _construct() {
		$this->_init("THAIHOANG\Staff\Model\ResourceModel\Staff");
	}
}