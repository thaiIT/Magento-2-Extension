<?php
namespace THAIHOANG\Staff\Model\ResourceModel;
class Staff extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
	protected function _construct() {
		$this->_init('staff','id');
	}
}