<?php
namespace Astir\FavList\Model\ResourceModel\Item;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'item_id';
    public function _construct() {
        $this->_init('Astir\FavList\Model\Item','Astir\FavList\Model\ResourceModel\Item');
    }
}