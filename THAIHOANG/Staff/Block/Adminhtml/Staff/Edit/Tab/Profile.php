<?php
namespace THAIHOANG\Staff\Block\Adminhtml\Staff\Edit\Tab;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Cms\Model\Wysiwyg\Config;
class Profile extends Generic implements TabInterface
{
    protected $_editor;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Config $editor,
        array $data = []
    ) {
        $this->_editor = $editor;
        parent::__construct($context,$registry,$formFactory, $data);
    }
    protected function _prepareForm() {
        $model = $this->_coreRegistry->registry('staff');
        $form = $this->_formFactory->create();
        $fieldset = $form->addFieldset(
            'profile_fieldset',
            ['legend' => __('Staff Profile'),"class" => "fieldset-wide"]
        );
        $editorConfig = $this->_editor->getConfig(['add_variables' => false, 'add_widgets' => false]);
        $fieldset->addField(
            'profile',
            'editor',
            [
                'name'        => 'profile',
                'label'    => __('Profile'),
                "config" => $editorConfig,
                "style" => "height:36em"
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }
    public function getTabLabel(){
        return __("Main Information");
    }
    public function getTabTitle(){
        return __("Main Information");
    }
    public function canShowTab(){
        return true;
    }
    public function isHidden(){
        return false;
    }
}