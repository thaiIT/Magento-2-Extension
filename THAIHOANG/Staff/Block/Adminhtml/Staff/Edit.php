<?php
namespace THAIHOANG\Staff\Block\Adminhtml\Staff;
use Magento\Backend\Block\Widget\Form\Container;
class Edit extends Container 
{
	public function _construct() {
		parent::_construct();
		$this->_objectId = 'id';
		$this->_blockGroup = "THAIHOANG_Staff";
		$this->_controller = "adminhtml_staff";
        $this->buttonList->update("save","label",__("Save"));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save And Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            -100
        );
        $this->buttonList->update("delete","label",__("Delete"));

	}
    protected function _getSaveAndContinueUrl(){
        return $this->getUrl("staff/*/save",["_current"=>true,"back"=>"edit","active_tab"=>""]);
    }
}