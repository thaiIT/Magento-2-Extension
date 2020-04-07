<?php
namespace Astir\FavList\Model;
class Item extends \Magento\Framework\Model\AbstractModel {
    protected function _construct() {
        $this->_init("Astir\FavList\Model\ResourceModel\Item");
    }
}