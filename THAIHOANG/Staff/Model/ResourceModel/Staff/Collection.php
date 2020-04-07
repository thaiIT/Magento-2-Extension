<?php 
namespace THAIHOANG\Staff\Model\ResourceModel\Staff;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
	public function _construct() {
		$this->_init('THAIHOANG\Staff\Model\Staff','THAIHOANG\Staff\Model\ResourceModel\Staff');
	}
}