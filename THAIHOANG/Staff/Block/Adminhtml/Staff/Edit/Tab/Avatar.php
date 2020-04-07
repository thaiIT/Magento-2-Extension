<?php
namespace THAIHOANG\Staff\Block\Adminhtml\Staff\Edit\Tab;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
class Avatar extends Generic implements TabInterface
{
    protected $_staffStatus;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context,$registry,$formFactory, $data);
    }
    protected function _prepareForm() {
        $model = $this->_coreRegistry->registry('staff');
        $form = $this->_formFactory->create();
        $fieldset = $form->addFieldset(
            'Avatar_fieldset',
            ['legend' => __('Upload Avatar'),"class" => "fieldset-wide"]
        );
        $fieldset->addField(
            'avatar',
            'image',
            [
                'name'        => 'avatar',
                'label'    => __('Avatar'),
                'required'     => true
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