<?php
namespace Astir\FavList\Model\ResourceModel\FavList;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'list_id';
    public function _construct() {
        $this->_init('Astir\FavList\Model\FavList','Astir\FavList\Model\ResourceModel\FavList');
    }
}