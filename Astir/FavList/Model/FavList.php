<?php
namespace Astir\FavList\Model;
class FavList extends \Magento\Framework\Model\AbstractModel {
    protected function _construct() {
        $this->_init("Astir\FavList\Model\ResourceModel\FavList");
    }
}