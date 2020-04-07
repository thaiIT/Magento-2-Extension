<?php

namespace Astir\FavList\Block\Adminhtml\FavList\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs {

    public function _construct() {
        $this->setId("staff_edit_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(__("Favourites List"));
        parent::_construct();
    }

    public function _beforeToHtml() {
        
        $this->addTab(
            "amlist_main",
            [
                "label" => __("General"),
                "title" => __("General"),
                "content" => $this->getLayout()->createBlock(
                    "Astir\FavList\Block\Adminhtml\FavList\Edit\Tabs\Main"
                )->toHtml(),
                "active" => true
            ]
        );

        $this->addTab(
            "product",
            [
                "label" => __("Products"),
                "title" => __("Products"),
                "content" => $this->getLayout()->createBlock(
                    "Astir\FavList\Block\Adminhtml\FavList\Edit\Tabs\Product"
                )->toHtml(),
                "active" => false
            ]
        );
        return parent::_beforeToHtml();
    }
}