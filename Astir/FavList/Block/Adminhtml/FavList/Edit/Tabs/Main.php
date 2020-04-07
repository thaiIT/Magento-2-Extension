<?php

namespace Astir\FavList\Block\Adminhtml\FavList\Edit\Tabs;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Astir\FavList\Model\Config\Status;
use Astir\FavList\Model\Config\CustomerCollection;

class Main extends Generic implements TabInterface
{
    protected $amlistDefault;
    protected $customerCollection;

    public function __construct(Context $context, Registry $registry, FormFactory $formFactory,Status $status,CustomerCollection $customerCollection, array $data = [])
    {
        $this->amlistDefault = $status;
        $this->customerCollection = $customerCollection;
        parent::__construct($context,$registry,$formFactory, $data);
    }

    protected function _prepareForm() {
        $model = $this->_coreRegistry->registry('list');

        if($model->getListId()) {
            $customerId = $model->getCustomerId();
            $model['customer_firstname'] = $this->customerCollection->getFirstname($customerId);
            $model['customer_lastname'] = $this->customerCollection->getLastname($customerId);
            $model['customer_email'] = $this->customerCollection->getEmail($customerId);
        }

        $form = $this->_formFactory->create();
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Information'),"class" => "fieldset-wide"]
        );

        if($model->getListId()) {
            $fieldset->addField(
                'list_id',
                'hidden',
                ['name'=>'list_id']
            );
        }

        $fieldset->addField (
            'title',
            'text',
            [
                'name'     => 'title',
                'label'    => __('Title'),
                'required' => true
            ]
        );
        
        $fieldset->addField(
            'customer_id',
            'select',
            [
                'name'        => 'customer_id',
                'label'    => __('Customer'),
                'required'     => true,
                'options' => $this->customerCollection->toOptionArray()
            ]
        );

        if($model->getListId()) {
            $fieldset->addField(
                'customer_firstname', 
                'label', 
                [
                    'label' => __('First Name'),
                    'name' => 'customer_firstname',
                ]
            );
            $fieldset->addField(
                'customer_lastname', 
                'label', 
                [
                    'label' => __('Last Name'),
                    'name' => 'customer_lastname',
                ]
            );
            $fieldset->addField(
                'customer_email', 
                'label', 
                [
                    'label' => __('Email'),
                    'name' => 'customer_email',
                ]
            );
        }

        $fieldset->addField(
            'is_default',
            'select',
            [
                'name'        => 'is_default',
                'label'    => __('Is Default'),
                'required'     => true,
                'options' => $this->amlistDefault->toOptionArray()
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(){
        return __("Information");
    }

    public function getTabTitle(){
        return __("Information");
    }

    public function canShowTab(){
        return true;
    }

    public function isHidden(){
        return false;
    }
}