<?php
namespace THAIHOANG\Staff\Block\Adminhtml\Staff\Edit;
use Magento\Backend\Block\Widget\Tabs as WidgetTabs;
class Tabs extends WidgetTabs {
    public function _construct() {
        $this->setId("staff_edit_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(__("Staff Manager"));
        parent::_construct();
    }
    public function _beforeToHtml() {
        $this->addTab(
            "staff_main",
            [
                "label" => __("Main Information"),
                "title" => __("Main Information"),
                "content" => $this->getLayout()->createBlock(
                    "THAIHOANG\Staff\Block\Adminhtml\Staff\Edit\Tab\Main"
                )->toHtml(),
                "active" => true
            ]
        );
        $this->addTab(
            "staff_avatar",
            [
                "label" => __("Upload Avatar"),
                "title" => __("Upload Avatar"),
                "content" => $this->getLayout()->createBlock(
                    "THAIHOANG\Staff\Block\Adminhtml\Staff\Edit\Tab\Avatar"
                )->toHtml(),
                "active" => false
            ]
        );
        $this->addTab(
            "staff_profile",
            [
                "label" => __("Staff Profile"),
                "title" => __("Staff Profile"),
                "content" => $this->getLayout()->createBlock(
                    "THAIHOANG\Staff\Block\Adminhtml\Staff\Edit\Tab\Profile"
                )->toHtml(),
                "active" => false
            ]
        );
        return parent::_beforeToHtml();
    }
}