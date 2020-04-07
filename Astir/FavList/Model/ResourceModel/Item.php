<?php
namespace Astir\FavList\Model\ResourceModel;
class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct() {
        $this->_init('amlist_item','item_id');
    }
}