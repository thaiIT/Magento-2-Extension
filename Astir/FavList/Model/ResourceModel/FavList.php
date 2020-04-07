<?php
namespace Astir\FavList\Model\ResourceModel;
class FavList extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct() {
        $this->_init('amlist_list','list_id');
    }
}